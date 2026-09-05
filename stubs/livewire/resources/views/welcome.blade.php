<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 antialiased dark:bg-gray-900 dark:text-gray-100">
    <div class="flex min-h-screen flex-col items-center justify-center px-4">
        <h1 class="text-3xl font-bold">{{ config('app.name', 'Laravel') }}</h1>
        @if (Route::has('login'))
            <div class="mt-6 flex gap-4 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="underline">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="underline">{{ __('Log in') }}</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="underline">{{ __('Register') }}</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</body>
</html>
