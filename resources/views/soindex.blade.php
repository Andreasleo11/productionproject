<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SO Data Index</title>
</head>
<body>
    <h1>Distinct DOC Numbers</h1>

    <!-- Display distinct doc_num values with buttons -->
    <ul>
        @foreach ($docNums as $docNum)
            <li>
                {{ $docNum->doc_num }}
                <a href="{{ route('so.process', [$docNum->doc_num]) }}" class="button">Process</a>
                
            </li>
        @endforeach
    </ul>

    <!-- Optional: Display status message -->
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif
</body>
</html>
