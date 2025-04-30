@extends('layouts.app')

@section('title', 'cart')

@section('content')
    <livewire:cart-page/>
@endsection

@push('styles')
    @vite('resources/css/pages/cart/cart.css')
@endpush
