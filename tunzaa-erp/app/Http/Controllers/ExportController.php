<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    /**
     * Export sales data to CSV
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportSales(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        // Set default date range to last 30 days if not specified
        $startDate = isset($validated['start_date']) 
            ? Carbon::parse($validated['start_date'])->startOfDay() 
            : Carbon::now()->subDays(30)->startOfDay();
            
        $endDate = isset($validated['end_date']) 
            ? Carbon::parse($validated['end_date'])->endOfDay() 
            : Carbon::now()->endOfDay();

        // Get sales for the authenticated user within the date range
        $sales = Sale::where('user_id', Auth::id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('saleItems.product')
            ->orderBy('created_at')
            ->get();

        // Create CSV file
        $filename = 'sales_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Create a file handle for output
        $handle = fopen('php://temp', 'r+');

        // Add CSV headers
        fputcsv($handle, [
            'Sale ID',
            'Date',
            'Product',
            'Quantity',
            'Unit Price',
            'Subtotal',
            'Sale Total',
            'Notes'
        ]);

        // Add sales data
        foreach ($sales as $sale) {
            $saleDate = Carbon::parse($sale->created_at)->format('Y-m-d H:i:s');
            
            foreach ($sale->saleItems as $item) {
                fputcsv($handle, [
                    $sale->id,
                    $saleDate,
                    $item->product->name,
                    $item->quantity,
                    $item->unit_price,
                    $item->subtotal,
                    $sale->total_amount,
                    $sale->notes
                ]);
            }
        }

        // Reset the file pointer to the beginning
        rewind($handle);
        
        // Get the content of the file
        $content = stream_get_contents($handle);
        fclose($handle);

        // Return the CSV as a download
        return Response::make($content, 200, $headers);
    }

    /**
     * Export inventory data to CSV
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportInventory()
    {
        // Get products for the authenticated user
        $products = Auth::user()->products()->orderBy('name')->get();

        // Create CSV file
        $filename = 'inventory_' . Carbon::now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        // Create a file handle for output
        $handle = fopen('php://temp', 'r+');

        // Add CSV headers
        fputcsv($handle, [
            'Product ID',
            'Name',
            'Description',
            'Price',
            'Current Stock',
            'Last Updated'
        ]);

        // Add product data
        foreach ($products as $product) {
            fputcsv($handle, [
                $product->id,
                $product->name,
                $product->description,
                $product->price,
                $product->stock_quantity,
                Carbon::parse($product->updated_at)->format('Y-m-d H:i:s')
            ]);
        }

        // Reset the file pointer to the beginning
        rewind($handle);
        
        // Get the content of the file
        $content = stream_get_contents($handle);
        fclose($handle);

        // Return the CSV as a download
        return Response::make($content, 200, $headers);
    }
}
