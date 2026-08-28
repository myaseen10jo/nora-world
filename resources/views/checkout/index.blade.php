@extends('layouts.app')

@section('title', 'Checkout - NORA')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-serif font-bold text-gray-800 mb-8">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Checkout Form --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Shipping Address --}}
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Shipping Address</h2>
                
                <form id="checkoutForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" name="shipping_address[first_name]" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" name="shipping_address[last_name]" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company (optional)</label>
                        <input type="text" name="shipping_address[company]" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                        <input type="text" name="shipping_address[address_line_1]" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                        <input type="text" name="shipping_address[address_line_2]" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" name="shipping_address[city]" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                            <input type="text" name="shipping_address[state]" 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code *</label>
                            <input type="text" name="shipping_address[postal_code]" required 
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Country *</label>
                        <select name="shipping_address[country]" id="country" required 
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-olive-500 focus:border-olive-500"
                                onchange="loadShippingMethods()">
                            <option value="">Select Country</option>
                            <option value="US">United States</option>
                            <option value="GB">United Kingdom</option>
                            <optgroup label="European Union">
                                <option value="AT">Austria</option>
                                <option value="BE">Belgium</option>
                                <option value="BG">Bulgaria</option>
                                <option value="HR">Croatia</option>
                                <option value="CY">Cyprus</option>
                                <option value="CZ">Czech Republic</option>
                                <option value="DK">Denmark</option>
                                <option value="EE">Estonia</option>
                                <option value="FI">Finland</option>
                                <option value="FR">France</option>
                                <option value="DE">Germany</option>
                                <option value="GR">Greece</option>
                                <option value="HU">Hungary</option>
                                <option value="IE">Ireland</option>
                                <option value="IT">Italy</option>
                                <option value="LV">Latvia</option>
                                <option value="LT">Lithuania</option>
                                <option value="LU">Luxembourg</option>
                                <option value="MT">Malta</option>
                                <option value="NL">Netherlands</option>
                                <option value="PL">Poland</option>
                                <option value="PT">Portugal</option>
                                <option value="RO">Romania</option>
                                <option value="SK">Slovakia</option>
                                <option value="SI">Slovenia</option>
                                <option value="ES">Spain</option>
                                <option value="SE">Sweden</option>
                            </optgroup>
                        </select>
                    </div>
                </form>
            </div>

            {{-- Shipping Method --}}
            <div class="bg-white rounded-lg p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Shipping Method</h2>
                <div id="shipping-methods" class="space-y-3">
                    <p class="text-gray-500 text-sm">Please select your country first to see available shipping methods.</p>
                </div>
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg p-6 shadow-sm sticky top-24">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Order Summary</h2>
                
                <div class="space-y-3 mb-6">
                    @foreach($cartItems as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span class="font-medium">${{ number_format($item->subtotal, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-200 pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping</span>
                        <span id="shipping-cost" class="font-medium">—</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-200 pt-2">
                        <span class="font-semibold text-gray-800">Total</span>
                        <span id="total" class="font-bold text-xl text-olive-500">${{ number_format($subtotal, 2) }}</span>
                    </div>
                </div>

                {{-- PayPal Button --}}
                <div class="mt-6">
                    <div id="paypal-button-container"></div>
                    <p class="text-xs text-gray-500 text-center mt-3 flex items-center justify-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Secure Checkout with PayPal
                    </p>
                </div>

                @if($isSandbox)
                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <p class="text-xs text-yellow-700">
                        <strong>PayPal Sandbox Mode</strong><br>
                        Use sandbox test credentials to test payments.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=USD"></script>
<script>
    let shippingCost = 0;
    let selectedMethodId = null;

    function loadShippingMethods() {
        const country = document.getElementById('country').value;
        if (!country) return;

        fetch('{{ route("checkout.shipping-methods") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ country }),
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('shipping-methods');
            if (data.methods && data.methods.length > 0) {
                container.innerHTML = data.methods.map(method => `
                    <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-olive-500">
                        <input type="radio" name="shipping_method_id" value="${method.id}" 
                               onchange="selectShippingMethod(${method.id}, ${method.flat_rate}, '${method.free_shipping_threshold || 0}', '${method.estimated_delivery_time}')"
                               class="text-olive-500 focus:ring-olive-500">
                        <div class="ml-3 flex-1">
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-800">${method.name}</span>
                                <span class="font-semibold text-olive-500">$${parseFloat(method.flat_rate).toFixed(2)}</span>
                            </div>
                            <p class="text-sm text-gray-500">${method.estimated_delivery_time}</p>
                            ${method.free_shipping_threshold ? `<p class="text-xs text-green-600">Free shipping on orders over $${parseFloat(method.free_shipping_threshold).toFixed(2)}</p>` : ''}
                        </div>
                    </label>
                `).join('');
            } else {
                container.innerHTML = '<p class="text-red-500 text-sm">No shipping methods available for this country.</p>';
            }
        });
    }

    function selectShippingMethod(methodId, flatRate, freeThreshold, deliveryTime) {
        selectedMethodId = methodId;
        const subtotal = {{ $subtotal }};

        if (freeThreshold && subtotal >= parseFloat(freeThreshold)) {
            shippingCost = 0;
            document.getElementById('shipping-cost').textContent = 'FREE';
        } else {
            shippingCost = parseFloat(flatRate);
            document.getElementById('shipping-cost').textContent = '$' + shippingCost.toFixed(2);
        }

        const total = subtotal + shippingCost;
        document.getElementById('total').textContent = '$' + total.toFixed(2);

        // Re-render PayPal buttons with new total
        renderPayPalButtons(total);
    }

    function renderPayPalButtons(total) {
        document.getElementById('paypal-button-container').innerHTML = '';

        paypal.Buttons({
            style: {
                color: 'blue',
                shape: 'rect',
                layout: 'vertical',
                label: 'pay',
            },
            createOrder: function(data, actions) {
                // Validate form
                const form = document.getElementById('checkoutForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                if (!selectedMethodId) {
                    alert('Please select a shipping method.');
                    return;
                }

                // Create order via server
                return fetch('{{ route("checkout.create-paypal-order") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        shipping_address: {
                            first_name: form.querySelector('[name="shipping_address[first_name]"]').value,
                            last_name: form.querySelector('[name="shipping_address[last_name]"]').value,
                            company: form.querySelector('[name="shipping_address[company]"]').value,
                            address_line_1: form.querySelector('[name="shipping_address[address_line_1]"]').value,
                            address_line_2: form.querySelector('[name="shipping_address[address_line_2]"]').value,
                            city: form.querySelector('[name="shipping_address[city]"]').value,
                            state: form.querySelector('[name="shipping_address[state]"]').value,
                            postal_code: form.querySelector('[name="shipping_address[postal_code]"]').value,
                            country: form.querySelector('[name="shipping_address[country]"]').value,
                        },
                        shipping_method_id: selectedMethodId,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }
                    return data.paypal_order_id;
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Redirect to success page
                    window.location.href = '{{ route("checkout.paypal.success") }}?token=' + data.orderID;
                });
            },
            onCancel: function(data) {
                window.location.href = '{{ route("checkout.paypal.cancel") }}?token=' + data.orderID;
            },
            onError: function(err) {
                console.error('PayPal error:', err);
                alert('An error occurred with PayPal. Please try again.');
            },
        }).render('#paypal-button-container');
    }

    // Initial render
    document.addEventListener('DOMContentLoaded', function() {
        renderPayPalButtons({{ $subtotal }});
    });
</script>
@endpush
@endsection
