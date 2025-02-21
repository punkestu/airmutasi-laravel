<!DOCTYPE html>
<html lang="en">

<head>
    @include('components/head')
    <title>Mutant | Rotasi</title>
</head>

<body class="font-sans tracking-wider relative">
    @include('components.header', ['static' => true])
    @include('components.modal-component')
    <main>
        <section class="flex flex-col items-center my-4 px-2">
            <form method="POST" action="/rotasi/pengajuan"
                class="w-full rounded-md bg-white shadow-lg md:w-2/3 p-8 flex flex-col gap-4"
                enctype="multipart/form-data">
                <h1 class="font-semibold text-2xl text-center">Tambah Data Pengajuan Mutasi</h1>
                @csrf
                <section id="section-1" class="sections flex flex-col gap-4 p-2 rounded-md">
                    <div class="grid md:grid-cols-2 gap-4">
                        <aside class="w-full relative">
                            <label class="font-semibold" for="nik">NIK</label><br />
                            <input type="text" name="nik" id="nik"
                                class="w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md peer/nik"
                                placeholder="Ketik Disini ..." value="{{ old('nik') }}" autocomplete="one-time-code"
                                onkeyup="searchNIKDebounce()" />
                            <div class="w-full absolute z-20 hidden flex-col items-start border p-1 gap-1 bg-white max-h-[200px] overflow-y-auto"
                                id="nik-suggestion">
                            </div>
                        </aside>
                        <aside class="w-full">
                            <label class="font-semibold" for="nama_lengkap">Nama Lengkap</label><br />
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                class="w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md"
                                placeholder="Ketik Disini ..." value="{{ old('nama_lengkap') }}" />
                        </aside>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <aside class="w-full">
                            <label class="font-semibold" for="masa_kerja">Masa Kerja</label><br />
                            <div class="grid grid-cols-4 mt-1 gap-2 items-center">
                                <input type="number" name="masa_kerja" id="masa_kerja"
                                    class="col-span-3 px-2 py-1 border-2 border-slate-400 rounded-md"
                                    placeholder="Ketik Disini ..." value="{{ old('masa_kerja') }}" />
                                <button type="button" id="sk_mutasi_file_button"
                                    class="col-span-1 bg-[#003285]  opacity-80 hover:opacity-100 duration-200 text-white px-2 py-1 rounded-md font-medium"
                                    popovertarget="sk_mutasi_popover">{{ old('sk_mutasi_url') ? '✅️ Ubah' : 'Berkas' }}</button>
                                @include('rotasi.components.file-popover', [
                                    'id' => 'sk_mutasi',
                                    'url' => 'sk_mutasi_url',
                                    'file' => 'sk_mutasi_file',
                                    'file_url' => old('sk_mutasi_url'),
                                ])
                            </div>
                        </aside>
                        <aside class="w-full">
                            <label class="font-semibold" for="jabatan">Jabatan</label><br />
                            <input type="text" name="jabatan" id="jabatan"
                                class="w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md"
                                placeholder="Ketik Disini ..." value="{{ old('jabatan') }}" />
                        </aside>
                        <input type="hidden" name="tidak_pindah" id="tidak_pindah"
                            value="{{ old('tidak_pindah') ?? 'tidak' }}">
                    </div>
                    <div class="flex justify-end gap-4 w-full">
                        <button type="button"
                            class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                            onclick="changeSection(0)">
                            Selanjutnya
                        </button>
                    </div>
                </section>
                <section id="section-0" class="sections hidden flex-col gap-4 p-2 rounded-md">
                    <label class="font-semibold text-center" for="tidak-pindah">Pilih Pengajun</label>
                    <div class="flex justify-center gap-2">
                        <button type="button"
                            class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                            onclick="tidakpindah()">
                            Tidak Pindah
                        </button>
                        <button type="button"
                            class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                            onclick="changeSection(2)">
                            Mutasi
                        </button>
                    </div>
                </section>
                <section id="section-2" class="sections hidden flex-col gap-4 p-2 rounded-md">
                    <div class="grid md:grid-cols-9 gap-4 items-stretch">
                        <aside class="w-full md:col-span-4 flex flex-col justify-between">
                            <div>
                                <label class="font-semibold" for="lokasi_awal_id">Lokasi Awal</label><br />
                                <select class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md"
                                    name="lokasi_awal_id" id="lokasi_awal">
                                    <option value disabled>--- Pilih Lokasi Awal ---</option>
                                    @foreach ($cabangs as $cabang)
                                        <option id="lokasi-awal-{{ $cabang->id }}" value="{{ $cabang->id }}"
                                            {{ old('lokasi_awal_id') == $cabang->id || (!old('lokasi_awal_id') && $loop->index === 0) ? 'selected' : '' }}>
                                            {{ $cabang->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="relative">
                                <label class="font-semibold" for="posisi_sekarang">Posisi Awal</label><br />
                                <input type="text" class="w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md"
                                    value="{{ old('posisi_sekarang') }}" name="posisi_sekarang" id="posisi_sekarang"
                                    placeholder="Ketik Disini..." onkeyup="searchPosisiSekarangDebounce()">
                                <div class="w-full absolute z-20 hidden flex-col items-start border p-1 gap-1 bg-white max-h-[200px] overflow-y-auto"
                                    id="posisi_sekarang-suggestion">
                                </div>
                            </div>
                        </aside>
                        <div class="self-center flex justify-center items-center">
                            <img src="/images/icons/switch_blue.svg" alt="switch">
                        </div>
                        <aside class="w-full md:col-span-4 flex flex-col justify-between">
                            <div>
                                <label class="font-semibold" for="lokasi_tujuan_id">Lokasi Tujuan</label><br />
                                <select class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md"
                                    name="lokasi_tujuan_id" id="lokasi_tujuan">
                                    <option value>--- Pilih Lokasi Tujuan ---</option>
                                </select>
                                <select
                                    class="{{ !old('use_tujuan_alt') ? 'hidden' : '' }} w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md"
                                    name="lokasi_tujuan_alt_id" id="lokasi_tujuan_alt"
                                    {{ !old('use_tujuan_alt') ? 'disabled' : '' }}>
                                    <option value>--- Pilih Lokasi Tujuan Alternatif ---</option>
                                </select>
                                <select
                                    class="{{ !old('use_tujuan_alt2') ? 'hidden' : '' }} w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md"
                                    name="lokasi_tujuan_alt2_id" id="lokasi_tujuan_alt2"
                                    {{ !old('use_tujuan_alt2') ? 'disabled' : '' }}>
                                    <option value>--- Pilih Lokasi Tujuan Alternatif 2 ---</option>
                                </select>
                            </div>
                            <div class="relative">
                                <label class="font-semibold" for="posisi_tujuan">Posisi Tujuan</label><br />
                                <input type="text"
                                    class="w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md"
                                    value="{{ old('posisi_tujuan') }}" name="posisi_tujuan" id="posisi_tujuan"
                                    placeholder="Ketik Disini..." onkeyup="searchPosisiTujuanDebounce()">
                                <div class="w-full absolute z-20 hidden flex-col items-start border p-1 gap-1 bg-white max-h-[200px] overflow-y-auto"
                                    id="posisi_tujuan-suggestion">
                                </div>
                            </div>
                        </aside>
                    </div>
                    <div class="self-end flex items-center gap-4">
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="use_tujuan_alt" id="use_tujuan_alt"
                                {{ old('use_tujuan_alt') ? 'checked' : '' }}>
                            Tujuan alternatif?
                        </label>
                        <label id="use_tujuan_alt2_container"
                            class="{{ !old('use_tujuan_alt') ? 'hidden' : 'flex' }} items-center gap-1">
                            <input type="checkbox" name="use_tujuan_alt2" id="use_tujuan_alt2"
                                {{ old('use_tujuan_alt2') ? 'checked' : '' }}>
                            Tujuan alternatif 2?
                        </label>
                        <label class="flex items-center gap-1">
                            <input type="checkbox" name="abnormal" id="abnormal"
                                {{ old('abnormal') ? 'checked' : '' }}>
                            @can('admin')
                                Mutasi abnormal?
                            @else
                                Pengajuan?
                            @endcan
                        </label>
                    </div>
                    <div class="flex justify-end gap-4 w-full">
                        <button type="button"
                            class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                            onclick="changeSection(1)">
                            Kembali
                        </button>
                        <button type="button"
                            class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                            onclick="changeSection(3)">
                            Selanjutnya
                        </button>
                    </div>
                </section>
                <section id="section-3" class="sections hidden flex-col gap-4 p-2 rounded-md">
                    <div class="grid md:grid-cols-2 auto-rows-auto gap-4">
                        <aside class="w-full">
                            <label class="font-semibold" for="kompetensi">Kompetensi</label><br />
                            <div id="kompetensi" class="w-full my-2">
                                @if (old('kompetensi'))
                                    @foreach (old('kompetensi') as $index => $kompetensi)
                                        @include('rotasi.components.kompetensi-item', [
                                            'index' => $index,
                                            'id' => 'kompetensi_' . $index,
                                            'nama' => $kompetensi['nama'],
                                            'file_url' => $kompetensi['url'],
                                        ])
                                    @endforeach
                                @else
                                    @include('rotasi.components.kompetensi-item', [
                                        'index' => 1,
                                        'id' => 'kompetensi_1',
                                    ])
                                @endif
                            </div>
                            <button
                                class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white py-2 rounded-lg font-semibold w-full mt-1"
                                type="button" id="kompetensi_tambah">Tambah +</button>
                        </aside>
                        <aside class="flex flex-col justify-center gap-0">
                            <label class="font-semibold" for="tujuan_rotasi">Tujuan Rotasi</label>
                            <textarea name="tujuan_rotasi" id="tujuan_rotasi"
                                class="resize-none flex-grow w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md" rows="4"
                                placeholder="Ketik Disini ...">{{ old('tujuan_rotasi') }}</textarea>
                        </aside>
                    </div>
                    <div>
                        <label class="font-semibold">Surat Persetujuan Pejabat Setempat</label>
                        <br>
                        <button type="button" id="surat_persetujuan_file_button"
                            class="w-full bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-2 py-1 mt-1 rounded-md font-medium"
                            popovertarget="surat_persetujuan_popover">{{ old('surat_persetujuan_url') ? '✅️ Ubah' : 'Berkas' }}</button>
                        @include('rotasi.components.file-popover', [
                            'id' => 'surat_persetujuan',
                            'url' => 'surat_persetujuan_url',
                            'file' => 'surat_persetujuan_file',
                            'file_url' => old('surat_persetujuan_url'),
                            'note' => '*surat GM, surat pernyataan, berkas data pribadi (CV)',
                        ])
                    </div>
                    <div>
                        <label class="font-semibold" for="keterangan">Keterangan Tambahan</label><br />
                        <textarea name="keterangan" id="keterangan" class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                            rows="3" placeholder="Ketik Disini ...">{{ old('keterangan') }}</textarea>
                    </div>
                    <div class="flex justify-end gap-4 w-full">
                        <button type="button"
                            class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                            onclick="changeSection(2)">
                            Kembali
                        </button>
                        <button type="submit"
                            class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold">
                            Kirim
                        </button>
                    </div>
                </section>
            </form>
        </section>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/filePopover.js"></script>
    <script>
        function changeSection(section) {
            document.querySelectorAll('.sections').forEach(s => s.classList.add('hidden'));
            document.querySelector(`#section-${section}`).classList.remove('hidden');
            document.querySelector(`#section-${section}`).classList.add('flex');
        }
    </script>
    <script>
        // (re)bind delete button event for kompetensi item by id
        function kompetensiBindDeleteBtn(id) {
            document.querySelector(`#${id} > button`).addEventListener('click', function() {
                this.parentElement.remove();
            });
        }
    </script>
    <script>
        document.querySelector("#use_tujuan_alt").addEventListener("change", (e) => {
            const tujuanAlt = document.querySelector("#lokasi_tujuan_alt");
            if (e.target.checked) {
                tujuanAlt.classList.remove("hidden");
                tujuanAlt.disabled = false;
                document.querySelector("#use_tujuan_alt2_container").classList.remove('hidden');
                document.querySelector("#use_tujuan_alt2_container").classList.add('flex');
            } else {
                tujuanAlt.classList.add("hidden");
                tujuanAlt.disabled = true;
                document.querySelector("#use_tujuan_alt2_container").classList.remove('flex');
                document.querySelector("#use_tujuan_alt2_container").classList.add('hidden');
            }
        });

        document.querySelector("#use_tujuan_alt2").addEventListener("change", (e) => {
            const tujuanAlt = document.querySelector("#lokasi_tujuan_alt2");
            if (e.target.checked) {
                tujuanAlt.classList.remove("hidden");
                tujuanAlt.disabled = false;
            } else {
                tujuanAlt.classList.add("hidden");
                tujuanAlt.disabled = true;
            }
        });
    </script>
    <script>
        const abnormalCheckbox = document.getElementById('abnormal');
        const lokasiAwal = document.getElementById('lokasi_awal')

        @if (!old('abnormal'))
            fetch('/api/rotasi/cabang-same-kelas/' + lokasiAwal.value)
                .then(response => response.json())
                .then(data => {
                    const lokasiTujuan = document.getElementById('lokasi_tujuan');
                    const lokasiTujuanAlt = document.getElementById('lokasi_tujuan_alt');
                    const lokasiTujuanAlt2 = document.getElementById('lokasi_tujuan_alt2');
                    lokasiTujuan.innerHTML = '<option value>--- Pilih Lokasi Tujuan ---</option>';
                    lokasiTujuanAlt.innerHTML = '<option value>--- Pilih Lokasi Tujuan Alternatif ---</option>';
                    lokasiTujuanAlt2.innerHTML =
                        '<option value>--- Pilih Lokasi Tujuan Alternatif 2 ---</option>';
                    data.forEach(cabang => {
                        const option = document.createElement('option');
                        option.id = 'lokasi-tujuan-' + cabang.id;
                        option.value = cabang.id;
                        option.innerText = cabang.nama;
                        option.selected = cabang.id == {{ old('lokasi_tujuan_id') ?? -1 }};
                        lokasiTujuan.appendChild(option);

                        const optionAlt = document.createElement('option');
                        optionAlt.id = 'lokasi-tujuan-alt-' + cabang.id;
                        optionAlt.value = cabang.id;
                        optionAlt.innerText = cabang.nama;
                        optionAlt.selected = cabang.id == {{ old('lokasi_tujuan_alt_id') ?? -1 }};
                        lokasiTujuanAlt.appendChild(optionAlt);

                        const optionAlt2 = document.createElement('option');
                        optionAlt2.id = 'lokasi-tujuan-alt2-' + cabang.id;
                        optionAlt2.value = cabang.id;
                        optionAlt2.innerText = cabang.nama;
                        optionAlt2.selected = cabang.id ==
                            {{ old('lokasi_tujuan_alt2_id') ?? -1 }};
                        lokasiTujuanAlt2.appendChild(optionAlt2);
                    });
                });
        @else
            fetch('/api/rotasi/cabang')
                .then(response => response.json())
                .then(data => {
                    const lokasiTujuan = document.getElementById('lokasi_tujuan');
                    const lokasiTujuanAlt = document.getElementById('lokasi_tujuan_alt');
                    const lokasiTujuanAlt2 = document.getElementById('lokasi_tujuan_alt2');
                    lokasiTujuan.innerHTML = '<option value>--- Pilih Lokasi Tujuan ---</option>';
                    lokasiTujuanAlt.innerHTML = '<option value>--- Pilih Lokasi Tujuan Alternatif ---</option>';
                    lokasiTujuanAlt2.innerHTML =
                        '<option value>--- Pilih Lokasi Tujuan Alternatif 2 ---</option>';
                    data.forEach(cabang => {
                        const option = document.createElement('option');
                        option.id = 'lokasi-tujuan-' + cabang.id;
                        option.value = cabang.id;
                        option.innerText = cabang.nama;
                        option.selected = cabang.id == {{ old('lokasi_tujuan_id') ?? -1 }};
                        lokasiTujuan.appendChild(option);

                        const optionAlt = document.createElement('option');
                        optionAlt.id = 'lokasi-tujuan-alt-' + cabang.id;
                        optionAlt.value = cabang.id;
                        optionAlt.innerText = cabang.nama;
                        optionAlt.selected = cabang.id == {{ old('lokasi_tujuan_alt_id') ?? -1 }};
                        lokasiTujuanAlt.appendChild(optionAlt);

                        const optionAlt2 = document.createElement('option');
                        optionAlt2.id = 'lokasi-tujuan-alt2-' + cabang.id;
                        optionAlt2.value = cabang.id;
                        optionAlt2.innerText = cabang.nama;
                        optionAlt2.selected = cabang.id ==
                            {{ old('lokasi_tujuan_alt2_id') ?? -1 }};
                        lokasiTujuanAlt2.appendChild(optionAlt2);
                    });
                });
        @endif

        lokasiAwal.addEventListener('change', function() {
            const lokasiAwalID = this.value;
            if (!abnormalCheckbox.checked) {
                const cabangTujuan = fetch('/api/rotasi/cabang-same-kelas/' + lokasiAwalID)
                    .then(response => response.json())
                    .then(data => {
                        const lokasiTujuan = document.getElementById('lokasi_tujuan');
                        const lokasiTujuanAlt = document.getElementById('lokasi_tujuan_alt');
                        const lokasiTujuanAlt2 = document.getElementById('lokasi_tujuan_alt2');
                        lokasiTujuan.innerHTML = '<option value>--- Pilih Lokasi Tujuan ---</option>';
                        lokasiTujuanAlt.innerHTML =
                            '<option value>--- Pilih Lokasi Tujuan Alternatif ---</option>';
                        lokasiTujuanAlt2.innerHTML =
                            '<option value>--- Pilih Lokasi Tujuan Alternatif 2 ---</option>';
                        data.forEach(cabang => {
                            const option = document.createElement('option');
                            option.id = 'lokasi-tujuan-' + cabang.id;
                            option.value = cabang.id;
                            option.innerText = cabang.nama;
                            option.selected = cabang.id == {{ old('lokasi_tujuan_id') ?? -1 }};
                            lokasiTujuan.appendChild(option);

                            const optionAlt = document.createElement('option');
                            optionAlt.id = 'lokasi-tujuan-alt-' + cabang.id;
                            optionAlt.value = cabang.id;
                            optionAlt.innerText = cabang.nama;
                            optionAlt.selected = cabang.id == {{ old('lokasi_tujuan_alt_id') ?? -1 }};
                            lokasiTujuanAlt.appendChild(optionAlt);

                            const optionAlt2 = document.createElement('option');
                            optionAlt2.id = 'lokasi-tujuan-alt2-' + cabang.id;
                            optionAlt2.value = cabang.id;
                            optionAlt2.innerText = cabang.nama;
                            optionAlt2.selected = cabang.id ==
                                {{ old('lokasi_tujuan_alt2_id') ?? -1 }};
                            lokasiTujuanAlt2.appendChild(optionAlt2);
                        });
                    });
            }
        });
        abnormalCheckbox.addEventListener('change', function() {
            const lokasiAwalID = lokasiAwal.value;
            if (!this.checked) {
                const cabangTujuan = fetch('/api/rotasi/cabang-same-kelas/' + lokasiAwalID)
                    .then(response => response.json())
                    .then(data => {
                        const lokasiTujuan = document.getElementById('lokasi_tujuan');
                        const lokasiTujuanAlt = document.getElementById('lokasi_tujuan_alt');
                        const lokasiTujuanAlt2 = document.getElementById('lokasi_tujuan_alt2');
                        lokasiTujuan.innerHTML = '<option value>--- Pilih Lokasi Tujuan ---</option>';
                        lokasiTujuanAlt.innerHTML =
                            '<option value>--- Pilih Lokasi Tujuan Alternatif ---</option>';
                        lokasiTujuanAlt2.innerHTML =
                            '<option value>--- Pilih Lokasi Tujuan Alternatif 2 ---</option>';
                        data.forEach(cabang => {
                            const option = document.createElement('option');
                            option.id = 'lokasi-tujuan-' + cabang.id;
                            option.value = cabang.id;
                            option.innerText = cabang.nama;
                            option.selected = cabang.id == {{ old('lokasi_tujuan_id') ?? -1 }};
                            lokasiTujuan.appendChild(option);

                            const optionAlt = document.createElement('option');
                            optionAlt.id = 'lokasi-tujuan-alt-' + cabang.id;
                            optionAlt.value = cabang.id;
                            optionAlt.innerText = cabang.nama;
                            optionAlt.selected = cabang.id ==
                                {{ old('lokasi_tujuan_alt_id') ?? -1 }};
                            lokasiTujuanAlt.appendChild(optionAlt);

                            const optionAlt2 = document.createElement('option');
                            optionAlt2.id = 'lokasi-tujuan-alt2-' + cabang.id;
                            optionAlt2.value = cabang.id;
                            optionAlt2.innerText = cabang.nama;
                            optionAlt2.selected = cabang.id ==
                                {{ old('lokasi_tujuan_alt2_id') ?? -1 }};
                            lokasiTujuanAlt2.appendChild(optionAlt2);
                        });
                    });
            } else {
                const cabangTujuan = fetch('/api/rotasi/cabang')
                    .then(response => response.json())
                    .then(data => {
                        const lokasiTujuan = document.getElementById('lokasi_tujuan');
                        const lokasiTujuanAlt = document.getElementById('lokasi_tujuan_alt');
                        const lokasiTujuanAlt2 = document.getElementById('lokasi_tujuan_alt2');
                        lokasiTujuan.innerHTML = '<option value>--- Pilih Lokasi Tujuan ---</option>';
                        lokasiTujuanAlt.innerHTML =
                            '<option value>--- Pilih Lokasi Tujuan Alternatif ---</option>';
                        lokasiTujuanAlt2.innerHTML =
                            '<option value>--- Pilih Lokasi Tujuan Alternatif 2 ---</option>';
                        data.forEach(cabang => {
                            const option = document.createElement('option');
                            option.id = 'lokasi-tujuan-' + cabang.id;
                            option.value = cabang.id;
                            option.innerText = cabang.nama;
                            option.selected = cabang.id == {{ old('lokasi_tujuan_id') ?? -1 }};
                            lokasiTujuan.appendChild(option);

                            const optionAlt = document.createElement('option');
                            optionAlt.id = 'lokasi-tujuan-alt-' + cabang.id;
                            optionAlt.value = cabang.id;
                            optionAlt.innerText = cabang.nama;
                            optionAlt.selected = cabang.id ==
                                {{ old('lokasi_tujuan_alt_id') ?? -1 }};
                            lokasiTujuanAlt.appendChild(optionAlt);

                            const optionAlt2 = document.createElement('option');
                            optionAlt2.id = 'lokasi-tujuan-alt2-' + cabang.id;
                            optionAlt2.value = cabang.id;
                            optionAlt2.innerText = cabang.nama;
                            optionAlt2.selected = cabang.id ==
                                {{ old('lokasi_tujuan_alt2_id') ?? -1 }};
                            lokasiTujuanAlt2.appendChild(optionAlt2);
                        });
                    });
            }
        });
    </script>
    <script>
        // counter for kompetensi id
        var kompetensiCount = 1;

        // set file popover behaviour
        initFilePopover('sk_mutasi');
        initFilePopover('surat_persetujuan');
        // bind delete button event for initial kompetensi item
        document.querySelectorAll(".kompetensi-item").forEach(kompetensi => {
            kompetensiBindDeleteBtn(kompetensi.id);
            initFilePopover(kompetensi.id);
        });

        // add kompetensi item
        document.querySelector("#kompetensi_tambah").addEventListener('click', function() {
            const kompetensiContainer = document.querySelector('#kompetensi');
            kompetensiContainer.insertAdjacentHTML("beforeend", `@include('rotasi.components.kompetensi-item', [
                'index' => '${kompetensiCount + 1}',
                'id' => 'kompetensi_${kompetensiCount + 1}',
            ])`);
            document.querySelectorAll(".kompetensi-item").forEach(kompetensi => {
                kompetensiBindDeleteBtn(kompetensi.id);
                initFilePopover(kompetensi.id);
            });
            kompetensiCount++;
        });
    </script>
    <script>
        function searchNIK() {
            const nik = document.getElementById('nik').value;
            if (nik.length < 4) {
                return;
            }
            fetch('/api/personel?nik=' + nik)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nik-suggestion').classList.remove('hidden');
                    document.getElementById('nik-suggestion').classList.add('flex');
                    const nikSuggestion = document.getElementById('nik-suggestion');
                    nikSuggestion.innerHTML = '';
                    data.forEach(personel => {
                        const button = document.createElement('button');
                        button.classList.add('border', 'hover:border-2', 'w-full', 'text-start', 'p-1');
                        button.innerText = personel.nik;
                        button.type = "button";
                        button.addEventListener('click', function() {
                            document.getElementById('nik').value = personel.nik;
                            document.getElementById('nama_lengkap').value = personel.name;
                            document.getElementById('masa_kerja').value = personel.masa_kerja;
                            document.getElementById('jabatan').value = personel.jabatan;
                            document.getElementById('lokasi_awal').value = personel.cabang_id;
                            document.getElementById('lokasi_awal').dispatchEvent(new Event('change'));
                            document.getElementById('nik-suggestion').classList.add('hidden');
                            document.getElementById('nik-suggestion').classList.remove('flex');
                        });
                        nikSuggestion.appendChild(button);
                    });
                });
        }

        function searchPosisi(posisiSekarang = true) {
            const posisi = document.getElementById(posisiSekarang ? 'posisi_sekarang' : 'posisi_tujuan').value;
            if (posisi.length < 2) {
                return;
            }
            fetch('/api/rotasi/posisi?search=' + posisi)
                .then(response => response.json())
                .then(data => {
                    const posisiSekarangSuggestion = document.getElementById('posisi_sekarang-suggestion');
                    const posisiTujuanSuggestion = document.getElementById('posisi_tujuan-suggestion');
                    posisiSekarangSuggestion.innerHTML = '';
                    posisiTujuanSuggestion.innerHTML = '';
                    if (posisiSekarang) {
                        posisiSekarangSuggestion.classList.remove('hidden');
                    } else {
                        posisiTujuanSuggestion.classList.remove('hidden');
                    }
                    data.forEach(posisi => {
                        const button = document.createElement('button');
                        button.classList.add('border', 'hover:border-2', 'w-full', 'text-start', 'p-1');
                        button.innerText = posisi["jabatan"];
                        button.type = "button";
                        button.addEventListener('click', function() {
                            if (posisiSekarang) {
                                document.getElementById('posisi_sekarang').value = posisi["jabatan"];
                                document.getElementById('posisi_sekarang-suggestion').classList.add(
                                    'hidden');
                                document.getElementById('posisi_sekarang-suggestion').classList.remove(
                                    'flex');
                            } else {
                                document.getElementById('posisi_tujuan').value = posisi["jabatan"];
                                document.getElementById('posisi_tujuan-suggestion').classList.add(
                                    'hidden');
                                document.getElementById('posisi_tujuan-suggestion').classList.remove(
                                    "flex");
                            }
                        });
                        if (posisiSekarang) {
                            posisiSekarangSuggestion.appendChild(button);
                        } else {
                            posisiTujuanSuggestion.appendChild(button);
                        }
                    });
                });
        }

        const body = document.querySelector('body');
        body.addEventListener('click', function(e) {
            if (!e.target.closest('#nik-suggestion') && !e.target.closest('#nik')) {
                document.getElementById('nik-suggestion').classList.add('hidden');
                document.getElementById('nik-suggestion').classList.remove('flex');
            }
            if (!e.target.closest('#posisi_sekarang-suggestion') && !e.target.closest('#posisi_sekarang')) {
                document.getElementById('posisi_sekarang-suggestion').classList.add('hidden');
            }
            if (!e.target.closest('#posisi_tujuan-suggestion') && !e.target.closest('#posisi_tujuan')) {
                document.getElementById('posisi_tujuan-suggestion').classList.add('hidden');
            }
        });

        function debounce(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    func.apply(this, args);
                }, timeout);
            };
        }
        const searchNIKDebounce = debounce(searchNIK, 500);
        const searchPosisiSekarangDebounce = debounce(() => searchPosisi(true), 500);
        const searchPosisiTujuanDebounce = debounce(() => searchPosisi(false), 500);

        function tidakpindah() {
            document.getElementById('tidak_pindah').value = 'ya';
            document.querySelector('form').submit();
        }
    </script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
