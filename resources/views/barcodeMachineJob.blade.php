<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcodes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 10px;
            padding: 15px;
            display: inline-block;
            vertical-align: top;
            width: calc(33% - 40px); /* Adjust width for responsive layout */
            box-sizing: border-box;
            position: relative; /* Required for positioning the ROHS FREE card */
        }

        .card-header {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .rohs-free {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e0f7fa;
            border: 1px solid #00acc1;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 12px;
            color: #00796b;
        }

        .card p {
            margin: 5px 0;
            font-size: 14px;
        }

        .barcode-container {
            text-align: center;
            margin-top: 10px;
        }

        .barcode-container img {
            display: block;
            margin: 0 auto;
            max-width: 100%;
        }

        .barcode-container .barcode {
            margin-bottom: 20px; /* Space between the two barcodes */
        }

        .barcode-header {
            font-weight: bold;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .card {
                width: calc(50% - 40px); /* Adjust width for medium screens */
            }
        }

        @media (max-width: 480px) {
            .card {
                width: calc(100% - 40px); /* Full width for small screens */
            }
        }
    </style>
</head>
<body>
    <h1>Generated Barcodes for Item Codes</h1>

    @foreach ($labels as $index => $label)
        <div class="card">
            <div class="rohs-free">ROHS FREE</div>
            <div class="card-header">PT Daijo Industrial</div>
            <p><strong>SPK:</strong> {{ $label['spk'] }}</p>
            <p><strong>Item Code:</strong> {{ $label['item_code'] }}</p>
            <p><strong>Warehouse:</strong> {{ $label['warehouse'] }}</p>
            <p><strong>Quantity:</strong> {{ $label['quantity'] }}</p>
            <p><strong>Label:</strong> {{ $label['label'] }}</p>
            <div class="barcode-container">
                <div class="barcode">
                    {!! $barcodes[$index]['first'] !!}
                </div>
                <div class="barcode-header">Loading Barcode</div>
                <div class="barcode">
                    {!! $barcodes[$index]['second'] !!}
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>
