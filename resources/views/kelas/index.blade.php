<!DOCTYPE html>
<html lang="en">

<head>
    @include('components/head')
    <title>Mutant | Kelas</title>
</head>

<body class="tracking-wider">
    @include('components.header', ['static' => true])
    @include('components.modal-component')
    <main class="min-h-screen p-8">
        <button popovertarget="tambah-data"
            class="col-span-4 lg:col-span-1 bg-[#003285] text-white opacity-80 hover:opacity-100 duration-300 w-full text-center text-lg p-2 rounded-lg font-semibold">Tambah
            Data</button>
        <div id="tambah-data" popover class="p-2 rounded-md w-1/2 max-h-[50vh] overflow-y-auto border-2">
            <form action="/kelas" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        {{-- select kelas --}}
                        <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                        <select name="kelas" id="kelas"
                            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            @foreach ($kelases as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="list-cabang">
                        <label for="cabang" class="block text-sm font-medium text-gray-700">Cabang</label>
                        <div class="grid grid-cols-12 gap-2">
                            <select name="cabang[]" id="cabang"
                                class="col-span-11 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach ($cabangs as $cabang)
                                    <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                                @endforeach
                            </select>
                            <button type="button" onclick="deleteCabang(this)"
                                class="rounded-md border-2 border-[#003285] aspect-square">X</button>
                        </div>
                    </div>
                    <button type="button" onclick="tambahCabang()"
                        class="border border-[#003285] text-[#003285] opacity-80 hover:opacity-100 duration-300 w-full text-center text-lg p-2 rounded-lg font-semibold">Tambah
                        Cabang</button>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-[#003285] text-white opacity-80 hover:opacity-100 duration-300 w-full text-center text-lg p-2 rounded-lg font-semibold">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="relative overflow-x-auto max-h-[70vh] overflow-y-auto block mt-4">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-50 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Kelas
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Cabang
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kelases as $kelas)
                        <tr class="bg-white border-b ">
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                {{ $kelas->nama_kelas }}
                            </td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap flex gap-2">
                                @if ($kelas->cabang->isEmpty())
                                    <div
                                        class="flex items-center gap-1 px-2 py-1 mt-1 bg-gray-200 border-2 border-slate-400 rounded-md">
                                        Belum ada cabang
                                    </div>
                                @endif
                                @foreach ($kelas->cabang as $cabang)
                                    <div
                                        class="flex items-center gap-1 px-2 py-1 mt-1 bg-gray-200 border-2 border-slate-400 rounded-md">
                                        {{ $cabang->nama }}
                                        <input type="hidden" name="jabatan[]" value="${nama}">
                                        <a href="/kelas/{{ $cabang->id }}/{{ $kelas->id }}/delete">X</a>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
    <script>
        function tambahCabang() {
            const listCabang = document.getElementById('list-cabang');
            const div = document.createElement('div');
            div.innerHTML = `
                <div class="grid grid-cols-12 gap-2">
                    <select name="cabang[]" id="cabang"
                        class="col-span-11 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="deleteCabang(this)" class="rounded-md border-2 border-[#003285] aspect-square">X</button>
                </div>
            `;
            listCabang.appendChild(div);
        }

        function deleteCabang(e) {
            e.parentElement.remove();
        }
    </script>
</body>

</html>
