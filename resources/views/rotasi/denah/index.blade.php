<!DOCTYPE html>
<html lang="en">

<head>
    @include('components/head')
    <title>Air Mutasi | Rotasi</title>
</head>

<body class="font-geruduk tracking-wider text-lg">
    @include('components.header')
    @include('components.modal-component')
    <main>
        {{-- Map start --}}
        <section class="flex flex-col items-center">
            <div class="w-full h-[50vh] md:h-screen flex">
                <div id="map" class="w-full h-full z-40">
                    <div class="w-full h-full flex items-center justify-center" id="loading">
                        <img src="/images/icons/ripples.svg" alt="loading"
                            class="w-20 h-20 z-40 p-2 bg-white rounded-full">
                    </div>
                </div>
                <div id="banner-img" class="w-0 h-full overflow-hidden">
                    <img class="w-full object-cover"  src="/images/backgrounds/banner.png" alt="banner">
                </div>
            </div>
            <div>
                <button id="map-show-btn" class="p-2 border-2 border-yellow-500 bg-yellow-500 rounded-full"></button>
                <button id="banner-show-btn" class="p-2 border-2 border-yellow-500 rounded-full"></button>
            </div>
        </section>
        {{-- Map end --}}
        <section class="p-4 w-full flex flex-col-reverse sm:grid sm:grid-cols-2 gap-2">
            <aside
                class="col-span-2 sm:col-span-1 text-[#474747] pb-2 pe-2 flex flex-col gap-2 max-h-[70vh] overflow-y-auto">
                <div class="sticky top-0 bg-[#ced0ff]">
                    <div>
                        <input id="search" class="w-full rounded-lg px-2 py-1 border-2 border-[#656B8E]"
                            type="search" placeholder="Search ..." />
                    </div>
                </div>
                <div id="anak-cabang" class="flex flex-col gap-2">
                    @if (count($cabangs) === 0)
                        <p class="text-center my-auto">Tidak ada data</p>
                    @endif
                    @foreach ($cabangs as $cabang)
                        @include('rotasi.components.cabang-item', ['cabang' => $cabang])
                    @endforeach
                </div>
            </aside>
            <aside class="col-span-2 sm:col-span-1 flex flex-col gap-2 sm:max-h-[70vh]">
                @can('admin')
                    <a href="/rotasi/denah/input"
                        class="bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white w-full text-center p-2 rounded-lg font-semibold">Tambah
                        Cabang</a>
                @endcan
                <div class="p-2 flex-grow flex flex-col gap-2 rounded-lg overflow-y-auto w-full">
                    <div class="flex items-center justify-center bg-white h-64 rounded-lg border-8 border-black">
                        <img id="thumbnail-placeholder" src="/images/icons/Full Image.svg" alt="image" />
                        <img id="thumbnail" class="hidden w-full h-full object-cover" />
                    </div>
                    <h1 id="nama" class="px-2 font-semibold text-lg hidden"></h1>
                    <p id="alamat" class="p-2 text-center">
                        Pilih Cabang
                    </p>
                    <a id="detail"
                        class="self-end bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg text-center font-semibold hidden">
                        Lihat Detail
                    </a>
                </div>
            </aside>
        </section>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/map.js"></script>
    <script src="/script/debounce.js"></script>
    <script>
        var currentPage = 0;
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
                mapShowBtn.classList.add('bg-yellow-500');
                bannerShowBtn.classList.remove('bg-yellow-500');
            }
        });
        bannerShowBtn.addEventListener('click', () => {
            if (currentPage !== 1) {
                mapContainer.classList.add('w-0');
                mapContainer.classList.remove('w-full');
                bannerContainer.classList.add('w-full');
                bannerContainer.classList.remove('w-0');
                currentPage = 1;
                mapShowBtn.classList.remove('bg-yellow-500');
                bannerShowBtn.classList.add('bg-yellow-500');
            }
        });
    </script>
    <script>
        // set data to aside cabang's summary
        function setCabang(cabang) {
            document.getElementById('thumbnail').src = cabang.thumbnail_url && cabang.thumbnail_url !== 'NULL' ? cabang
                .thumbnail_url :
                "/images/default_tower.jpg";
            document.getElementById('thumbnail-placeholder').classList.add('hidden');
            document.getElementById('thumbnail').classList.remove('hidden');
            document.getElementById('alamat').classList.remove('text-center');
            document.getElementById('alamat').innerText = cabang.alamat;
            document.getElementById('detail').classList.remove('hidden');
            document.getElementById('detail').href = `/rotasi/denah/${cabang.id}`;
            document.getElementById('nama').classList.remove('hidden');
            document.getElementById('nama').innerText = cabang.nama;
            if (cabang.longitude || cabang.latitude) {
                document.getElementById('anak-cabang').innerHTML = `@include('rotasi.components.cabang-item', ['cabang' => false])`;
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
                        activated.classList.remove('bg-[#FFB72D]');
                        activated.classList.add('bg-white');
                    }
                    cabangItem.classList.add('bg-[#FFB72D]');
                    cabangItem.classList.remove('bg-white');
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
                    document.getElementById('anak-cabang').innerHTML = data.map(cabang =>
                        `@include('rotasi.components.cabang-item', ['cabang' => false])`).join('');
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
</body>

</html>
