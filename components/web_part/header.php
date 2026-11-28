<?php

$base = "";
$asset = "../../assets/";
$logo = $asset . "icon/logo.jpeg";

$is_logged_in = !empty($_SESSION["user_id"]);

$display_name = $_SESSION["nama"]
    ?? (isset($_SESSION["email"])
        ? explode("@", $_SESSION["email"])[0]
        : "");

$current_page = basename($_SERVER["PHP_SELF"] ?? "");

$nav_items = [
    "Home" => "index.php",
    "About" => "about.php",
    "Event" => "event.php",
];

$search_pages = [
    "index.php",
    "event.php"
];

$show_search = in_array($current_page, $search_pages);

$search_action = $current_page;

$search_value = htmlspecialchars(
    $_GET["q"] ?? "",
    ENT_QUOTES,
    "UTF-8"
);
?>

<header class="bg-white border-b border-slate-200 p-2">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">

        <nav class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="<?= $base ?>index.php" class="inline-flex items-center gap-2.5">

                <img src="<?= $logo ?>" alt="Eventify" class="h-11 w-auto">

                <span class="text-3xl transition font-bold text-slate-800">
                    Eventify
                </span>

            </a>

            <!-- Menu Tengah -->
            <ul class="hidden md:flex items-center gap-8 text-sm">

                <?php foreach ($nav_items as $label => $file): ?>

                    <li>
                        <a href="<?= $base . $file ?>" class="text-2xl transition <?= $current_page === $file
                                ? 'text-indigo-600 font-semibold'
                                : 'text-slate-800 hover:text-indigo-800' ?>">

                            <?= $label ?>

                        </a>
                    </li>

                <?php endforeach; ?>

            </ul>

            <!-- Kanan: Search + Account / Auth -->
            <div class="flex items-center gap-3">

                <?php if ($is_logged_in): ?>

                    <!-- Search Bar -->
                    <?php if ($show_search): ?>

                        <form action="<?= $search_action ?>" method="GET"
                            class="hidden sm:flex items-center gap-2 bg-slate-100 border border-slate-300 rounded-full px-4 py-2.5 w-52 lg:w-64">

                            <button type="submit" aria-label="Search">

                                <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">

                                    <circle cx="11" cy="11" r="7" />
                                    <path d="m20 20-4-4" />

                                </svg>

                            </button>

                            <input type="text" name="q" placeholder="Cari event..." value="<?= $search_value ?>"
                                class="w-full bg-transparent outline-none text-sm">

                        </form>

                    <?php endif; ?>

                    <!-- Account -->
                    <a href="<?= $base ?>profile.php"
                        class="flex items-center gap-2 bg-indigo-950 hover:bg-indigo-900 text-white text-base font-semibold rounded-full px-5 py-2.5 transition whitespace-nowrap">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">

                            <circle cx="12" cy="8" r="4" />
                            <path d="M5 21v-2a7 7 0 0 1 14 0v2" />

                        </svg>

                        <?= htmlspecialchars($display_name ?: "Account", ENT_QUOTES, "UTF-8") ?>

                    </a>

                <?php else: ?>

                    <!-- Login -->
                    <a href="<?= $base ?>loginpage_user.php"
                        class="hidden sm:inline-block text-slate-700 text-base font-semibold rounded-full px-5 py-3 transition hover:bg-slate-100 whitespace-nowrap">

                        Login

                    </a>

                    <!-- Register -->
                    <a href="<?= $base ?>registerpage.php"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-base font-semibold rounded-full px-6 py-3 transition">

                        Register

                    </a>

                <?php endif; ?>

                <!-- Hamburger -->
                <button id="menuBtn" type="button" aria-label="Menu" aria-expanded="false"
                    class="md:hidden rounded-full w-10 h-10 flex items-center justify-center text-slate-700 hover:bg-slate-100 transition">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">

                        <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />

                    </svg>

                </button>

            </div>

        </nav>

        <!-- Dropdown Mobile -->
        <div id="mobileMenu" class="hidden md:hidden pb-3">

            <ul class="border border-slate-200 rounded-xl overflow-hidden text-sm text-slate-700">

                <?php foreach ($nav_items as $label => $file): ?>

                    <li>
                        <a href="<?= $base . $file ?>" class="block px-5 py-3 hover:bg-indigo-50 <?= $current_page === $file
                                ? 'text-indigo-600 font-semibold'
                                : '' ?>">

                            <?= $label ?>

                        </a>
                    </li>

                <?php endforeach; ?>

                <?php if ($is_logged_in): ?>

                    <?php if ($show_search): ?>

                        <li class="p-3">

                            <form action="<?= $search_action ?>" method="GET"
                                class="flex items-center gap-2 bg-slate-100 border border-slate-300 rounded-full px-4 py-2.5">

                                <input type="text" name="q" placeholder="Cari event..." value="<?= $search_value ?>"
                                    class="w-full bg-transparent outline-none text-sm">

                                <button type="submit" aria-label="Search">

                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">

                                        <circle cx="11" cy="11" r="7" />
                                        <path d="m20 20-4-4" />

                                    </svg>

                                </button>

                            </form>

                        </li>

                    <?php endif; ?>

                    <li>
                        <a href="<?= $base ?>profile.php" class="block px-5 py-3 hover:bg-indigo-50">

                            Account

                        </a>
                    </li>

                <?php else: ?>

                    <li>
                        <a href="<?= $base ?>loginpage_user.php" class="block px-5 py-3 hover:bg-indigo-50">

                            Login

                        </a>
                    </li>

                    <li>
                        <a href="<?= $base ?>registerpage.php" class="block px-5 py-3 hover:bg-indigo-50">

                            Register

                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>

    </div>
</header>

<script>
    const menuBtn = document.getElementById("menuBtn");
    const mobileMenu = document.getElementById("mobileMenu");

    if (menuBtn && mobileMenu) {

        menuBtn.addEventListener("click", function () {

            mobileMenu.classList.toggle("hidden");

            const isOpen = !mobileMenu.classList.contains("hidden");

            menuBtn.setAttribute("aria-expanded", isOpen);

        });

    }
</script>