<!DOCTYPE html>
<html lang="en">

<head>
    @include('components/head')
    <title>Mutant | Rotasi</title>
</head>

<body class="font-sans tracking-wider">
    @include('components.header', ['static' => true])
    @include('components.modal-component')
    <main class="min-h-[90vh] flex justify-center items-center p-4">
        <form action="/rotasi/cabang/input/{{ $cabang->id }}" method="post"
            class="w-full rounded-md bg-white shadow-lg md:w-2/3 p-8 flex flex-col gap-4" enctype="multipart/form-data">
            <h1 class="font-semibold text-2xl text-center">Ubah Data Cabang</h1>
            @csrf
            <section id="section-1" class="sections flex flex-col gap-1 p-2 rounded-md">
                <label for="nama" class="font-semibold">Nama Cabang</label>
                <input type="text" name="nama" id="nama" placeholder="Nama Cabang ..."
                    class="w-full p-2 border-2 border-slate-400 rounded-md"
                    value="{{ old('nama') === null ? $cabang->nama : old('nama') }}">
                <label for="alamat" class="font-semibold">Alamat Cabang</label>
                <textarea name="alamat" id="alamat" cols="30" rows="5" placeholder="Alamat ..."
                    class="resize-none w-full p-2 border-2 border-slate-400 rounded-md">{{ old('alamat') === null ? $cabang->alamat : old('alamat') }}</textarea>
                <label for="thumbnail_file_button" class="font-semibold">Foto Tower</label>
                <button id="thumbnail_file_button"
                    class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-2 py-1 rounded-md font-medium"
                    type="button" popovertarget="thumbnail_popover">Pilih file</button>
                <img id="thumbnail-preview" src="" alt="default" class="hidden">
                @include('rotasi.components.file-popover', [
                    'id' => 'thumbnail',
                    'url' => 'thumbnail_url',
                    'file' => 'thumbnail_file',
                    'file_url' => old('thumbnail_url') === null ? $cabang->thumbnail_url : old('thumbnail_url'),
                    'isImage' => true,
                ])
                <div class="mt-3 flex justify-end gap-4 w-full">
                    <button type="button"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(2)">
                        Selanjutnya
                    </button>
                </div>
            </section>
            <section id="section-2" class="sections hidden flex-col gap-1 p-2 rounded-md">
                <label class="font-semibold">Kelas</label>
                <div id="kelas" class="w-full my-1">
                    @if (old('kelas'))
                        @foreach (old('kelas') as $index => $kelas)
                            @include('rotasi.components.kelas-item', [
                                'index' => $index,
                                'db_id' => $kelas,
                            ])
                        @endforeach
                    @else
                        @foreach ($cabang->kelases as $index => $kelas)
                            @include('rotasi.components.kelas-item', [
                                'index' => $index,
                                'db_id' => $kelas['kelas_id'],
                            ])
                        @endforeach
                    @endif
                </div>
                <button
                    class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white py-2 rounded-lg font-semibold w-full mt-1"
                    type="button" id="kelas_tambah">Tambah +</button>
                <div class="mt-3 flex justify-end gap-4 w-full">
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
            <section id="section-3" class="sections hidden flex-col gap-1 p-2 rounded-md">
                <label for="jumlah_personel" class="font-semibold">Jumlah Eksisting ATC</label>
                <input type="number" name="jumlah_personel" id="jumlah_personel" placeholder="Jumlah Eksisting ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('jumlah_personel') === null ? $cabang->jumlah_personel : old('jumlah_personel') }}">
                <label for="formasi" class="font-semibold">Jumlah Optimal ATC</label>
                <input type="number" name="formasi" id="formasi" placeholder="Jumlah Optimal ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('formasi') === null ? $cabang->formasi : old('formasi') }}">
                <label for="frms" class="font-semibold">FRMS ATC</label>
                <input type="number" name="frms" id="frms" placeholder="FRMS ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('frms') === null ? $cabang->frms : old('frms') }}">
                <div class="mt-3 flex justify-end gap-4 w-full">
                    <button type="button"
                        class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(2)">
                        Kembali
                    </button>
                    <button type="button"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(4)">
                        Selanjutnya
                    </button>
                </div>
            </section>
            <section id="section-4" class="sections hidden flex-col gap-1 p-2 rounded-md">
                <label for="jumlah_personel_aco" class="font-semibold">Jumlah Eksisting ACO</label>
                <input type="number" name="jumlah_personel_aco" id="jumlah_personel_aco"
                    placeholder="Jumlah Eksisting ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('jumlah_personel_aco') === null ? $cabang->jumlah_personel_aco : old('jumlah_personel_aco') }}">
                <label for="formasi_aco" class="font-semibold">Jumlah Optimal ACO</label>
                <input type="number" name="formasi_aco" id="formasi_aco" placeholder="Jumlah Optimal ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('formasi_aco') === null ? $cabang->formasi_aco : old('formasi_aco') }}">
                <div class="mt-3 flex justify-end gap-4 w-full">
                    <button type="button"
                        class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(3)">
                        Kembali
                    </button>
                    <button type="button"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(5)">
                        Selanjutnya
                    </button>
                </div>
            </section>
            <section id="section-5" class="sections hidden flex-col gap-1 p-2 rounded-md">
                <label for="jumlah_personel_ais" class="font-semibold">Jumlah Eksisting AIS</label>
                <input type="number" name="jumlah_personel_ais" id="jumlah_personel_ais"
                    placeholder="Jumlah Eksisting ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('jumlah_personel_ais') === null ? $cabang->jumlah_personel_ais : old('jumlah_personel_ais') }}">
                <label for="formasi_ais" class="font-semibold">Jumlah Optimal AIS</label>
                <input type="number" name="formasi_ais" id="formasi_ais" placeholder="Jumlah Optimal ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('formasi_ais') === null ? $cabang->formasi_ais : old('formasi_ais') }}">
                <div class="mt-3 flex justify-end gap-4 w-full">
                    <button type="button"
                        class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(4)">
                        Kembali
                    </button>
                    <button type="button"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(6)">
                        Selanjutnya
                    </button>
                </div>
            </section>
            <section id="section-6" class="sections hidden flex-col gap-1 p-2 rounded-md">
                <label for="jumlah_personel_atfm" class="font-semibold">Jumlah Eksisting ATFM</label>
                <input type="number" name="jumlah_personel_atfm" id="jumlah_personel_atfm"
                    placeholder="Jumlah Eksisting ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('jumlah_personel_atfm') === null ? $cabang->jumlah_personel_atfm : old('jumlah_personel_atfm') }}">
                <label for="formasi_atfm" class="font-semibold">Jumlah Optimal ATFM</label>
                <input type="number" name="formasi_atfm" id="formasi_atfm" placeholder="Jumlah Optimal ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('formasi_atfm') === null ? $cabang->formasi_atfm : old('formasi_atfm') }}">
                <div class="mt-3 flex justify-end gap-4 w-full">
                    <button type="button"
                        class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(5)">
                        Kembali
                    </button>
                    <button type="button"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(7)">
                        Selanjutnya
                    </button>
                </div>
            </section>
            <section id="section-7" class="sections hidden flex-col gap-1 p-2 rounded-md">
                <label for="jumlah_personel_tapor" class="font-semibold">Jumlah Eksisting TAPOR</label>
                <input type="number" name="jumlah_personel_tapor" id="jumlah_personel_tapor"
                    placeholder="Jumlah Eksisting ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('jumlah_personel_tapor') === null ? $cabang->jumlah_personel_tapor : old('jumlah_personel_tapor') }}">
                <label for="formasi_tapor" class="font-semibold">Jumlah Optimal TAPOR</label>
                <input type="number" name="formasi_tapor" id="formasi_tapor" placeholder="Jumlah Optimal ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('formasi_tapor') === null ? $cabang->formasi_tapor : old('formasi_tapor') }}">
                <div class="mt-3 flex justify-end gap-4 w-full">
                    <button type="button"
                        class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(6)">
                        Kembali
                    </button>
                    <button type="button"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(8)">
                        Selanjutnya
                    </button>
                </div>
            </section>
            <section id="section-8" class="sections hidden flex-col gap-1 p-2 rounded-md">
                <label for="jumlah_personel_ats_system" class="font-semibold">Jumlah Eksisting ATS System</label>
                <input type="number" name="jumlah_personel_ats_system" id="jumlah_personel_ats_system"
                    placeholder="Jumlah Eksisting ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('jumlah_personel_ats_system') === null ? $cabang->jumlah_personel_ats_system : old('jumlah_personel_ats_system') }}">
                <label for="formasi_ats_system" class="font-semibold">Jumlah Optimal ATS System</label>
                <input type="number" name="formasi_ats_system" id="formasi_ats_system"
                    placeholder="Jumlah Optimal ..."
                    class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                    value="{{ old('formasi_ats_system') === null ? $cabang->formasi_ats_system : old('formasi_ats_system') }}">
                <div class="mt-3 flex justify-end gap-4 w-full">
                    <button type="button"
                        class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(7)">
                        Kembali
                    </button>
                    <button type="button"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(9)">
                        Selanjutnya
                    </button>
                </div>
            </section>
            <section id="section-9" class="sections hidden flex-col items-center gap-1 p-2 rounded-md">
                <label class="select-none">
                    <input type="checkbox" name="induk" id="induk"
                        {{ old('induk') !== null || $cabang->coord !== null ? 'checked' : '' }}>
                    Cabang Induk ?
                </label>
                <div id="map" class="h-0 z-10 w-full">
                    <div class="w-full h-full flex items-center justify-center" id="loading">
                        <img src="/images/icons/ripples.svg" alt="loading"
                            class="w-20 h-20 z-40 p-2 bg-white rounded-full">
                    </div>
                </div>
                <div class="hidden w-full" id="latlng">
                    <label for="latitude" class="font-semibold">Coordinate</label>
                    <div class="flex gap-2">
                        <input type="text" name="latitude" id="latitude"
                            value="{{ old('latitude') === null ? ($cabang->coord !== null ? $cabang->coord->latitude : '') : old('latitude') }}"
                            class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                            placeholder="Latitude" step=".00000001">
                        <input type="text" name="longitude" id="longitude"
                            value="{{ old('longitude') === null ? ($cabang->coord !== null ? $cabang->coord->longitude : '') : old('longitude') }}"class="resize-none w-full p-2 mt-1 border-2 border-slate-400 rounded-md"
                            placeholder="Longitude" step=".00000001">
                    </div>
                </div>
                <div class="mt-3 flex justify-end gap-4 w-full">
                    <button type="button"
                        class="bg-white border-2 border-[#003285] opacity-80 hover:opacity-100 duration-200 text-gray-950 px-4 py-2 rounded-lg font-semibold"
                        onclick="changeSection(8)">
                        Kembali
                    </button>
                    <button type="submit"
                        class="bg-[#003285] opacity-80 hover:opacity-100 duration-200 text-white px-4 py-2 rounded-lg font-semibold">
                        Simpan
                    </button>
                </div>
            </section>
        </form>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/map.js"></script>
    <script src="/script/filePopover.js"></script>
    {{-- same as rotasi/cabang/input.blade --}}
    <script>
        function changeSection(section) {
            document.querySelectorAll('.sections').forEach(s => s.classList.add('hidden'));
            document.querySelector(`#section-${section}`).classList.remove('hidden');
            document.querySelector(`#section-${section}`).classList.add('flex');

            if (document.querySelector("#section-9").classList.contains("flex")) {
                setInduk();
                setMarkerFromCoord();
            }
        }
    </script>
    <script>
        initFilePopover('thumbnail');
        // (re)bind delete button event for item by id
        function bindDeleteBtn(id) {
            document.querySelector(`#${id} > button`).addEventListener('click', function() {
                this.parentElement.remove();
            });
        }
    </script>
    <script>
        // counter for kompetensi id
        var kelasCount = 1;

        // bind delete button event for initial kompetensi item
        document.querySelectorAll(".kelas-item").forEach(kelas => {
            bindDeleteBtn(kelas.id);
            kelasCount++;
        });

        // add kelas item
        document.querySelector("#kelas_tambah").addEventListener('click', function() {
            const kelasContainer = document.querySelector('#kelas');
            kelasContainer.insertAdjacentHTML("beforeend", `@include('rotasi.components.kelas-item', [
                'index' => '${kelasCount + 1}',
            ])`);
            document.querySelectorAll(".kelas-item").forEach(kelas => {
                bindDeleteBtn(kelas.id);
            });
            kelasCount++;
        });
    </script>
    <script>
        var marker;
        const setInduk = () => {
            if (document.getElementById("induk").checked) {
                document.getElementById("map").classList.remove("h-0");
                document.getElementById("map").classList.add("h-[50vh]");
                document.getElementById("latlng").classList.remove("hidden");
                map = InitMap();
                map.on('click', function(e) {
                    var lat = e.latlng.lat;
                    var lng = e.latlng.lng;
                    document.getElementById("latitude").value = lat;
                    document.getElementById("longitude").value = lng;
                    if (marker) {
                        marker.remove();
                    }
                    marker = new L.marker([lat, lng], {
                        icon: L.icon({
                            iconUrl: '/images/icons/marker.svg',
                            iconSize: [30, 30],
                            iconAnchor: [15, 30],
                            popupAnchor: [0, -30],
                        }),
                    }).addTo(map);
                });
            } else {
                document.getElementById("map").classList.remove("h-[50vh]");
                document.getElementById("map").classList.add("h-0");
                document.getElementById("latlng").classList.add("hidden");
            }
        }
        const setMarkerFromCoord = () => {
            var lat = document.getElementById("latitude").value;
            var lng = document.getElementById("longitude").value;
            if (lat && lng) {
                if (marker) marker.remove();
                marker = new L.marker([lat, lng], {
                    icon: L.icon({
                        iconUrl: '/images/icons/marker.svg',
                        iconSize: [30, 30],
                        iconAnchor: [15, 30],
                        popupAnchor: [0, -30],
                    }),
                }).addTo(map);
            }
        }
        document.getElementById("induk").addEventListener("change", setInduk);
        document.getElementById("latitude").addEventListener("input", setMarkerFromCoord);
        document.getElementById("longitude").addEventListener("input", setMarkerFromCoord);
    </script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
