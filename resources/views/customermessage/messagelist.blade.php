<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Messages') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Welcome back {{ ucfirst(auth()->user()->name) }}. Here are the customer messages.
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search and Filter Section -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                </div>
                                <input type="text" id="searchInput"
                                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    placeholder="Search by name, email, or subject...">
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="sm:w-48">
                            <select id="statusFilter"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="all">All Status</option>
                                <option value="new">New</option>
                                <option value="read">Read</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="-m-1.5 overflow-x-auto">
                            <div class="p-1.5 min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Name</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Email</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Phone</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Subject</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status</th>
                                                <th scope="col"
                                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($customers as $customer)
                                                <tr class="hover:bg-gray-50 transition-colors duration-150 customer-row"
                                                    data-name="{{ strtolower($customer->name) }}"
                                                    data-email="{{ strtolower($customer->email) }}"
                                                    data-subject="{{ strtolower($customer->subject->name ?? '') }}"
                                                    data-status="{{ $customer->status }}">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $customer->name }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">{{ $customer->phone ?? '-' }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-900">
                                                            {{ $customer->subject->name ?? '-' }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span id="status-badge-{{ $customer->id }}"
                                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            {{ $customer->status === 'new' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                            {{ ucfirst($customer->status) }}
                                                        </span>
                                                    </td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <button
                                                            onclick='openModal(
                                                                {{ $customer->id }},
                                                                @json($customer->name),
                                                                @json($customer->email),
                                                                @json($customer->phone ?? "-"),
                                                                @json($customer->subject->name ?? "-"),
                                                                @json($customer->message)
                                                            )'
                                                            class="inline-flex items-center gap-x-2 px-3 py-2 text-sm font-semibold rounded-lg border border-transparent text-blue-600 hover:text-white hover:bg-blue-600 transition-all duration-150">
                                                            <i class="fa-solid fa-eye"></i>
                                                            View
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            @if ($customers->isEmpty())
                                                <tr>
                                                    <td colspan="6"
                                                        class="px-6 py-8 text-center text-sm text-gray-500">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <i
                                                                class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
                                                            <p class="font-medium">No customer messages found.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="messageModal"
        class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-lg bg-white">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Message Details</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors duration-150">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Modal Content -->
            <div class="mt-5 space-y-5">
                <!-- Name -->
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-user text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p class="font-medium text-gray-900" id="modalName"></p>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900" id="modalEmail"></p>
                    </div>
                </div>

                <!-- Phone -->
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-phone text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium text-gray-900" id="modalPhone"></p>
                    </div>
                </div>

                <!-- Subject -->
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-file-lines text-sm"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Subject</p>
                        <p class="font-medium text-gray-900" id="modalSubject"></p>
                    </div>
                </div>

                <!-- Message -->
                <div class="flex items-start gap-3">
                    <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                        <i class="fa-solid fa-message text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500">Message</p>
                        <p class="font-medium leading-relaxed text-gray-800 bg-gray-50 p-3 rounded-lg mt-1"
                            id="modalMessage"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id, name, email, phone, subject, message) {

            document.getElementById('modalName').textContent = name;
            document.getElementById('modalEmail').textContent = email;
            document.getElementById('modalPhone').textContent = phone;
            document.getElementById('modalSubject').textContent = subject;
            document.getElementById('modalMessage').textContent = message;

            document.getElementById('messageModal').classList.remove('hidden');


            fetch(`/customer-messages/${id}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                       
                        const badge = document.getElementById(`status-badge-${id}`);
                        if (badge) {
                            badge.textContent = 'Read';
                            badge.classList.remove('bg-green-100', 'text-green-800');
                            badge.classList.add('bg-gray-100', 'text-gray-800');
                        }

                        const row = badge.closest('tr');
                        if (row) {
                            row.setAttribute('data-status', 'read');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function closeModal() {
            document.getElementById('messageModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('messageModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Search and Filter Functionality
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const customerRows = document.querySelectorAll('.customer-row');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            let visibleCount = 0;

            customerRows.forEach(row => {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                const subject = row.getAttribute('data-subject');
                const status = row.getAttribute('data-status');


                const matchesSearch = searchTerm === '' ||
                    name.includes(searchTerm) ||
                    email.includes(searchTerm) ||
                    subject.includes(searchTerm);


                const matchesStatus = statusValue === 'all' || status === statusValue;


                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });


            updateNoResultsMessage(visibleCount);
        }

        function updateNoResultsMessage(visibleCount) {
            const tbody = document.querySelector('tbody');
            let noResultsRow = document.getElementById('no-results-row');

            if (visibleCount === 0 && customerRows.length > 0) {
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'no-results-row';
                    noResultsRow.innerHTML = `
                        <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-search text-4xl text-gray-300 mb-3"></i>
                                <p class="font-medium">No messages found matching your criteria.</p>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(noResultsRow);
                }
                noResultsRow.style.display = '';
            } else if (noResultsRow) {
                noResultsRow.style.display = 'none';
            }
        }

        searchInput.addEventListener('keyup', filterTable);
        statusFilter.addEventListener('change', filterTable);
    </script>
</x-app-layout>
