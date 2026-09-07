@props(['active' => false])
@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-500 text-start text-base font-semibold text-indigo-700 bg-indigo-50/50 dark:text-indigo-300 dark:bg-indigo-900/20'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 transition-colors duration-200 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
