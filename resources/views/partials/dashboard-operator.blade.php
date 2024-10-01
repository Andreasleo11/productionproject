<!-- Display Success and Error Messages -->
<div class="p-3">
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-2" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-2" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

@if ($uniquedata != null)
    <!-- Scan Mode Toggle Section -->
    <div class="px-6">
        <div id="scanModeBanner" class="hidden p-3 bg-yellow-100 border border-yellow-500 text-yellow-700 rounded mb-4">
            <strong>Scan Mode is Active!</strong> Only the Scan Barcode section is visible. Please scan your items.
        </div>
    </div>

    <!-- Toggle Scan Mode -->
    <div class="flex justify-end px-6">
        <button id="toggleScanMode"
            class="py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition">
            Activate Scan Mode
        </button>
    </div>
@endif

<!-- Other Sections to be Hidden in Scan Mode -->
<div class="not-scan-section mt-2">
    <!-- Active Job Section -->
    <div class="mx-auto sm:px-4 lg:px-6 pt-2">
        <div class="bg-white shadow-sm sm:rounded-lg p-4">
            <div class="text-gray-900">
                <span class="font-bold ">Active Job:</span>
                @if ($itemCode)
                    <span class="text-blue-500">{{ $itemCode }}</span>
                @else
                    <span class="text-red-500">No item code scanned</span>
                    <p class="text-gray-400 text-sm">You must scan the master list barcode as assigned in the daily item
                        codes.</p>
                    @if ($datas->isNotEmpty())
                        <div class="mt-1">
                            <form action="{{ route('update.machine_job') }}" method="POST">
                                @csrf
                                <div>
                                    <input type="text" id="item_code" name="item_code" required
                                        class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('item_code') border-red-500 @enderror"
                                        placeholder="Item Code" />
                                    <button type="submit"
                                        class="py-1 px-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition inline-flex">
                                        Update Job
                                    </button>
                                    @error('item_code')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </form>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Files Section -->
    <div class="mx-auto sm:px-4 lg:px-6 pt-2">
        <div class="bg-white shadow-sm sm:rounded-lg p-4">
            @if ($itemCode)
                <section>
                    @if (count($files) > 1)
                        <div class="font-bold text-2xl">Files</div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 mt-4">
                            @foreach ($files as $file)
                                <a href="{{ asset('files/' . $file->name) }}" data-fancybox="gallery"
                                    data-caption="{{ $file->name }}">
                                    <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                                        src="{{ asset('files/' . $file->name) }}" alt="{{ $file->name }}" />
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-red-500 text-sm my-2">No files attached to this item code.</p>
                    @endif
                </section>
            @else
                <h1 class="font-bold text-lg">Files</h1>
                <p class="text-red-500 text-sm my-2">Please scan the master list first.</p>
            @endif
        </div>
    </div>

    <!-- Daily Production Plan Section -->
    <div class="mx-auto sm:px-4 lg:px-6 pt-6">
        <div class="bg-white shadow-sm sm:rounded-lg">
            <div class="p-4">
                <h3 class="text-xl font-bold mb-2">Daily Production Plan <span class="text-gray-400">(Assigned Item
                        Code)</span></h3>
                @if ($datas->isNotEmpty())
                    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden text-center mt-3">
                        <thead class="bg-indigo-100">
                            <tr>
                                <th class="py-1 px-2 text-gray-700">Item Code</th>
                                <th class="py-1 px-2 text-gray-700">Start Date - End Date</th>
                                <th class="py-1 px-2 text-gray-700">Shift</th>
                                <th class="py-1 px-2 text-gray-700">Quantity</th>
                                <th class="py-1 px-2 text-gray-700">Loss Package Quantity</th>
                                <th class="py-1 px-2 text-gray-700">Actual Quantity</th>
                                @if ($itemCode)
                                    <th class="py-1 px-2 text-gray-700">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datas as $data)
                                @php
                                    $startTime = \Carbon\Carbon::parse($data->start_time)->format('H:i');
                                    $endTime = \Carbon\Carbon::parse($data->end_time)->format('H:i');
                                    $startDate = \Carbon\Carbon::parse($data->start_date)->format('d/m/Y'); // Format start date as dd/mm/yyyy
                                    $endDate = \Carbon\Carbon::parse($data->end_date)->format('d/m/Y'); // Format end date as dd/mm/yyyy
                                @endphp

                                <tr class="bg-white border-b text-center">
                                    <td class="py-1 px-2">{{ $data->item_code }}</td>
                                    <td class="py-1 px-2">{{ $startDate }} - {{ $endDate }}</td>
                                    <td class="py-1 px-2">{{ $data->shift }} ({{ $startTime }} -
                                        {{ $endTime }})</td>
                                    <td class="py-1 px-2">{{ $data->quantity }}</td>
                                    <td class="py-1 px-2">{{ $data->loss_package_quantity }}</td>
                                    <td class="py-1 px-2">{{ $data->actual_quantity }}</td>
                                    @if ($itemCode)
                                        @php
                                            $disabled = $data->shift !== $machineJobShift;
                                        @endphp
                                        <td class="py-1 px-2">
                                            <form
                                                action="{{ route('generate.itemcode.barcode', ['item_code' => $data->item_code, 'quantity' => $data->quantity]) }}"
                                                method="get">
                                                <button
                                                    class="m-1 p-2 rounded text-white focus:outline-none transition ease-in-out duration-150 {{ $disabled ? 'bg-gray-500 cursor-not-allowed' : 'bg-indigo-500 hover:bg-indigo-800' }}"
                                                    {{ $disabled ? 'disabled' : '' }}>
                                                    Generate Barcode
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-red-500 text-sm">No assigned item code yet</p>
                @endif
            </div>
        </div>
    </div>
