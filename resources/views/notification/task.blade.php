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
        <h1 class="text-3xl font-semibold mb-2">Notifikasi Task</h1>
        <div class="text-xl font-semibold text-gray-800 px-4 py-2 bg-gray-100 flex gap-4">
            <a href="/rotasi/notification">Notifikasi</a>
            <a href="/rotasi/notification/task" class="underline">Notifikasi Task</a>
        </div>
        <section class="flex flex-col gap-4 min-h-[50vh]">
            @forelse ($notifications as $notification)
                <a href="/personel/task/{{ $notification->task->id }}"
                    class="flex flex-col gap-2 p-4 bg-[#003285] text-white rounded-md">
                    <p>{{ $notification->task->deskripsi }}</p>                   
                    <p class="text-sm opacity-50 self-end">{{ $notification->created_at->diffForHumans() }}</p>
                </a>
            @empty
                <p>Belum ada notifikasi</p>
            @endforelse
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
