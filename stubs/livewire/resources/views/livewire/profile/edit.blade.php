<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 p-4 shadow sm:rounded-lg sm:p-8">
                <livewire:profile.update-profile-information-form />
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 shadow sm:rounded-lg sm:p-8">
                <livewire:profile.update-password-form />
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 shadow sm:rounded-lg sm:p-8">
                <livewire:profile.delete-user-form />
            </div>
        </div>
    </div>
</div>
