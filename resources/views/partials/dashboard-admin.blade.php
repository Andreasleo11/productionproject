<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <h2 class="text-3xl font-bold mb-4">Files</h2>
            <div class="space-y-4">
                <a href="{{ asset('a.pdf') }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-500">
                    PDF A
                </a>
                <a href="#"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-500">
                    PDF B
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 mt-8">
                <a href="{{ asset('a_pages-to-jpg-0001.jpg') }}" data-fancybox="gallery" data-caption="Caption #1">
                    <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                        src="{{ asset('a_pages-to-jpg-0001.jpg') }}" alt="Image 1" />
                </a>
                <a href="{{ asset('a_pages-to-jpg-0002.jpg') }}" data-fancybox="gallery" data-caption="Caption #2">
                    <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                        src="{{ asset('a_pages-to-jpg-0002.jpg') }}" alt="Image 2" />
                </a>
                <a href="{{ asset('a.pdf') }}" data-fancybox="gallery" data-caption="PDF 1">
                    <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                        src="{{ asset('a.pdf') }}" alt="PDF 1" />
                </a>
                <a href="{{ asset('b.pdf') }}" data-fancybox="gallery" data-caption="PDF 2">
                    <img class="w-full h-auto rounded-lg shadow-lg hover:shadow-2xl transition-transform transform hover:scale-105"
                        src="#" alt="PDF 2" />
                </a>
            </div>
        </div>
    </div>
</div>
