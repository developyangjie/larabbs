<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>@yield('title', 'FinancialFreedom')</title>
    <meta name="description" content="@yield('description', setting('seo_description', 'FinancialFreedom 爱好者社区。'))" />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    	 @yield('styles')
</head>

<body>
<div id="app" class="{{ route_class() }}-page" >

    @include("layouts._header")

    <div class="container" >
        @include("layouts._message")
        @yield("content")

    </div>

    @include('layouts._footer')
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/jquery.textcomplete.js') }}"></script>
@yield('scripts')

@if(app()->isLocal())
    @include('sudosu::user-selector')
@endif

</body>
</html>