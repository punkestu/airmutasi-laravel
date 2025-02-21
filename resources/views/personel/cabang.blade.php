<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Mutant | Personel</title>
</head>

<body class="bg-[#373737] font-sans tracking-wider text-lg">
    @include('components/header', ['static' => true])
    @include('components.modal-component')
    <main class="min-h-screen">
        {{-- <div id="export-personel-popover" popover class="bg-white p-2 rounded-md border-2">
            <form action="/personel/export?posisi={{ $tab }}&cabang_id={{ $cabang->id }}" method="POST"
                class="flex flex-col gap-2" enctype="multipart/form-data">
                @csrf
                <input type="file" name="sheet" id="sheet" accept=".csv">
                <button type="submit"
                    class="text-center bg-[#7186F3] hover:bg-[#435EEF] duration-200 text-white px-4 py-2 rounded-lg font-semibold flex-grow">Export</button>
            </form>
        </div> --}}
        <div id="detail-pindah-modal" tabindex="-1" aria-hidden="true"
            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow ">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t ">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Detail Data Pindah
                        </h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                            data-modal-hide="detail-pindah-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div id="body" class="p-4 md:p-5 space-y-4">

                    </div>
                </div>
            </div>
        </div>
        <section>
            <div class="bg-[#FFB72D] p-8">
                <h1 class="text-center font-bold text-xl">DATA PERSONIL OPERASI <br>
                    AIRNAV INDONESIA</h1>
                <h2 class="font-semibold text-lg mt-8">{{ $cabang->nama }}</h2>
            </div>
        </section>
        <section class="p-8">
            <div class="bg-white rounded-lg border-2 border-[#293676]">
                <div class="overflow-x-auto p-4 border-b-2 border-[#293676] text-[#293676]">
                    <div class="flex flex-nowrap gap-4">
                        @foreach ($categories as $category)
                            <a class="text-nowrap {{ $tab == $category ? 'font-semibold underline' : '' }}"
                                href="/personel/cabang/{{ $cabang->id }}?tab={{ $category }}">Personel
                                {{ $category }}</a>
                        @endforeach
                        <a class="{{ $tab == 'lainnya' ? 'font-semibold underline' : '' }}"
                            href="/personel/cabang/{{ $cabang->id }}?tab=lainnya">Lainnya</a>
                    </div>
                </div>
                <div class="relative overflow-x-auto max-h-[70vh] overflow-y-auto block">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No.
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Aksi
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    NIK-AirNav
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    E-NIK
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    NIK-AP1
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Gelar
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kelamin
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tempat, Tanggal Lahir
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Usia
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status Karyawan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    TMT Kerja Airnav
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    TMT Kerja Golongan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    TMT Pensiun
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Lokasi
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Lokasi Induk
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Lokasi Kedudukan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Unit
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Jabatan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    TMT Jabatan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Masa Kerja Jabatan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Level Jabatan (Level)
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    TMT Level Jabatan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Masa Kerja Level Jabatan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Skala Jabatan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Fungsi
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Job Text
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tidak Pindah
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Pengajuan Pindah
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cabang->personels as $personel)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row"
                                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $loop->iteration }}
                                    </th>
                                    <td class="px-6 py-4">
                                        <a href="/personel/pensiun/{{ $personel->id }}">
                                            {{ $personel->pensiun ? 'Batalkan pensiun' : 'Pensiun' }}
                                        </a>
                                        <a href="/personel/delete/{{ $personel->id }}" class="text-red-500">Hapus</a>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->nik }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->e_nik }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->nik_ap1 }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->gelar }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->kelamin }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->tempat_lahir }}, {{ $personel->tgl_lahir }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->usia_th }} Tahun, {{ $personel->usia_bl }} Bulan
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->sts_karyawan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->tmt_kerja_airnav }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->tmt_kerja_golongan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->tmt_pensiun }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->cabang->nama }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->lokasi ? $personel->lokasiCabang->nama : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->lokasi_induk ? $personel->lokasiInduk->nama : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->unit }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->posisi }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->tmt_jabatan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->masa_kerja }} Tahun,
                                        {{ $personel->masa_kerja_jabatan_bl }} Bulan
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->nama_level_jabatan }} ({{ $personel->level }})
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->tmt_level_jabatan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->masa_kerja_level_jabatan_th }} Tahun,
                                        {{ $personel->masa_kerja_level_jabatan_bl }} Bulan
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->skala_jabatan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->fungsi }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->job_text }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $personel->tidak_pindah ? 'Tidak pindah sampai ' . date('j F, Y', strtotime($personel->expired)) : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if (count($personel->pengajuan_pindah) > 0)
                                            <button data-modal-target="detail-pindah-modal"
                                                data-modal-toggle="detail-pindah-modal"
                                                onclick='setPindahDetail(@json($personel->pengajuan_pindah))'>✅</button>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-between px-4 py-2">
                    <a
                        @if ($page > 0) href="?tab={{ $tab }}&page={{ $page - 1 }}" @endif>Back</a>
                    <a href="?tab={{ $tab }}&page={{ $page + 1 }}">Next</a>
                </div>
            </div>
        </section>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script>
        function setPindahDetail(dataPindah) {
            const body = document.querySelector('#detail-pindah-modal #body');
            body.innerHTML = '';
            dataPindah.forEach(data => {
                const div = `
            <div class="flex gap-2 border mb-2 px-2 py-1">
                <span>Dari: ${data.lokasi_awal.nama}</span>
                <span>Ke: ${data.lokasi_tujuan.nama}</span>
                <span>Diajukan: ${data.created_at}</span>
            </div>
            `;
                body.innerHTML += div;
            });
        }
        var activepopup = null;
        const aksiPopup = document.querySelectorAll('.personels-action');
        aksiPopup.forEach((element) => {
            element.addEventListener('click', () => {
                if (activepopup) {
                    activepopup.querySelector('div').classList.toggle('max-h-0');
                    activepopup.querySelector('div').classList.toggle('max-h-[15%]');
                    activepopup.querySelector('button > img').classList.toggle('rotate-90');
                }
                if (activepopup === element) {
                    activepopup = null;
                    return;
                }
                const dropdown = element.querySelector('div');
                dropdown.classList.toggle('max-h-0');
                dropdown.classList.toggle('max-h-[15%]');
                element.querySelector('button > img').classList.toggle('rotate-90');
                activepopup = element;
            });
        });
    </script>
    <script src="/script/chatbot.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>

</html>
