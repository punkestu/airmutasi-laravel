<header
    class="bg-white flex flex-col xl:text-sm xl:flex-row items-center justify-between px-8 py-4 gap-4 {{ !empty($static) && $static ? 'sticky' : 'fixed' }} top-0 left-0 z-50 w-full font-sans text-lg tracking-widest font-lg">
    <a href="/"><img class="xl:h-10" src="/images/logo.svg" alt="logo" /></a>
    <button class="xl:hidden">☰</button>
    <nav
        class="xl:min-w-1/4 hidden xl:flex flex-col sm:flex-row items-center justify-around gap-2 sm:gap-4 xl:gap-16 z-[60]">
        <div id="rotasi-nav">
            <button class="flex items-center gap-1">Rotasi <img src="/images/icons/moreArrow.svg"
                    class="duration-300"></button>
            <div
                class="max-h-0 duration-300 absolute flex flex-col gap-1 bg-white mt-4 text-gray-800 text-base font-light overflow-hidden">
                <div class="flex xl:text-sm flex-col gap-1 px-2 py-1 bg-slate-100">
                    <a href="/rotasi/cabang">Denah Rotasi</a>
                    <hr>
                    <a href="/rotasi/pengajuan">Input Personal (IP)</a>
                    @can('admin')
                        <hr>
                        <a href="/rotasi/selektif">Selektif Admin</a>
                    @endcan
                </div>
            </div>
        </div>
        <a class="text-gray-500" href="#">Demosi</a>
        <a class="text-gray-500" href="#">Promosi</a>
        @auth
            @php
                $hasNotification =
                    ($adminHasUnreadNotifications) ||
                    (auth()->user()->profile &&
                        auth()->user()->profile->cabang &&
                        auth()->user()->profile->cabang->notreadnotifications->count() > 0) || (auth()->user()->notReadTaskNotifications->count() > 0);
            @endphp
            <a href="/rotasi/notification"
                class="flex aspect-square border-2 rounded-full p-1 {{ $hasNotification ? 'border-red-500' : 'border-gray-800' }}">
                <svg class="w-6 h-6 {{ $hasNotification ? 'text-red-500' : 'text-gray-800' }}" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        d="M17.133 12.632v-1.8a5.406 5.406 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V3.1a1 1 0 0 0-2 0v2.364a.955.955 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C6.867 15.018 5 15.614 5 16.807 5 17.4 5 18 5.538 18h12.924C19 18 19 17.4 19 16.807c0-1.193-1.867-1.789-1.867-4.175ZM8.823 19a3.453 3.453 0 0 0 6.354 0H8.823Z" />
                </svg>
            </a>
        @endauth
    </nav>
    <div id="account" class="hidden xl:flex flex-col sm:flex-row gap-2">
        @auth
            <a href="/akun"
                class="text-center text-sm hover:bg-[#003285] hover:text-white duration-300 text-black border-2 border-[#003285] px-8 py-1 rounded-full">Akun</a>
            <a href="/logout"
                class="text-center text-sm hover:bg-white hover:text-black duration-300 bg-[#003285] text-white border-2 border-[#003285] px-8 py-1 rounded-full">Logout</a>
        @else
            <a href="/login"
                class="text-center text-sm hover:bg-[#003285] hover:text-white duration-300 text-black border-2 border-[#003285] px-8 py-1 rounded-full">Login</a>
        @endauth
    </div>
</header>

<script>
    const rotasiNav = document.getElementById('rotasi-nav');
    rotasiNav.addEventListener('click', () => {
        const dropdown = rotasiNav.querySelector('div');
        dropdown.classList.toggle('max-h-0');
        dropdown.classList.toggle('max-h-[170%]');
        rotasiNav.querySelector('button > img').classList.toggle('rotate-90');
    });
</script>
