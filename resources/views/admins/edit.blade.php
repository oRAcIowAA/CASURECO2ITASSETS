<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Administrator Account') }}
            </h2>
            <a href="{{ route('admins.index') }}" class="text-indigo-600 hover:text-indigo-900 flex items-center text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 text-sm">
                
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800">Profile Information</h3>
                    <p class="text-xs text-gray-500 mt-1">Update this administrator's fundamental account information.</p>
                </div>

                <form method="POST" action="{{ route('admins.update', $admin->id) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <x-input-label for="name" :value="__('Full Name')" class="font-bold text-gray-700 uppercase tracking-wide text-xs mb-2" />
                        <x-text-input id="name" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="name" :value="old('name', $admin->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-600 text-xs font-semibold" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-6">
                        <x-input-label for="email" :value="__('Email Address')" class="font-bold text-gray-700 uppercase tracking-wide text-xs mb-2" />
                        <x-text-input id="email" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="email" name="email" :value="old('email', $admin->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-xs font-semibold" />
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-b border-gray-200 -mx-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Security Credentials</h3>
                        <p class="text-xs text-gray-500 mt-1">These will be used if you forget your password.</p>
                    </div>

                    <!-- Security Question -->
                    <div class="mb-6">
                        <x-input-label for="security_question" :value="__('Security Question')" class="font-bold text-gray-700 uppercase tracking-wide text-xs mb-2" />
                        <x-text-input id="security_question" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="security_question" :value="old('security_question', $admin->security_question)" placeholder="e.g., What is the name of your first pet?" required />
                        <x-input-error :messages="$errors->get('security_question')" class="mt-2 text-red-600 text-xs font-semibold" />
                    </div>

                    <!-- Security Answer -->
                    <div class="mb-8">
                        <x-input-label for="security_answer" :value="__('Security Answer')" class="font-bold text-gray-700 uppercase tracking-wide text-xs mb-2" />
                        <x-text-input id="security_answer" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="security_answer" :value="old('security_answer', $admin->security_answer)" required />
                        <x-input-error :messages="$errors->get('security_answer')" class="mt-2 text-red-600 text-xs font-semibold" />
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-b border-gray-200 -mx-6 mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Change Password</h3>
                        <p class="text-xs text-gray-500 mt-1">Leave these fields completely blank if you do not want to change the password.</p>
                    </div>

                    <!-- Current Password -->
                    <div class="mb-6">
                        <x-input-label for="current_password" :value="__('Current Password')" class="font-bold text-gray-700 uppercase tracking-wide text-xs mb-2" />
                        <x-text-input id="current_password" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="password" name="current_password" />
                        <p class="text-[11px] text-gray-400 mt-1 font-medium italic">Required if setting a new password.</p>
                        <x-input-error :messages="$errors->get('current_password')" class="mt-2 text-red-600 text-xs font-semibold" />
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <x-input-label for="password" :value="__('New Password')" class="font-bold text-gray-700 uppercase tracking-wide text-xs mb-2" />
                        <x-text-input id="password" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="password" name="password" autocomplete="new-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-xs font-semibold" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-8">
                        <x-input-label for="password_confirmation" :value="__('Confirm New Password')" class="font-bold text-gray-700 uppercase tracking-wide text-xs mb-2" />
                        <x-text-input id="password_confirmation" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="password" name="password_confirmation" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-600 text-xs font-semibold" />
                    </div>

                    <div class="flex items-center justify-end mt-4 pt-4 border-t border-gray-100">
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-900 border-indigo-700 text-white shadow-md shadow-indigo-200">
                            {{ __('Save Changes') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
