<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{url('favicon.ico')}}">
    <link id="theme-css" rel="stylesheet" type="text/css" href="/themes/aura-light-green/theme.css">
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(
            [
                'csrfToken' => csrf_token(),
                'baseUrl' => url('/'),
            ],
            JSON_UNESCAPED_UNICODE,
        ) !!};
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
    @routes
        @if (isset($isAdmin) && $isAdmin)
            @vite(['resources/js/admin.js', "resources/js/Pages/{$page['component']}.vue"])
        @else
            @vite(['resources/js/admin.js', "resources/js/Pages/{$page['component']}.vue"])
        @endif
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
