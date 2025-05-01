<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-amber-800 leading-tight">
                {{ __('Sales Dashboard') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('sales.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200 shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Sale
                </a>
                <a href="{{ route('export.sales') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white text-amber-700 border border-amber-300 rounded-lg hover:bg-amber-50 transition-colors duration-200 shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Export Sales
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-amber-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Sales Chart Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-amber-100 mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-amber-900">Sales Overview - Last 7 Days</h3>
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-amber-500 rounded-full"></div>
                                <span class="text-sm text-amber-700">Sales Amount</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-amber-200 rounded-full"></div>
                                <span class="text-sm text-amber-700">Products Sold</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-lg h-80">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Sales Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-amber-100">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-amber-900 mb-4">Recent Sales</h3>
                    
                    @if($recentSales->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-amber-200">
                                <thead>
                                    <tr class="bg-amber-50">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Items</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-amber-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-amber-100">
                                    @foreach($recentSales as $sale)
                                        <tr class="hover:bg-amber-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-amber-900">{{ $sale->created_at->format('M d, Y') }}</div>
                                                <div class="text-xs text-amber-600">{{ $sale->created_at->format('H:i') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                    {{ $sale->saleItems->count() }} items
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900">
                                                ${{ number_format($sale->total_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <a href="{{ route('sales.show', $sale) }}" 
                                                   class="text-amber-600 hover:text-amber-900 inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-amber-900">No sales yet</h3>
                            <p class="mt-1 text-sm text-amber-500">Get started by creating a new sale.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salesData = @json($salesByDay);
            window.initSalesChart(salesData);
        });
    </script>
    @endpush
</x-app-layout>
