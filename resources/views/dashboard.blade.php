<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    @if (auth()->user()->specification->name === 'ADMINISTRATOR' || auth()->user()->specification->name === 'Operator')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl"> Files
                        </div>
                        <div class="my-4">
                            <a href="{{ asset('a.pdf') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                PDF A</a>
                            <a href="{{ asset('b.pdf') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                PDF B</a>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 my-8">
                            <a href="{{ asset('a_pages-to-jpg-0001.jpg') }}" data-fancybox="gallery"
                                data-caption="Caption #1">
                                <img class="w-full h-auto rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-lg"
                                    src="{{ asset('a_pages-to-jpg-0001.jpg') }}" alt="Description of Image 1" />
                            </a>

                            <a href="{{ asset('a_pages-to-jpg-0002.jpg') }}" data-fancybox="gallery"
                                data-caption="Caption #2">
                                <img class="w-full h-auto rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-lg"
                                    src="{{ asset('a_pages-to-jpg-0002.jpg') }}" alt="Description of Image 2" />
                            </a>

                            <a href="{{ asset('a.pdf') }}" data-fancybox="gallery" data-caption="PDF 1">
                                <img class="w-full h-auto rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-lg"
                                    src="{{ asset('a.pdf') }}" alt="PDF 1" />
                            </a>

                            <a href="{{ asset('b.pdf') }}" data-fancybox="gallery" data-caption="PDF 2">
                                <img class="w-full h-auto rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-lg"
                                    src="{{ asset('b.pdf') }}" alt="PDF 2" />
                            </a>
                        </div>
                        <script type="module">
                            Fancybox.bind('[data-fancybox="gallery"]', {
                                Thumbs: {
                                    autoStart: true,
                                },
                                Image: {
                                    zoom: true,
                                },
                                transitionEffect: "fade",
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->specification->name === 'ADMINISTRATOR' || auth()->user()->specification->name === 'PE')
        <div class="flex justify-center items-center">
            PE USER
        </div>
    @elseif(auth()->user()->specification->name === 'Machine')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">


                        <div class="text-4xl">Files</div>
                            <span class="font-bold text-lg">Active Job: </span>
                        @if($itemCode)
                            <span class="text-blue-500">{{ $itemCode }}</span>
                        @else
                            <span class="text-red-500">No item code scanned</span>
                        @endif
                        <div class="my-4">
                            @foreach ($files as $file)
                                <a href="{{ asset('files/' . $file->name) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ $file->name }}
                                </a>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 my-8">
                            @foreach ($files as $file)
                                <a href="{{ asset('files/' . $file->name) }}" data-fancybox="gallery"
                                    data-caption="{{ 'files/' . $file->name }}">
                                    <img class="w-full h-auto rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-lg"
                                        src="{{ asset('files/' . $file->name) }}"
                                        alt="{{ 'files/' . $file->name }}" />
                                </a>
                            @endforeach
                        </div>
                        <script type="module">
                            Fancybox.bind('[data-fancybox="gallery"]', {
                                Thumbs: {
                                    autoStart: true,
                                },
                                Image: {
                                    zoom: true,
                                },
                                transitionEffect: "fade",
                            });
                        </script>

                        <div class="mt-8">
                            <div class="text-4xl">Assigned Daily Item Codes</div>
                            <table class="min-w-full bg-white border border-gray-200 mt-4">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="py-2 px-4 border-b text-left">Item Code</th>
                                        <th class="py-2 px-4 border-b text-left">Quantity</th>
                                        <th class="py-2 px-4 border-b text-left">Loss Package Quantity</th>
                                        <th class="py-2 px-4 border-b text-left">Actual Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $data)
                                        <tr>
                                        
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('generate.itemcode.barcode', ['item_code' => $data->item_code, 'quantity' => $data->quantity]) }}" class="text-blue-500 hover:underline">
                                                    {{ $data->item_code }}
                                                </a>
                                            </td>
                                            <td class="py-2 px-4 border-b">{{ $data->quantity }}</td>
                                            <td class="py-2 px-4 border-b">{{ $data->loss_package_quantity }}</td>
                                            <td class="py-2 px-4 border-b">{{ $data->actual_quantity }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="mt-8">
                            <div class="text-4xl">Scan Item Code</div>
                            <form action="{{ route('update.machine_job') }}" method="POST" class="mt-4">
                                @csrf
                                <div class="mb-4">
                                    <label for="item_code" class="block text-gray-700 text-sm font-bold mb-2">Item Code:</label>
                                    <input type="text" id="item_code" name="item_code" required
                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                <div>
                                    <button type="submit"
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
                                    <button type="submit" class="bg-green-500 text-white font-semibold py-2 px-4 rounded-lg shadow-md hover:bg-green-600 transition duration-300 ease-in-out">
                                        Done
                                    </button>
                                </form>
                            @else
                                <!-- Optionally, show a message if the condition is not met -->
                                <p>Item belum discan semua</p>
                            @endif

                            <h2>Scan Barcode</h2>
                                <form action="{{ route('process.productionbarcode') }}" method="POST">
                                    @csrf
                                    
                                    <!-- Hidden inputs for passing `datas` -->
                                    <input type="hidden" name="datas" value="{{ json_encode($datas) }}">
                                    
                                    <!-- Hidden inputs for passing `uniquedata` -->
                                    <input type="hidden" name="uniquedata" value="{{ json_encode($uniquedata) }}">

                                    <!-- Add other inputs if needed, like quantity or label -->
                                    <div>
                                        <label for="spk_code" class="block text-sm font-medium text-gray-700">SPK Code:</label>
                                        <input type="text" id="spk_code" name="spk_code" required
                                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="item_code" class="block text-sm font-medium text-gray-700">Item Code:</label>
                                        <input type="text" id="item_code" name="item_code" required
                                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="warehouse" class="block text-sm font-medium text-gray-700">Warehouse:</label>
                                        <input type="text" id="warehouse" name="warehouse" required
                                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    
                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
                                        <input type="number" id="quantity" name="quantity" required
                                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>

                                    <div>
                                        <label for="label" class="block text-sm font-medium text-gray-700">Label:</label>
                                        <input type="number" id="label" name="label" required
                                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <!-- Submit button -->
                                    <button type="submit">Scan </button>
                                </form>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const spkCodeElement = document.getElementById('spk_code');
            const itemCodeElement = document.getElementById('item_code');

            if (spkCodeElement) {
                // If spk_code element is present, focus on it
                spkCodeElement.focus();
            } else if (itemCodeElement) {
                // If spk_code is not present but item_code is, focus on item_code
                itemCodeElement.focus();
            }
        });
    </script>
</x-app-layout>
