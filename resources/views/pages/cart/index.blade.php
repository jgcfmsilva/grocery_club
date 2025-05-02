@extends('layouts.app')

@section('title', 'Cart')

@section('content')
    @livewire('cart-page')
@endsection

@push('styles')
    @vite('resources/css/pages/cart/cart.css')
@endpush

