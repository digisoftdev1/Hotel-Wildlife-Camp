<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->has('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="overflow-x-auto">
                        <div class="flex justify-between items-center mb-3">
                            <input type="text" id="usersSearch" placeholder="Search users..."
                                class="p-2 border border-gray-300 rounded w-1/3">

                            @if (auth()->user()->role == 'superadmin')
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center px-3 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Add New User
                                </a>
                            @endif

                        </div>


                        <table id="usersTable" class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Username</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if ($user->role === 'admin') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>


                                            <form action="{{ route('users.toggleAdmin', $user->id) }}" method="POST"
                                                class="inline-block mr-3"
                                                onsubmit="return confirm('Are you sure you want to change admin status?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="px-3 py-1 rounded-md text-xs font-semibold border
                                                            @if ($user->role === 'admin') bg-red-100 text-red-700 border-red-300 hover:bg-red-200
                                                            @else bg-yellow-100 text-yellow-700 border-yellow-300 hover:bg-yellow-200 @endif">
                                                    @if ($user->role === 'admin')
                                                        Revoke Admin
                                                    @else
                                                        Make Admin
                                                    @endif
                                                </button>
                                            </form>


                                            @if (auth()->user()->role === 'superadmin')
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    class="inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 rounded-md text-xs font-semibold border bg-red-100 text-red-700 border-red-300 hover:bg-red-200">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('users.change-password.form', $user->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 ml-3 underline">Change
                                                password</a>


                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->

                </div>
            </div>
        </div>
    </div>

    <!-- DataTable Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('usersTable');
            const searchInput = document.getElementById('usersSearch');
            const rows = Array.from(table.querySelectorAll('tbody tr'));

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                rows.forEach(row => {
                    const text = row.innerText.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        });
    </script>
</x-app-layout>
