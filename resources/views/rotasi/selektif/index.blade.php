<!DOCTYPE html>
<html lang="en">

<head>
    @include('components/head')
    <title>Mutant | Rotasi</title>
</head>

<body class="font-sans tracking-wider">
    @include('components.header', ['static' => true])
    @include('components.modal-component')
    @if (count($pengajuans) > 0)
        <div id="detail-pengajuan" class="border-2 rounded-md p-4 w-5/6 sm:w-2/3 md:w-1/2 max-h-[90vh]" popover>
            <div class="bg-white flex flex-col items-center">
                <h1 class="font-bold text-xl">Detail</h1>
                <p class="my-1 text-center">
                    <span>{{ $pengajuan->lokasiAwal->nama }}</span>
                    ->
                    <span>{{ $pengajuan->lokasiTujuan->nama }}</span>
                </p>
                <div class="grid grid-cols-2 gap-5 w-full">
                    <aside class="flex flex-col items-end">
                        <p class="font-semibold mt-2 text-right">Nama</p>
                        <p class="text-right">{{ $pengajuan->nama_lengkap }}</p>
                        <p class="font-semibold mt-2">NIK</p>
                        <p class="text-right">{{ $pengajuan->nik }}</p>
                    </aside>
                    <aside>
                        <p class="font-semibold mt-2">Masa Kerja</p>
                        <p>{{ $pengajuan->masa_kerja }} Tahun</p>
                        <p class="font-semibold mt-2">Jabatan</p>
                        <p>{{ $pengajuan->jabatan }}</p>
                    </aside>
                </div>
                <p class="font-semibold mt-2">Rotasi</p>
                <p>
                    <span>{{ $pengajuan->posisi_sekarang }}</span>
                    ->
                    <span>{{ $pengajuan->posisi_tujuan }}</span>
                </p>
                <p class="font-semibold mt-2">Kompetensi</p>
                <div class="flex gap-2">
                    @foreach ($pengajuan->kompetensis as $kompetensi)
                        @if ($kompetensi->file_url && $kompetensi->file_url !== 'null')
                            <a class="bg-[#003285] duration-200 text-white px-2 py-1 font-semibold rounded-lg"
                                href="{{ $kompetensi->file_url }}">{{ $kompetensi->nama }}</a>
                        @else
                            <p class="text-white bg-gray-400 px-2 py-1 font-semibold rounded-lg">
                                {{ $kompetensi->nama }}
                            </p>
                        @endif
                    @endforeach
                </div>
                <p class="font-semibold mt-2">Berkas Lain</p>
                <div class="flex gap-2">
                    @if ($pengajuan->sk_mutasi_url)
                        <a class="flex-grow text-center bg-[#003285] duration-200 text-white px-2 py-1 mt-1 font-semibold rounded-lg"
                            href="{{ $pengajuan->file_url }}">SK Mutasi</a>
                    @endif
                    @if ($pengajuan->surat_persetujuan_url)
                        <a class="flex-grow text-center bg-[#003285] duration-200 text-white px-2 py-1 mt-1 font-semibold rounded-lg"
                            href="{{ $pengajuan->surat_persetujuan_url }}">Surat Persetujuan</a>
                    @endif
                </div>
                <p class="font-semibold mt-2">Tujuan</p>
                <p class="text-justify">{{ $pengajuan->tujuan_rotasi }}</p>
                <p class="font-semibold mt-2">Keterangan</p>
                <p class="text-justify">{{ $pengajuan->keterangan }}</p>
            </div>
        </div>
    @endif
    <div id="filter" class="border-2 rounded-lg p-4 w-1/2 max-h-[90vh]" popover>
        <form method="get" class="flex flex-col gap-1">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <input type="text" class="px-2 py-1 border-2 rounded-md" name="search_nama" id="search_nama"
                placeholder="Nama" value="{{ $request->search_nama }}">
            <input type="text" class="px-2 py-1 border-2 rounded-md" name="nik" id="nik" placeholder="NIK"
                value="{{ $request->nik }}">
            <select class="px-2 py-1 border-2 rounded-md" name="lokasi_awal" id="lokasi_awal">
                <option value>--- Lokasi awal ---</option>
                @foreach ($cabangs as $cabang)
                    <option value="{{ $cabang->nama }}"
                        {{ $request->lokasi_awal === $cabang->nama ? 'selected' : '' }}>{{ $cabang->nama }}</option>
                @endforeach
            </select>
            <select class="px-2 py-1 border-2 rounded-md" name="lokasi_tujuan" id="lokasi_tujuan">
                <option value>--- Lokasi tujuan ---</option>
                @foreach ($cabangs as $cabang)
                    <option value="{{ $cabang->nama }}"
                        {{ $request->lokasi_tujuan === $cabang->nama ? 'selected' : '' }}>{{ $cabang->nama }}</option>
                @endforeach
            </select>

            <button id="filter-reset" type="button" class="underline self-end">Reset</button>

            <button class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-2 py-1 font-semibold rounded-lg"
                type="submit">Filter</button>
        </form>
    </div>
    <main>
        <section class="flex flex-col-reverse md:grid md:grid-cols-2 md:items-start m-4 gap-4 md:min-h-screen">
            <aside class="flex flex-col gap-2 pe-2 pb-2 h-[50vh] md:h-full md:max-h-screen overflow-y-auto">
                <div class="sticky top-0 w-full bg-white">
                    <div class="text-black flex gap-2 py-2 justify-evenly items-center">
                        <a class="{{ !$tab || $tab === 'diajukan' ? 'font-semibold underline' : '' }}"
                            href="/rotasi/selektif?tab=diajukan{{ $query }}">Pengajuan</a>
                        <a class="{{ $tab === 'dapat' ? 'font-semibold underline' : '' }}"
                            href="/rotasi/selektif?tab=dapat{{ $query }}">Diterima</a>
                        <a class="{{ $tab === 'tidak_dapat' ? 'font-semibold underline' : '' }}"
                            href="/rotasi/selektif?tab=tidak_dapat{{ $query }}">Tidak Diterima</a>
                        <button popovertarget="filter" class="relative">
                            @if ($query != '')
                                <span class="absolute -right-1 -top-0 rounded-full w-2 h-2 bg-red-500"></span>
                            @endif
                            <img src="/images/icons/filter.svg" alt="filter">
                        </button>
                    </div>
                    <hr class="w-full border-2 border-[#003285]" />
                </div>
                @if (count($pengajuans) === 0)
                    <div class="h-full flex items-center justify-center">
                        <h1 class="font-bold text-2xl text-center">Tidak Ada Pengajuan</h1>
                    </div>
                @else
                    @foreach ($pengajuans as $currPengajuan)
                        <a href="/rotasi/selektif?id={{ $currPengajuan->id }}&tab={{ $tab }}{{ $query }}"
                            class="pengajuan-item {{ $currPengajuan->id === $pengajuan->id ? 'pengajuan-active bg-[#003285] text-white' : 'bg-white text-black border border-[#003285]' }} px-8 py-3 rounded-lg grid grid-cols-1 sm:grid-cols-2 items-center justify-between gap-2">
                            <aside class="flex flex-col items-center sm:items-start">
                                <h3 class="font-semibold text-lg">{{ $currPengajuan->nama_lengkap }}</h3>
                                <p>{{ $currPengajuan->nik }}</p>
                            </aside>
                            <aside class="flex justify-center items-center gap-4 flex-wrap flex-row md:flex-col">
                                <h4 class="font-semibold text-center">{{ $currPengajuan->lokasiAwal->nama }}</h4>
                                <img src="/images/icons/{{ $currPengajuan->id === $pengajuan->id ? 'switch_white' : 'switch_blue' }}.svg"
                                    alt="switch" />
                                <h4 class="font-semibold text-center">{{ $currPengajuan->lokasiTujuan->nama }}</h4>
                            </aside>
                        </a>
                    @endforeach
                @endif
            </aside>
            @if (count($pengajuans) > 0)
                <form method="POST" action="/rotasi/selektif/{{ $pengajuan->id }}" class="flex flex-col gap-2">
                    <div class="flex flex-col flex-grow gap-4 bg-white rounded-lg p-4 overflow-y-auto">
                        @csrf
                        <div class="flex flex-col gap-2 sm:flex-row items-center justify-between">
                            <aside class="flex flex-col items-center sm:items-start font-semibold">
                                <p>Tanggal Pengajuan</p>
                                <p id="tanggal-pengajuan">{{ $pengajuan->tanggal_pengajuan }}</p>
                            </aside>
                            <aside class="flex gap-2">
                                <a href="/rotasi/pengajuan/{{ $pengajuan->id }}"
                                    class="flex-grow bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-800 flex items-center px-6 py-2 font-semibold rounded-lg text-center">Ubah</a>
                                <button type="button"
                                    class="flex-grow bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-6 py-2 font-semibold rounded-lg"
                                    popovertarget="detail-pengajuan">Detail</button>
                            </aside>
                        </div>
                        <a href="/download/pengajuan/{{ $pengajuan->id }}" target="_blank"
                            class="flex-grow text-center bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-6 py-2 font-semibold rounded-lg">Unduh
                            Dokumen</a>
                        <hr class="w-full border-2 border-[#003285]" />
                        <div>
                            <label class="font-semibold" for="nama">Nama</label>
                            <input class="w-full border-2 border-[#B6B6B6] px-2 py-1 mt-1 bg-[#C6C6C6]" type="text"
                                id="nama" name="nama" value="{{ $pengajuan->nama_lengkap }}" disabled />
                        </div>
                        <div>
                            <label class="font-semibold" for="nik">NIK</label>
                            <input class="w-full border-2 border-[#B6B6B6] px-2 py-1 mt-1 bg-[#C6C6C6]" type="text"
                                id="nik" name="nik" value="{{ $pengajuan->nik }}" disabled />
                        </div>
                        <div>
                            @if ($tab === 'dapat')
                                <h2 class="font-semibold">Status Pemindahan</h2>
                                <input type="checkbox" name="status" id="diterima" value="diterima">
                                <label for="diterima">Disetujui</label><br />
                            @elseif ($tab === 'tidak_dapat')
                                <h2 class="font-semibold">Keterangan Penolakan</h2>
                                <p>{{ $pengajuan->keteranganPenolakan ? $pengajuan->keteranganPenolakan->catatan : '-' }}
                                </p>
                                <h2 class="font-semibold">Rekomendasi</h2>
                                <p>{{ $pengajuan->rekomendasi ? $pengajuan->rekomendasi->catatan : '-' }}</p>
                            @else
                                <h2 class="font-semibold">Status Pemindahan</h2>
                                <input type="radio" name="status" id="dapat" value="dapat"
                                    {{ old('status') == 'dapat' ? 'checked' : '' }} />
                                <label for="dapat">Diterima</label><br />
                                <input type="radio" name="status" id="tidak_dapat" value="tidak"
                                    {{ old('status') == 'tidak' ? 'checked' : '' }} />
                                <label for="tidak_dapat">Tidak Diterima</label>
                            @endif
                        </div>
                        <div id="keterangan-field" class="hidden">
                            <label class="font-semibold" for="keterangan">Keterangan Penolakan</label>
                            <textarea name="keterangan" id="keterangan" placeholder="Ketik Disini ..."
                                class="w-full border-2 border-[#B6B6B6] px-2 py-1 mt-1 resize-none" rows="5"></textarea>
                        </div>
                        <div id="rekomendasi-field" class="hidden">
                            <label class="font-semibold" for="rekomendasi">Rekomendasi</label>
                            <textarea name="rekomendasi" id="rekomendasi" placeholder="Ketik Disini ..."
                                class="w-full border-2 border-[#B6B6B6] px-2 py-1 mt-1 resize-none" rows="5"></textarea>
                        </div>
                        <button
                            class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-6 py-2 font-semibold rounded-lg"
                            type="submit">
                            Submit
                        </button>
                    </div>
                </form>
            @else
                <div class="h-full bg-white flex items-center justify-center border-2 border-[#003285] rounded-md">
                    <h1 class="font-bold text-2xl text-center">Tidak Ada Pengajuan</h1>
                </div>
            @endif
        </section>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script>
        // reset all filter value to empty
        document.getElementById("filter-reset").addEventListener("click", () => {
            document.getElementById("search_nama").value = "";
            document.getElementById("nik").value = "";
            document.getElementById("lokasi_awal").value = "";
            document.getElementById("lokasi_tujuan").value = "";
        });
    </script>
    <script>
        // show or hide keterangan and rekomendasi field by the status choosed
        document.querySelectorAll('input[name="status"]').forEach((radio) => {
            radio.addEventListener("change", () => {
                if (radio.value === "dapat") {
                    document.getElementById("keterangan-field").classList.add("hidden");
                    document.getElementById("rekomendasi-field").classList.add("hidden");
                } else if (radio.value === "tidak") {
                    document.getElementById("keterangan-field").classList.remove("hidden");
                    document.getElementById("rekomendasi-field").classList.remove("hidden");
                }
            });
        });

        // hide keterangan and rekomendasi at initial
        if (document.querySelector('input[name="status"]:checked') && document.querySelector('input[name="status"]:checked')
            .value === "tidak") {
            document.getElementById("keterangan-field").classList.remove("hidden");
            document.getElementById("rekomendasi-field").classList.remove("hidden");
        }
    </script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
