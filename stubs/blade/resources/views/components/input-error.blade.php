@props(['messages'])
@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-1.5 space-y-1 text-sm text-red-600 dark:text-red-400']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1">
                <svg class="h-4 w-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                <span>{{ $message }}</span>
            </li>
        @endforeach
    </ul>
@endif
