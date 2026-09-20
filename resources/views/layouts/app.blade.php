<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Universal Ecommerce' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>

    @include('layouts.header')

    <main>
        {{ $slot ?? '' }}

        @yield('content')
    </main>

    @include('layouts.footer')

    @livewireScripts

</body>
</html>