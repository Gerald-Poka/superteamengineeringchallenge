<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-amber-800 leading-tight">
                {{ __('Record New Sale') }}
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
                <div class="p-8">
                    <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                        @csrf
                        
                        @if($errors->any())
                            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div id="productRows">
                            <div class="product-row mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-amber-700 mb-1">Product</label>
                                        <select name="product_ids[]" required 
                                                class="mt-1 block w-full rounded-lg border-amber-300 shadow-sm 
                                                       focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                                            <option value="">Select a product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" 
                                                        data-price="{{ $product->price }}" 
                                                        data-stock="{{ $product->stock_quantity }}">
                                                    {{ $product->name }} (Stock: {{ $product->stock_quantity }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-amber-700 mb-1">Quantity</label>
                                        <input type="number" name="quantities[]" required min="1" 
                                               class="mt-1 block w-full rounded-lg border-amber-300 shadow-sm 
                                                      focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-amber-700 mb-1">Subtotal</label>
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-amber-500">$</span>
                                            <input type="text" readonly 
                                                   class="subtotal mt-1 block w-full pl-8 rounded-lg border-amber-300 bg-amber-50 
                                                          text-amber-700 font-medium">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="addProduct" 
                                class="mt-4 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 
                                       transition-colors duration-200 shadow-md inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Another Product
                        </button>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-amber-700 mb-1">Notes</label>
                            <textarea name="notes" rows="3" 
                                      class="mt-1 block w-full rounded-lg border-amber-300 shadow-sm 
                                             focus:border-amber-500 focus:ring focus:ring-amber-200 focus:ring-opacity-50"></textarea>
                        </div>

                        <div class="mt-8 flex justify-between items-center bg-amber-50 p-4 rounded-lg border border-amber-100">
                            <div class="text-xl font-bold text-amber-900">
                                Total: $<span id="grandTotal">0.00</span>
                            </div>
                            
                            <div class="flex gap-3">
                                <a href="{{ route('sales.index') }}" 
                                   class="px-4 py-2 bg-white text-amber-700 border border-amber-300 rounded-lg 
                                          hover:bg-amber-50 transition-colors duration-200 shadow-md">Cancel</a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 
                                               transition-colors duration-200 shadow-md inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Record Sale
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('saleForm');
            const productRows = document.getElementById('productRows');
            const addProductBtn = document.getElementById('addProduct');
            const grandTotalSpan = document.getElementById('grandTotal');

            // Add form submission handler
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate all rows
                let isValid = true;
                const rows = document.querySelectorAll('.product-row');
                
                rows.forEach(row => {
                    const select = row.querySelector('select[name="product_ids[]"]');
                    const quantity = row.querySelector('input[name="quantities[]"]');
                    const stock = parseInt(select.options[select.selectedIndex]?.dataset.stock || 0);
                    const qty = parseInt(quantity.value) || 0;
                    
                    if (qty > stock) {
                        isValid = false;
                        quantity.classList.add('border-red-500');
                        alert(`Not enough stock for ${select.options[select.selectedIndex].text}`);
                    }
                });
                
                if (isValid) {
                    this.submit();
                }
            });

            function updateSubtotal(row) {
                const select = row.querySelector('select[name="product_ids[]"]');
                const quantity = row.querySelector('input[name="quantities[]"]');
                const subtotal = row.querySelector('.subtotal');
                const stock = parseInt(select.options[select.selectedIndex]?.dataset.stock || 0);
                
                // Remove error styling
                quantity.classList.remove('border-red-500');
                
                if (select.selectedIndex > 0) {
                    const price = parseFloat(select.options[select.selectedIndex].dataset.price);
                    const qty = parseInt(quantity.value) || 0;
                    
                    // Validate stock
                    if (qty > stock) {
                        quantity.classList.add('border-red-500');
                    }
                    
                    subtotal.value = (price * qty).toFixed(2);
                } else {
                    subtotal.value = '0.00';
                }
                
                updateGrandTotal();
            }

            function updateGrandTotal() {
                const subtotals = document.querySelectorAll('.subtotal');
                let total = 0;
                
                subtotals.forEach(input => {
                    total += parseFloat(input.value) || 0;
                });
                
                grandTotalSpan.textContent = total.toFixed(2);
            }

            function addProductRow() {
                const template = document.querySelector('.product-row').cloneNode(true);
                template.querySelector('select').selectedIndex = 0;
                template.querySelector('input[name="quantities[]"]').value = '';
                template.querySelector('.subtotal').value = '0.00';
                
                // Add remove button if more than one row
                if (document.querySelectorAll('.product-row').length > 0) {
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'remove-row mt-2 text-red-600 hover:text-red-800';
                    removeBtn.innerHTML = '&times; Remove';
                    removeBtn.onclick = function() {
                        this.closest('.product-row').remove();
                        updateGrandTotal();
                    };
                    template.querySelector('.grid').appendChild(removeBtn);
                }
                
                productRows.appendChild(template);
                bindRowEvents(template);
            }

            function bindRowEvents(row) {
                const select = row.querySelector('select[name="product_ids[]"]');
                const quantity = row.querySelector('input[name="quantities[]"]');
                
                select.addEventListener('change', () => {
                    // Reset quantity when product changes
                    quantity.value = '';
                    updateSubtotal(row);
                });
                quantity.addEventListener('input', () => updateSubtotal(row));
            }

            addProductBtn.addEventListener('click', addProductRow);
            bindRowEvents(document.querySelector('.product-row'));
        });
    </script>
    @endpush
</x-app-layout>
