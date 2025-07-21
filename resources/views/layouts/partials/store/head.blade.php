<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!--favicon icon-->
<link rel="icon" href="{{ asset('assets/img/favicon.png') }}" type="image/png" sizes="16x16">

<!--title-->
<title>@yield('title')</title>

<!--build:css-->
@stack('styles')
@include('layouts.include.store.css')

@stack('scripts')
@include('layouts.include.store.scripts')