<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center mt-5">Distinct DOC Numbers</h1>
                <hr>

                <!-- Display distinct doc_num values with buttons -->
                <ul class="space-y-4">
                    @foreach ($docNums as $docNum)
                        <li class="flex justify-between items-center bg-white p-4 rounded-lg shadow-md">
                            <span class="text-gray-700 text-lg">{{ $docNum->doc_num }}</span>
                            <a href="{{ route('so.process', [$docNum->doc_num]) }}"
                                class="inline-block px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Process
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Optional: Display status message -->
                @if (session('status'))
                    <p class="mt-4 text-center text-green-600 font-semibold">{{ session('status') }}</p>
                @endif

            </div>
        </div>
    </div>

</x-app-layout>
