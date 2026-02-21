<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Kazeo Official') }} - Authentikasi</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
    </head>
    
    <body class="bg-zinc-50 pt-24 pb-12 flex flex-col min-h-screen">
        
        <x-navbar />

        <main class="flex-grow flex items-center justify-center w-full px-4 sm:px-6 lg:px-8 mt-4 mb-12 relative">
            <div class="absolute w-72 h-72 bg-red-100 rounded-full blur-3xl opacity-50 pointer-events-none -z-10"></div>
            
            {{ $slot }}
        </main>

        <x-footer />
        <x-active-user/>
    </body>
</html>