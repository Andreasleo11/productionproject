<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Item Code</title>
</head>
<body>
    <h1>Assign Item Code to User</h1>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('itemcode.assign') }}" method="POST">
        @csrf
        <div>
            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id" required>
                <option value="" disabled selected>Select a user</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="item_code">Select Item:</label>
            <select name="item_code" id="item_code" required>
                <option value="" disabled selected>Select an item</option>
                @foreach($items as $item)
                    <option value="{{ $item->item_code }}">{{ $item->item_code }} - {{ $item->item_group }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit">Assign Item Code</button>
    </form>
    <h2>Assigned Machine Names and Item Codes</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Machine Name (User)</th>
                <th>Assigned Item Code</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                <tr>
                    <td>{{ $assignment->machine_name }}</td>
                    <td>{{ $assignment->item_code }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>