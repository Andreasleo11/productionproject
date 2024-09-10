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

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Assign Item Codes to Machines</h2>
                    <form method="POST" action="{{ route('daily-item-code.store') }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Schedule Date</label>
                            <input type="date" name="schedule_date" id="schedule_date"
                                class="block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Machine Name</label>
                            <select id="machine-selector" name="machine_id"
                                class="block w-full py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                required>
                                <option value="" selected disabled>-- Select Machine Name --</option>
                                @foreach ($machines as $machine)
                                    <option value="{{ $machine->id }}" data-tipe-mesin="{{ $machine->tipe_mesin }}">
                                        {{ $machine->name }}</option>
                                @endforeach
                            </select>
                            @error('machine_id')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        @for ($shift = 1; $shift <= 3; $shift++)
                            <div class="space-y-4 mb-6 border border-gray-400 rounded-md p-3">
                                <h3 class="text-md font-bold">Shift {{ $shift }}</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Item Code</label>
                                    <select name="item_codes[{{ $shift }}]" required
                                        class="item-code-selector mt-1 block w-full bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        disabled>
                                        <option value="" selected disabled>-- Select Item Code --</option>
                                        @foreach ($itemcodes as $itemcode)
                                            <option value="{{ $itemcode->item_code }}"
                                                data-tipe-mesin="{{ $itemcode->tipe_mesin }}">
                                                {{ $itemcode->item_code }}</option>
                                        @endforeach
                                    </select>
                                    @error("item_codes.{$shift}")
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                                    <input type="number" name="quantities[{{ $shift }}]" required
                                        class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error("quantities.{$shift}")
                                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="md:col-span-3 grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Start
                                            Time</label>
                                        <input type="time" name="start_times[{{ $shift }}]" required
                                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error("start_times.{$shift}")
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">End Time</label>
                                        <input type="time" name="end_times[{{ $shift }}]" required
                                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error("end_times.{$shift}")
                                            <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Hidden input to capture shift number -->
                                <input type="hidden" name="shifts[{{ $shift }}]" value="{{ $shift }}">
                                @error("shifts.{$shift}")
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endfor

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
            // Initialize TomSelect on all select elements only if they have valid options
            document.querySelectorAll('select').forEach(function(selectElement) {
                if (!selectElement.tomselect && selectElement.querySelector('option')) {
                    try {
                        new TomSelect(selectElement, {
                            create: false,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                    } catch (e) {
                        console.error('Error initializing TomSelect:', e);
                    }
                }
            });

            // Disable item code selectors initially
            const itemCodeSelectors = document.querySelectorAll('.item-code-selector');
            itemCodeSelectors.forEach(selector => selector.disabled = true);

            // Enable and filter item codes based on selected machine
            const machineSelector = document.getElementById('machine-selector');
            machineSelector.addEventListener('change', function() {
                const selectedMachineTipe = this.options[this.selectedIndex].getAttribute(
                    'data-tipe-mesin');

                itemCodeSelectors.forEach(function(selector) {
                    // Enable each item code selector
                    selector.disabled = false;

                    // Remove existing TomSelect instance before re-initializing
                    if (selector.tomselect) {
                        try {
                            selector.tomselect.destroy();
                        } catch (e) {
                            console.error('Error destroying TomSelect:', e);
                        }
                    }

                    // Define options variable and filter based on the selected machine type
                    const options = selector.querySelectorAll('option');
                    options.forEach(function(option) {
                        const optionTipeMesin = option.getAttribute('data-tipe-mesin');
                        if (optionTipeMesin && optionTipeMesin === selectedMachineTipe) {
                            option.style.display = 'block'; // Show matching item codes
                        } else if (option.value === "") {
                            option.style.display = 'block'; // Show default disabled option
                        } else {
                            option.style.display = 'none'; // Hide non-matching item codes
                        }
                    });

                    // Remove whitespace and invalid characters from options to prevent 'trim' errors
                    options.forEach(function(option) {
                        if (option.value) {
                            option.value = option.value.trim();
                        }
                        if (option.textContent) {
                            option.textContent = option.textContent.trim();
                        }
                    });

                    // Re-initialize TomSelect after filtering and sanitizing
                    try {
                        new TomSelect(selector, {
                            create: false,
                            sortField: {
                                field: "text",
                                direction: "asc"
                            }
                        });
                    } catch (e) {
                        console.error('Error initializing TomSelect after filtering:', e);
                    }

                    // Log to check which selectors are enabled and processed
                    console.log('Enabling selector for shift:', selector.name);
                    console.log('Filtered options for:', selector.name, options);
                });
            });
        });
    </script>
</x-app-layout>
