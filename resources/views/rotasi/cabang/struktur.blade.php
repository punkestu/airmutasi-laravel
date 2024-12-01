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
        <img src="/images/struktur.jpg" alt="struktur">
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
