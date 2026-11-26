<?php
$hero_img = "assets/img/indexmain.png";
$logo_img = "assets/icon/logo.jpeg";

$halaman_home = "pages/user/index.php";
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

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet">

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

    <!-- MAIN BACKGROUND -->
    <div class="flex flex-col min-h-dvh md:h-dvh md:overflow-hidden bg-cover bg-center bg-no-repeat bg-slate-blue-200"
        style="background-image: url('<?= $e($hero_img) ?>');">

        <!-- NAVBAR -->
        <nav class="shrink-0 w-full bg-white/50 backdrop-blur-md border-b border-sapphire/30 relative z-50">

            <div class="max-w-screen-xl mx-auto px-5 sm:px-8">

                <div class="grid grid-cols-[1fr_auto] md:grid-cols-3 items-center min-h-[72px]">

                    <!-- LOGO -->
                    <a href="<?= $e($halaman_home) ?>" class="flex items-center gap-3 w-fit">

                        <img src="<?= $e($logo_img) ?>" alt="Eventify" class="w-10 h-10 object-contain">

                        <span class="text-2xl font-bold tracking-tight text-brand-ink">
                            Eventify
                        </span>
                    </a>

                    <!-- DESKTOP NAVIGATION -->
                    <div class="hidden md:flex items-center justify-center">

                        <ul class="flex items-center gap-8 lg:gap-10 text-lg font-medium">

                            <li>
                                <a href="<?= $e($halaman_home) ?>"
                                    class="text-majorelle hover:text-majorelle-700 transition">
                                    Home
                                </a>
                            </li>

                            <li>
                                <a href="<?= $e($halaman_about) ?>"
                                    class="text-meta-gray hover:text-majorelle transition">
                                    About
                                </a>
                            </li>

                            <li>
                                <a href="<?= $e($halaman_event) ?>"
                                    class="text-meta-gray hover:text-majorelle transition">
                                    Event
                                </a>
                            </li>

                        </ul>
                    </div>

                    <!-- AUTH BUTTONS -->
                    <div class="hidden md:flex items-center justify-end gap-2.5">

                        <a href="<?= $e($halaman_register) ?>"
                            class="min-w-[100px] text-center text-white bg-sapphire hover:bg-sapphire-700 font-semibold text-sm rounded-full px-6 py-2.5 transition duration-200 shadow-sm">
                            Register
                        </a>

                        <a href="<?= $e($halaman_login) ?>"
                            class="min-w-[85px] text-center text-sapphire border border-sapphire hover:bg-sapphire hover:text-white font-semibold text-sm rounded-full px-6 py-2.5 transition duration-200">
                            Login
                        </a>

                    </div>

                    <!-- MOBILE BUTTON -->
                    <button data-collapse-toggle="navbar-menu" type="button"
                        class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-full text-brand-ink hover:bg-white/70 transition"
                        aria-controls="navbar-menu" aria-expanded="false">

                        <span class="sr-only">Buka menu</span>

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />

                        </svg>
                    </button>

                </div>

                <!-- MOBILE NAVIGATION -->
                <div class="hidden md:hidden pb-4" id="navbar-menu">

                    <ul class="flex flex-col gap-1 p-3 rounded-xl bg-white/70 border border-white/60">

                        <li>
                            <a href="<?= $e($halaman_home) ?>"
                                class="block px-4 py-3 rounded-lg text-majorelle hover:bg-white/70 text-base">
                                Home
                            </a>
                        </li>

                        <li>
                            <a href="<?= $e($halaman_about) ?>"
                                class="block px-4 py-3 rounded-lg text-meta-gray hover:bg-white/70 text-base">
                                About
                            </a>
                        </li>

                        <li>
                            <a href="<?= $e($halaman_event) ?>"
                                class="block px-4 py-3 rounded-lg text-meta-gray hover:bg-white/70 text-base">
                                Event
                            </a>
                        </li>

                        <li class="grid grid-cols-2 gap-2 pt-2">

                            <a href="<?= $e($halaman_register) ?>"
                                class="text-center text-white bg-sapphire hover:bg-sapphire-700 rounded-full py-2.5 font-semibold text-sm transition">
                                Register
                            </a>

                            <a href="<?= $e($halaman_login) ?>"
                                class="text-center text-sapphire border border-sapphire hover:bg-sapphire hover:text-white rounded-full py-2.5 font-semibold text-sm transition">
                                Login
                            </a>

                        </li>

                    </ul>
                </div>

            </div>
        </nav>

        <!-- HERO SECTION -->
        <main class="flex-1 min-h-0 flex items-center justify-center px-4 py-6">

            <section
                class="w-full max-w-3xl flex flex-col items-center text-center gap-5 lg:gap-6 [@media(max-height:760px)]:gap-3">

                <!-- LIVE NOW -->
                <span
                    class="inline-flex items-center gap-2 rounded-full bg-white/60 backdrop-blur-sm px-5 py-2 text-sm shadow-sm">

                    <span class="h-2 w-2 rounded-full bg-red-500"></span>

                    <span>
                        <b class="text-majorelle">LIVE NOW</b>:
                        <b>Global Electronic Arena — 18.4K tuned in</b>
                    </span>

                </span>

                <!-- MAIN TITLE -->
                <div
                    class="w-full rounded-[28px] border border-white/60 bg-white/40 backdrop-blur-md px-6 sm:px-12 py-8 lg:py-10 [@media(max-height:760px)]:py-5 shadow-lg shadow-majorelle/10">

                    <h1 class="text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">

                        Find Your Next

                        <span class="text-sapphire">
                            Unforgettable
                        </span>

                        Event

                    </h1>

                </div>

                <!-- SEARCH BAR -->
                <form action="<?= $e($halaman_event) ?>" method="get" class="w-full max-w-[420px]" role="search">

                    <label for="q" class="sr-only">
                        Cari event
                    </label>

                    <div class="relative">

                        <div class="absolute inset-y-0 start-0 flex items-center ps-5 pointer-events-none">

                            <svg class="w-6 h-6 text-meta-gray" aria-hidden="true" fill="none" viewBox="0 0 20 20">

                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />

                            </svg>

                        </div>

                        <input type="search" id="q" name="q" placeholder="Discover event, artist, or location..."
                            class="block w-full py-3.5 ps-14 pe-4 text-sm text-brand-ink placeholder:text-meta-gray bg-white/80 backdrop-blur-sm border-0 rounded-full shadow-sm focus:ring-2 focus:ring-majorelle focus:outline-none">

                    </div>
                </form>

                <!-- DESCRIPTION -->
                <div
                    class="w-full max-w-[420px] flex items-center gap-4 rounded-[22px] border border-white/60 bg-white/55 backdrop-blur-md px-5 py-4 text-left shadow-sm">

                    <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-slate-blue-200/80"
                        aria-hidden="true">

                        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linejoin="round">

                            <path
                                d="M4 9a2 2 0 0 0 0 4v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3a2 2 0 0 1 0-4V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v2z"
                                transform="rotate(-35 12 12)" />

                            <path d="M14 8.5l-.01.01M14 12l-.01.01M14 15.5l-.01.01" stroke-linecap="round"
                                transform="rotate(-35 12 12)" />

                        </svg>

                    </span>

                    <p class="text-sm leading-relaxed text-meta-gray">

                        Discover elite concert experiences, select prime seating in real-time, and purchase 100%
                        verified premium tickets seamlessly.

                    </p>

                </div>

                <!-- EXPLORE BUTTON -->
                <a href="<?= $e($halaman_event) ?>"
                    class="text-white bg-majorelle hover:bg-majorelle-700 focus:ring-4 focus:outline-none focus:ring-majorelle/30 font-semibold rounded-full text-base px-10 py-3.5 shadow-lg shadow-majorelle/30 transition hover:scale-105 active:scale-95">

                    Explore Events

                </a>

            </section>
        </main>

    </div>

    <!-- FLOWBITE SCRIPT -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

</body>

</html>