<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
    </div>

    @if ($status)
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">{{ $status }}</div>
    @endif

    <form wire:submit="sendResetLink">
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
        </div>
    </form>
</div>
