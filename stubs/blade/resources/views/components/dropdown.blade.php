@props(['align' => 'right', 'width' => '48'])
@php
$alignmentClasses = $align === 'left' ? 'ltr:origin-top-left rtl:origin-top-right start-0' : 'ltr:origin-top-right rtl:origin-top-left end-0';
$widthClass = $width === '48' ? 'w-48' : "w-{$width}";
@endphp
<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <div @click="open = ! open">{{ $trigger }}</div>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute z-50 mt-2 rounded-xl shadow-xl {{ $widthClass }} {{ $alignmentClasses }} ring-1 ring-black/5 dark:ring-white/10"
         style="display: none;"
         @click="open = false">
        <div class="rounded-xl bg-white py-1.5 dark:bg-gray-800">{{ $content }}</div>
    </div>
</div>
