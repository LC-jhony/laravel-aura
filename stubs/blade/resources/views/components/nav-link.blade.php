@props(['active' => false])
@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-500 text-sm font-semibold text-gray-900 dark:text-white'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-500 transition-colors duration-200 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-600 dark:hover:text-gray-300';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
