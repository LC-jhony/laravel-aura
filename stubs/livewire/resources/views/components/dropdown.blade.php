@props(['align' => 'right', 'width' => '48'])
@php
$alignmentClasses = $align === 'left' ? 'ltr:origin-top-left rtl:origin-top-right start-0' : 'ltr:origin-top-right rtl:origin-top-left end-0';
$widthClass = $width === '48' ? 'w-48' : "w-{$width}";
@endphp
<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <div @click="open = ! open">{{ $trigger }}</div>
    <div x-show="open" x-transition class="absolute z-50 mt-2 rounded-md shadow-lg {{ $widthClass }} {{ $alignmentClasses }}" style="display: none;" @click="open = false">
        <div class="rounded-md bg-white py-1 ring-1 ring-black ring-opacity-5 dark:bg-gray-700">{{ $content }}</div>
    </div>
</div>
