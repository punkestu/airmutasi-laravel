<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Mutant | Profil</title>
</head>

<body class="font-sans tracking-wider">
    @include('components/header', ['static' => true])
    @include('components.modal-component')
    <main class="px-8 py-16">
        <h1 class="text-3xl font-semibold mb-2">Notifikasi</h1>
        <div class="text-xl font-semibold text-gray-800 px-4 py-2 bg-gray-100 flex gap-4">
            <a href="/rotasi/notification">Pengajuan</a>
            <a href="/rotasi/notification/task">Task</a>
            @if (auth()->user()->role->name == 'admin')
                <a href="/rotasi/notification/user" class="underline">User</a>
            @endif
        </div>
        <section class="flex flex-col gap-4 min-h-[50vh]">
            @forelse ($notifications as $notification)
                <div class="flex flex-col gap-2 p-4 bg-[#003285] text-white rounded-md">
                    <div class="flex flex-col gap-2">
                        <p>{{ $notification->user->name }} - {{ $notification->user->email }}</p>
                        <p>{{ $notification->description }}</p>
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
