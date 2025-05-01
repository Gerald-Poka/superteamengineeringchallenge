<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PDF;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::where('user_id', Auth::id())
            ->with('saleItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('user_id', Auth::id())
            ->where('stock_quantity', '>', 0)
            ->orderBy('name')
            ->get();
            
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $saleItems = [];

            // Calculate total and prepare sale items
            foreach ($validated['product_ids'] as $index => $productId) {
                $product = Product::findOrFail($productId);
                $quantity = $validated['quantities'][$index];

                // Check if user owns this product
                if ($product->user_id !== Auth::id()) {
                    throw new \Exception('Unauthorized product access');
                }

                // Check if enough stock
                if ($product->stock_quantity < $quantity) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                $subtotal = $product->price * $quantity;
                $totalAmount += $subtotal;

                $saleItems[] = [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                    'subtotal' => $subtotal,
                ];

                // Update product stock
                $product->stock_quantity -= $quantity;
                $product->save();
            }

            // Create sale
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create sale items and inventory movements
            foreach ($saleItems as $item) {
                $saleItem = new SaleItem($item);
                $sale->saleItems()->save($saleItem);

                // Record inventory movement
                InventoryMovement::create([
                    'product_id' => $item['product_id'],
                    'sale_id' => $sale->id,
                    'type' => 'sale',
                    'quantity' => -$item['quantity'],
                    'notes' => 'Sale #' . $sale->id,
                ]);
            }

            DB::commit();

            return redirect()->route('sales.show', $sale)
                ->with('success', 'Sale recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error recording sale: ' . $e->getMessage());
        }
    }

    public function show(Sale $sale)
    {
        // Check if the authenticated user owns this sale
        if ($sale->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $sale->load('saleItems.product');
        
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        // Sales cannot be edited after creation for integrity reasons
        abort(404);
    }

    public function update(Request $request, Sale $sale)
    {
        // Sales cannot be updated after creation for integrity reasons
        abort(404);
    }

    public function destroy(Sale $sale)
    {
        // Check if the authenticated user owns this sale
        if ($sale->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // In a real application, you might want to implement a more complex
        // logic for canceling sales, such as restoring inventory, etc.
        // For simplicity, we'll just prevent deletion
        
        return redirect()->route('sales.index')
            ->with('error', 'Sales cannot be deleted once recorded.');
    }

    public function export()
    {
        $sales = Sale::where('user_id', Auth::id())
            ->with('saleItems.product')
            ->orderBy('created_at', 'desc')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=sales.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Items', 'Total Amount', 'Notes']);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->created_at->format('Y-m-d H:i:s'),
                    $sale->saleItems->count(),
                    $sale->total_amount,
                    $sale->notes ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportCsv(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sale-'.$sale->id.'.csv"',
        ];

        $callback = function() use ($sale) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Product', 'Quantity', 'Unit Price', 'Subtotal']);
            
            // Data rows
            foreach ($sale->saleItems as $item) {
                fputcsv($file, [
                    $item->product->name,
                    $item->quantity,
                    $item->unit_price,
                    $item->subtotal
                ]);
            }
            
            // Total row
            fputcsv($file, ['', '', 'Total:', $sale->total_amount]);
            
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function print(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403);
        }

        return view('sales.print', compact('sale'));
    }
}
