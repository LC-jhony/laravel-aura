@props(['active' => false])
@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-700 bg-indigo-50'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:bg-gray-50';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
