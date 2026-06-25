<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($user) ? __('Edit User') : __('Add New User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('useradded'))
                        <div class="bg-green-500 text-white p-3 rounded mb-3">
                            {{ session('useradded') }}
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ isset($user) ? route('users.update', $user->id) : route('register.user') }}">
                        @csrf
                        @if (isset($user))
                            @method('PATCH')
                        @endif


                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                value="{{ old('name', $user->name ?? '') }}" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>


                        <div class="mb-4">
                            <x-input-label for="username" :value="__('Username')" />
                            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username"
                                value="{{ old('username', $user->username ?? '') }}" required />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        </div>


                        <div class="mb-4">
                            <x-input-label for="contact" :value="__('Contact')" />
                            <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact"
                                value="{{ old('contact', $user->contact ?? '') }}" />
                            <x-input-error :messages="$errors->get('contact')" class="mt-2" />
                        </div>


                        <div class="mb-4">
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address"
                                value="{{ old('address', $user->address ?? '') }}" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>


                        <div class="mb-4">
                            <x-input-label for="email" :value="__('Email (Optional)')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                value="{{ old('email', $user->email ?? '') }}" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        @if (!isset($user))
                            @if (auth()->user()->role === 'superadmin' || auth()->user()->role === 'admin')
                                <div class="mb-4">
                                    <x-input-label for="password" :value="__('Password')" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                                        name="password" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                        name="password_confirmation" autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            @endif
                        @endif


                        <div class="mb-4">
                            <x-input-label for="role" :value="__('Role')" />
                            <select id="role" name="role"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                required>
                                <option value="">Select Role</option>
                                <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>
                                    User
                                </option>
                                @if (auth()->user()->role === 'superadmin')
                                    <option value="admin"
                                        {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>
                                        Admin
                                    </option>
                                @endif
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->has('error'))
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                {{ $errors->first('error') }}
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('users.list') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ isset($user) ? __('Update User') : __('Add User') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
