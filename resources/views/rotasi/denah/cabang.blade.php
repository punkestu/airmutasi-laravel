<!DOCTYPE html>
<html lang="en">

<head>
    @include('components/head')
    <title>Air Mutasi | Rotasi</title>
</head>

<body class="bg-[#CED0FF] font-poppins">
    @include('components.header', ['static' => true])
    <main>
        <section class="bg-[#293676] text-[#474747] p-8 flex flex-col md:grid md:grid-cols-3 gap-8">
            <aside class="col-span-3 md:col-span-1 sm:h-full flex flex-col">
                <div class="flex items-center justify-center flex-grow h-[50vh] md:max-h-[50%] rounded-lg">
                    <img src="{{ $cabang->thumbnail_url && $cabang->thumbnail_url != 'NULL' ? $cabang->thumbnail_url : '/images/default_tower.jpg' }}"
                        alt="foto cabang" class="w-full h-full object-cover rounded-lg">
                </div>
                <h1 id="nama" class="text-white p-2 font-semibold text-lg">
                    {{ $cabang->nama }}
                </h1>
                <p class="text-white p-2">
                    {{ $cabang->alamat }}
                </p>
                @can ('admin')
                    <a href="/rotasi/denah/input/{{ $cabang->id }}"
                        class="bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white w-full text-center p-2 rounded-lg font-semibold mb-2">Update
                        Cabang</a>
                    <a href="/rotasi/denah/input/{{ $cabang->id }}/delete"
                        class="bg-red-500 hover:bg-red-700 duration-200 text-white w-full text-center p-2 rounded-lg font-semibold">Hapus
                        Cabang</a>
                @endcan
            </aside>
            <aside class="flex-grow col-span-3 md:col-span-2 md:h-screen grid md:grid-cols-2 md:grid-rows-2 gap-4">
                <div class="col-span-2 md:col-span-1 flex items-center justify-center bg-white rounded-lg p-4">
                    <div id="stats-bar" class="w-full h-full"></div>
                </div>
                <div class="col-span-2 md:col-span-1 flex items-center justify-center bg-white rounded-lg p-4">
                    <div id="stats-pie" class="w-full h-full"></div>
                </div>
                <div class="col-span-2 grid grid-cols-2 gap-2 bg-white rounded-lg p-4">
                    <div
                        class="col-span-2 sm:col-span-1 border-4 rounded-lg flex flex-col items-center justify-center p-2">
                        <h2 class="font-bold text-xl">Jumlah Personel</h2>
                        <p class="font-medium">ATC {{ $cabang->jumlah_personel }} Orang</p>
                        <p class="font-medium">ACO {{ $cabang->jumlah_personel_aco }} Orang</p>
                    </div>
                    <div
                        class="col-span-2 sm:col-span-1 border-4 rounded-lg flex flex-col items-center justify-center p-2">
                        <h2 class="font-bold text-xl">Formasi</h2>
                        <p class="font-medium">ATC {{ $cabang->formasi }} Orang</p>
                        <p class="font-medium">ACO {{ $cabang->formasi_aco }} Orang</p>
                    </div>
                    <div class="border-4 rounded-lg col-span-2 flex flex-col items-center justify-center p-2">
                        <h2 class="font-bold text-xl">Prediksi personel</h2>
                        <p class="font-medium">
                            ATC {{ $cabang->jumlah_personel + count($cabang->in) - count($cabang->out) }} Orang</p>
                        <p class="font-medium">
                            ACO {{ $cabang->jumlah_personel_aco + count($cabang->inACO) - count($cabang->outACO) }}
                            Orang</p>
                    </div>
                </div>
            </aside>
        </section>
        <section class="p-8">
            <div class="bg-white rounded-lg border-2 border-[#293676]">
                <div class="flex gap-4 p-4 border-b-2 border-[#293676] text-[#293676]">
                    <a class="{{ $tab != 'out' ? 'font-semibold' : '' }}"
                        href="/rotasi/denah/{{ $cabang->id }}?tab=in">PERSONEL IN</a>
                    <a class="{{ $tab == 'out' ? 'font-semibold' : '' }}"
                        href="/rotasi/denah/{{ $cabang->id }}?tab=out">PERSONEL OUT</a>
                </div>
                <div>
                    @if (($tab != 'out' && $cabang->inAll->isEmpty()) || ($tab == 'out' && $cabang->outAll->isEmpty()))
                        <p class="p-4 text-center">Tidak ada data</p>
                    @endif
                    @foreach ($tab == 'out' ? $cabang->outAll : $cabang->inAll as $pengajuan)
                        <div
                            class="font-medium px-4 py-2 {{ $loop->index % 2 === 0 ? 'bg-slate-200' : '' }} grid grid-cols-12 items-center gap-2">
                            <img class="h-14" src="/images/icons/User_fill.svg" alt="user" />
                            <h2 class="col-span-4">{{ $pengajuan->nama_lengkap }}</h2>
                            <p class="col-span-5">{{ $pengajuan->nik }}</p>
                            <p>{{ $pengajuan->posisi_tujuan }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="/script/nav.js"></script>
    <script src="/script/chart.js"></script>
    <script>
        const series = [{
            name: "Jumlah Personel",
            data: [
                {{ $cabang->frms }},
                {{ $cabang->jumlah_personel }},
                {{ $cabang->formasi }}
            ],
        }, ];

        var chartBar = new ApexCharts(
            document.querySelector("#stats-bar"),
            generateBarChart("Grafik Personel", series)
        );
        chartBar.render();

        var chartPie = new ApexCharts(
            document.querySelector("#stats-pie"),
            generatePieChart("Distribusi Personel", series[0].data)
        );
        chartPie.render();
    </script>
</body>

</html>
