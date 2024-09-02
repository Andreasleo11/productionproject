<x-app-layout>
    <h1 class="text-xl font-bold mb-4">Daily Item Codes Index</h1>
    
    <div class="flex flex-wrap -mx-2">
        <!-- First Column: Form to Assign Item Codes -->
        <div class="w-full md:w-1/2 px-2 mb-4">
            <h2 class="text-lg font-semibold mb-2">Assign Item Codes to Machines</h2>
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b text-left">Machine Name</th>
                        <th class="py-2 px-4 border-b text-left">Item Codes</th>
                        <th class="py-2 px-4 border-b text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($machines as $machine)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $machine->name }}</td>
                            <td class="py-2 px-4 border-b">
                                <form method="POST" action="{{ route('apply-item-code', ['machine_id' => $machine->id, 'user_id' => $machine->id]) }}">
                                    @csrf
                                    <select name="item_code" class="border border-gray-300 rounded px-2 py-1" required>
                                        @foreach($itemcodes as $itemcode)
                                            <option value="{{ $itemcode->item_code }}">{{ $itemcode->item_code }}</option>
                                        @endforeach
                                    </select>
                            </td>
                            <td class="py-2 px-4 border-b">
                                    <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600">Apply</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Second Column: View Assigned Item Codes -->
        <div class="w-full md:w-1/2 px-2 mb-4">
            <h2 class="text-lg font-semibold mb-2">Assigned Item Codes</h2>
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b text-left">Machine Name</th>
                        <th class="py-2 px-4 border-b text-left">Assigned Item Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($machines as $machine)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $machine->name }}</td>
                            <td class="py-2 px-4 border-b">
                                @if($machine->dailyItemCode)
                                    {{ $machine->dailyItemCode->item_code }}
                                @else
                                    <span class="text-red-500">No Item Code Assigned</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
