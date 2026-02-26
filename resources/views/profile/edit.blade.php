<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-gray-900">{{ __('Profile') }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">Manage your account settings</p>
        </div>
    </x-slot>

    <div class="max-w-3xl space-y-6">
        <div class="p-6 bg-white rounded-xl border border-gray-100">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl border border-gray-100">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 bg-white rounded-xl border border-gray-100">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
