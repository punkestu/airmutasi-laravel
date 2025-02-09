<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Mutant | Profil</title>
</head>

<body class="font-sans tracking-wider text-lg">
    @include('components/header', ['static' => true])
    @include('components.modal-component')
    <main class="px-8 py-16">
        <form action="/akun/add" method="POST" class="flex justify-center gap-16">
            @csrf
            <aside class="w-2/5">
                <img class="h-full object-cover rounded-md" src="/images/backgrounds/LOGIN.jpeg" alt="illustration">
            </aside>
            <aside class="w-3/5 flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                    <aside>
                        <h1 class="font-semibold text-2xl">Tambah Akun</h1>
                    </aside>
                    <aside class="flex items-center gap-4">
                        <a href="/akun"
                            class="px-4 py-2 bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-300 rounded-md">Cancel</a>
                        <button type="submit"
                            class="px-4 py-2 bg-[#003285] border-2 border-[#003285] text-white opacity-80 hover:opacity-100 duration-300 rounded-md">Save</button>
                    </aside>
                </div>
                <hr>
                <div class="flex flex-col gap-2">
                    <label for="name" class="font-semibold">Nama <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name"
                        class="w-full p-2 mt-1 border-2 border-slate-400 rounded-md mb-2" placeholder="Nama"
                        value="{{ old('name') }}">
                    <label for="nik" class="font-semibold">NIK <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" id="nik"
                        class="w-full p-2 mt-1 border-2 border-slate-400 rounded-md mb-2" placeholder="NIK"
                        value="{{ old('nik') }}">
                    <label for="email" class="font-semibold">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email"
                        class="w-full p-2 mt-1 border-2 border-slate-400 rounded-md mb-2" placeholder="Email"
                        value="{{ old('email') }}">
                    <label for="password" class="font-semibold">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" id="password"
                        class="w-full p-2 mt-1 border-2 border-slate-400 rounded-md mb-2"
                        placeholder="Minimal 8 karakter">
                    <div class="flex gap-2 w-full sm:flex-row flex-col">
                        <aside class="flex-grow">
                            <label for="masa_kerja" class="font-semibold">Masa Kerja <span
                                    class="text-red-500">*</span></label>
                            <input type="number" name="masa_kerja" id="masa_kerja"
                                class="w-full p-2 mt-1 border-2 border-slate-400 rounded-md mb-2"
                                placeholder="Masa Kerja" value="{{ old('masa_kerja') }}">
                        </aside>
                        <aside class="flex-grow">
                            <label for="cabang_id" class="font-semibold">Cabang <span
                                    class="text-red-500">*</span></label>
                            <select name="cabang_id" id="cabang_id"
                                class="w-full p-2 mt-1 bg-white border-2 border-slate-400 rounded-md">
                                <option value disabled selected>--- Pilih Cabang ---</option>
                                @foreach ($cabangs as $currCabang)
                                    <option value="{{ $currCabang->id }}">{{ $currCabang->nama }}</option>
                                @endforeach
                            </select>
                        </aside>
                    </div>
                    <label for="jabatan" class="font-semibold">Jabatan <span class="text-red-500">*</span></label>
                    <input type="jabatan" name="jabatan" id="jabatan"
                        class="w-full p-2 mt-1 border-2 border-slate-400 rounded-md mb-2" placeholder="Jabatan"
                        value="{{ old('jabatan') }}">
                </div>
            </aside>
        </form>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
