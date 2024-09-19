<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Latest Barcode Items</title>
    </head>
    <body class="font-sans bg-gray-100">
        <h1 class="text-3xl font-bold text-center my-8">Latest Barcode Items</h1>

        <form method="GET" action="{{ route('updated.barcode.item.position') }}" class="flex flex-wrap items-center justify-between bg-white p-4 rounded-lg shadow-md mb-6">
            <div class="flex flex-col mb-4 w-full md:w-1/4">
                <label for="partNo" class="font-bold text-gray-700 mb-2">Part No:</label>
                <select id="partNo" name="partNo" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select Part No</option>
                    @foreach ($partNumbers as $partNumber)
                        <option value="{{ $partNumber->partNo }}" {{ request('partNo') == $partNumber->partNo ? 'selected' : '' }}>
                            {{ $partNumber->partNo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col mb-4 w-full md:w-1/4">
                <label for="scantime" class="font-bold text-gray-700 mb-2">Scan Time (yyyy-mm-dd):</label>
                <input type="date" id="scantime" name="scantime" value="{{ request('scantime') }}" class="border border-gray-300 rounded-lg px-4 py-2">
            </div>

            <div class="flex flex-col mb-4 w-full md:w-1/4">
                <label for="position" class="font-bold text-gray-700 mb-2">Position:</label>
                <select id="position" name="position" class="border border-gray-300 rounded-lg px-4 py-2">
                    <option value="">Select Position</option>
                    <option value="Jakarta" {{ request('position') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                    <option value="Karawang" {{ request('position') == 'Karawang' ? 'selected' : '' }}>Karawang</option>
                    <option value="CustomerJakarta" {{ request('position') == 'CustomerJakarta' ? 'selected' : '' }}>CustomerJakarta</option>
                    <option value="CustomerKarawang" {{ request('position') == 'CustomerKarawang' ? 'selected' : '' }}>CustomerKarawang</option>
                </select>
            </div>

            <div class="flex flex-col mb-4 w-full md:w-1/4">
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition duration-300">
                    Filter
                </button>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full table-auto bg-white shadow-md rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-300 text-left text-sm font-semibold text-gray-700">Part No</th>
                        <th class="px-6 py-3 border-b border-gray-300 text-left text-sm font-semibold text-gray-700">Label</th>
                        <th class="px-6 py-3 border-b border-gray-300 text-left text-sm font-semibold text-gray-700">Scan Time</th>
                        <th class="px-6 py-3 border-b border-gray-300 text-left text-sm font-semibold text-gray-700">No Dokumen</th>
                        <th class="px-6 py-3 border-b border-gray-300 text-left text-sm font-semibold text-gray-700">Position</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sortedItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 border-b border-gray-300">{{ (string) $item->partNo }}</td>
                            <td class="px-6 py-4 border-b border-gray-300">{{ (string) $item->label }}</td>
                            <td class="px-6 py-4 border-b border-gray-300">{{ (string) $item->scantime }}</td>
                            <td class="px-6 py-4 border-b border-gray-300">{{ (string) $item->noDokumen }}</td>
                            <td class="px-6 py-4 border-b border-gray-300">{{ (string) $item->position }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
    </html>
</x-app-layout>
