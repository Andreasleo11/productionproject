<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <span class="font-bold text-lg">Active Job: </span>
                @if ($itemCode)
                    <span class="text-blue-500">{{ $itemCode }}</span>
                @else
                    <span class="text-red-500">No item code scanned</span>
                @endif
                <div class="text-4xl">Files</div>
                @if ($files === null)
                    <h1> no files</h1>
                @else
                    <div class="my-4">
                        @foreach ($files as $file)
                            <a href="{{ asset('files/' . $file->name) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ $file->name }}
                            </a>
                        @endforeach
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
                    @if ($datas->isNotEmpty())
                        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Item Code</th>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Quantity</th>
                                    <th class="py-3 px-4 text-left font-medium text-gray-700">Loss Package Quantity
                                    </th>
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
                    @else
                        <p class="text-red-500">No assigned item code yet</p>
                    @endif
                </div>
                @if ($datas->isNotEmpty())
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
                @endif

                @if ($uniquedata != null)
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
                                @php
                                    $canTriggerFunction = true;
                                @endphp
                                @foreach ($uniquedata as $data)
                                    <tr class="bg-white border-b">
                                        <td class="py-3 px-4">{{ $data['spk'] }}</td>
                                        <td class="py-3 px-4">{{ $data['item_code'] }}</td>
                                        <td class="py-3 px-4">{{ $data['scannedData'] }}/{{ $data['count'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($canTriggerFunction)
                            <form method="GET" action="{{ route('reset.jobs') }}" class="mt-6">
                                <button type="submit"
                                    class="w-full py-2 px-4 bg-green-600 text-white font-semibold rounded-md hover:bg-green-700 transition">
                                    Done
                                </button>
                            </form>
                        @else
                            <p class="mt-4 text-red-500">Item belum discan semua</p>
                        @endif
                    </div>

                    <div class="mt-8">
                        <h3 class="text-2xl font-bold mb-4">Scan Barcode</h3>
                        <form action="{{ route('process.productionbarcode') }}" method="POST" class="space-y-6">
                            @csrf

                            <input type="hidden" id="uniqueData" name="uniqueData" value="{{ json_encode($uniquedata) }}">
                            <input type="hidden" id="datas" name="datas" value="{{ json_encode($datas) }}">
                            
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
                                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
                                <input type="number" id="quantity" name="quantity" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="label" class="block text-sm font-medium text-gray-700">Label:</label>
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
