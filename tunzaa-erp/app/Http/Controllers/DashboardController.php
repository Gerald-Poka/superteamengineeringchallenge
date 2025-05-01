<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get sales from the past 7 days
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();
        
        $dailySales = Sale::where('user_id', Auth::id())
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Fill in missing days with zero sales
        $salesByDay = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $salesByDay[$date] = 0;
        }
        
        foreach ($dailySales as $sale) {
            $salesByDay[$sale->date] = $sale->total;
        }
        
        // Get recent sales
        $recentSales = Sale::where('user_id', Auth::id())
            ->with(['saleItems.product'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        return view('dashboard', [
            'salesByDay' => $salesByDay,
            'recentSales' => $recentSales,
        ]);
    }
}
