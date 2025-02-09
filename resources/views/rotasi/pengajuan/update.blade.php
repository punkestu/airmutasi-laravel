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
            <form method="POST" action="/rotasi/pengajuan/{{ $pengajuan->id }}"
                class="w-full rounded-md bg-white shadow-lg md:w-2/3 p-8 flex flex-col gap-4"
                enctype="multipart/form-data">
                <h1 class="font-semibold text-xl">Ubah Data Personel</h1>
                @csrf
                <section id="section-1" class="sections flex flex-col gap-4 p-2 rounded-md">
                    <div class="grid md:grid-cols-2 gap-4">
                        <aside class="relative w-full">
                            <label class="font-semibold" for="nik">NIK</label><br />
                            <input type="text" name="nik" id="nik"
                                class="w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md"
                                placeholder="Ketik Disini ..." value="{{ old('nik') ? old('nik') : $pengajuan->nik }}"
                                autocomplete="one-time-code" onkeyup="searchNIKDebounce()" />
                            <div class="w-full absolute z-20 hidden flex-col items-start border p-1 gap-1 bg-white max-h-[200px] overflow-y-auto"
                                id="nik-suggestion">
                            </div>
                        </aside>
                        <aside class="w-full">
                            <label class="font-semibold" for="nama_lengkap">Nama Lengkap</label><br />
                            <input type="text" name="nama_lengkap" id="nama_lengkap"
                                class="w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md"
                                placeholder="Ketik Disini ..."
                                value="{{ old('nama_lengkap') ? old('nama_lengkap') : $pengajuan->nama_lengkap }}" />
                        </aside>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <aside class="w-full">
                            <label class="font-semibold" for="masa_kerja">Masa Kerja</label><br />
                            <div class="grid grid-cols-4 mt-1 gap-2 items-center">
                                <input type="number" name="masa_kerja" id="masa_kerja"
                                    class="col-span-3 px-2 py-1 border-2 border-slate-400 rounded-md"
                                    placeholder="Ketik Disini ..."
                                    value="{{ old('masa_kerja') ? old('masa_kerja') : $pengajuan->masa_kerja }}" />
                                <button type="button" id="sk_mutasi_file_button"
                                    class="col-span-1 bg-[#003285]  opacity-80 hover:opacity-100 duration-200 text-white px-2 py-1 rounded-md font-medium"
                                    popovertarget="sk_mutasi_popover">{{ old('sk_mutasi_url') ? '✅️ Ubah' : 'Berkas' }}</button>
                                @include('rotasi.components.file-popover', [
                                    'id' => 'sk_mutasi',
                                    'url' => 'sk_mutasi_url',
                                    'file' => 'sk_mutasi_file',
                                    'file_url' => old('sk_mutasi_url')
                                        ? old('sk_mutasi_url')
                                        : $pengajuan->sk_mutasi_url,
                                ])
                            </div>
                        </aside>
                        <aside class="w-full">
                            <label class="font-semibold" for="jabatan">Jabatan</label><br />
                            <input type="text" name="jabatan" id="jabatan"
                                class="w-full px-2 py-1 mt-1 border-2 border-slate-400 rounded-md"
                                placeholder="Ketik Disini ..."
                                value="{{ old('jabatan') ? old('jabatan') : $pengajuan->jabatan }}" />
                        </aside>
                    </div>
                    <div class="flex justify-end gap-4 w-full">
                        <button type="button"
                            class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                            onclick="changeSection(2)">
                            Selanjutnya
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
                                            {{ old('lokasi_awal_id') == $cabang->id || (!old('lokasi_awal_id') && $pengajuan->lokasi_awal_id == $cabang->id) ? 'selected' : '' }}>
                                            {{ $cabang->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="font-semibold" for="posisi_sekarang">Posisi Awal</label><br />
                                <select name="posisi_sekarang" id="posisi_sekarang"
                                    class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md">
                                    <option value disabled {{ !old('posisi_sekarang') ? 'selected' : '' }}>--- Pilih
                                        Posisi
                                        ---
                                    </option>
                                    @foreach (['ATC (TWR)', 'ATC (APS)', 'ATC (ACS)', 'ACO', 'AIS', 'ATFM', 'TAPOR', 'ATSSystem', 'STAFF'] as $posisi)
                                        <option value="{{ $posisi }}"
                                            {{ old('posisi_sekarang') == $posisi || (!old('posisi_sekarang') && $pengajuan->posisi_sekarang == $posisi) ? 'selected' : '' }}>
                                            {{ $posisi }}</option>
                                    @endforeach
                                </select>
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
                                    @foreach ($cabangs as $cabang)
                                        <option id="lokasi-tujuan-{{ $cabang->id }}" value="{{ $cabang->id }}"
                                            {{ old('lokasi_tujuan_id') == $cabang->id || (!old('lokasi_tujuan_id') && $pengajuan->lokasi_tujuan_id == $cabang->id) ? 'selected' : '' }}>
                                            {{ $cabang->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="font-semibold" for="posisi_tujuan">Posisi Tujuan</label><br />
                                <select name="posisi_tujuan" id="posisi_tujuan"
                                    class="w-full px-2 py-1 mt-1 bg-white border-2 border-slate-400 rounded-md">
                                    <option value disabled {{ !old('posisi_tujuan') ? 'selected' : '' }}>--- Pilih
                                        Posisi
                                        ---
                                    </option>
                                    @foreach (['ATC (TWR)', 'ATC (APS)', 'ATC (ACS)', 'ACO', 'AIS', 'ATFM', 'TAPOR', 'ATSSystem', 'STAFF'] as $posisi)
                                        <option value="{{ $posisi }}"
                                            {{ old('posisi_tujuan') == $posisi || (!old('posisi_tujuan') && $pengajuan->posisi_tujuan == $posisi) ? 'selected' : '' }}>
                                            {{ $posisi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </aside>
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
                                            'file_url' => isset($kompetensi['url']) ? $kompetensi['url'] : null,
                                            'db_id' => isset($kompetensi['id']) ? $kompetensi['id'] : null,
                                        ])
                                    @endforeach
                                @else
                                    @foreach ($pengajuan->kompetensis as $index => $kompetensi)
                                        @include('rotasi.components.kompetensi-item', [
                                            'index' => $index,
                                            'id' => 'kompetensi_' . $index,
                                            'nama' => $kompetensi['nama'],
                                            'file_url' => $kompetensi['file_url'],
                                            'db_id' => $kompetensi['id'],
                                        ])
                                    @endforeach
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
                                placeholder="Ketik Disini ...">{{ old('tujuan_rotasi') ? old('tujuan_rotasi') : $pengajuan->tujuan_rotasi }}</textarea>
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
                            'file_url' => old('surat_persetujuan_url')
                                ? old('surat_persetujuan_url')
                                : $pengajuan->surat_persetujuan_url,
                            'note' => '*surat GM, surat pernyataan, berkas data pribadi (CV)'
                        ])
                    </div>
                    <div>
                        <label class="font-semibold" for="keterangan">Keterangan Tambahan</label><br />
                        <textarea name="keterangan" id="keterangan" class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                            rows="3" placeholder="Ketik Disini ...">{{ old('keterangan') ? old('keterangan') : $pengajuan->keterangan }}</textarea>
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
    {{-- same as rotasi/pengajuan/input.blade --}}
    <script>
        function changeSection(section) {
            document.querySelectorAll('.sections').forEach(s => s.classList.add('hidden'));
            document.querySelector(`#section-${section}`).classList.remove('hidden');
            document.querySelector(`#section-${section}`).classList.add('flex');
        }
    </script>
    <script>
        function kompetensiBindDeleteBtn(id) {
            document.querySelector(`#${id} > button`).addEventListener('click', function() {
                this.parentElement.remove();
            });
        }
    </script>
    <script>
        var kompetensiCount = 1;
        initFilePopover('sk_mutasi');
        initFilePopover('surat_persetujuan');
        document.querySelectorAll(".kompetensi-item").forEach(kompetensi => {
            kompetensiBindDeleteBtn(kompetensi.id);
            initFilePopover(kompetensi.id);
        });
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

        const body = document.querySelector('body');
        body.addEventListener('click', function(e) {
            if (!e.target.closest('#nik-suggestion') && !e.target.closest('#nik')) {
                document.getElementById('nik-suggestion').classList.add('hidden');
                document.getElementById('nik-suggestion').classList.remove('flex');
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
    </script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
