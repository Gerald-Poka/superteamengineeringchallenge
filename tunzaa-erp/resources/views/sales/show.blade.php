<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-amber-800 leading-tight">
                {{ __('Sale Details #') . $sale->id }}
            </h2>
            <a href="{{ route('sales.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white text-amber-700 border border-amber-300 rounded-lg hover:bg-amber-50 transition-colors duration-200 shadow-md">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Sales
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-amber-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-amber-100">
                <div class="p-6">
                    <div class="mb-6 flex justify-between items-start">
                        <div>
                            <div class="text-lg font-medium text-amber-900">Sale Information</div>
                            <p class="text-amber-600">{{ $sale->created_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <span class="px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                            {{ $sale->saleItems->count() }} items
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-amber-200">
                            <thead>
                                <tr class="bg-amber-50">
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-amber-700 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-amber-100">
                                @foreach($sale->saleItems as $item)
                                    <tr class="hover:bg-amber-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-amber-900">{{ $item->product->name }}</div>
                                            @if($item->product->description)
                                                <div class="text-xs text-amber-600">{{ Str::limit($item->product->description, 50) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                                {{ $item->quantity }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-900">
                                            ${{ number_format($item->unit_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-900">
                                            ${{ number_format($item->subtotal, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-amber-50">
                                    <td colspan="3" class="px-6 py-4 text-sm font-medium text-amber-900 text-right">
                                        Total Amount:
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-amber-900">
                                        ${{ number_format($sale->total_amount, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($sale->notes)
                        <div class="mt-6 p-4 bg-amber-50 rounded-lg border border-amber-100">
                            <h4 class="text-sm font-medium text-amber-900">Notes:</h4>
                            <p class="mt-1 text-sm text-amber-700">{{ $sale->notes }}</p>
                        </div>
                    @endif

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('sales.export.csv', $sale) }}" 
                           class="inline-flex items-center px-4 py-2 bg-white text-amber-700 border border-amber-300 rounded-lg hover:bg-amber-50 transition-colors duration-200 shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                            Export CSV
                        </a>
                        <a href="{{ route('sales.print', $sale) }}" 
                           class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors duration-200 shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Print Receipt
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
