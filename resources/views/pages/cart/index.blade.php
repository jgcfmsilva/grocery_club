@extends('layouts.app')

@section('title', 'Cart - ' . config('vars.app_name'))

@section('content')
    @livewire('cart-page')
@endsection


