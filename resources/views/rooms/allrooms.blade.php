 @props(['rooms' => []])

 <x-app-layout>
     <x-slot name="header">
         <h2 class="font-semibold text-xl text-gray-800 leading-tight">
             {{ __('Rooms') }}
         </h2>
         <p class="mt-1 text-sm text-gray-500">List of rooms added</p>
     </x-slot>

     <div class="py-12">
         <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
             <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                 <div class="p-6 text-gray-900">

                     <!-- Top controls -->
                     <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                         <!-- Entries select -->
                         <div class="flex items-center gap-2">
                             <label for="entriesSelect" class="text-sm text-gray-700">Show</label>
                             <select id="entriesSelect"
                                 class="border border-gray-300 w-16 rounded-md px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                 <option value="10">10</option>
                                 <option value="25">25</option>
                                 <option value="50">50</option>
                                 <option value="100">100</option>
                             </select>
                             <span class="text-sm text-gray-700">entries</span>
                         </div>

                         <!-- Search -->
                         <div class="w-full sm:w-auto">
                             <div class="relative">
                                 <input type="text" id="searchInput" placeholder="Search highlights..."
                                     class="w-full sm:w-64 border border-gray-300 rounded-md pl-10 pr-4 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                 <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400" fill="none"
                                     stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                         d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                 </svg>
                             </div>
                         </div>
                     </div>

                     <!-- Table -->
                     <div class=" rounded-lg border border-gray-200">
                         <table id="highlightsTable" class="min-w-full divide-y divide-gray-200 table-auto">
                             <thead class="bg-gray-50">
                                 <tr>
                                     <th
                                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                     </th>
                                     <th
                                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                         Room</th>
                                     <th
                                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                         Headline</th>
                                     <th
                                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                         Capacity</th>
                                     <th
                                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                         Price/Night</th>
                                     <th
                                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                         Status</th>

                                     <th
                                         class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                         Actions</th>
                                 </tr>
                             </thead>
                             <tbody class="bg-white divide-y divide-gray-200">
                                 @foreach ($rooms as $activity)
                                     <tr class="hover:bg-gray-50 transition-colors">
                                         <td class="px-2  whitespace-nowrap">
                                             <img src="{{ Storage::disk('public')->url($activity->featured_image) }}"
                                                 alt="{{ $activity->activity_name }}"
                                                 class="rounded-md object-contain h-20 w-20">
                                         </td>
                                         <td class="px-6 py-4">
                                             <div class="flex items-center gap-2">
                                                 <span class="text-sm font-medium text-gray-900">
                                                     {{ $activity->room_name }}
                                                 </span>


                                             </div>
                                         </td>

                                         <td class="px-6 py-4">
                                             <div class="flex items-center gap-2">
                                                 <span class="text-sm font-medium text-gray-900">
                                                     {{ $activity->headline }}
                                                 </span>
                                             </div>
                                         </td>
                                         <td class="px-6 py-4">
                                             <div class="flex items-center gap-2">
                                                 <span class="text-sm font-medium text-gray-900">
                                                     {{ $activity->occupancy }}
                                                 </span>
                                             </div>
                                         </td>
                                         <td class="px-6 py-4">
                                             <div class="flex items-center gap-2">
                                                 <span class="text-sm font-medium text-gray-900">
                                                     {{ $activity->currency->sign }} {{ $activity->price }}
                                                 </span>
                                             </div>
                                         </td>
                                         <td class="px-6 py-4">
                                             <div class="flex items-center gap-2">
                                                 <span class="text-sm font-medium text-gray-900">
                                                     @if ($activity->status === 'published')
                                                         <span
                                                             class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 
                                                        text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                                                             Published
                                                         </span>
                                                     @elseif ($activity->status === 'draft')
                                                         <span
                                                             class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 
                                                        text-xs font-semibold text-gray-700 ring-1 ring-inset ring-gray-500/20">
                                                             Draft
                                                         </span>
                                                     @endif
                                                 </span>
                                             </div>
                                         </td>
                                         <td class="px-6 py-4 whitespace-nowrap">
                                             <a href="{{ route('rooms.edit', $activity->id) }}"
                                                 class="text-blue-600 underline hover:text-blue-800 font-medium text-sm">Edit</a>

                                             <form action="{{ route('rooms.destroy', $activity->id) }}" method="POST"
                                                 class="inline">
                                                 @csrf
                                                 @method('DELETE')
                                                 <button type="submit"
                                                     class="text-red-600 underline hover:text-red-800 font-medium text-sm ml-4"
                                                     onclick="return confirm('Are you sure you want to delete this highlight?')">Delete</button>
                                             </form>
                                         </td>
                                     </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>

                     <!-- Table info -->
                     <div class="mt-4 text-sm text-gray-700" id="tableInfo">
                         Showing <span id="startEntry">1</span> to <span id="endEntry">{{ count($rooms) }}</span>
                         of <span id="totalEntries">{{ count($rooms) }}</span> entries
                     </div>

                 </div>
             </div>
         </div>
     </div>

     @push('scripts')
         <script type="module">
             $(document).ready(function() {
                 const table = $('#highlightsTable').DataTable({
                     dom: 't',
                     pageLength: 10,
                     language: {
                         emptyTable: "No rooms  added"
                     }
                 });

                 $('#searchInput').on('keyup', function() {
                     table.search(this.value).draw();
                 });

                 $('#entriesSelect').on('change', function() {
                     table.page.len(this.value).draw();
                 });

                 table.on('draw', function() {
                     const info = table.page.info();
                     $('#startEntry').text(info.start + 1);
                     $('#endEntry').text(info.end);
                     $('#totalEntries').text(info.recordsTotal);
                 });
             });
         </script>
     @endpush
 </x-app-layout>
