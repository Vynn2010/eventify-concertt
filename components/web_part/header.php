<?php
// components/landing/header.php
$root = "/";
$base = $root . "pages/user/";
$logo = $root . "assets/icon/logo.jpeg";

$is_logged_in = !empty($_SESSION["user_id"]);
$display_name = $_SESSION["nama"] ?? (isset($_SESSION["email"]) ? explode("@", $_SESSION["email"])[0] : "");
$current_page = basename($_SERVER['PHP_SELF'] ?? '');

$nav_items = [
    'Home' => 'home_page_user.php',
    'About' => 'about.php',
    'Event' => 'event.php',
];
?>

<header class="bg-white border-b border-slate-200 p-2">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center justify-between h-16">

            <!-- Logo -->
            <a href="<?= $base ?>mainpage.php" class="inline-flex items-center gap-2.5">
                <img src="../../assets/icon/logo.jpg" alt="Eventify" class="h-9 w-auto">
                <span class="text-3xl transition font-bold text-slate-800">Eventify</span>
            </a>

            <!-- Menu tengah -->
            <ul class="hidden md:flex items-center gap-8 text-sm">
                <?php foreach ($nav_items as $label => $file): ?>
                    <li>
                        <a href="<?= $base . $file ?>"
                            class="text-2xl transition <?= $current_page === $file ? 'text-indigo-600 font-semibold' : 'text-slate-800 hover:text-indigo-800' ?>">
                            <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Kanan: auth + hamburger -->
            <div class="flex items-center gap-2">

                <?php if ($is_logged_in): ?>

                    <a href="<?= $base ?>profile.php"
                        class="max-w-[16rem] truncate bg-indigo-600 text-white text-2xl font-semibold rounded-full px-5 py-2.5 transition hover:bg-indigo-700">
                        <?= htmlspecialchars($display_name ?: 'Profile') ?>
                    </a>

                <?php elseif (empty($hide_auth)): ?>

                    <a href="<?= $base ?>loginpage_user.php"
                        class="hidden sm:inline-block text-slate-700 text-base font-semibold rounded-full px-5 py-3 transition hover:bg-slate-100 whitespace-nowrap">
                        Login
                    </a>

                    <a href="<?= $base ?>registerpage.php"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-base font-semibold rounded-full px-6 py-3 transition">
                        Register
                    </a>

                <?php endif; ?>

                <!-- Hamburger tetap dipertahankan -->
                <button id="menuBtn" type="button" aria-label="Menu"
                    class="md:hidden rounded-full w-10 h-10 flex items-center justify-center text-slate-700 hover:bg-slate-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>

            </div>
        </nav>

        <!-- Dropdown mobile -->
        <div id="mobileMenu" class="hidden md:hidden pb-3">
            <ul class="border border-slate-200 rounded-x1 overflow-hidden text-sm text-slate-700">
                <?php foreach ($nav_items as $label => $file): ?>
                    <li>
                        <a href="<?= $base . $file ?>"
                            class="block px-5 py-3 hover:bg-indigo-50 <?= $current_page === $file ? 'text-indigo-600 font-semibold' : '' ?>">
                            <?= $label ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if (!$is_logged_in): ?>
                    <li>
                        <a href="<?= $base ?>loginpage_user.php" class="block px-5 py-3 hover:bg-indigo-50">
                            Login
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</header>

<script>
    document.getElementById('menuBtn').addEventListener('click', function () {
        document.getElementById('mobileMenu').classList.toggle('hidden');
    });
</script>