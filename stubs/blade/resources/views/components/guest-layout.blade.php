<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased dark:bg-gray-950 dark:text-white/50">
    <div class="flex min-h-screen flex-col items-center bg-gradient-to-br from-gray-50 to-gray-100 pt-6 dark:from-gray-900 dark:to-gray-950 sm:justify-center sm:pt-0">
        <div class="mb-6">
            <a href="/">
                <x-application-logo class="h-16 w-16 fill-current text-indigo-600 dark:text-indigo-400" />
            </a>
        </div>

        <div class="w-full overflow-hidden bg-white px-6 py-8 shadow-xl ring-1 ring-gray-200/50 dark:bg-gray-800 dark:ring-gray-700/50 sm:max-w-md sm:rounded-2xl">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
