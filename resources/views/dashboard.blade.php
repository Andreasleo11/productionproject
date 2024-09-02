<x-app-layout>
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    @if (auth()->user()->specification->name === 'ADMINISTRATOR' || auth()->user()->specification->name === 'Operator')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl"> Files
                        </div>
                        <div class="my-4">
                            <a href="{{ asset('a.pdf') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                PDF A</a>
                            <a href="{{ asset('b.pdf') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                PDF B</a>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 my-8">
                            <a href="{{ asset('a_pages-to-jpg-0001.jpg') }}" data-fancybox="gallery"
                                data-caption="Caption #1">
                                <img class="w-full h-auto rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-lg"
                                    src="{{ asset('a_pages-to-jpg-0001.jpg') }}" alt="Description of Image 1" />
                            </a>

                            <a href="{{ asset('a_pages-to-jpg-0002.jpg') }}" data-fancybox="gallery"
                                data-caption="Caption #2">
                                <img class="w-full h-auto rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-lg"
                                    src="{{ asset('a_pages-to-jpg-0002.jpg') }}" alt="Description of Image 2" />
                            </a>
                        </div>
                        <script type="module">
                            Fancybox.bind('[data-fancybox="gallery"]', {
                                Thumbs: {
                                    autoStart: true,
                                },
                                Image: {
                                    zoom: true,
                                },
                                transitionEffect: "fade",
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->specification->name === 'ADMINISTRATOR' || auth()->user()->specification->name === 'PE')
        <div class="flex justify-center items-center">
            PE USER
        </div>
    @elseif(auth()->user()->specification->name === 'Machine')
    <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-4xl">Files</div>
                        <div class="my-4">
                            @foreach ($files as $file)
                                <a href="{{ asset('files/' . $file->name) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ $file->name }}
                                </a>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 my-8">
                            @foreach ($files as $file)
                                <a href="{{ asset('files/'. $file->name) }}" data-fancybox="gallery" data-caption="{{ 'files/' . $file->name }}">
                                    <img class="w-full h-auto rounded-lg shadow-md transition-transform transform hover:scale-105 hover:shadow-lg"
                                        src="{{ asset('files/' .$file->name ) }}" alt="{{ 'files/' . $file->name }}" />
                                </a>
                            @endforeach
                        </div>
                        <script type="module">
                            Fancybox.bind('[data-fancybox="gallery"]', {
                                Thumbs: {
                                    autoStart: true,
                                },
                                Image: {
                                    zoom: true,
                                },
                                transitionEffect: "fade",
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
