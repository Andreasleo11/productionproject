<div class="p-3">
    <!-- Display Success and Error Messages -->
    @if (session('success'))
        <div
            class="bg-green-100 border border-green-400 text-green-700 px-3 py-2 rounded mb-2"
            role="alert"
        >
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div
            class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded mb-2"
            role="alert"
        >
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div
            class="bg-red-100 border border-red-400 text-red-700 px-3 py-2 rounded"
            role="alert"
        >
            <strong class="font-bold">Error!</strong>
            <ul class="text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div
    class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-7xl mx-auto sm:px-4 lg:px-6 pt-6"
>
    <!-- Active Job Section -->
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-4 text-gray-900">
            <span class="font-bold text-md">Active Job:</span>

            @if ($itemCode)
                <span class="text-blue-500">{{ $itemCode }}</span>
            @else
                <span class="text-red-500">No item code scanned</span>
                <p class="text-gray-400 text-sm">
                    You must scan the master list barcode as assigned in the
                    daily item codes.
                </p>
                @if ($datas->isNotEmpty())
                    <div class="mt-1">
                        <form
                            action="{{ route('update.machine_job') }}"
                            method="POST"
                        >
                            @csrf
                            <div>
                                <input
                                    type="text"
                                    id="item_code"
                                    name="item_code"
                                    required
                                    class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('item_code') border-red-500 @enderror"
                                    placeholder="Item Code"
                                />
                                <button
                                    type="submit"
                                    class="py-1 px-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition inline-flex"
                                >
                                    Update Job
                                </button>
                                @error('item_code')
                                    <p class="text-red-500 text-xs mt-1">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Files Section -->
    <div class="bg-white shadow-sm sm:rounded-lg p-4">
        @if ($itemCode)
            <section>
                @if (count($files) > 1)
                    <div class="font-bold text-2xl mt-1">Files</div>
                    <div
                        class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2 mt-4"
                    >
                        @foreach ($files as $file)
                            <a
                                href="{{ asset('files/' . $file->name) }}"
                                data-fancybox="gallery"
                                data-caption="{{ 'files/' . $file->name }}"
                            >
                                <img
                                    class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                                    src="{{ asset('files/' . $file->name) }}"
                                    alt="{{ $file->name }}"
                                />
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-red-500 text-sm my-2">
                        No files attached to this item code.
                    </p>
                @endif
            </section>
        @else
            <div class="font-bold text-lg mt-1">Files</div>
            <p class="text-red-500 text-sm my-2">
                Please scan the master list first.
            </p>
        @endif
    </div>
</div>

<!-- Daily Production Plan Section -->
<div class="max-w-7xl mx-auto sm:px-4 lg:px-6 pt-6">
    <div class="bg-white shadow-sm sm:rounded-lg">
        <div class="p-4">
            <h3 class="text-lg font-bold mb-2">
                Daily Production Plan
                <span class="text-gray-400">(Assigned Item Code)</span>
            </h3>
            @if ($datas->isNotEmpty())
                <table
                    class="min-w-full bg-white shadow-md rounded-lg overflow-hidden text-center text-sm"
                >
                    <thead class="bg-indigo-100">
                        <tr>
                            <th class="py-1 px-2 text-gray-700">Item Code</th>
                            <th class="py-1 px-2 text-gray-700">Shift</th>
                            <th class="py-1 px-2 text-gray-700">Quantity</th>
                            <th class="py-1 px-2 text-gray-700">
                                Loss Package Quantity
                            </th>
                            <th class="py-1 px-2 text-gray-700">
                                Actual Quantity
                            </th>
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
                            @endphp

                            <tr class="bg-white border-b text-center">
                                <td class="py-1 px-2">
                                    {{ $data->item_code }}
                                </td>
                                <td class="py-1 px-2">
                                    {{ $data->shift }} ({{ $startTime }} -
                                    {{ $endTime }})
                                </td>
                                <td class="py-1 px-2">
                                    {{ $data->quantity }}
                                </td>
                                <td class="py-1 px-2">
                                    {{ $data->loss_package_quantity }}
                                </td>
                                <td class="py-1 px-2">
                                    {{ $data->actual_quantity }}
                                </td>
                                @if ($itemCode)
                                    <td class="py-1 px-2">
                                        <form
                                            action="{{ route('generate.itemcode.barcode', ['item_code' => $data->item_code, 'quantity' => $data->quantity]) }}"
                                            method="get"
                                        >
                                            <button
                                                class="p-2 text-sm rounded text-white focus:outline-none transition ease-in-out duration-150 {{ $data->item_code !== $itemCode ? 'bg-gray-500 cursor-not-allowed' : 'bg-indigo-500 hover:bg-indigo-800' }}"
                                                {{ $data->item_code !== $itemCode ? 'disabled' : '' }}
                                            >
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

<!-- SPK Section -->
<div class="max-w-7xl mx-auto sm:px-4 lg:px-6 pt-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
        @if ($uniquedata != null)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="text-2xl">SPK</div>
                    <table
                        class="min-w-full bg-white border border-gray-200 mt-2 text-sm"
                    >
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-1 px-2 border-b text-left">
                                    SPK Number
                                </th>
                                <th class="py-1 px-2 border-b text-left">
                                    Item Code
                                </th>
                                <th class="py-1 px-2 border-b text-left">
                                    Scanned Quantity
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($uniquedata as $data)
                                <tr class="bg-white border-b">
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

                    <form
                        method="GET"
                        action="{{ route('reset.jobs') }}"
                        class="mt-4"
                    >
                        <button
                            type="submit"
                            class="w-full py-2 px-4 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition"
                        >
                            Done
                        </button>
                    </form>
                </div>

                <div class="mt-8">
                    <h3 class="text-2xl font-bold mb-4">Scan Barcode</h3>
                    <form
                        action="{{ route('process.productionbarcode') }}"
                        method="POST"
                        class="space-y-6"
                    >
                        @csrf
                        <input
                            type="hidden"
                            id="uniqueData"
                            name="uniqueData"
                            value="{{ json_encode($uniquedata) }}"
                        />
                        <input
                            type="hidden"
                            id="datas"
                            name="datas"
                            value="{{ json_encode($datas) }}"
                        />

                        <div>
                            <label
                                for="spk_code"
                                class="block text-sm font-medium text-gray-700"
                            >
                                SPK Code:
                            </label>
                            <input
                                type="text"
                                id="spk_code"
                                name="spk_code"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <label
                                for="item_code"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Item Code:
                            </label>
                            <input
                                type="text"
                                id="item_code"
                                name="item_code"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <label
                                for="warehouse"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Warehouse:
                            </label>
                            <input
                                type="text"
                                id="warehouse"
                                name="warehouse"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <label
                                for="quantity"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Quantity:
                            </label>
                            <input
                                type="number"
                                id="quantity"
                                name="quantity"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <label
                                for="label"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Label:
                            </label>
                            <input
                                type="number"
                                id="label"
                                name="label"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            />
                        </div>
                        <div>
                            <button
                                type="submit"
                                class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition"
                            >
                                Scan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <p class="text-red-500 text-sm mt-4">
                There's no SPK related for this item code.
            </p>
        @endif
    </div>
</div>
