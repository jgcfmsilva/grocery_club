<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho -->
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Cart</h1>
    </header>

    <div class="flex flex-col lg:flex-row gap-8">
        <main class="lg:w-2/3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="hidden md:flex bg-gray-50 px-6 py-4 border-b">
                    <div class="w-2/5 font-semibold text-gray-700">Product</div>
                    <div class="w-1/5 font-semibold text-gray-700 text-center">Price</div>
                    <div class="w-1/5 font-semibold text-gray-700 text-center">Ammount</div>
                    <div class="w-1/5 font-semibold text-gray-700 text-right">Total</div>
                </div>

                <!-- Lista de itens -->
                <div id="cart-items">
                    @foreach ($cart as $id => $item)
                    <div class="flex flex-col md:flex-row items-stretch p-6 border-b min-h-[120px] md:min-h-0">
                        <!-- Coluna do Produto -->
                        <div class="w-full md:w-2/5 flex items-center mb-4 md:mb-0">
                            <img src="{{ asset('storage/products/' . $item['photo']) }}" alt="{{ $item['name'] }}"
                                class="w-20 h-20 object-cover rounded">
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-800">{{ $item['name'] }}</h3>
                            </div>
                        </div>

                        <!-- Coluna do Preço - Agora alinhada -->
                        <div class="w-full md:w-1/5 flex items-center justify-center py-4">
                            <div>
                                <span class="md:hidden font-semibold text-gray-700 mr-2">Preço:</span>
                                <span class="font-semibold block text-center">€{{ number_format($item['price'], 2) }}</span>
                            </div>
                        </div>

                        <!-- Coluna da Quantidade - Agora alinhada -->
                        <div class="w-full md:w-1/5 flex items-center justify-center py-4">
                            <div class="flex items-center border rounded-lg h-10">
                                <button class="quantity-btn decrease px-3 py-1 text-gray-600 hover:bg-gray-100 h-full flex items-center">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="text" value="{{ $item['quantity'] }}"
                                    class="w-12 text-center border-0 focus:ring-0 h-full">
                                <button class="quantity-btn increase px-3 py-1 text-gray-600 hover:bg-gray-100 h-full flex items-center">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Coluna do Total -->
                        <div class="w-full md:w-1/5 flex items-center justify-between md:justify-end py-4">
                            <div class="flex items-center">
                                <span class="md:hidden font-semibold text-gray-700 mr-2">Total:</span>
                                <span class="font-semibold">€{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                            </div>
                            <button class="remove-btn ml-4 text-red-500 hover:text-red-700">
                                &nbsp;<i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>

        <!-- Resumo do Pedido -->
        <aside class="lg:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h2 class="text-xl font-bold text-gray-800 mb-6">Resumo do Pedido</h2>

                <div class="space-y-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold">€1,347.98</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Descontos</span>
                        <span class="text-green-600">-€100.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Envio</span>
                        <span class="font-semibold">€5.99</span>
                    </div>
                    <div class="border-t pt-4 flex justify-between">
                        <span class="text-lg font-bold text-gray-800">Total</span>
                        <span class="text-xl font-bold text-gray-800">€1,253.97</span>
                    </div>
                </div>

                <button
                    class="w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Finalizar Compra
                </button>

                <div class="mt-6 text-sm text-gray-500">
                    <p class="mb-2">Ao finalizar, você concorda com nossos <a href="#"
                            class="text-blue-600 hover:underline">Termos de Serviço</a>.</p>
                    <p>Frete e taxas calculados na finalização.</p>
                </div>
            </div>

            <!-- Métodos de Pagamento -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h3 class="font-semibold text-gray-800 mb-4">Métodos de Pagamento</h3>
                <div class="flex space-x-4">
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fab fa-cc-visa text-2xl text-blue-900"></i>
                    </div>
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fab fa-cc-mastercard text-2xl text-red-900"></i>
                    </div>
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fab fa-cc-paypal text-2xl text-blue-700"></i>
                    </div>
                    <div class="w-12 h-8 bg-gray-200 rounded flex items-center justify-center">
                        <i class="fab fa-cc-apple-pay text-2xl text-black"></i>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
