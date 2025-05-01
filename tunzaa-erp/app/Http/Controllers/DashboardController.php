<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $salesByDay = Sale::where('user_id', Auth::id())
            ->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->get()
            ->groupBy(function ($sale) {
                return $sale->created_at->format('l');
            })
            ->map(function ($sales) {
                return [
                    'day' => $sales->first()->created_at->format('l'),
                    'amount' => $sales->sum('total_amount'),
                    'products_count' => $sales->sum(function ($sale) {
                        return $sale->saleItems->sum('quantity');
                    })
                ];
            })
            ->values();

        // Fill in missing days
        $daysOfWeek = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
        $filledSalesByDay = $daysOfWeek->map(function ($day) use ($salesByDay) {
            return $salesByDay->firstWhere('day', $day) ?? [
                'day' => $day,
                'amount' => 0,
                'products_count' => 0
            ];
        });

        return view('dashboard', [
            'recentSales' => Sale::where('user_id', Auth::id())
                ->with('saleItems')
                ->latest()
                ->take(5)
                ->get(),
            'salesByDay' => $filledSalesByDay
        ]);
    }
}
