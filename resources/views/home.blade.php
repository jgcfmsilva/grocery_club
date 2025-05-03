@extends('layouts.app')

@section('title', 'Welcome to ' . config('vars.app_name'))

@section('content')
    @include('layouts.partials.alerts.alerts')

    <div class="relative w-full h-72 sm:h-96 mb-10">
        <img src="{{ asset('assets/img/banners/home-banner-1.jpg') }}" alt="Banner" class="w-full h-full object-cover rounded-xl">

        <div class="absolute top-0 left-0 right-0 bottom-0 flex items-center justify-center text-center p-4 rounded-xl">
            <div>
                <p class="text-3xl font-bold text-gray-800">Welcome to Grocery Club</p>
                <p class="mt-4 text-gray-800">Explore our exclusive gourmet products!</p>
            </div>
        </div>
    </div>


    @if($categories->count())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-3xl font-bold text-gray-800 text-center mb-10 border-b border-gray-200 pb-3">
                Product Categories
            </h2>

            <div class="grid grid-cols-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($categories as $category)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl hover:-translate-y-1 transform transition duration-300 overflow-hidden">
                        <a href="{{ route('products.category', $category->id) }}" class="block">
                            @if($category->image)
                                <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-48 object-cover">
                            @else
                                <img src="{{ asset('storage/categories/category_no_image.png') }}" alt="{{ $category->name }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-5">
                                <h3 class="text-xl font-semibold text-gray-800">{{ $category->name }}</h3>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="text-center text-gray-500 mt-20">
            <p class="text-lg">Nenhuma categoria disponível no momento 😕</p>
            <p class="text-sm mt-2">Volte em breve para ver as novidades!</p>
        </div>
    @endif
@endsection

@push('scripts')
    @vite('resources/js/pages/home.js')
@endpush
