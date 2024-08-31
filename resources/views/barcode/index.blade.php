<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Tipe Mesin</title>
</head>
<body>
    <h1>Select Tipe Mesin</h1>

    <form action="{{ route('barcode.generate') }}" method="POST">
        @csrf
        <div>
            <label for="tipe_mesin">Select Tipe Mesin:</label>
            <select name="tipe_mesin" id="tipe_mesin" required>
                <option value="" disabled selected>Select a tipe mesin</option>
                @foreach($tipeMesins as $tipeMesin)
                    <option value="{{ $tipeMesin }}">{{ $tipeMesin }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Generate Barcodes</button>
    </form>
</body>
</html>
