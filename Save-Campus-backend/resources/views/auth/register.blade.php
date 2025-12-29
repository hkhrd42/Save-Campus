<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ role: '{{ old('role', 'student') }}' }">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role Selection -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Register As')" />
            <div class="mt-2 space-y-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="role" value="student" class="text-green-600 focus:ring-green-500" x-model="role" {{ old('role', 'student') === 'student' ? 'checked' : '' }} required>
                    <span class="ml-2 text-gray-700">Student</span>
                </label>
                <label class="inline-flex items-center ml-6">
                    <input type="radio" name="role" value="staff" class="text-green-600 focus:ring-green-500" x-model="role" {{ old('role') === 'staff' ? 'checked' : '' }} required>
                    <span class="ml-2 text-gray-700">Staff (Admin)</span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Admin Code (Only for Staff) -->
        <div class="mt-4" x-show="role === 'staff'" x-cloak>
            <x-input-label for="admin_code" :value="__('Admin Code')" />
            <x-text-input id="admin_code" class="block mt-1 w-full" type="text" name="admin_code" :value="old('admin_code')" placeholder="Enter your staff registration code" />
            <p class="mt-1 text-sm text-gray-600">Contact your administrator to obtain a staff registration code.</p>
            <x-input-error :messages="$errors->get('admin_code')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-green-600 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-guest-layout>
