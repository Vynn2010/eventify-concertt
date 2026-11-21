<?php
$hero_img = "assets/img/indexmain.png";
$logo_img = "assets/icon/logo.jpeg";

$halaman_home = "index.php";
$halaman_about = "pages/user/about.php";
$halaman_event = "pages/user/event.php";
$halaman_register = "pages/user/registerpage.php";
$halaman_login = "pages/user/loginpage_user.php";

$e = fn($s) => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventify - Find Your Next Unforgettable Event</title>
    <link rel="icon" href="<?= $e($logo_img) ?>">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind (link yang sama) + Flowbite -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet">

    <!-- Color system dari desain -->
    <style type="text/tailwindcss">
        @theme {
            --font-sans: "Outfit", ui-sans-serif, system-ui, sans-serif;
            --color-ghost: #F8F8FF;
            --color-sapphire: #4169FF;
            --color-sapphire-700: #3451D1;
            --color-majorelle: #5342FF;
            --color-majorelle-700: #4234D6;
            --color-brand-ink: #14143C;
            --color-meta-gray: #4B5563;
            --color-avail-pink: #FFB0D8;
            --color-slate-blue: #7B6CFF;
            --color-slate-blue-200: #CFC9FF;
        }
    </style>
</head>

<body class="m-0 p-0 antialiased font-sans text-brand-ink bg-ghost">

    <!-- Satu layar penuh di desktop (100vh), bisa scroll normal di HP -->
    <div class="flex flex-col min-h-dvh md:h-dvh md:overflow-hidden bg-cover bg-center bg-no-repeat bg-slate-blue-200"
        style="background-image: url('<?= $e($hero_img) ?>');">

        <!-- ============ NAVBAR (Flowbite) ============ -->
        <nav class="shrink-0 w-full bg-white/50 backdrop-blur-md border-b border-sapphire/30">
            <div class="max-w-screen-xl mx-auto flex flex-wrap items-center justify-between px-4 py-3">

                <a href="<?= $e($halaman_home) ?>" class="flex items-center gap-3">
                    <img src="<?= $e($logo_img) ?>" alt="Logo Eventify"
                        class="h-10 w-10 rounded-xl object-contain bg-white/70">
                    <span class="text-2xl font-bold tracking-tight text-brand-ink">Eventify</span>
                </a>

                <div class="flex md:order-2 items-center gap-2">
                    <a href="<?= $e($halaman_register) ?>"
                        class="text-white bg-sapphire hover:bg-sapphire-700 focus:ring-4 focus:outline-none focus:ring-sapphire/30 font-medium rounded-full text-sm px-6 py-2.5 text-center">
                        Register
                    </a>
                    <a href="<?= $e($halaman_login) ?>"
                        class="text-sapphire border border-sapphire hover:bg-sapphire/10 focus:ring-4 focus:outline-none focus:ring-sapphire/30 font-medium rounded-full text-sm px-6 py-2.5 text-center">
                        Login
                    </a>
                    <button data-collapse-toggle="navbar-menu" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-meta-gray rounded-lg md:hidden hover:bg-white/60 focus:outline-none focus:ring-2 focus:ring-sapphire/30"
                        aria-controls="navbar-menu" aria-expanded="false">
                        <span class="sr-only">Buka menu</span>
                        <svg class="w-5 h-5" aria-hidden="true" fill="none" viewBox="0 0 17 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 1h15M1 7h15M1 13h15" />
                        </svg>
                    </button>
                </div>

                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-menu">
                    <ul
                        class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-white/60 rounded-lg bg-white/60 md:space-x-8 md:flex-row md:mt-0 md:border-0 md:bg-transparent text-lg">
                        <li><a href="<?= $e($halaman_home) ?>" class="block py-2 px-3 text-majorelle md:p-0"
                                aria-current="page">Home</a></li>
                        <li><a href="<?= $e($halaman_about) ?>"
                                class="block py-2 px-3 text-meta-gray hover:text-majorelle md:p-0">About</a></li>
                        <li><a href="<?= $e($halaman_event) ?>"
                                class="block py-2 px-3 text-meta-gray hover:text-majorelle md:p-0">Event</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- ============ HERO (otomatis menyesuaikan tinggi layar) ============ -->
        <main class="flex-1 min-h-0 flex items-center justify-center px-4 py-6">
            <section
                class="w-full max-w-3xl flex flex-col items-center text-center gap-4 lg:gap-5 [@media(max-height:760px)]:gap-3">

                <span
                    class="inline-flex items-center gap-2 rounded-full bg-white/60 backdrop-blur-sm px-4 py-1.5 text-xs sm:text-sm shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-red-500"></span>
                    <span><b class="text-majorelle">LIVE NOW</b>: <b>Global Electronic Arena — 18.4K tuned in</b></span>
                </span>

                <div
                    class="w-full rounded-[28px] border border-white/60 bg-white/40 backdrop-blur-md px-6 sm:px-12 py-7 lg:py-9 [@media(max-height:760px)]:py-5 shadow-lg shadow-majorelle/10">
                    <h1 class="text-3xl lg:text-4xl xl:text-5xl font-extrabold tracking-tight leading-tight">
                        Find Your Next <span class="text-sapphire">Unforgettable</span> Event
                    </h1>
                </div>

                <form action="<?= $e($halaman_event) ?>" method="get" class="w-full max-w-[420px]" role="search">
                    <label for="q" class="sr-only">Cari event</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-5 pointer-events-none">
                            <svg class="w-5 h-5 text-meta-gray" aria-hidden="true" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                            </svg>
                        </div>
                        <input type="search" id="q" name="q" placeholder="Discover event, artist, or location..."
                            class="block w-full py-3 ps-12 pe-4 text-sm text-brand-ink placeholder:text-meta-gray bg-white/80 backdrop-blur-sm border-0 rounded-full shadow-sm focus:ring-2 focus:ring-majorelle focus:outline-none">
                    </div>
                </form>

                <div
                    class="w-full max-w-[420px] flex items-center gap-4 rounded-[22px] border border-white/60 bg-white/55 backdrop-blur-md px-5 py-3.5 text-left shadow-sm">
                    <span class="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-slate-blue-200/80"
                        aria-hidden="true">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linejoin="round">
                            <path
                                d="M4 9a2 2 0 0 0 0 4v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a2 2 0 0 1 0-4V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v2z"
                                transform="rotate(-35 12 12)" />
                            <path d="M14 8.5l-.01.01M14 12l-.01.01M14 15.5l-.01.01" stroke-linecap="round"
                                transform="rotate(-35 12 12)" />
                        </svg>
                    </span>
                    <p class="text-xs leading-relaxed text-meta-gray">
                        Discover elite concert experiences, select prime seating in real-time, and purchase 100%
                        verified premium tickets seamlessly.
                    </p>
                </div>

                <a href="<?= $e($halaman_event) ?>"
                    class="text-white bg-majorelle hover:bg-majorelle-700 focus:ring-4 focus:outline-none focus:ring-majorelle/30 font-semibold rounded-full text-sm px-10 py-3 shadow-lg shadow-majorelle/30 transition hover:scale-105 active:scale-95">
                    Explore Events
                </a>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>

</html>