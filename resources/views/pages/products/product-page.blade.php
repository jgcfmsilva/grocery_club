@extends('layouts.app')

@section('title', 'Product Page')

@section('content')
    <livewire:product-page :product="$product" />
@endsection
