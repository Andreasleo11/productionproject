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
                <div class="mb-4">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center">
                                <a href="{{ route('daily-item-code.index') }}"
                                    class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-3 h-3 me-2.5 size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                    </svg>
                                    Daily Production Calendar
                                </a>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <a href="{{ route('daily-item-code.daily', ['date' => $selected_date]) }}"
                                        class="ms-1 text-sm font-medium md:ms-2 text-gray-400 hover:text-300">Daily
                                        Production Plan</a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <span
                                        class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Assign</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">
                        Assign Item Codes to Machines
                    </h2>
                    <form id="input-form" method="POST" action="{{ route('daily-item-code.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Schedule Date -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Schedule Date
                                </label>
                                <input type="date" name="schedule_date" id="schedule_date"
                                    class="block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                    value="{{ old('schedule_date', $selected_date) }}" required />
                                @error('schedule_date')
                                    <div class="text-red-500 text-sm mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Machine Selector -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Machine Name
                                </label>
                                <select id="machine-selector" name="machine_id"
                                    class="block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                    required>
                                    <option value="" selected disabled>
                                        -- Select Machine Name --
                                    </option>
                                    @foreach ($machines as $machine)
                                        <option value="{{ $machine->id }}" data-tipe-mesin="{{ $machine->tipe_mesin }}"
                                            {{ old('machine_id', $machine_id) == $machine->id ? 'selected' : '' }}>
                                            {{ $machine->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('machine_id')
                                    <div class="text-red-500 text-sm mt-1">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Shift Checkboxes -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">
                                Select Shifts
                            </label>
                            <div id="max-quantity-display" class="max-quantity-display">
                                Available Quantity :
                            </div>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="checkbox" class="shift-checkbox" name="shifts[]" value="1"
                                        {{ is_array(old('shifts')) && in_array('1', old('shifts')) ? 'checked' : '' }} />
                                    <span class="ml-2">Shift 1</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="shift-checkbox" name="shifts[]" value="2"
                                        {{ is_array(old('shifts')) && in_array('2', old('shifts')) ? 'checked' : '' }} />
                                    <span class="ml-2">Shift 2</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="shift-checkbox" name="shifts[]" value="3"
                                        {{ is_array(old('shifts')) && in_array('3', old('shifts')) ? 'checked' : '' }} />
                                    <span class="ml-2">Shift 3</span>
                                </label>
                            </div>
                            @error('shifts')
                                <div class="text-red-500 text-sm mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Dynamic Shift Inputs -->
                        <div id="shift-container">
                            @if (old('shifts'))
                                @foreach (old('shifts') as $shift)
                                    <div class="space-y-4 mb-6 border border-gray-400 rounded-md p-3">
                                        <h3 class="text-md font-bold">
                                            Shift {{ $shift }}
                                        </h3>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                Item Code
                                            </label>
                                            <select name="item_codes[{{ $shift }}]" required
                                                class="item-code-selector mt-1 block w-full bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="" disabled>
                                                    -- Select Item Code --
                                                </option>
                                                @foreach ($itemcodes as $itemcode)
                                                    <option value="{{ $itemcode->item_code }}"
                                                        {{ old("item_codes.$shift") == $itemcode->item_code ? 'selected' : '' }}>
                                                        {{ $itemcode->item_code }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error("item_codes.$shift")
                                                <div class="text-red-500 text-sm mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">
                                                Quantity
                                            </label>
                                            <input type="number" name="quantities[{{ $shift }}]"
                                                value="{{ old("quantities.$shift") }}" required
                                                class="quantity-input mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                            @error("quantities.$shift")
                                                <div class="text-red-500 text-sm mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <!-- Start and End Date Inputs -->
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Start Date
                                                </label>
                                                <input type="date" name="start_dates[{{ $shift }}]"
                                                    value="{{ old("start_dates.$shift") }}" required
                                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                                @error("start_dates.$shift")
                                                    <div class="text-red-500 text-sm mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    End Date
                                                </label>
                                                <input type="date" name="end_dates[{{ $shift }}]"
                                                    value="{{ old("end_dates.$shift") }}" required
                                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                                @error("end_dates.$shift")
                                                    <div class="text-red-500 text-sm mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    Start Time
                                                </label>
                                                <input type="time" name="start_times[{{ $shift }}]"
                                                    required value="{{ old("start_times.$shift") }}"
                                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                                @error("start_times.$shift")
                                                    <div class="text-red-500 text-sm mt-1">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">
                                                    End Time
                                                </label>
                                                <input type="time" name="end_times[{{ $shift }}]" required
                                                    value="{{ old("end_times.$shift") }}"
                                                    class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                                                @error("end_times.$shift")
                                                    <div class="text-red-500 text-sm mt-1">
                                                        {{ $message }}
                                                    </div>
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

            const itemCodeMaxQuantities = {};

            const form = document.getElementById('input-form');
            const maxQuantityDisplay = document.getElementById('max-quantity-display');
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
                                        class="item-code-selector mt-1 block w-full bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="" selected disabled>-- Select Item Code --</option>
                                        @foreach ($itemcodes as $itemcode)
                                            <option value="{{ $itemcode->item_code }}" data-tipe-mesin="{{ $itemcode->tipe_mesin }}">
                                                {{ $itemcode->item_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantities[${shift}]" id="quantity-input-${shift}" required
                                        class="quantity-input mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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

                // Function to trigger AJAX call
                function triggerAjax(shift, itemCode, quantity) {
                    // Check if we already have a max_quantity stored for this item_code
                    let maxQuantity = itemCodeMaxQuantities[itemCode] || null;

                    if (!maxQuantity && itemCode && quantity) {
                        // Make AJAX call if max_quantity isn't stored yet
                        fetch("{{ route('calculate.item') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    item_code: itemCode,
                                    quantity: quantity
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    alert(data.error);
                                } else {
                                    // Store the max_quantity for this item_code
                                    itemCodeMaxQuantities[itemCode] = data.max_quantity - quantity;
                                    console.log('Calculated Data:', data);

                                    // Update the display with item_code and updated max_quantity
                                    updateMaxQuantityDisplay(itemCode, itemCodeMaxQuantities[itemCode]);

                                    if (!maxQuantity && itemCode && quantity >= 0) {
                                        fetch("{{ route('calculate.item') }}", {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    item_code: itemCode,
                                                    quantity: quantity
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.error) {
                                                    alert(data.error);
                                                } else {
                                                    let remainingQuantity = data.max_quantity - quantity;
                                                    itemCodeMaxQuantities[itemCode] = remainingQuantity;

                                                    // Update the UI with the remaining quantity
                                                    updateMaxQuantityDisplay(itemCode, remainingQuantity);
                                                    // Check if the next shift has the same item_code, and continue calculation
                                                    processNextShift(shift, itemCode);
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                            });
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                    // Handle existing maxQuantity logic (not shown for brevity)
                }

                // Helper function to reset all quantities for the given item_code
                function resetAllQuantitiesForItem(itemCode) {
                    itemCodeMaxQuantities[itemCode] = originalMaxQuantities[itemCode];
                    updateMaxQuantityDisplay(itemCode, '');
                    document.querySelectorAll(`select[name^="item_codes"]`).forEach((selector) => {
                        if (selector.value === itemCode) {
                            const shift = selector.name.match(/\d+/)[0];
                            const quantityInput = document.querySelector(
                                `input[name="quantities[${shift}]"]`);
                            if (quantityInput) {
                                quantityInput.value = ''; // Clear the quantity input field
                            }
                        }
                    });
                }

                function updateMaxQuantityDisplay(itemCode, maxQuantity) {
                    const displayContainer = document.getElementById('max-quantity-display');
                    let itemDisplay = document.querySelector(`[data-item-code="${itemCode}"]`);
                    if (!itemDisplay) {
                        itemDisplay = document.createElement('div');
                        itemDisplay.setAttribute('data-item-code', itemCode);
                        displayContainer.appendChild(itemDisplay);
                    }
                    itemDisplay.textContent = `${itemCode}: ${maxQuantity}`;
                }

                // Function to process the next shift with the same item_code
                function processNextShift(currentShift, itemCode) {
                    const nextShift = parseInt(currentShift) + 1;
                    const nextItemCodeSelector = document.querySelector(`select[name="item_codes[${nextShift}]"]`);
                    const nextQuantityInput = document.querySelector(`input[name="quantities[${nextShift}]"]`);

                    if (nextItemCodeSelector && nextItemCodeSelector.value === itemCode && nextQuantityInput) {
                        const nextQuantity = parseFloat(nextQuantityInput.value);
                        if (nextQuantity) {
                            triggerAjax(nextShift, itemCode, nextQuantity);
                        }
                    }
                }

                // Add event listener for item code change
                document.querySelectorAll('.item-code-selector').forEach(selector => {
                    selector.addEventListener('change', function() {
                        const itemCode = this.value;
                        const shift = this.name.match(/\d+/)[
                            0]; // Extract shift number from name attribute
                        const quantity = document.querySelector(
                            `input[name="quantities[${shift}]"]`).value;

                        // Trigger AJAX when both item_code and quantity are provided
                        if (itemCode && quantity) {
                            triggerAjax(shift, itemCode, quantity);
                        }
                    });
                });

                document.querySelectorAll('.quantity-input').forEach(input => {
                    let typingTimer; // Timer identifier
                    const doneTypingInterval = 500; // Time in ms, 0.5 seconds

                    input.addEventListener('keyup', function() {
                        clearTimeout(typingTimer);
                        typingTimer = setTimeout(() => {
                            const shift = this.name.match(/\d+/)[
                                0]; // Extract shift number from name attribute
                            const itemCode = document.querySelector(
                                `select[name="item_codes[${shift}]"]`).value;
                            const quantity = this.value;

                            // Only call triggerAjax if both itemCode and quantity are present
                            if (itemCode && quantity) {
                                triggerAjax(shift, itemCode, quantity);
                            }
                        }, doneTypingInterval);
                    });

                    input.addEventListener('keydown', function() {
                        clearTimeout(typingTimer);
                    });
                });
            }

            // Update shift inputs on checkbox change
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (checkbox.checked) {
                        const checkedCount = Array.from(checkboxes).filter(chk => chk.checked)
                            .length;
                        if (checkedCount <= maxShifts) {
                            updateShiftInputs();
                        } else {
                            checkbox.checked = false; // Prevent selecting more than maxShifts
                        }
                    } else {
                        updateShiftInputs(); // Update the UI
                    }
                });
            });
        });
    </script>

</x-app-layout>
