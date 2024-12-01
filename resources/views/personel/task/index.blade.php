<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Air Mutasi | Personel</title>
</head>

<body class="font-sans tracking-wider text-lg">
    @include('components/header', ['static' => true])
    @include('components.modal-component')

    <div id="import-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <form method="POST" action="/personel/import" enctype="multipart/form-data"
                class="relative bg-white rounded-lg shadow ">
                @csrf
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                    <h3 class="text-xl font-semibold text-gray-900 ">
                        Import Data Personel
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                        data-modal-hide="import-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <input type="file" name="sheet" id="sheet" required>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button data-modal-hide="import-modal" type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Kirim</button>
                    <button data-modal-hide="import-modal" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">Batal</button>
                </div>
            </form>
        </div>
    </div>
    <main class="min-h-screen">
        <div class="text-xl font-semibold text-gray-800 px-4 py-2 bg-gray-100 flex gap-4">
            <a href="/personel">Personel</a>
            <a href="/personel/konsep">Konsep</a>
            <a href="/personel/task" class="underline">Task</a>
        </div>
        <form action="" method="POST" enctype="multipart/form-data" class="flex flex-col p-4">
            @csrf
            <div class="flex flex-col gap-2 border-2 p-2 rounded-md">
                <label for="deskripsi">Deskripsi</label>
                <input type="text" name="deskripsi" id="deskripsi"
                    class="resize-none flex-grow p-2 border-2 border-slate-400 rounded-s-md"
                    placeholder="Nama Berkas ...">
                <label for="url">Berkas</label>
                <div class="flex w-full">
                    <input type="text" name="url" id="url"
                        class="resize-none flex-grow p-2 border-2 border-slate-400 rounded-s-md"
                        placeholder="URL Berkas">
                    <button id="url_set" type="button"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white p-2 rounded-e-lg font-semibold">Set</button>
                </div>
                <p class="text-center">atau</p>
                <label
                    class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-2 py-1 rounded-md font-medium hover:cursor-pointer text-center">
                    <span id="berkas_label" class="text-center">Upload Berkas (max 2MB)</span>
                    <input type="file" name="berkas" id="berkas" class="h-0 w-0" accept=".pdf,.jpeg,.png">
                </label>
                <p id="errors" class="text-center text-red-500 text-sm hidden"></p>
                <button
                    class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-2 py-1 rounded-md font-medium hover:cursor-pointer text-center">Kirim</button>
            </div>
        </form>
        <div class="flex flex-col px-4 py-2 gap-2">
            @foreach ($tasks as $task)
                <div class="w-full">
                    <a href="{{$task->berkas}}" class="w-full flex gap-2" target="_blank">{{ $task->deskripsi }} <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778" />
                        </svg>
                    </a>
                    <embed class="w-full h-[60vh]" src="{{ $task->berkas }}" frameborder="0"></embed>
                </div>
            @endforeach
        </div>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
    <script>
        document
            .querySelector(`#url_set`)
            .addEventListener("click", function() {
                document.querySelector(`#berkas`).value = "";
                document.querySelector(`#url_set`).innerHTML = "✅️ Ubah";
                document.querySelector(`#berkas_label`).innerHTML = "Upload Berkas (max 2MB)";
            });
        document
            .querySelector(`#berkas`)
            .addEventListener("change", function() {
                var file = this.files[0];
                if (!file) return;

                document.querySelector(`#url`).value = "";
                document.querySelector(`#url_set`).innerHTML = "Set";
                document.querySelector(`#berkas_label`).innerHTML = "✅️ Ubah";
            });
    </script>
</body>

</html>
