<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Mutant</title>
</head>

<body class="font-sans tracking-wider">
    @include('components/header', ['static' => true])
    <main class="flex flex-col items-center justify-center gap-4 p-8 h-[49.9vh]">
        Belum Diimplementasikan
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
