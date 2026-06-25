<x-guest-layout>
    <div class="w-full max-w-md">
        <div class=" p-8">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <div class="flex items-center justify-center mx-auto w-48 h-24">
                    <img src="{{ asset('assets/logo/logo.png') }}" alt="Logo"
                        class="w-full h-full object-contain drop-shadow-sm">
                </div>
                <h1 class="text-2xl font-extrabold tracking-wide text-gray-900 leading-tight">
                    <span class="text-lg font-semibold text-gray-600 uppercase tracking-widest">
                        Admin
                    </span>
                </h1>
                <p class="text-gray-600 mt-2">Sign in to your account</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Username/Email Field -->
                <div>

                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input type="text" id="login" name="login" value="{{ old('login') }}"
                            placeholder="Enter your username or email"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                            required autofocus autocomplete="username">
                    </div>
                    <x-input-error :messages="$errors->get('login')" class="mt-2" />
                </div>

                <!-- Password Field -->
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" placeholder="Enter your password"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-200"
                            required autocomplete="current-password">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember_me" name="remember"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                            {{ __('Remember me') }}
                        </label>
                    </div>

                </div>

                {!! NoCaptcha::display() !!}
                @error('g-recaptcha-response')
                    <p class="text-red-600 text-sm mt-2">{{ __('Please verify the reCAPTCHA') }}</p>
                @enderror

                <!-- Login Button -->
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-200 font-medium">
                    {{ __('Sign In') }}
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-xs text-gray-500">
                    © 2026 Hotel Wildlife Camp Admin Panel. All rights reserved.
                </p>
            </div>
            <footer class="text-center mt-4 flex justify-center">
                <p class="text-xs text-gray-500 flex items-center gap-1.5">
                    Developed by
                    <a href="https://www.digisoft.com.np" target="_blank"
                        class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 transition-colors font-medium">
                        <img src="{{ asset('assets/logo/company-logo.png') }}" alt="Digisoft Logo"
                            class="w-5 h-5 rounded-full object-cover shadow-sm bg-white">
                        Digisoft Developers Pvt. Ltd.
                    </a>
                </p>
            </footer>
        </div>
    </div>
    {!! NoCaptcha::renderJs() !!}

</x-guest-layout>