</div>
@if ($uniquedata != null)
    <div id="scanSection">
        <!-- SPK Section -->

        <div class="mx-auto sm:px-4 lg:px-6 pt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <span class="text-xl font-bold">SPK</span>
                <table class="min-w-full bg-white mt-2 rounded-md shadow-md overflow-hidden">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-1 px-2">
                                SPK Number
                            </th>
                            <th class="py-1 px-2">
                                Item Code
                            </th>
                            <th class="py-1 px-2">
                                Scanned Quantity
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($uniquedata as $data)
                            <tr class="bg-white text-center">
                                <td class="py-2 px-3">
                                    {{ $data['spk'] }}
                                </td>
                                <td class="py-2 px-3">
                                    {{ $data['item_code'] }}
                                </td>
                                <td class="py-2 px-3">
                                    {{ $data['scannedData'] }}/{{ $data['count'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Modal Structure with Fade and Scale for Modal, Fade for Background -->
                <div id="reasonModalOverlay"
                    class="fixed inset-0 bg-black bg-opacity-0 transition-opacity duration-300 hidden flex items-center justify-center">
                    <div id="reasonModal"
                        class="bg-white p-4 rounded-md w-96 opacity-0 transform scale-90 transition-all duration-300">
                        <h3 class="text-lg font-bold mb-2">Provide Reason</h3>
                        <p class="text-sm mb-2">The scanned quantity does not match the required count. Please provide a
                            reason:</p>
                        <textarea id="reason" name="reason" class="border border-gray-300 rounded-md shadow-sm w-full p-2" rows="3"
                            placeholder="Enter your reason..."></textarea>
                        <p id="reasonError" class="text-red-500 text-sm hidden mt-1">Reason is required!</p>
                        <div class="flex justify-end space-x-2 mt-4">
                            <button id="modalCancel"
                                class="py-2 px-4 bg-gray-500 text-white rounded-md hover:bg-gray-600">Cancel</button>
                            <button id="modalSubmit"
                                class="py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Submit</button>
                        </div>
                    </div>
                </div>

                <!-- Form for Reset Jobs -->
                <form method="GET" action="{{ route('reset.jobs') }}" id="resetJobsForm">
                    <input type="hidden" id="uniqueData" name="uniqueData"
                        value="{{ json_encode($uniquedata) }}" />
                    <input type="hidden" id="datas" name="datas" value="{{ json_encode($datas) }}" />
                    <input type="hidden" id="reasonInput" name="reason" value="">

                    <button type="button" id="resetJobsButton"
                        class="w-full py-2 px-4 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition mt-4">
                        Submit
                    </button>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const resetButton = document.getElementById('resetJobsButton');
                        const form = document.getElementById('resetJobsForm');
                        const modalOverlay = document.getElementById('reasonModalOverlay');
                        const modal = document.getElementById('reasonModal');
                        const reasonInput = document.getElementById('reasonInput');
                        const reasonField = document.getElementById('reason');
                        const reasonError = document.getElementById('reasonError');
                        const modalSubmit = document.getElementById('modalSubmit');
                        const modalCancel = document.getElementById('modalCancel');

                        function showModal() {
                            modalOverlay.classList.remove('hidden');
                            setTimeout(() => {
                                modalOverlay.classList.remove('bg-opacity-0');
                                modalOverlay.classList.add('bg-opacity-50');
                                modal.classList.remove('opacity-0', 'scale-90');
                            }, 10); // Small delay for transition to apply
                        }

                        function hideModal() {
                            modal.classList.add('opacity-0', 'scale-90');
                            modalOverlay.classList.remove('bg-opacity-50');
                            modalOverlay.classList.add('bg-opacity-0');
                            setTimeout(() => {
                                modalOverlay.classList.add('hidden');
                            }, 300); // Match the transition duration
                        }

                        resetButton.addEventListener('click', function() {
                            let showReasonModal = false;

                            @foreach ($uniquedata as $data)
                                if ({{ $data['scannedData'] }} !== {{ $data['count'] }}) {
                                    showReasonModal = true;
                                }
                            @endforeach

                            if (showReasonModal) {
                                showModal();
                            } else {
                                form.submit();
                            }
                        });

                        // Handle modal submit
                        modalSubmit.addEventListener('click', function() {
                            const reasonValue = reasonField.value.trim();

                            if (reasonValue === '') {
                                reasonError.classList.remove('hidden');
                                reasonField.classList.add('border-red-500');
                            } else {
                                reasonInput.value = reasonValue;
                                reasonError.classList.add('hidden');
                                reasonField.classList.remove('border-red-500');
                                hideModal();
                                setTimeout(() => {
                                    form.submit();
                                }, 300); // Match the transition duration
                            }
                        });

                        // Handle modal cancel
                        modalCancel.addEventListener('click', function() {
                            hideModal();
                        });
                    });
                </script>
            </div>
        </div>

        <!-- Scan Barcode Section -->
        <div class="mx-auto sm:px-4 lg:px-6 pt-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-4">
                <h3 class="text-xl font-bold">Scan Barcode</h3>
                <form id="scanForm" action="{{ route('process.productionbarcode') }}" method="POST"
                    class="space-y-3">
                    @csrf
                    <input type="hidden" id="uniqueData" name="uniqueData"
                        value="{{ json_encode($uniquedata) }}" />
                    <input type="hidden" id="datas" name="datas" value="{{ json_encode($datas) }}" />

                    <!-- Grid Layout for 2 Columns -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="spk_code">SPK Code</label>
                            <input type="text" id="spk_code" name="spk_code" required
                                class="border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full"
                                placeholder="SPK Code" />
                        </div>
                        <div>
                            <label for="item_code">Item Code</label>
                            <input type="text" id="item_code" name="item_code" required
                                class="border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full"
                                placeholder="Item Code" />
                        </div>
                        <div>
                            <label for="warehouse">Warehouse</label>
                            <input type="text" id="warehouse" name="warehouse" required
                                class="border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full"
                                placeholder="Warehouse" />
                        </div>
                        <div>
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="quantity" required
                                class="border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full"
                                placeholder="Quantity" />
                        </div>
                        <div>
                            <label for="label">Label</label>
                            <input type="number" id="label" name="label" required
                                class="border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 w-full"
                                placeholder="Label" />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition mt-4">
                        Scan
                    </button>
                </form>
            </div>
        </div>
    </div>
@else
    <div class="mx-auto sm:px-4 lg:px-6 pt-6">
        <div class="bg-white shadow-sm sm:rounded-lg p-4">
            <p class="text-red-500 text-sm ">
                There's no SPK related for this item code.
            </p>
        </div>
    </div>
@endif

<script type="module">
    Fancybox.bind('[data-fancybox="gallery"]', {
        Thumbs: {
            autoStart: true,
        },
        Image: {
            zoom: true,
        },
        transitionEffect: 'fade',
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let scanMode = localStorage.getItem('scanMode') === 'true';
        const toggleButton = document.getElementById('toggleScanMode');
        const scanSection = document.getElementById('scanSection');
        const spkCodeInput = document.getElementById('spk_code');
        const scanModeBanner = document.getElementById('scanModeBanner');
        const otherSections = document.querySelectorAll('.not-scan-section');

        // Function to update the UI for Scan Mode
        function updateScanMode() {
            if (scanMode) {
                otherSections.forEach(section => section.style.display = 'none');
                scanSection.style.display = 'block';
                scanModeBanner.style.display = 'block';
                toggleButton.innerText = 'Deactivate Scan Mode';
                toggleButton.classList.remove('bg-indigo-600');
                toggleButton.classList.add('bg-red-600', 'hover:bg-red-700');
                spkCodeInput.focus();
            } else {
                otherSections.forEach(section => section.style.display = 'block');
                scanSection.style.display = 'none';
                scanModeBanner.style.display = 'none';
                toggleButton.innerText = 'Activate Scan Mode';
                toggleButton.classList.remove('bg-red-600', 'hover:bg-red-700');
                toggleButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            }
        }

        // Toggle Scan Mode when the button is clicked
        toggleButton.addEventListener('click', function() {
            scanMode = !scanMode;
            localStorage.setItem('scanMode', scanMode); // Save state to local storage
            updateScanMode();
        });

        // Initialize the page based on Scan Mode
        updateScanMode();

        // Auto-submit the form when all inputs are filled
        const form = document.getElementById('scanForm');
        const inputs = form.querySelectorAll('input[type="text"], input[type="number"]');

        function checkAllInputsFilled() {
            for (let i = 0; i < inputs.length; i++) {
                if (inputs[i].value.trim() === '') {
                    return false;
                }
            }
            return true;
        }

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                if (checkAllInputsFilled()) {
                    form.submit(); // Auto-submit the form when all inputs are filled
                }
            });
        });
    });
</script>
