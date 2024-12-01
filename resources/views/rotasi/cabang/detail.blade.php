<!DOCTYPE html>
<html lang="en">

<head>
    @include('components/head')
    <title>Air Mutasi | Rotasi</title>
</head>

<body class="font-sans tracking-wider">
    @include('components.header', ['static' => true])
    <main>
        <section class="p-8 flex flex-col md:grid md:grid-cols-3 gap-8">
            <aside class="col-span-3 md:col-span-1 sm:h-full flex flex-col">
                <div class="flex items-center justify-center rounded-lg">
                    <img src="{{ $cabang->thumbnail_url && $cabang->thumbnail_url != 'NULL' ? $cabang->thumbnail_url : '/images/default_tower.jpg' }}"
                        alt="foto cabang" class="w-full h-full object-cover rounded-lg">
                </div>
                <h1 id="nama" class="p-2 font-semibold text-lg">
                    {{ $cabang->nama }}
                </h1>
                @can('cabangOwner', $cabang->id)
                    <div class="px-2 flex flex-wrap gap-2">
                        @foreach ($cabang->kelases as $kelas)
                            <p class="text-xs bg-gray-300 px-2 py-1 rounded-md">{{ $kelas->kelas->nama_kelas }}</p>
                        @endforeach
                    </div>
                @endcan
                <pre style="white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;"
                    class="p-2 break-all w-full font-sans">{{ $cabang->alamat }}</pre>
                @can('cabangOwner', $cabang->id)
                    <a class="mt-4 bg-[#003285] hover:bg-white text-white hover:text-black border-2 border-[#003285] duration-200 w-full text-center p-2 rounded-lg font-semibold mb-2"
                        href="/personel/cabang/{{ $cabang->id }}?tab=ATC">Personel</a>
                    <a href="/rotasi/cabang/input/{{ $cabang->id }}"
                        class="bg-[#003285] hover:bg-white text-white hover:text-black border-2 border-[#003285] duration-200 w-full text-center p-2 rounded-lg font-semibold mb-2">Update</a>
                    <a href="/rotasi/cabang/input/{{ $cabang->id }}/delete"
                        class="bg-white hover:bg-[#003285] text-black hover:text-white border-2 border-[#003285] duration-200 w-full text-center p-2 rounded-lg font-semibold mb-2">Hapus
                        Cabang</a>
                    <a href="/rotasi/cabang/konsep/{{ $cabang->id }}"
                        class="bg-white hover:bg-[#003285] text-black hover:text-white border-2 border-[#003285] duration-200 w-full text-center p-2 rounded-lg font-semibold">Berkas</a>
                @endcan
            </aside>
            <aside class="flex-grow col-span-2 grid md:grid-cols-2 md:grid-rows-1 gap-4">
                <div
                    class="bg-[#003285] col-span-2 md:col-span-1 flex items-center justify-center rounded-lg p-4 min-h-[50vh]">
                    <div id="stats-radar" class="w-full"></div>
                </div>
                <div
                    class="bg-[#003285] text-white col-span-2 md:col-span-1 flex flex-col gap-4 items-center justify-center rounded-lg p-4">
                    @php
                        $range = $cabang->formasi - $cabang->frms;
                        $skalaPersonelATC = number_format(
                            (($cabang->jumlah_personel - $cabang->frms) / ($range > 0 ? $range : 1)) * 10,
                            0,
                        );
                    @endphp
                    <h2 class="font-bold">Skala Personel ATC</h2>
                    <div class="w-full flex justify-center items-center gap-1">
                        <div class="{{ $skalaPersonelATC <= 1 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#ff0000]">
                            <img src="/images/icons/worst.svg" alt="worst">
                        </div>
                        <div class="{{ $skalaPersonelATC == 2 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#ff3300]">
                            <img src="/images/icons/worst.svg" alt="worst">
                        </div>
                        <div class="{{ $skalaPersonelATC == 3 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#ff6600]">
                            <img src="/images/icons/bad.svg" alt="bad">
                        </div>
                        <div class="{{ $skalaPersonelATC == 4 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#ff9900]">
                            <img src="/images/icons/bad.svg" alt="bad">
                        </div>
                        <div class="{{ $skalaPersonelATC == 5 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#ffcc00]">
                            <img src="/images/icons/netral.svg" alt="netral">
                        </div>
                        <div class="{{ $skalaPersonelATC == 6 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#ccff00]">
                            <img src="/images/icons/netral.svg" alt="netral">
                        </div>
                        <div class="{{ $skalaPersonelATC == 7 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#99ff00]">
                            <img src="/images/icons/good.svg" alt="good">
                        </div>
                        <div class="{{ $skalaPersonelATC == 8 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#66ff00]">
                            <img src="/images/icons/good.svg" alt="good">
                        </div>
                        <div class="{{ $skalaPersonelATC == 9 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#33ff00]">
                            <img src="/images/icons/best.svg" alt="best">
                        </div>
                        <div
                            class="{{ $skalaPersonelATC >= 10 ? 'w-12 h-12' : 'w-8 h-8' }} aspect-square bg-[#00ff00]">
                            <img src="/images/icons/best.svg" alt="best">
                        </div>
                    </div>
                    {{ $skalaPersonelATC < 0 ? 0 : ($skalaPersonelATC > 10 ? 10 : $skalaPersonelATC) }}/10
                </div>
                <div class="col-span-2 grid grid-cols-2 gap-4 bg-white rounded-lg">
                    <div class="col-span-2 sm:col-span-1 border-4 border-[#003285] rounded-lg flex flex-col p-2">
                        <h2 class="font-bold text-xl text-center">ATC</h2>
                        <hr class="border-[1px] my-1">
                        <p class="font-medium ms-4">Jumlah Eksisting {{ $cabang->jumlah_personel }} Orang</p>
                        <p class="font-medium ms-4">Jumlah FRMS Optimal {{ $cabang->formasi }} Orang</p>
                        <p class="font-medium ms-4">Jumlah FRMS Minimal {{ $cabang->frms }} Orang</p>
                    </div>
                    <div class="col-span-2 sm:col-span-1 border-4 border-[#003285] rounded-lg p-2 grid">
                        <h2 class="font-bold text-xl text-center col-span-12">Unit</h2>
                        <hr class="border-[1px] my-1 col-span-12">
                        <table class="col-span-12">
                            <tr>
                                <th class="text-start">Posisi</th>
                                <th class="text-start">Eksisting</th>
                                <th class="text-start">Formasi</th>
                            </tr>
                            <tr>
                                <td>ACO</td>
                                <td>{{ $cabang->jumlah_personel_aco }}</td>
                                <td>{{ $cabang->formasi_aco }}</td>
                            </tr>
                            <tr>
                                <td>AIS</td>
                                <td>{{ $cabang->jumlah_personel_ais }}</td>
                                <td>{{ $cabang->formasi_ais }}</td>
                            </tr>
                            <tr>
                                <td>ATFM</td>
                                <td>{{ $cabang->jumlah_personel_atfm }}</td>
                                <td>{{ $cabang->formasi_atfm }}</td>
                            </tr>
                            <tr>
                                <td>TAPOR</td>
                                <td>{{ $cabang->jumlah_personel_tapor }}</td>
                                <td>{{ $cabang->formasi_tapor }}</td>
                            </tr>
                            <tr>
                                <td>ATS System</td>
                                <td>{{ $cabang->jumlah_personel_ats_system }}</td>
                                <td>{{ $cabang->formasi_ats_system }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-span-2 border-4 border-[#003285] rounded-lg flex flex-col justify-center p-2">
                        <h2 class="font-bold text-xl text-center">Prediksi Personel {{ date('Y') + 1 }}</h2>
                        <hr class="border-[1px] my-1">
                        <p class="font-medium ms-4 text-center">
                            ATC
                            {{ $cabang->jumlah_personel - count($cabang->personelPensiunATC) + count($cabang->personelMagangATC) }}
                            Orang</p>
                        <p class="font-medium ms-4 text-center">
                            ACO
                            {{ $cabang->jumlah_personel_aco - count($cabang->personelPensiunACO) + count($cabang->personelMagangACO) }}
                            Orang</p>
                        <p class="font-medium ms-4 text-center">
                            AIS
                            {{ $cabang->jumlah_personel_ais - count($cabang->personelPensiunAIS) + count($cabang->personelMagangAIS) }}
                            Orang</p>
                        <p class="font-medium ms-4 text-center">
                            ATFM
                            {{ $cabang->jumlah_personel_atfm - count($cabang->personelPensiunATFM) + count($cabang->personelMagangATFM) }}
                            Orang</p>
                        <p class="font-medium ms-4 text-center">
                            TAPOR
                            {{ $cabang->jumlah_personel_tapor - count($cabang->personelPensiunTAPOR) + count($cabang->personelMagangTAPOR) }}
                            Orang</p>
                        <p class="font-medium ms-4 text-center">
                            ATS System
                            {{ $cabang->jumlah_personel_ats_system - count($cabang->personelPensiunATSSystem) + count($cabang->personelMagangATSSystem) }}
                            Orang</p>
                    </div>
                </div>
            </aside>
        </section>
        <section class="p-8">
            <div class="bg-white rounded-md border-2 border-[#003285]">
                <div class="flex gap-4 p-4 border-b-2 border-[#003285] text-black">
                    <a class="{{ $tab != 'out' ? 'font-semibold underline' : '' }}"
                        href="/rotasi/cabang/{{ $cabang->id }}?tab=in">PERSONEL IN</a>
                    <a class="{{ $tab == 'out' ? 'font-semibold underline' : '' }}"
                        href="/rotasi/cabang/{{ $cabang->id }}?tab=out">PERSONEL OUT</a>
                </div>
                <div>
                    @if (($tab != 'out' && $cabang->inAll->isEmpty()) || ($tab == 'out' && $cabang->outAll->isEmpty()))
                        <p class="p-4 text-center">Tidak ada data</p>
                    @endif
                    @foreach ($tab == 'out' ? $cabang->outAll : $cabang->inAll as $pengajuan)
                        <div
                            class="font-medium px-4 py-2 {{ $loop->index % 2 === 0 ? 'bg-slate-200' : '' }} grid grid-cols-12 items-center gap-2">
                            <p class="col-span-3">{{ $pengajuan->nik }}</p>
                            <h2 class="col-span-6">{{ $pengajuan->nama_lengkap }}</h2>
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
        var options = generateRadarChart("Distribusi Personel", [{
            name: 'Minimal',
            data: [
                {{ $cabang->frms }},
                {{ $cabang->frms_aco }},
                {{ $cabang->frms_ais }},
                {{ $cabang->frms_atfm }},
                {{ $cabang->frms_tapor }},
                {{ $cabang->frms_ats_system }},
            ],
        }, {
            name: 'Eksisting',
            data: [
                {{ $cabang->jumlah_personel }},
                {{ $cabang->jumlah_personel_aco }},
                {{ $cabang->jumlah_personel_ais }},
                {{ $cabang->jumlah_personel_atfm }},
                {{ $cabang->jumlah_personel_tapor }},
                {{ $cabang->jumlah_personel_ats_system }},
            ],
        }, {
            name: 'Optimal',
            data: [
                {{ $cabang->formasi }},
                {{ $cabang->formasi_aco }},
                {{ $cabang->formasi_ais }},
                {{ $cabang->formasi_atfm }},
                {{ $cabang->formasi_tapor }},
                {{ $cabang->formasi_ats_system }},
            ],
        }]);

        var chart = new ApexCharts(document.querySelector("#stats-radar"), options);
        chart.render();
    </script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
