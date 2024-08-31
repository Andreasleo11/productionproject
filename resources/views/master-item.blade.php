<x-app-layout>
    <!-- Alert Notification -->
    <div id="successAlert"
        class="fixed top-5 right-5 w-full max-w-sm bg-green-100 shadow-lg rounded-lg overflow-hidden p-4 items-center space-x-3 hidden">
        <!-- Check Icon -->
        <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <!-- Message -->
        <div class="flex-grow">
            <span id="alertMessage" class="text-green-900 font-medium"></span>
        </div>
        <!-- Close Button -->
        <button onclick="closeAlert()" class="text-green-900 focus:outline-none ml-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <script>
        function showAlert(message) {
            const alertElement = document.getElementById('successAlert');
            const messageElement = document.getElementById('alertMessage');
            messageElement.textContent = message;
            alertElement.classList.remove('hidden');

            setTimeout(function() {
                alertElement.classList.add('hidden');
            }, 5000); // Auto-hide after 5 seconds
        }

        function closeAlert() {
            document.getElementById('successAlert').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                showAlert("{{ session('success') }}");
            @endif
        });
    </script>

    <div class="container mx-auto p-6 bg-gray-100">
        <div class="overflow-x-auto shadow-lg rounded-lg">
            <table class="min-w-full bg-white rounded-lg">
                <thead>
                    <tr class="w-full bg-gray-800 text-white">
                        <th class="py-3 px-6 text-left">Item Code</th>
                        <th class="py-3 px-6 text-left">Files</th>
                        <th class="py-3 px-6 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr class="border-b">
                            <td class="py-4 px-6">{{ $item->item_code }}</td>
                            <td class="py-4 px-6">
                                <!-- Nested table for files -->
                                @if ($item->files->isNotEmpty())
                                    <table class="min-w-full bg-gray-100 rounded-lg">
                                        <thead>
                                            <tr class="w-full bg-gray-300 text-gray-800">
                                                <th class="py-3 px-4 text-left">File Name</th>
                                                <th class="py-3 px-4 text-left">Mime Type</th>
                                                <th class="py-3 px-4 text-left">Size</th>
                                                <th class="py-3 px-4 text-left">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item->files as $file)
                                                <tr class="border-b">
                                                    <td class="py-3 px-4">{{ $file->name }}</td>
                                                    <td class="py-3 px-4">{{ $file->mime_type }}</td>
                                                    <td class="py-3 px-4">{{ $file->size }}</td>
                                                    <td class="py-3 px-3">
                                                        <form action="{{ route('file.delete', $file->id) }}"
                                                            method="post">
                                                            @csrf @method('delete')
                                                            <button type="submit"
                                                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg cursor-pointer">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    No files uploaded.
                                @endif
                            </td>
                            <td>
                                <button
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg cursor-pointer"
                                    onclick="openModal('{{ $item->item_code }}')">Upload Files</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination links -->
            <div class="mt-4 mb-2 mx-3">
                {{ $items->links() }}
            </div>
        </div>

        <!-- Modal -->
        <div id="uploadModal" class="fixed inset-0 items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="w-full max-w-lg mx-auto mt-12">
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="px-6 py-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Upload Files</h2>
                        <div class="mb-4">
                            <label class="block text-gray-700 font-medium mb-2" for="files">Select Files</label>
                            <div class="flex items-center justify-center w-full">
                                <label
                                    class="flex flex-col items-center w-full h-32 px-4 transition bg-white border-2 border-gray-300 border-dashed rounded-md cursor-pointer hover:border-gray-400 hover:bg-gray-50">
                                    <span class="flex items-center space-x-2">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V8m10 8V8m-5 8V8m-5 8h10"></path>
                                        </svg>
                                        <span class="font-medium text-gray-600">Drop files here or <span
                                                class="text-blue-600 underline">browse</span></span>
                                    </span>
                                    <form action="{{ route('file.upload') }}" method="post"
                                        enctype="multipart/form-data" id="formUploadFile">
                                        @csrf
                                        <input type="hidden" name="item_code" id="item_code">
                                        <input id="files" type="file" name="files[]" class="hidden" multiple>
                                    </form>
                                </label>
                            </div>
                            <div id="fileList" class="mt-4 text-sm text-gray-600"></div>
                        </div>
                        <div class="flex justify-center">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full"
                                onclick="document.getElementById('formUploadFile').submit()">
                                Upload
                            </button>
                            <button
                                class="ml-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-full"
                                onclick="closeModal()">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openModal(item_code) {
                console.log(item_code);
                document.getElementById('item_code').value = item_code;
                document.getElementById('uploadModal').classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('uploadModal').classList.add('hidden');
            }

            document.getElementById('files').addEventListener('change', function() {
                const fileList = document.getElementById('fileList');
                fileList.innerHTML = '';
                for (let i = 0; i < this.files.length; i++) {
                    const listItem = document.createElement('div');
                    listItem.textContent = this.files[i].name;
                    fileList.appendChild(listItem);
                }
            });

            // document.addEventListener('DOMContentLoaded', function() {
            //     @if (session('success'))
            //         alert("{{ session('success') }}");
            //     @endif
            // });
        </script>
    </div>
</x-app-layout>
