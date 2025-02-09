<!DOCTYPE html>
<html lang="en">

<head>
    @include('components/head')
    <title>Mutant | Rotasi</title>
</head>

<body class="tracking-wider">
    @include('components.header', ['static' => true])
    @include('components.modal-component')
    <main>
        {{-- Map start --}}
        <section class="px-8 flex flex-col items-center">
            <div class="w-full h-[50vh] md:h-[75vh] border-4 rounded-md flex">
                <div id="map" class="w-full h-full z-40">
                    <div class="w-full h-full flex items-center justify-center" id="loading">
                        <img src="/images/icons/ripples.svg" alt="loading"
                            class="w-20 h-20 z-40 p-2 bg-white rounded-full">
                    </div>
                </div>
            </div>
        </section>
        {{-- Map end --}}
        <div class="flex justify-center p-4 text-sm font-semibold">
            <div id="tab-cabang" class="flex gap-2 border-2 border-[#003285] rounded-md p-2">
                <button id="list" class="rounded-md bg-[#003285] text-white px-2 py-1">List Cabang</button>
                <button id="summary" class="rounded-md px-2 py-1">Summary Cabang</button>
            </div>
        </div>
        <section id="cabang-list" class="p-4 w-full flex flex-col-reverse sm:grid sm:grid-cols-2 gap-2">
            <aside class="col-span-2 sm:col-span-1 bg-[#003285] rounded-md pt-2 flex flex-col gap-2 max-h-[70vh]">
                <h1 class="font-bold text-lg text-white mx-2 text-center">Daftar Kantor Cabang</h1>
                <div class="sticky top-0 bg-[#ced0ff] rounded-md mx-2">
                    <div>
                        <input id="search" class="w-full rounded-md px-2 py-1" type="search"
                            placeholder="Search ..." />
                    </div>
                </div>
                <div class="flex-grow overflow-y-auto">
                    <div id="anak-cabang" class="flex flex-col">
                        @if (count($cabangs) === 0)
                            <p class="text-center my-auto">Tidak ada data</p>
                        @endif
                        @foreach ($cabangs as $cabang)
                            @include('rotasi.components.cabang-item', [
                                'cabang' => $cabang,
                                'index' => $loop->index,
                            ])
                        @endforeach
                    </div>
                </div>
            </aside>
            <aside class="col-span-2 sm:col-span-1 flex flex-col gap-2 sm:max-h-[70vh]">
                @can('admin')
                    <div class="grid grid-cols-4 gap-2">
                        <a href="/rotasi/cabang/input"
                            class="col-span-4 lg:col-span-3 bg-[#003285] text-white opacity-80 hover:opacity-100 duration-300 w-full text-center text-lg p-2 rounded-lg font-semibold">Tambah
                            Cabang</a>
                        <a href="/kelas"
                            class="col-span-4 lg:col-span-1 bg-[#003285] text-white opacity-80 hover:opacity-100 duration-300 w-full text-center text-lg p-2 rounded-lg font-semibold">Atur Kelas</a>
                    </div>
                @endcan
                <div class="py-2 flex-grow flex flex-col gap-2 rounded-lg overflow-y-auto w-full">
                    <div
                        class="flex items-center justify-center bg-white md:h-80 lg:h-[22rem] xl:h-96 rounded-lg border-8 border-[#003285]">
                        <img id="thumbnail-placeholder" src="/images/icons/Full Image.svg" alt="image" />
                        <div id="thumbnail-container" class="w-full h-full relative flex hidden items-center justify-center">
                            <img id="thumbnail-bg" class="hidden w-full h-full object-cover blur-md" />
                            <img id="thumbnail" class="absolute top-0 hidden h-full object-cover" />
                        </div>
                    </div>
                    <h1 id="nama" class="px-2 font-semibold text-3xl hidden"></h1>
                    <p id="alamat" class="p-2 text-center">
                        Pilih Cabang
                    </p>
                    <a id="detail"
                        class="self-end bg-[#003285] text-white opacity-80 hover:opacity-100 duration-300 px-4 py-2 rounded-lg text-center font-semibold hidden">
                        Lihat Detail
                    </a>
                </div>
            </aside>
        </section>
        <section id="cabang-summary" class="hidden p-4">
            <div id="anak-cabang" class="flex flex-col gap-4">
                @if (count($cabangs) === 0)
                    <p class="text-center my-auto">Tidak ada data</p>
                @endif
                @foreach ($cabangs as $cabang)
                    <a class="border-2 border-[#003285] rounded-md px-4 py-2 flex flex-col sm:flex-row items-center justify-between"
                        @can('admin') href="/rotasi/selektif?lokasi_awal={{ $cabang->nama }}" @endcan>
                        <span class="sm:max-w-[40%] font-semibold">
                            {{ $cabang->nama }}
                        </span>
                        <span class="flex-grow flex justify-center sm:justify-end gap-2">
                            <span class="flex flex-col items-center">
                                <span class="font-medium">Pengajuan</span>
                                <span class="flex gap-2">
                                    <span class="flex items-center">
                                        <img class="w-5" src="/images/icons/in.svg" alt="in">
                                        {{ count($cabang->inAll) }}
                                    </span>
                                    <span class="flex items-center">
                                        <img class="w-5" src="/images/icons/out.svg" alt="out">
                                        {{ count($cabang->outAll) }}
                                    </span>
                                </span>
                            </span>
                            <span class="flex flex-col items-center">
                                <span class="font-medium">Diterima</span>
                                <span class="flex gap-2">
                                    <span class="flex items-center">
                                        <img class="w-5" src="/images/icons/in.svg" alt="in">
                                        {{ count($cabang->inDapat) }}
                                    </span>
                                    <span class="flex items-center">
                                        <img class="w-5" src="/images/icons/out.svg" alt="out">
                                        {{ count($cabang->outDapat) }}
                                    </span>
                                </span>
                            </span>
                            <span class="flex flex-col items-center">
                                <span class="font-medium">Disetujui</span>
                                <span class="flex gap-2">
                                    <span class="flex items-center">
                                        <img class="w-5" src="/images/icons/in.svg" alt="in">
                                        {{ count($cabang->inDiterima) }}
                                    </span>
                                    <span class="flex items-center">
                                        <img class="w-5" src="/images/icons/out.svg" alt="out">
                                        {{ count($cabang->outDiterima) }}
                                    </span>
                                </span>
                            </span>
                            <span class="flex flex-col items-center">
                                <span class="font-medium">Ditolak</span>
                                <span class="flex gap-2">
                                    <span class="flex items-center">
                                        <img class="w-5" src="/images/icons/in.svg" alt="in">
                                        {{ count($cabang->inTidakDapat) }}
                                    </span>
                                    <span class="flex items-center">
                                        <img class="w-5" src="/images/icons/out.svg" alt="out">
                                        {{ count($cabang->outTidakDapat) }}
                                    </span>
                                </span>
                            </span>
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/map.js"></script>
    <script src="/script/debounce.js"></script>
    <script>
        function openListCabang() {
            document.getElementById('cabang-list').classList.remove('hidden');
            document.getElementById('cabang-list').classList.add('flex');
            document.getElementById('cabang-list').classList.add('sm:grid');
            document.getElementById('cabang-summary').classList.add('hidden');
            document.querySelector('#tab-cabang #list').classList.add('bg-[#003285]');
            document.querySelector('#tab-cabang #list').classList.add('text-white');
            document.querySelector('#tab-cabang #summary').classList.remove('bg-[#003285]');
            document.querySelector('#tab-cabang #summary').classList.remove('text-white');
        }

        function openSummaryCabang() {
            document.getElementById('cabang-list').classList.add('hidden');
            document.getElementById('cabang-list').classList.remove('flex');
            document.getElementById('cabang-list').classList.remove('sm:grid');
            document.getElementById('cabang-summary').classList.remove('hidden');
            document.querySelector('#tab-cabang #list').classList.remove('bg-[#003285]');
            document.querySelector('#tab-cabang #list').classList.remove('text-white');
            document.querySelector('#tab-cabang #summary').classList.add('bg-[#003285]');
            document.querySelector('#tab-cabang #summary').classList.add('text-white');
        }
        document.querySelector('#tab-cabang #list').addEventListener('click', openListCabang);
        document.querySelector('#tab-cabang #summary').addEventListener('click', openSummaryCabang);
    </script>
    <script>
        /*var currentPage = 0;
                const mapContainer = document.getElementById('map');
                const bannerContainer = document.querySelector('#banner-img');
                const mapShowBtn = document.getElementById('map-show-btn');
                const bannerShowBtn = document.getElementById('banner-show-btn');
                mapShowBtn.addEventListener('click', () => {
                    if (currentPage !== 0) {
                        mapContainer.classList.remove('w-0');
                        mapContainer.classList.add('w-full');
                        bannerContainer.classList.remove('w-full');
                        bannerContainer.classList.add('w-0');
                        currentPage = 0;
                        mapShowBtn.classList.add('bg-[#003285]');
                        bannerShowBtn.classList.remove('bg-[#003285]');
                    }
                });
                bannerShowBtn.addEventListener('click', () => {
                    if (currentPage !== 1) {
                        mapContainer.classList.add('w-0');
                        mapContainer.classList.remove('w-full');
                        bannerContainer.classList.add('w-full');
                        bannerContainer.classList.remove('w-0');
                        currentPage = 1;
                        mapShowBtn.classList.remove('bg-[#003285]');
                        bannerShowBtn.classList.add('bg-[#003285]');
                    }
                });*/
    </script>
    <script>
        // set data to aside cabang's summary
        function setCabang(cabang) {
            document.getElementById('thumbnail-bg').src = cabang.thumbnail_url && cabang.thumbnail_url !== 'NULL' ? cabang
                .thumbnail_url :
                "/images/default_tower.jpg";
            document.getElementById('thumbnail').src = cabang.thumbnail_url && cabang.thumbnail_url !== 'NULL' ? cabang
                .thumbnail_url :
                "/images/default_tower.jpg";
            document.getElementById('thumbnail-placeholder').classList.add('hidden');
            document.getElementById('thumbnail-container').classList.remove('hidden');
            document.getElementById('thumbnail-bg').classList.remove('hidden');
            document.getElementById('thumbnail').classList.remove('hidden');
            document.getElementById('alamat').classList.remove('text-center');
            document.getElementById('alamat').innerText = cabang.alamat;
            document.getElementById('detail').classList.remove('hidden');
            document.getElementById('detail').href = `/rotasi/cabang/${cabang.id}`;
            document.getElementById('nama').classList.remove('hidden');
            document.getElementById('nama').innerText = cabang.nama;
            if (cabang.longitude || cabang.latitude) {
                document.getElementById('anak-cabang').innerHTML = `@include('rotasi.components.cabang-item', ['cabang' => false, 'index' => 0])`;
                document.getElementById("search").value = cabang.nama;
            }
        }

        // activated cabang on cabang list
        var activated;

        // (re)bind cabang item click event
        function bindCabangItemClick() {
            if (activated) activated = null;
            document.querySelectorAll('.cabang-item').forEach(cabangItem => {
                cabangItem.addEventListener('click', () => {
                    if (activated) {
                        activated.classList.remove('bg-slate-600');
                        if (activated.classList.contains('bg-whited')) {
                            activated.classList.remove('bg-whited');
                            activated.classList.add('bg-white');
                        }
                    }
                    cabangItem.classList.add('bg-slate-600');
                    if (cabangItem.classList.contains('bg-white')) {
                        cabangItem.classList.remove('bg-white');
                        cabangItem.classList.add('bg-whited');
                    }
                    activated = cabangItem;
                    fetch(`/api/rotasi/cabang-summary/${cabangItem.value}`)
                        .then(response => response.json())
                        .then(data => {
                            setCabang(data);
                        });
                });
            })
        }
        // initial bind cabang item click
        bindCabangItemClick();

        // search cabang by search value and place it on cabang list then rebind the click event
        document.getElementById('search').addEventListener('input', debounce(() => {
            const search = document.getElementById('search').value;
            fetch(`/api/rotasi/cabang-search?search=${search}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('anak-cabang').innerHTML = data.map((cabang, i) =>
                        `@include('rotasi.components.cabang-item', ['cabang' => false, 'index' => "\${i}"])`).join('');
                    bindCabangItemClick();
                });
        }, 200));
        // fetch all cabang induk and place its coordinate's pointer on map 
        // and bind the click event on the pointer to set the cabang's summary
        fetch('/api/rotasi/cabang-induk')
            .then(response => response.json())
            .then(data => {
                data.forEach(element => {
                    L.marker([element.latitude, element.longitude], {
                        icon: L.icon({
                            iconUrl: '/images/icons/marker.svg',
                            iconSize: [30, 30],
                            iconAnchor: [15, 30],
                            popupAnchor: [0, -30],
                        }),
                    }).addTo(map).on('click', () => {
                        setCabang(element);
                        const selected = document.querySelector('.cabang-item');
                        selected.classList.add('bg-[#FFB72D]');
                        selected.classList.remove('bg-white');
                    });
                });
            });
    </script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
