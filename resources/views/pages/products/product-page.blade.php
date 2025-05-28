@extends('layouts.app')

@section('title', 'Product Page')

@section('content')
    <livewire:product-page :product="$product" />

    @if(isset($recommendedProducts) && $recommendedProducts->count())
        <div class="container mx-auto mt-10"
             x-data="{
                start: 0,
                visible: 5,
                max: {{ $recommendedProducts->count() }},
                get cardWidth() {
                    return 100 / Math.min(this.visible, this.max);
                },
                get flexWidth() {
                    return this.max * this.cardWidth;
                },
                get translateX() {
                    return this.start * this.cardWidth;
                }
             }">
            <h2 class="text-2xl font-bold mb-4">Produtos Recomendados</h2>
            <div class="relative">
                <button
                    x-show="start > 0"
                    @click="start = Math.max(0, start - 1)"
                    class="absolute left-0 top-1/2 -translate-y-1/2 bg-gray-200 hover:bg-gray-300 rounded-full p-2 z-10"
                    x-transition
                >
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="overflow-hidden w-full">
                    <div class="flex transition-all duration-300"
                         :style="`width: ${flexWidth}% ; transform: translateX(-${translateX}%);`">
                        @foreach($recommendedProducts as $recProduct)
                            <div class="bg-white rounded shadow p-4 flex flex-col items-center mx-2"
                                 :style="'width:' + cardWidth + '%; flex: 0 0 ' + cardWidth + '%;'">
                                <img src="{{ $recProduct->image_url }}" alt="{{ $recProduct->name }}" class="w-32 h-32 object-cover rounded mb-2">
                                <div class="text-lg font-semibold">{{ $recProduct->name }}</div>
                                <div class="text-gray-700 mb-1">{{ number_format($recProduct->price, 2, ',', '') }}€</div>
                                <a href="{{ route('products.show', $recProduct->id) }}"
                                   class="mt-2 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Ver Produto</a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button
                    x-show="start < max - visible"
                    @click="start = Math.min(max - visible, start + 1)"
                    class="absolute right-0 top-1/2 -translate-y-1/2 bg-gray-200 hover:bg-gray-300 rounded-full p-2 z-10"
                    x-transition
                >
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    @endif
@endsection
