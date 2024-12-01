<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Air Mutasi | Profil</title>
</head>

<body class="font-sans tracking-wider">
    @include('components/header', ['static' => true])
    @include('components.modal-component')
    <main class="px-8 py-16">        
        <h1 class="text-3xl font-semibold mb-2">Notifikasi</h1>
        <div class="text-xl font-semibold text-gray-800 px-4 py-2 bg-gray-100 flex gap-4">
            <a href="/rotasi/notification" class="underline">Notifikasi</a>
            <a href="/rotasi/notification/task">Notifikasi Task</a>
        </div>
        <section class="flex flex-col gap-4 min-h-[50vh]">
            @forelse ($notifications as $notification)
                <div
                    class="flex flex-col gap-2 p-4 {{ $notification->status == 'dapat' ? 'bg-[#003285]' : ($notification->status == 'tidak' ? 'Tidak bg-red-900' : ($notification->status == 'diajukan' ? 'bg-gray-800' : 'bg-green-900')) }} text-white rounded-md">
                    <div class="flex flex-wrap gap-6">
                        <aside>
                            <p>Status:</p>
                            <strong>{{ $notification->status == 'dapat' ? 'Diterima' : ($notification->status == 'tidak' ? 'Tidak Diterima' : ($notification->status == 'diajukan' ? 'Diajukan' : 'Disetujui')) }}</strong>
                        </aside>
                        <aside>
                            <p>Atas nama:</p>
                            <strong>{{ $notification->pengajuan->nama_lengkap }}</strong>
                        </aside>
                        <aside>
                            <p>Dari:</p>
                            <strong>{{ $notification->pengajuan->lokasiAwal->nama }}
                                ({{ $notification->pengajuan->posisi_sekarang }})
                            </strong>
                        </aside>
                        <aside>
                            <p>Ke:</p>
                            <strong>{{ $notification->pengajuan->lokasiTujuan->nama }}
                                ({{ $notification->pengajuan->posisi_tujuan }})</strong>
                        </aside>
                    </div>
                    <p class="text-sm opacity-50 self-end">{{ $notification->created_at->diffForHumans() }}</p>
                </div>
            @empty
                <p>Belum ada notifikasi</p>
            @endforelse
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
