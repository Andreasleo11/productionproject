<x-app-layout>
    <div class="max-w-7xl mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6 text-center">
            Second Process Planning Overview
        </h1>

        <div class="overflow-x-auto bg-white shadow-md rounded-lg p-6">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            ID
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Plan Date
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Line
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Item Code
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Item Description
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Quantity Hour
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Process Time
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Quantity Plan
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            PIC
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Customer
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Shift
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Created At
                        </th>
                        <th
                            class="px-4 py-2 border-b text-left text-sm font-medium text-gray-700"
                        >
                            Updated At
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->id }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->plan_date }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->line }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->item_code }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->item_description }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->quantity_hour ?? 'N/A' }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->process_time ?? 'N/A' }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->quantity_plan ?? 'N/A' }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->pic }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->customer }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->shift }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->created_at ?? 'N/A' }}
                            </td>
                            <td
                                class="px-4 py-2 border-b text-sm text-gray-700"
                            >
                                {{ $data->updated_at ?? 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
