<x-app-layout>
    {{-- Header Slot --}}
    <x-slot name="header">
        <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    {{-- Content --}}
    @if (auth()->user()->specification->name === 'Admin')
        @include('partials.dashboard-admin')
    @elseif (auth()->user()->specification->name === 'Admin' || auth()->user()->specification->name === 'PE')
        <div class="flex justify-center items-center">PE USER</div>
    @elseif (auth()->user()->specification->name === 'Operator')
        @include('partials.dashboard-operator')
    @elseif (auth()->user()->specification->name === 'PPIC')
        <div class="mx-auto sm:px-4 lg:px-6 pt-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="text-2xl font-semibold my-3">
                    Production Reports
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Machine Name
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    SPK No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Target Qty
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Scanned Qty
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Outstanding Qty
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-2">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($productionReports as $report)
                                <tr class="bg-white border-b">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $report->user->name }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $report->spk_no }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $report->target }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $report->scanned }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $report->outstanding }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($report->outstanding === 0)
                                            <span
                                                class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">Success</span>
                                        @else
                                            <span
                                                class="bg-red-100 text-red-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded">Failed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-primary-button>Action</x-primary-button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-white border-b text-center">
                                    <td colspan="10">No Data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    @endif
</x-app-layout>
