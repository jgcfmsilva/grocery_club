@extends('layouts.app')

@section('title', 'Cart - ' . config('vars.app_name'))

@section('content')
    @livewire('cart-page')
@endsection

@push('styles')
    @vite('resources/css/pages/cart/cart.css')
@endpush

