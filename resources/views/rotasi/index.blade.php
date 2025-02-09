<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Mutant | Rotasi</title>
</head>

<body class="bg-[#373737] font-sans tracking-wider text-lg">
    @include('components.header')
    <main class="h-[70vh] sm:h-screen">
        <section class="h-full w-full p-4">
            <div id="map" class="w-full h-full z-40">
                <div class="w-full h-full flex items-center justify-center" id="loading">
                    <img src="/images/icons/ripples.svg" alt="loading" class="w-20 h-20 z-40 p-2 bg-white rounded-full">
                </div>
            </div>
        </section>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/map.js"></script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
