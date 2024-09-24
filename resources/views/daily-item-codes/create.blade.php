<x-app-layout>
    <!-- Display Success and Error Messages -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Assign Item Codes to Machines</h2>
                    <form method="POST" action="{{ route('daily-item-code.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Schedule Date -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Schedule Date</label>
                                <input type="date" name="schedule_date" id="schedule_date"
                                    class="block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                    value="{{ old('schedule_date') }}" required>
                                @error('schedule_date')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Machine Selector -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Machine Name</label>
                                <select id="machine-selector" name="machine_id"
                                    class="block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                    required>
                                    <option value="" selected disabled>-- Select Machine Name --</option>
                                    @foreach ($machines as $machine)
                                        <option value="{{ $machine->id }}" data-tipe-mesin="{{ $machine->tipe_mesin }}"
                                            {{ old('machine_id') == $machine->id ? 'selected' : '' }}>
                                            {{ $machine->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('machine_id')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Shift Checkboxes -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Select Shifts</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" class="shift-checkbox" name="shifts[]" value="1"
                                        {{ is_array(old('shifts')) && in_array('1', old('shifts')) ? 'checked' : '' }}>
                                    <span class="ml-2">Shift 1</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="shift-checkbox" name="shifts[]" value="2"
                                        {{ is_array(old('shifts')) && in_array('2', old('shifts')) ? 'checked' : '' }}>
                                    <span class="ml-2">Shift 2</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="shift-checkbox" name="shifts[]" value="3"
                                        {{ is_array(old('shifts')) && in_array('3', old('shifts')) ? 'checked' : '' }}>
                                    <span class="ml-2">Shift 3</span>
                                </label>
                            </div>
                            @error('shifts')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dynamic Shift Inputs -->
                        <div id="shift-container">
                            @if (old('shifts'))
                                @foreach (old('shifts') as $shift)
                                    <div class="space-y-4 mb-6 border border-gray-400 rounded-md p-3">
                                        <h3 class="text-md font-bold">Shift {{ $shift }}</h3>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Item Code</label>
                                            <select name="item_codes[{{ $shift }}]" required
                                                class="item-code-selector mt-1 block w-full bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="" disabled>-- Select Item Code --</option>
                                                @foreach ($itemcodes as $itemcode)
                                                    <option value="{{ $itemcode->item_code }}"
                                                        {{ old("item_codes.$shift") == $itemcode->item_code ? 'selected' : '' }}>
                                                        {{ $itemcode->item_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("item_codes.$shift")
                                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                            <input type="number" name="quantities[{{ $shift }}]"
                                                value="{{ old("quantities.$shift") }}" required
                                                class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error("quantities.$shift")
                                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Start and End Date Inputs -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Start
                                                    Date</label>
                                                <input type="date" name="start_dates[{{ $shift }}]"
                                                    value="{{ old("start_dates.$shift") }}" required
                                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error("start_dates.$shift")
                                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                                <input type="date" name="end_dates[{{ $shift }}]"
                                                    value="{{ old("end_dates.$shift") }}" required
                                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error("end_dates.$shift")
                                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Start
                                                    Time</label>
                                                <input type="time" name="start_times[{{ $shift }}]" required
                                                    value="{{ old("start_times.$shift") }}"
                                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error("start_times.$shift")
                                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">End Time</label>
                                                <input type="time" name="end_times[{{ $shift }}]" required
                                                    value="{{ old("end_times.$shift") }}"
                                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @error("end_times.$shift")
                                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const shiftContainer = document.getElementById('shift-container');
            const checkboxes = document.querySelectorAll('.shift-checkbox');
            const maxShifts = 3; // Limit to 3 shifts max

            // Clear the container and add shift inputs dynamically
            function updateShiftInputs() {
                shiftContainer.innerHTML = ''; // Clear the shift container

                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        const shift = checkbox.value;
                        const shiftHtml = `
                            <div class="space-y-4 mb-6 border border-gray-400 rounded-md p-3">
                                <h3 class="text-md font-bold">Shift ${shift}</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Item Code</label>
                                    <select name="item_codes[${shift}]" required
                                        class="item-code-selector mt-1 block w-full bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        disabled>
                                        <option value="" selected disabled>-- Select Item Code --</option>
                                        @foreach ($itemcodes as $itemcode)
                                            <option value="{{ $itemcode->item_code }}" data-tipe-mesin="{{ $itemcode->tipe_mesin }}">
                                                {{ $itemcode->item_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantities[${shift}]" required
                                        class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <!-- Start Date and End Date -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" name="start_dates[${shift}]" required
                                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" name="end_dates[${shift}]" required
                                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>

                                <!-- Start Time and End Time -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Start Time</label>
                                        <input type="time" name="start_times[${shift}]" required
                                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">End Time</label>
                                        <input type="time" name="end_times[${shift}]" required
                                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                        `;
                        shiftContainer.insertAdjacentHTML('beforeend', shiftHtml);
                    }
                });

                // Initialize TomSelect on the newly added item code selectors
                document.querySelectorAll('.item-code-selector').forEach(selector => {
                    selector.disabled = false;
                    if (!selector.tomselect) {
                        new TomSelect(selector, {
                            create: false,
                        });
                    }
                });
            }

            // Add event listener for each checkbox
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Ensure the user can't select more than 3 shifts
                    const selectedShifts = Array.from(checkboxes).filter(checkbox => checkbox
                        .checked);
                    if (selectedShifts.length > maxShifts) {
                        alert(`You can only select a maximum of ${maxShifts} shifts.`);
                        checkbox.checked = false; // Deselect if the limit is reached
                    }
                    updateShiftInputs();
                });
            });
        });
    </script>
</x-app-layout>
