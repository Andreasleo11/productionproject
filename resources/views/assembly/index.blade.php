<!-- resources/views/assembly_dashboard.blade.php -->
<x-dashboard-layout>
    <div class="max-w-7xl mx-auto py-8 space-y-10">
        <!-- Dashboard Title -->
        <h1 class="text-3xl font-bold text-center mb-8">Assembly Dashboard</h1>

        <!-- Overview Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Quantity Assembled Card -->
            <div
                class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center"
            >
                <h2 class="text-lg font-semibold">Total Quantity Assembled</h2>
                <p class="text-3xl font-bold text-blue-600 mt-2">
                    {{ $totalQuantityAssembled }}
                </p>
            </div>

            <!-- Average Assembly per Line Card -->
            <div
                class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center"
            >
                <h2 class="text-lg font-semibold">Average Assembly per Line</h2>
                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ number_format($averageAssemblyPerLine, 2) }}
                </p>
            </div>

            <!-- Assembly Lines Count -->
            <div
                class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center"
            >
                <h2 class="text-lg font-semibold">Total Assembly Lines</h2>
                <p class="text-3xl font-bold text-red-600 mt-2">
                    {{ $totalLines }}
                </p>
            </div>
        </div>

        <!-- Assembly Line Breakdown Table -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold mb-4">Assembly Line Breakdown</h2>
            <table
                class="min-w-full border border-gray-200 rounded-lg overflow-hidden"
            >
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-4 text-left">Line</th>
                        <th class="p-4 text-left">Item Code</th>
                        <th class="p-4 text-left">Description</th>
                        <th class="p-4 text-left">Quantity Assembled</th>
                        <th class="p-4 text-left">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($assemblyData as $data)
                        <tr class="border-b">
                            <td class="p-4">{{ $data->line }}</td>
                            <td class="p-4">{{ $data->item_code }}</td>
                            <td class="p-4">{{ $data->item_description }}</td>
                            <td class="p-4">{{ $data->quantity }}</td>
                            <td class="p-4">{{ $data->remark }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Chart Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Quantity Assembled by Line Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-4">
                    Quantity Assembled by Line
                </h2>
                <canvas id="lineChart"></canvas>
            </div>

            <!-- Daily Assembly Quantity Chart -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-4">
                    Daily Assembly Quantity
                </h2>
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Load Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Quantity Assembled by Line Chart
            const lineLabels = @json($lineData->keys());
            const lineData = @json($lineData->values());
            const lineCtx = document
                .getElementById('lineChart')
                .getContext('2d');
            new Chart(lineCtx, {
                type: 'bar',
                data: {
                    labels: lineLabels,
                    datasets: [
                        {
                            label: 'Quantity Assembled',
                            data: lineData,
                            backgroundColor: 'rgba(75, 192, 192, 0.5)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });

            // Daily Assembly Quantity Chart
            const dailyLabels = @json($dailyData->keys());
            const dailyData = @json($dailyData->values());
            const dailyCtx = document
                .getElementById('dailyChart')
                .getContext('2d');
            new Chart(dailyCtx, {
                type: 'line',
                data: {
                    labels: dailyLabels,
                    datasets: [
                        {
                            label: 'Daily Quantity Assembled',
                            data: dailyData,
                            backgroundColor: 'rgba(153, 102, 255, 0.5)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });
        });
    </script>
</x-dashboard-layout>
