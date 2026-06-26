<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Currencies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Add Currency Form -->
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Currency</h3>
                <form action="{{ route('currencies.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="US Dollar" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Code</label>
                        <input type="text" name="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="USD" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Sign</label>
                        <input type="text" name="sign" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="$" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Add Currency</button>
                    </div>
                </form>
            </div>

            <!-- Currencies List -->
            <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sign</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($currencies as $currency)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $currency->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $currency->code }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $currency->sign }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <form action="{{ route('currencies.destroy', $currency->id) }}" method="POST" onsubmit="return confirm('Delete this currency?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
