<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Delete Account') }}</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}
        </p>
    </header>

    <x-danger-button wire:click="confirmUserDeletion">{{ __('Delete Account') }}</x-danger-button>

    @if ($confirmingUserDeletion)
        <div class="mt-4 rounded-md border border-red-300 bg-red-50 p-4 dark:border-red-800 dark:bg-red-900/30">
            <form wire:submit="deleteUser" class="space-y-4">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />
                <x-text-input wire:model="password" id="password" name="password" type="password" class="block w-3/4" placeholder="{{ __('Password') }}" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />

                <div class="flex gap-3">
                    <x-secondary-button wire:click="$set('confirmingUserDeletion', false)">{{ __('Cancel') }}</x-secondary-button>
                    <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
                </div>
            </form>
        </div>
    @endif
</section>
