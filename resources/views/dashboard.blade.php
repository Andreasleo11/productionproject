<x-app-layout>
    {{-- Header --}}
    {{-- <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">
                Dashboard
            </h1>
        </div>
    </div> --}}

    {{-- Content --}}
    @if (auth()->user()->specification->name === 'ADMINISTRATOR' || auth()->user()->specification->name === 'Operator')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                    <h2 class="text-3xl font-bold mb-4">Files</h2>
                    <div class="space-y-4">
                        <a href="{{ asset('a.pdf') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-500">
                            PDF A
                        </a>
                        <a href="{{ asset('b.pdf') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-500">
                            PDF B
                        </a>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-8">
                        <a href="{{ asset('a_pages-to-jpg-0001.jpg') }}" data-fancybox="gallery"
                            data-caption="Caption #1">
                            <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                                src="{{ asset('a_pages-to-jpg-0001.jpg') }}" alt="Image 1" />
                        </a>
                        <a href="{{ asset('a_pages-to-jpg-0002.jpg') }}" data-fancybox="gallery"
                            data-caption="Caption #2">
                            <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                                src="{{ asset('a_pages-to-jpg-0002.jpg') }}" alt="Image 2" />
                        </a>
                        <a href="{{ asset('a.pdf') }}" data-fancybox="gallery" data-caption="PDF 1">
                            <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                                src="{{ asset('a.pdf') }}" alt="PDF 1" />
                        </a>
                        <a href="{{ asset('b.pdf') }}" data-fancybox="gallery" data-caption="PDF 2">
                            <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                                src="{{ asset('b.pdf') }}" alt="PDF 2" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif (auth()->user()->specification->name === 'PE')
        <div class="flex justify-center items-center py-12">
            <span class="text-2xl font-semibold text-gray-600">PE USER</span>
        </div>
    @elseif (auth()->user()->specification->name === 'Machine')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                    <h2 class="text-3xl font-bold mb-4">Active Job</h2>
                    <span class="text-lg">
                        @if ($itemCode)
                            <span class="text-green-500">{{ $itemCode }}</span>
                        @else
                            <span class="text-red-500">No item code scanned</span>
                        @endif
                    </span>

                    @if ($files)
                        <h3 class="text-2xl font-bold mt-8">Files</h3>
                        <div class="space-y-4 mt-4">
                            @forelse ($files as $file)
                                <a href="{{ asset('files/' . $file->name) }}"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-500">
                                    {{ $file->name }}
                                </a>
                            @empty
                                <span class="text-red-400">No files for this item code yet</span>
                            @endforelse
                        </div>
                    @endif

                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-8">
                        @foreach ($files as $file)
                            <a href="{{ asset('files/' . $file->name) }}" data-fancybox="gallery"
                                data-caption="{{ 'files/' . $file->name }}">
                                <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                                    src="{{ asset('files/' . $file->name) }}" alt="{{ 'files/' . $file->name }}" />
                            </a>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        <h3 class="text-2xl font-bold mb-4">Assigned Daily Item Codes</h3>
                        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Item Code</th>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Quantity</th>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Loss Package Quantity</th>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Actual Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($datas as $data)
                                    <tr class="bg-white border-b">
                                        <td class="py-3 px-4 text-blue-500 hover:underline">
                                            <a
                                                href="{{ route('generate.itemcode.barcode', ['item_code' => $data->item_code, 'quantity' => $data->quantity]) }}">
                                                {{ $data->item_code }}
                                            </a>
                                        </td>
                                        <td class="py-3 px-4">{{ $data->quantity }}</td>
                                        <td class="py-3 px-4">{{ $data->loss_package_quantity }}</td>
                                        <td class="py-3 px-4">{{ $data->actual_quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-2xl font-bold mb-4">Scan Item Code</h3>
                        <form action="{{ route('update.machine_job') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="item_code" class="block text-sm font-medium text-gray-700">Item
                                    Code:</label>
                                <input type="text" id="item_code" name="item_code" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <button type="submit"
                                    class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                                    Update Machine Job
                                </button>
                            </div>
                        </form>
                    </div>

                    @if ($uniquedata != null)
                        <div class="mt-8">
                            <h3 class="text-2xl font-bold mb-4">Unique Data Table</h3>
                            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-3 px-4 text-left font-medium text-gray-700">SPK</th>
                                        <th class="py-3 px-4 text-left font-medium text-gray-700">Item Code</th>
                                        <th class="py-3 px-4 text-left font-medium text-gray-700">Scanned Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $canTriggerFunction = true;
                                    @endphp
                                    @foreach ($uniquedata as $data)
                                        <tr class="bg-white border-b">
                                            <td class="py-3 px-4">{{ $data['spk'] }}</td>
                                            <td class="py-3 px-4">{{ $data['item_code'] }}</td>
                                            <td class="py-3 px-4">{{ $data['scannedData'] }}/{{ $data['count'] }}</td>
                                        </tr>
                                        @if ($data['scannedData'] != $data['count'])
                                            @php
                                                $canTriggerFunction = false;
                                            @endphp
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($canTriggerFunction)
                                <form method="GET" action="{{ route('reset.jobs') }}" class="mt-6">
                                    <button type="submit"
<<<<<<< Updated upstream
                                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                        Update Machine Job
                                    </button>
                                </div>
                            </form>
                        </div>

            <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #ddd;
        }
                </style>
                @if($uniquedata != null)
                        <h1>Unique Data Table</h1>
                            <table>
                                <thead>
                                    <tr>
                                        <th>SPK</th>
                                        <th>Item Code</th>
                                        <th>Scanned Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $canTriggerFunction = true; // Assume all scannedData match count
                                @endphp
                                @foreach ($uniquedata as $data)
                                    <tr>
                                        <td>{{ $data['spk'] }}</td>
                                        <td>{{ $data['item_code'] }}</td>
                                        <td>{{ $data['scannedData'] }}/{{ $data['count'] }}</td>
                                    </tr>
                                    @if($data['scannedData'] != $data['count'])
                                        @php
                                            $canTriggerFunction = false; // If any row does not match, set to false
                                        @endphp
                                    @endif
                                @endforeach
                                </tbody>
                            </table>

                            @if($canTriggerFunction)
                                <!-- Button only shows if all scannedData match count -->
                                <form method="GET" action="{{ route('reset.jobs') }}">
                                <input type="hidden" name="uniquedata" value="{{ json_encode($uniquedata) }}">
                                    <button type="submit" class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-green-600 transition duration-300 ease-in-out">
=======
                                        class="w-full py-2 px-4 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition">
>>>>>>> Stashed changes
                                        Done
                                    </button>
                                </form>
                            @else
<<<<<<< Updated upstream
                                <!-- Optionally, show a message if the condition is not met -->
                                <form method="GET" action="{{ route('reset.jobs') }}">
                                <input type="hidden" name="uniquedata" value="{{ json_encode($uniquedata) }}">
                                    <button type="submit" class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-green-600 transition duration-300 ease-in-out">
                                        Done
                                    </button>
                                </form>
                                <p>Item belum discan semua</p>
=======
                                <p class="mt-4 text-red-500">Item belum discan semua</p>
>>>>>>> Stashed changes
                            @endif
                        </div>

                        <div class="mt-8">
                            <h3 class="text-2xl font-bold mb-4">Scan Barcode</h3>
                            <form action="{{ route('process.productionbarcode') }}" method="POST"
                                class="space-y-6">
                                @csrf
                                <div>
                                    <label for="spk_code" class="block text-sm font-medium text-gray-700">SPK
                                        Code:</label>
                                    <input type="text" id="spk_code" name="spk_code" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="item_code" class="block text-sm font-medium text-gray-700">Item
                                        Code:</label>
                                    <input type="text" id="item_code" name="item_code" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="warehouse"
                                        class="block text-sm font-medium text-gray-700">Warehouse:</label>
                                    <input type="text" id="warehouse" name="warehouse" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="quantity"
                                        class="block text-sm font-medium text-gray-700">Quantity:</label>
                                    <input type="number" id="quantity" name="quantity" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label for="label"
                                        class="block text-sm font-medium text-gray-700">Label:</label>
                                    <input type="number" id="label" name="label" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <button type="submit"
                                        class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition">
                                        Scan
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- JS for focusing on form inputs --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const spkCodeElement = document.getElementById('spk_code');
            const itemCodeElement = document.getElementById('item_code');

            if (spkCodeElement) {
                spkCodeElement.focus();
            } else if (itemCodeElement) {
                itemCodeElement.focus();
            }
        });
    </script> --}}
</x-app-layout>
