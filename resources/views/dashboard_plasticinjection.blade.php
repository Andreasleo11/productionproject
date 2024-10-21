<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard Table</title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
            }
            table,
            th,
            td {
                border: 1px solid black;
            }
            th,
            td {
                padding: 8px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <h2>Machine Job Dashboard</h2>

        <table>
            <thead>
                <tr>
                    <th>Nama Mesin</th>
                    <th>Nama Operator</th>
                    <th>Item Code</th>
                    <th>Receipt Quantity</th>
                    <th>Quantity</th>
                    <th>Status Operator</th>
                    <th>Status Akhir</th>
                    <th>Shift</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datas as $job)
                    <tr>
                        <td>{{ $job->user->name }}</td>
                        <td>
                            {{ $job->machinerelation->employee_name ?? 'Waiting Operator' }}
                        </td>
                        <td>{{ $job->item_code }}</td>
                        <td>{{ $job->scannedData->sum('quantity') }}</td>
                        <td>{{ $job->quantity }}</td>
                        <td>
                            @if ($job->machinerelation && $job->machinerelation->employee_name)
                                Running
                            @else
                                    Waiting
                            @endif
                        </td>
                        <td>
                            @if ($job->scannedData->sum('quantity') == $job->quantity)
                                Finish
                            @else
                                    Not Finish
                            @endif
                        </td>
                        <td>{{ $job->shift }}</td>
                        <td>{{ $job->start_date }}</td>
                        <td>{{ $job->end_date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
</html>
