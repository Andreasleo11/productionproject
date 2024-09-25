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
            -webkit-print-color-adjust: exact;
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
            width: calc(33% - 40px);
            /* Adjust width for responsive layout */
            box-sizing: border-box;
            position: relative;
            /* Required for positioning the ROHS FREE card */
        }

        .card-header {
            font-weight: bold;
            font-size: 25px;
            margin-bottom: 10px;
        }

        .toplabel {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e0f7fa;
            border: 1px solid #00acc1;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 20px;
            color: #00796b;
        }

        .rohs-free {
            background: #e0f7fa;
            border: 1px solid #00acc1;
            border-radius: 5px;
            padding: 5px 10px;
            font-size: 20px;
            color: #00796b;
        }

        .card p {
            margin: 5px 0;
            /* font-size: 14px; */
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
            margin-bottom: 20px;
            /* Space between the two barcodes */
        }

        .barcode-header {
            font-weight: bold;
            margin-top: 20px;
        }

        .special-header {
            text-align: center;
            /* Centers the text horizontally */
            font-size: 18px;
            /* Adjust font size as needed */
            font-weight: bold;
            /* Makes the text bold */
            margin: 20px 0;
            /* Adds vertical space above and below the content */
        }

        .label-container {
            margin: 10px 0;
            /* Optional margin for spacing */
        }

        .label-row {
            display: flex;
            justify-content: space-between;
            /* Ensures the items in the row are spaced out evenly */
            margin-bottom: 10px;
            /* Adds some space between rows */
        }

        .label-row p {
            margin: 0 10px;
            /* Adds some space between the two columns */
            flex: 1;
            /* Ensures each element in the row takes up equal width */
        }

        .company-logo {
            height: 20px;
            /* Adjust image size */
            margin-right: 5px;
            /* Spacing between the logo and the text */
        }

        @media print {
            body {
                background-color: white;
                /* Remove background for print */
            }

            .card {
                width: calc(30% - 10px);
                /* 50% width for two cards per row */
                box-shadow: none;
                /* Remove shadow for print */
                page-break-inside: avoid;
                /* Prevent page breaks inside cards */
            }

            .barcode-container .barcode {
                max-width: 100%;
                height: auto;
            }

            /* Remove margins and paddings to better fit the page */
            h1,
            body {
                margin: 0;
                padding: 0;
            }

            /* Ensure two cards per row in print */
            .card-container {
                display: flex;
                flex-wrap: wrap;
            }

            @page {
                size: A3 landscape;
            }
        }
    </style>
</head>

<body>
    <h1>Generated Barcodes for Item Codes</h1>

    @foreach ($labels as $index => $label)
        <div class="card">
            <div class="toplabel">{{ $label['label'] }}</div>
            <div class="card-header">
                <img src="{{ asset('logoDaijo.png') }}" alt="PT Daijo Industrial Logo" class="company-logo">
                <span>PT Daijo Industrial</span>
            </div>
            <div class="special-header">
                <p> {{ $label['item_code'] }}</p>
                <p> {{ $label['item_name'] }}</p>
            </div>
            <div class="label-container">
                <div class="label-row">
                    <p><strong>SPK:</strong> {{ $label['spk'] }}</p>
                    <p><strong>Warehouse:</strong> {{ $label['warehouse'] }}</p>
                </div>
                <div class="label-row">
                    <p><strong>Quantity:</strong> {{ $label['quantity'] }}</p>
                    <p><strong>Shift:</strong> I / II / III</p>
                </div>
                <div class="label-row">
                    <p><strong>Prod Date:</strong> </p>

                </div>
                <div class="label-row">
                    <p><strong>Operator:</strong></p>
                    <div class="rohs-free">
                        <p>ROHS FREE</p>
                    </div>
                </div>
            </div>
            <br>
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
