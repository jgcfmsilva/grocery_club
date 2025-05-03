@extends('layouts.app')

@section('title', 'Wishlist - ' . config('vars.app_name'))

@section('content')
    <livewire:wishlist-page/>
@endsection
