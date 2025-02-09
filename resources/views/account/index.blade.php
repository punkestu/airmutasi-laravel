<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Mutant | Profil</title>
</head>

<body class="font-sans tracking-wider">
    @include('components/header', ['static' => true])
    @include('components.modal-component')
    <main class="px-8 py-16">
        <section class="flex justify-center gap-16">
            <aside class="w-2/5">
                <img class="h-full object-cover rounded-md" src="/images/thumbnail2.jpeg" alt="illustration">
            </aside>
            <aside class="w-3/5 flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                    <aside>
                        <h1 class="font-semibold text-2xl">{{ $akun->name }}</h1>
                        @if ($akun->profile != null)
                            <p class="text-xl">{{ $akun->profile->jabatan }}</p>
                        @endif
                    </aside>
                    <aside class="flex items-center gap-4">
                        <a href="/akun/edit"
                            class="px-4 py-2 bg-[#003285] text-white opacity-80 hover:opacity-100 duration-300 rounded-md">Edit</a>
                        @can('admin')
                            <button
                                class="px-4 py-2 bg-[#003285] text-white opacity-80 hover:opacity-100 duration-300 rounded-md"
                                popovertarget="admin-action">☰</button>
                        @endcan
                    </aside>
                </div>
                <hr>
                <div class="flex flex-col gap-2">
                    @if ($akun->profile != null)
                        @if ($akun->profile->cabang_id != null)
                            <p>Cabang:</p>
                            <p class="w-full border-2 border-[#0005] rounded-md px-2 py-1">
                                {{ $akun->profile->cabang->nama }}</p>
                        @endif
                        <p>NIK:</p>
                        <p class="w-full border-2 border-[#0005] rounded-md px-2 py-1">{{ $akun->profile->nik }}</p>
                        <p>Masa Kerja:</p>
                        <p class="w-full border-2 border-[#0005] rounded-md px-2 py-1">{{ $akun->profile->masa_kerja }}
                            Tahun</p>
                    @endif
                    <p>Email:</p>
                    <p class="w-full border-2 border-[#0005] rounded-md px-2 py-1">{{ $akun->email }}</p>
                </div>
            </aside>
        </section>
        @can('admin')
            <section popover="auto" id="admin-action" class="rounded-md p-4 shadow-lg">
                <div class="flex flex-col gap-2">
                    <a href="/akun/add"
                        class="px-4 py-2 bg-[#003285] text-white opacity-80 hover:opacity-100 rounded-md duration-300">Buat
                        akun personel baru</a>
                    <button popovertarget="cabang-assign"
                        class="px-4 py-2 bg-[#003285] text-white opacity-80 hover:opacity-100 rounded-md duration-300">Daftarkan
                        cabang ke
                        akun</button>
                    <button popovertarget="kategori-jabatan"
                        class="px-4 py-2 bg-[#003285] text-white opacity-80 hover:opacity-100 rounded-md duration-300">Kategori
                        jabatan</button>
                    <button popovertarget="ubah-welcome-popup"
                        class="px-4 py-2 bg-[#003285] text-white opacity-80 hover:opacity-100 rounded-md duration-300">Ubah
                        welcome popup</button>
                    <div id="kategori-jabatan" popover class="p-2 rounded-md w-1/2 max-h-[50vh] overflow-y-auto border-2">
                        <div>
                            @if ($kategori_jabatan->count() == 0)
                                <div class="flex flex-col items-center gap-4">
                                    <p class="text-center">Belum ada kategori jabatan</p>
                                    <button popovertarget="add-kategori"
                                        class=" bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg font-semibold text-center">Tambah
                                        Kategori</button>
                                </div>
                            @else
                                <button popovertarget="add-kategori"
                                    class=" bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg font-semibold text-center mb-2">Tambah
                                    Kategori</button>
                                <table class="w-full">
                                    <thead>
                                        <tr>
                                            <th class="border-2 border-slate-400">Nama</th>
                                            <th class="border-2 border-slate-400">Jabatan</th>
                                            <th class="border-2 border-slate-400">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kategori_jabatan as $currKategori)
                                            <tr>
                                                <td class="border-2 border-slate-400 px-2 py-1">
                                                    {{ $currKategori->category }}</td>
                                                <td class="border-2 border-slate-400 px-2 py-1 flex gap-2 flex-wrap">
                                                    @foreach ($jabatans[$currKategori->category] as $currJabatan)
                                                        <div
                                                            class="flex items-center gap-1 px-2 py-1 mt-1 bg-gray-200 border-2 border-slate-400 rounded-md">
                                                            {{ $currJabatan->jabatan }}
                                                            <input type="hidden" name="jabatan"
                                                                value="{{ $currJabatan->jabatan }}">
                                                            <a href="/akun/jabatan/{{ $currJabatan->id }}/delete">X</a>
                                                        </div>
                                                    @endforeach
                                                </td>
                                                <td class="border-2 border-slate-400 px-2 py-1">
                                                    <button popovertarget="add-jabatan"
                                                        class="underline text-blue-500">Tambah</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                    <div id="add-jabatan" popover class="p-2 rounded-md border-2">
                        <form action="/akun/jabatan" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <h1 class="text-center font-semibold text-xl">Tambah Jabatan</h1>
                            <select name="category" id="category"
                                class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md text-center">
                                <option value disabled selected>--- Pilih Kategori ---</option>
                                @foreach ($kategori_jabatan as $currKategori)
                                    <option value="{{ $currKategori->category }}">{{ $currKategori->category }}</option>
                                @endforeach
                            </select>
                            <input type="text" name="jabatan" id="jabatan"
                                class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md text-center"
                                placeholder="Nama Jabatan">
                            <button
                                class="bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg font-semibold text-center">Simpan</button>
                        </form>
                    </div>
                    <div id="add-kategori" popover class="p-2 rounded-md border-2">
                        <form action="/akun/jabatan" id="add-jabatan-form" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <h1 class="text-center font-semibold text-xl">Tambah Kategori Jabatan</h1>
                            <input type="text" name="category" id="category"
                                class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md text-center"
                                placeholder="Nama Kategori">
                            <div class="flex flex-wrap gap-2">
                                <div class="flex flex-wrap gap-2" id="jabatan-list">
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <input type="text" id="jabatan-baru"
                                        class="max-w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md text-center"
                                        placeholder="Jabatan Baru ...">
                                    <button type="button"
                                        class="bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg font-semibold text-center"
                                        onclick="tambahJabatan()">Tambah</button>
                                </div>
                            </div>
                            <button
                                class="bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg font-semibold text-center">Simpan</button>
                        </form>
                    </div>
                    <div id="cabang-assign" popover class="p-2 rounded-md w-1/2 border-2">
                        <form action="/akun/assign" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <h1 class="text-center font-semibold text-xl">Daftarkan cabang ke akun</h1>
                            <select name="user_id" id="user_id"
                                class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md text-center">
                                <option value disabled selected>--- Pilih Akun ---</option>
                                @foreach ($akuns as $currAkun)
                                    <option value="{{ $currAkun->id }}">{{ $currAkun->user->name }}</option>
                                @endforeach
                            </select>
                            <select name="cabang_id" id="cabang_id"
                                class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md text-center">
                                <option value disabled selected>--- Pilih Cabang ---</option>
                                @foreach ($cabangs as $currCabang)
                                    <option value="{{ $currCabang->id }}">{{ $currCabang->nama }}</option>
                                @endforeach
                            </select>
                            <button
                                class="bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg font-semibold text-center">Daftarkan</button>
                        </form>
                    </div>
                    <div id="ubah-welcome-popup" popover>
                        <form action="/welcome-popup" method="POST" class="flex flex-col gap-2" enctype="multipart/form-data">
                            @csrf
                            <h1 class="text-center font-semibold text-xl">Ubah Welcome Popup</h1>
                            <input type="file" name="welcome-popup" id="welcome-popup-input">
                            <button
                                class="bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg font-semibold text-center">Simpan</button>
                        </form>
                    </div>
                </div>
            </section>
        @endcan
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
    <script>
        function itemJabatan(nama) {
            return `<div
                class="flex items-center gap-1 px-2 py-1 mt-1 bg-gray-200 border-2 border-slate-400 rounded-md">
                ${nama}
                <input type="hidden" name="jabatan[]" value="${nama}">
                <button type="button" onclick="removeJabatan(this)">X</button>
            </div>`;
        }

        function tambahJabatan() {
            const jabatanBaru = document.getElementById('jabatan-baru').value;
            if (jabatanBaru === "") {
                return;
            }
            const jabatanList = document.getElementById('jabatan-list');
            jabatanList.innerHTML += itemJabatan(jabatanBaru);
            document.getElementById('jabatan-baru').value = "";
        }

        function removeJabatan(e) {
            e.parentElement.remove();
        }
        document.getElementById('jabatan-baru').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                tambahJabatan();
            }
        });
        document.getElementById('add-jabatan-form').addEventListener('submit', function(e) {
            if (document.activeElement === document.getElementById("jabatan-baru")) {
                e.preventDefault();
                return;
            }
        });
    </script>
</body>

</html>
