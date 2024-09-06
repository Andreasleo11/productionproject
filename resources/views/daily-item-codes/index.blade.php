<x-app-layout>
    <!-- Display Success Message -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Display Error Message -->
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- View Assigned Item Codes -->
    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold mb-6">Daily Item Codes Index</h1>
                <!-- Create Button -->
                <div class="mb-6">
                    <a href="{{ route('daily-item-code.create') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        + Create New Item Code
                    </a>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="w-full p-4">
                        <div class="bg-white shadow-md rounded-lg p-6">
                            <h2 class="text-lg font-semibold mb-4">Assigned Item Codes</h2>
                            <table class="w-full">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Machine Name</th>
                                        <th class="py-3 px-4 text-left">Status</th>
                                        <th class="py-3 px-4 text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($machines as $machine)
                                        @php
                                            $hasAssignedData =
                                                $machine->dailyItemCode && count($machine->dailyItemCode) > 0;
                                        @endphp

                                        <!-- Summary Row (collapsible) -->
                                        <tr class="bg-white">
                                            <!-- Machine Name -->
                                            <td class="py-2 px-4 border-b font-bold">
                                                {{ $machine->name }}
                                            </td>

                                            <!-- Status Badge -->
                                            <td class="py-2 px-4 border-b">
                                                @if ($hasAssignedData)
                                                    <span
                                                        class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full">Assigned</span>
                                                @else
                                                    <span
                                                        class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full">Not
                                                        Assigned</span>
                                                @endif
                                            </td>

                                            <!-- Action Button to Show Details -->
                                            <td class="py-2 px-4 border-b text-blue-500">
                                                <button onclick="toggleRowDetails({{ $machine->id }})"
                                                    class="hover:underline">
                                                    {{ $hasAssignedData ? 'View Details' : 'No Details' }}
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Collapsible Details Row with Improved Readability -->
                                        <tr id="details-row-{{ $machine->id }}" class="hidden">
                                            <td colspan="3" class="py-2 px-4 bg-gray-50">
                                                @if ($hasAssignedData)
                                                    <div class="p-4 border rounded-lg bg-white shadow-sm">
                                                        <h3 class="text-lg font-semibold mb-3">Details for
                                                            {{ $machine->name }}</h3>
                                                        <table class="w-full text-sm">
                                                            <thead>
                                                                <tr class="border-b">
                                                                    <th class="py-2 px-4 text-left">Item Code</th>
                                                                    <th class="py-2 px-4 text-left">Quantity</th>
                                                                    <th class="py-2 px-4 text-left">Shift</th>
                                                                    <th class="py-2 px-4 text-left">Start Time</th>
                                                                    <th class="py-2 px-4 text-left">End Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($machine->dailyItemCode as $itemCode)
                                                                    <tr class="border-b">
                                                                        <td class="py-2 px-4">
                                                                            {{ $itemCode->item_code }}
                                                                        </td>
                                                                        <td class="py-2 px-4">
                                                                            {{ $itemCode->quantity }}
                                                                        </td>
                                                                        <td class="py-2 px-4">
                                                                            {{ $itemCode->shift }}</td>
                                                                        <td class="py-2 px-4">
                                                                            {{ $itemCode->start_time }}
                                                                        </td>
                                                                        <td class="py-2 px-4">
                                                                            {{ $itemCode->end_time }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                @else
                                                    <span class="text-red-500">No data assigned for this
                                                        machine</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleRowDetails(machineId) {
            var detailsRow = document.getElementById('details-row-' + machineId);
            detailsRow.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
