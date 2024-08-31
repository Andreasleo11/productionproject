<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SO Results</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
        /* Custom styling for alerts */
        .alert-container {
            margin-top: 20px;
        }
    </style>
<body>
    <h1>Results for SO Number: {{ $docNum }}</h1>
    <h1>Customer : {{$customer}}</h1>
    <h1>DATE : {{ $date }}</h1>

    <div id="message"></div>

    @if($data->isEmpty())
        <p>No data found for this SO Number.</p>
    @else
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Model</th>
                    <th>Description</th>
                    <th>Delivery Quantity</th>
                    <th>CTN </th>
                    <th>Remarks </th>
                    <th>Scanned Box</th>
                    <!-- Add more fields as needed -->
                </tr>
            </thead>
            <tbody>
            @php
                $totalQuantity = 0;
                $totalCTN = 0;
            @endphp

                @foreach($data as $item)
                @php
                    $ctn = $item->quantity / $item->packaging_quantity;
                    $totalQuantity += $item->quantity;
                    $totalCTN += $ctn;
                @endphp
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->item_code }}</td>
                        <td>{{ $item->item_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($ctn) }}</td>
                        <td></td>
                        <td>{{ $item->scannedCount }} / {{$ctn}}</td>
                        <!-- Add more fields as needed -->
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th>{{ $totalQuantity }}</th>
                    <th>{{ number_format($totalCTN) }}</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    @endif

    @if(session('success'))
            <div class="alert alert-success alert-container">
                {{ session('success') }}
            </div>
        @endif

        @if($allFinished)
        <a href="{{ route('update.so.data', ['docNum' => $docNum]) }}" class="btn btn-primary">Update All</a>
    @else
        <p>Not all items are finished yet.</p>
    @endif

    <form id="barcode-form" method="POST" action="{{ route('so.scanBarcode') }}">
        @csrf
        <input type="hidden" name="so_number" value="{{ $docNum }}">
        <label for="item_code">Item Code:</label>
        <input type="text" id="item_code" name="item_code" required>
        <br>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" required>
        <br>
        <label for="warehouse">Warehouse:</label>
        <input type="text" id="warehouse" name="warehouse" required>
        <br>
        <label for="label">Label:</label>
        <input type="number" id="label" name="label" required>
        <br>
        <button type="submit">Scan Barcode</button>
    </form>


    <h2>Scanned Data</h2>

@foreach($scandatas as $itemCode => $scans)
    <h3>Item Code: {{ $itemCode }}</h3>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Quantity</th>
                <th>Warehouse</th>
                <th>Label</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scans as $scan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $scan->quantity }}</td>
                    <td>{{ $scan->warehouse }}</td>
                    <td>{{ $scan->label }}</td>
                    <td>{{ $scan->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br> <!-- Add spacing between groups -->
@endforeach

    <script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('barcode-form');
    const labelInput = document.getElementById('label');

    // Function to submit the form
    function submitForm() {
        form.submit();
    }

    // Listen for input events on the label field
    labelInput.addEventListener('input', function () {
        // Automatically submit the form when input value changes
        submitForm();
    });
});


</script>

   <!-- Include Bootstrap JS for modal (optional) -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('#barcode-form').on('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting normally

            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        // Handle successful scan (e.g., show a success message or update the table)
                        alert(response.success);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400 || xhr.status === 404) {
                        // Display error in modal
                        $('#modal-message').text(xhr.responseJSON.error);
                        $('#errorModal').modal('show');
                    }
                }
            });
        });
    });
</script>

<script>
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('item_code').focus();
        });
    </script>
</body>
</html>
