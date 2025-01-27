<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Air Mutasi</title>
</head>

<body class="font-sans tracking-wider">
    @include('components/header', ['static' => true])
    <main class="flex flex-col gap-4 p-8">
        <section class="flex flex-col-reverse md:flex-row items-stretch gap-4">
            <aside class="md:w-3/5 flex flex-col justify-between">
                <p class="text-4xl 2xl:text-6xl font-black text-[#003285]">
                    Sistem Mutasi AirNav
                </p>
                <p class="font-medium mt-4 md:text-left text-justify">
                    Mutant adalah sebuah platform berbasis web yang dirancang untuk mengelola berbagai proses terkait
                    rotasi, demosi, dan promosi karyawan di lingkungan AirNav. Aplikasi ini dapat diakses melalui
                    browser, memudahkan pengelolaan dan pemantauan dari mana saja dan kapan saja.
                    Airmutasi membantu AirNav untuk mengelola sumber daya manusia secara lebih efektif, memastikan bahwa
                    setiap proses rotasi, demosi, dan promosi dilakukan dengan cara yang adil dan efisien.
                </p>
                <div class="grid md:grid-cols-2 gap-4 mt-4">
                    <a href="/cabang/struktur" class="bg-[#003285] text-white p-4 rounded-md">
                        <p class="font-semibold text-2xl text-center">{{ $cabangs->count() }}+</p>
                        <p class="opacity-60 text-center">STRUKTUR KANTOR CABANG</p>
                    </a>
                    <a href="/personel" class="bg-[#003285] text-white p-4 rounded-md">
                        <p class="font-semibold text-2xl text-center">{{ $personel }}+</p>
                        <p class="opacity-60 text-center">SDM OPERASI</p>
                    </a>
                </div>
            </aside>
            <aside class="md:w-2/5 aspect-video flex" id="carousel">
                @for ($i = 0; $i < 7; $i++)
                    <img class="{{ $i != 0 ? 'w-0' : '' }} object-cover rounded-md duration-300"
                        src="/images/slider/{{ $i + 1 }}.jpeg" alt="thumbnail-{{ $i }}">
                @endfor
            </aside>
        </section>
        <div class="grid md:grid-cols-3 gap-4">
            <a href="/rotasi/cabang"
                class="bg-[#003285] text-white font-semibold text-lg 2xl:text-xl flex flex-col justify-center items-center p-8 rounded-md 2xl:rounded-2xl opacity-90 hover:opacity-100 duration-300"><img
                    src="/images/icons/rotasi1.svg" alt="rotasi" class="w-48" />Rotasi</a>
            <a href="/promosi"
                class="bg-[#003285] text-white font-semibold text-lg 2xl:text-xl flex flex-col justify-center items-center p-8 rounded-md 2xl:rounded-2xl opacity-90 hover:opacity-100 duration-300">
                <img src="/images/icons/promosi1.svg" alt="promosi" class="w-48" />Promosi</a>
            <a href="/demosi"
                class="bg-[#003285] text-white font-semibold text-lg 2xl:text-xl flex flex-col justify-center items-center p-8 rounded-md 2xl:rounded-2xl opacity-90 hover:opacity-100 duration-300">
                <img src="/images/icons/demosi1.svg" alt="demosi" class="w-48" />Demosi</a>
        </div>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script>
        const carouselImgs = document.querySelectorAll("#carousel > img");
        var currCarouselIndex = 0;
        setInterval(() => {
            carouselImgs[currCarouselIndex].classList.add("w-0");
            carouselImgs[currCarouselIndex].classList.remove("w-full");
            currCarouselIndex = (currCarouselIndex + 1) % carouselImgs.length;
            carouselImgs[currCarouselIndex].classList.remove("w-0");
            carouselImgs[currCarouselIndex].classList.add("w-full");
        }, 2000);
    </script>
    <script src="/script/chatbot.js"></script>
</body>

</html>
