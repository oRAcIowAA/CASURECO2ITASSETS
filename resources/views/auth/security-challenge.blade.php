<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Please answer your security question to continue.') }}
    </div>

    <form method="POST" action="{{ route('password.security.verify') }}">
        @csrf

        <!-- Security Question (Read Only) -->
        <div class="mb-4">
            <x-input-label for="question" :value="__('Security Question')" />
            <div class="mt-1 p-3 bg-gray-50 border border-gray-200 rounded-md text-sm font-semibold text-gray-800">
                {{ $user->security_question }}
            </div>
        </div>

        <!-- Answer -->
        <div class="mt-4">
            <x-input-label for="answer" :value="__('Your Answer')" />
            <x-text-input id="answer" class="block mt-1 w-full" type="text" name="answer" required autofocus autocomplete="off" />
            <x-input-error :messages="$errors->get('answer')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Verify Answer') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>


