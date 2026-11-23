<?php
require_once __DIR__ . "/../../config/database.php";
// include "../../middleware/check_user_login.php";
require_once __DIR__ . "/../../actions/auth/session_init.php";
require_once __DIR__ . "/../../repo/user.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Switch account: akhiri sesi lalu kembali ke halaman login.
if (isset($_GET['switch'])) {
    $_SESSION = [];
    session_destroy();
    header("Location: loginpage_user.php");
    exit;
}

// Pastikan pengguna sudah login.
if (empty($_SESSION['user_id'])) {
    header("Location: loginpage_user.php");
    exit;
}
$user_id = (int) $_SESSION['user_id'];

// Batalkan tiket yang dipilih.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_tickets'])) {
    $ticket_ids = array_values(array_unique(array_filter(
        array_map('intval', (array) ($_POST['ticket_ids'] ?? [])),
        fn($id) => $id > 0
    )));

    if (empty($ticket_ids)) {
        header("Location: profile.php?cancel=none");
        exit;
    }

    $placeholders = implode(',', array_fill(0, count($ticket_ids), '?'));
    $types = 'i' . str_repeat('i', count($ticket_ids));

    try {
        UserProfile::findUserStatusTicket($conn, $placeholders, $types, $user_id, $ticket_ids);
        $cancelled_count = UserProfile::deleteUserStatusTicket(
            $conn,
            $placeholders,
            $types,
            $user_id,
            $ticket_ids
        );
        header("Location: profile.php?cancel=ok&n=" . (int) $cancelled_count);
    } catch (mysqli_sql_exception $e) {
        header("Location: profile.php?cancel=error");
    }
    exit;
}

$cancel_status = $_GET['cancel'] ?? '';
$cancelled_count = (int) ($_GET['n'] ?? 0);

function rupiah($n)
{
    return 'Rp ' . number_format((float) $n, 0, ',', '.');
}

// Ambil data pengguna tanpa mengambil password.
$user = UserProfile::findUserPassword($conn, $user_id);

if (!$user) {
    $_SESSION = [];
    session_destroy();
    header("Location: loginpage_user.php");
    exit;
}

$email = $user['email'];
$nama = ucfirst(strstr($email, '@', true) ?: $email);
$inisial = strtoupper(substr($nama, 0, 1));

// Ambil riwayat pembayaran/tiket pengguna.
$hasil = UserProfile::findUserHistory($conn, $user_id);
$history = [];
while ($row = mysqli_fetch_assoc($hasil)) {
    $history[] = $row;
}

$jumlah_tiket = count($history);
$event_unik = [];
foreach ($history as $item) {
    $event_unik[$item['title']] = true;
}
$jumlah_event = count($event_unik);

// Ambil foto konser dari tiket terbaru; jika tidak ada, gunakan konser terdekat.
$foto = null;
$judul_foto = '';
foreach ($history as $h) {
    if (!empty($h['img'])) {
        $foto = $h['img'];
        $judul_foto = $h['title'];
        break;
    }
}
if (!$foto) {
    $r = UserProfile::findUserProfile($conn);
    if ($r && ($c = mysqli_fetch_assoc($r))) {
        $foto = $c['img'];
        $judul_foto = $c['title'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Eventify</title>

    <!-- Tailwind v4 (layout). Jangan pasang flowbite.min.css -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style type="text/tailwindcss">
        @theme inline {
            --font-sans: "Outfit", ui-sans-serif, system-ui, sans-serif;
    --color-sapphire-50: var(--ds-sapphire-50);
    --color-sapphire-100: var(--ds-sapphire-100);
    --color-sapphire-200: var(--ds-sapphire-200);
    --color-sapphire-300: var(--ds-sapphire-300);
    --color-sapphire-400: var(--ds-sapphire-400);
    --color-sapphire-500: var(--ds-sapphire-500);
    --color-sapphire-600: var(--ds-sapphire-600);
    --color-sapphire-700: var(--ds-sapphire-700);
    --color-sapphire-800: var(--ds-sapphire-800);
    --color-sapphire-900: var(--ds-sapphire-900);
    --color-sapphire: var(--ds-sapphire);
    --color-majorelle-50: var(--ds-majorelle-50);
    --color-majorelle-100: var(--ds-majorelle-100);
    --color-majorelle-200: var(--ds-majorelle-200);
    --color-majorelle-300: var(--ds-majorelle-300);
    --color-majorelle-400: var(--ds-majorelle-400);
    --color-majorelle-500: var(--ds-majorelle-500);
    --color-majorelle-600: var(--ds-majorelle-600);
    --color-majorelle-700: var(--ds-majorelle-700);
    --color-majorelle-800: var(--ds-majorelle-800);
    --color-majorelle-900: var(--ds-majorelle-900);
    --color-majorelle: var(--ds-majorelle);
    --color-slate-blue-50: var(--ds-slate-blue-50);
    --color-slate-blue-100: var(--ds-slate-blue-100);
    --color-slate-blue-200: var(--ds-slate-blue-200);
    --color-slate-blue-300: var(--ds-slate-blue-300);
    --color-slate-blue-400: var(--ds-slate-blue-400);
    --color-slate-blue-500: var(--ds-slate-blue-500);
    --color-slate-blue-600: var(--ds-slate-blue-600);
    --color-slate-blue-700: var(--ds-slate-blue-700);
    --color-slate-blue-800: var(--ds-slate-blue-800);
    --color-slate-blue-900: var(--ds-slate-blue-900);
    --color-slate-blue: var(--ds-slate-blue);
    --color-pink-50: var(--ds-pink-50);
    --color-pink-100: var(--ds-pink-100);
    --color-pink-200: var(--ds-pink-200);
    --color-pink-300: var(--ds-pink-300);
    --color-pink-400: var(--ds-pink-400);
    --color-pink-500: var(--ds-pink-500);
    --color-pink-600: var(--ds-pink-600);
    --color-pink-700: var(--ds-pink-700);
    --color-pink: var(--ds-pink);
    --color-third-50: var(--ds-third-50);
    --color-third-100: var(--ds-third-100);
    --color-third-200: var(--ds-third-200);
    --color-third-300: var(--ds-third-300);
    --color-third-400: var(--ds-third-400);
    --color-third-500: var(--ds-third-500);
    --color-third-600: var(--ds-third-600);
    --color-third-700: var(--ds-third-700);
    --color-ghost: var(--ds-ghost);
    --color-brand-ink: var(--ds-brand-ink);
    --color-meta-gray: var(--ds-meta-gray);
    --color-card-white: var(--ds-card-white);
    --background-image-sidebar: linear-gradient(180deg, #1e2a78, var(--ds-sapphire-600));
    --background-image-avatar: linear-gradient(135deg, var(--ds-majorelle-500), var(--ds-sapphire-500));
    --background-image-thumb-cool: linear-gradient(135deg, #1e2a78, #4ac6c0);
    --background-image-thumb-pink: linear-gradient(135deg, var(--ds-majorelle-500), var(--ds-pink-500));
        }
    </style>

    <!-- Color system (file ini di pages/user/ → naik 2 folder) -->
    <link rel="stylesheet" href="../../style/design-system.css">
</head>

<body class="bg-ghost text-brand-ink font-sans min-h-screen antialiased">

    <!-- ===== SIDEBAR DESKTOP ===== -->
    <aside class="bg-sidebar hidden lg:flex fixed top-0 left-0 z-40 w-60 h-screen flex-col px-5 py-6">
        <a href="../../index.php" class="flex items-center gap-3 mb-10">
            <img src="../../assets/icon/logo.jpeg" alt="Logo Eventify"
                class="h-10 w-10 rounded-xl object-contain bg-white/70">
            <span class="text-2xl font-extrabold text-white">Eventify</span>
        </a>
        <ul class="space-y-2">
            <li><a href="../../index.php"
                    class="flex items-center gap-4 px-3 py-3 rounded-xl text-white text-lg font-semibold hover:bg-white/10 transition"><iconify-icon
                        icon="mdi:home" class="text-3xl"></iconify-icon>Home</a></li>
            <li><a href="about.php"
                    class="flex items-center gap-4 px-3 py-3 rounded-xl text-white text-lg font-semibold hover:bg-white/10 transition"><iconify-icon
                        icon="mdi:information-outline" class="text-3xl"></iconify-icon>About</a></li>
            <li><a href="event.php"
                    class="flex items-center gap-4 px-3 py-3 rounded-xl text-white text-lg font-semibold hover:bg-white/10 transition"><iconify-icon
                        icon="mdi:calendar-text" class="text-3xl"></iconify-icon>Event</a></li>
        </ul>
    </aside>

    <!-- ===== SIDEBAR MOBILE (Flowbite drawer) ===== -->
    <aside id="mobile-drawer" tabindex="-1"
        class="bg-sidebar lg:hidden fixed top-0 left-0 z-50 w-64 h-screen -translate-x-full transition-transform px-5 py-6">
        <div class="flex items-center justify-between mb-10">
            <span class="flex items-center gap-3">
                <img src="../../assets/icon/logo.jpeg" alt="Logo Eventify"
                    class="h-10 w-10 rounded-xl object-contain bg-white/70">
                <span class="text-2xl font-extrabold text-white">Eventify</span>
            </span>
            <button type="button" data-drawer-hide="mobile-drawer"
                class="text-white p-1 rounded-lg hover:bg-white/10"><iconify-icon icon="mdi:close"
                    class="text-2xl"></iconify-icon></button>
        </div>
        <ul class="space-y-2">
            <li><a href="../../index.php"
                    class="flex items-center gap-4 px-3 py-3 rounded-xl text-white text-lg font-semibold hover:bg-white/10"><iconify-icon
                        icon="mdi:home" class="text-3xl"></iconify-icon>Home</a></li>
            <li><a href="about.php"
                    class="flex items-center gap-4 px-3 py-3 rounded-xl text-white text-lg font-semibold hover:bg-white/10"><iconify-icon
                        icon="mdi:information-outline" class="text-3xl"></iconify-icon>About</a></li>
            <li><a href="event.php"
                    class="flex items-center gap-4 px-3 py-3 rounded-xl text-white text-lg font-semibold hover:bg-white/10"><iconify-icon
                        icon="mdi:calendar-text" class="text-3xl"></iconify-icon>Event</a></li>
        </ul>
    </aside>

    <!-- ===== KONTEN ===== -->
    <div class="lg:ml-60 min-h-screen flex flex-col">

        <!-- TOPBAR -->
        <header class="bg-ghost border-b border-third-300 shadow-sm sticky top-0 z-30">
            <div class="flex items-center gap-3 px-4 sm:px-8 py-3">
                <button type="button" data-drawer-target="mobile-drawer" data-drawer-toggle="mobile-drawer"
                    aria-controls="mobile-drawer" class="lg:hidden p-2 text-meta-gray rounded-lg hover:bg-third-100">
                    <span class="sr-only">Buka menu</span><iconify-icon icon="mdi:menu" class="text-2xl"></iconify-icon>
                </button>

                <form action="event.php" method="GET" class="flex-1 max-w-[14rem] sm:max-w-xs">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none"><iconify-icon
                                icon="mdi:magnify" class="text-xl text-meta-gray"></iconify-icon></span>
                        <input type="search" id="search" name="q" placeholder="Search event...."
                            class="block w-full py-2.5 pl-11 pr-4 bg-majorelle-100 text-brand-ink text-sm font-semibold rounded-xl border-0 outline-none placeholder:text-meta-gray focus:ring-2 focus:ring-majorelle">
                    </div>
                </form>

                <div class="ml-auto flex items-center gap-4">
                    <button type="button"
                        class="relative p-2 rounded-full bg-majorelle-100 text-brand-ink hover:bg-majorelle-200 transition">
                        <iconify-icon icon="mdi:bell-outline" class="text-xl"></iconify-icon>
                        <span class="absolute top-1 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <button type="button" id="user-menu-button" data-dropdown-toggle="user-dropdown"
                        data-dropdown-placement="bottom-end" class="flex items-center gap-2">
                        <span
                            class="bg-avatar text-white font-bold w-10 h-10 rounded-full flex items-center justify-center"><?= htmlspecialchars($inisial) ?></span>
                        <iconify-icon icon="mdi:chevron-down" class="text-xl text-meta-gray"></iconify-icon>
                    </button>
                    <div id="user-dropdown"
                        class="hidden z-50 w-56 bg-card-white divide-y divide-third-100 rounded-xl shadow-lg border border-third-100">
                        <div class="px-4 py-3">
                            <p class="text-sm font-semibold"><?= htmlspecialchars($nama) ?></p>
                            <p class="text-sm text-meta-gray truncate"><?= htmlspecialchars($email) ?></p>
                        </div>
                        <ul class="py-2 text-sm">
                            <li><a href="profile.php?switch=1"
                                    class="flex items-center gap-2 px-4 py-2 hover:bg-third-100"><iconify-icon
                                        icon="mdi:account-switch-outline" class="text-lg"></iconify-icon>Switch
                                    account</a></li>
                            <li><a href="../../actions/auth/logout.php"
                                    class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50"><iconify-icon
                                        icon="mdi:logout" class="text-lg"></iconify-icon>Log out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN -->
        <main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-8 py-6 sm:py-8">

            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold">My Profile</h1>
                    <p class="mt-3 text-sm font-semibold">Manage Your Account and preferences</p>
                </div>
                <a href="#"
                    class="inline-flex items-center gap-2 px-7 py-3 text-sm font-semibold text-white bg-majorelle hover:bg-majorelle-600 rounded-full shadow-lg shadow-majorelle/30 transition">
                    <iconify-icon icon="mdi:pencil" class="text-xl"></iconify-icon>Edit Profile
                </a>
            </div>

            <!-- KARTU PROFIL -->
            <section
                class="mt-6 bg-card-white rounded-3xl shadow-lg shadow-brand-ink/10 p-5 sm:p-7 flex flex-col md:flex-row md:items-center gap-6">
                <div class="flex items-center gap-5 flex-1 min-w-0">
                    <!-- Kalau ada foto: <img src="..." class="w-24 h-24 rounded-full object-cover ring-4 ring-majorelle-100"> -->
                    <div
                        class="bg-avatar text-white text-4xl font-bold w-24 h-24 shrink-0 rounded-full flex items-center justify-center ring-4 ring-majorelle-100">
                        <?= htmlspecialchars($inisial) ?>
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-xl font-bold truncate"><?= htmlspecialchars($nama) ?></h2>
                        <p class="text-sm font-bold break-all"><?= htmlspecialchars($email) ?></p>
                        <span
                            class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 text-[11px] font-semibold text-majorelle-700 bg-majorelle-100 border border-majorelle-200 rounded-full">
                            <iconify-icon icon="mdi:crown" class="text-base"></iconify-icon>Eventify Member
                        </span>
                    </div>
                </div>
                <div class="grid grid-cols-3 md:flex md:items-stretch text-center">
                    <div class="px-5 sm:px-7 flex flex-col items-center justify-center gap-1">
                        <iconify-icon icon="mdi:ticket-confirmation" class="text-3xl text-majorelle"></iconify-icon>
                        <span class="text-xl font-bold"><?= $jumlah_tiket ?></span><span
                            class="text-xs font-semibold">Tickets</span>
                    </div>
                    <div class="px-5 sm:px-7 flex flex-col items-center justify-center gap-1 border-l border-third-300">
                        <iconify-icon icon="mdi:calendar-check" class="text-3xl text-majorelle"></iconify-icon>
                        <span class="text-xl font-bold"><?= $jumlah_event ?></span><span
                            class="text-xs font-semibold">Event Joined</span>
                    </div>
                    <div class="px-5 sm:px-7 flex flex-col items-center justify-center gap-1 border-l border-third-300">
                        <iconify-icon icon="mdi:heart" class="text-3xl text-majorelle"></iconify-icon>
                        <span class="text-xl font-bold">—</span><span class="text-xs font-semibold">Like</span>
                    </div>
                </div>
            </section>

            <!-- TICKETS + SETTINGS -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-5 gap-6 items-start">

                <section
                    class="lg:col-span-3 bg-card-white rounded-3xl shadow-lg shadow-brand-ink/10 p-5 sm:p-6 flex flex-col">
                    <?php if ($cancel_status === 'ok'): ?>
                        <p class="mb-4 rounded-xl bg-green-100 px-4 py-3 text-sm text-green-800">
                            <?= $cancelled_count ?> tiket berhasil dibatalkan dan kursinya dikembalikan.
                        </p>
                    <?php elseif ($cancel_status === 'none'): ?>
                        <p class="mb-4 rounded-xl bg-yellow-100 px-4 py-3 text-sm text-yellow-800">
                            Pilih minimal satu tiket yang ingin dibatalkan.
                        </p>
                    <?php elseif ($cancel_status === 'error'): ?>
                        <p class="mb-4 rounded-xl bg-red-100 px-4 py-3 text-sm text-red-800">
                            Gagal membatalkan tiket. Tidak ada data yang diubah.
                        </p>
                    <?php endif; ?>
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <h3 class="flex items-center gap-3 text-lg font-bold"><iconify-icon
                                icon="mdi:ticket-confirmation" class="text-3xl text-majorelle"></iconify-icon>My Tickets
                        </h3>
                        <a href="tickets.php"
                            class="inline-flex items-center gap-2 text-xs font-semibold text-majorelle hover:text-majorelle-700">View
                            all<iconify-icon icon="mdi:arrow-right" class="text-xl"></iconify-icon></a>
                    </div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-meta-gray">Klik tiket untuk melihat QR-nya.</p>
                        <?php if (!empty($history)): ?>
                            <label class="text-xs font-semibold flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox" id="select-all" class="w-4 h-4 accent-majorelle">Select all
                            </label>
                        <?php endif; ?>
                    </div>

                    <form id="cancel-form" action="profile.php" method="POST"
                        class="max-h-72 overflow-y-auto pr-1 flex flex-col gap-3">
                        <?php if (empty($history)): ?>
                            <p class="py-6 text-center text-sm text-meta-gray">Belum ada riwayat tiket.</p>
                        <?php endif; ?>
                        <?php foreach ($history as $h):
                            $total = (float) $h['harga_konser'] + (float) $h['harga_kursi'];
                            $waktu = !empty($h['created_at'])
                                ? date('d M Y, H:i', strtotime($h['created_at']))
                                : '-';
                            $gambar_tiket = !empty($h['img']) ? $h['img'] : $foto;
                            ?>
                            <div
                                class="flex items-center bg-sapphire-50 hover:bg-sapphire-100 border border-sapphire-200 rounded-2xl transition">
                                <label class="pl-4 pr-2 self-stretch flex items-center cursor-pointer">
                                    <input type="checkbox" name="ticket_ids[]" value="<?= (int) $h['id'] ?>"
                                        class="ticket-check w-4 h-4 accent-majorelle shrink-0">
                                </label>
                                <a href="ticket.php?id=<?= (int) $h['id'] ?>" title="Lihat tiket dan QR"
                                    class="min-w-0 flex-1 flex items-center gap-4 py-3 pr-4">
                                    <?php if (!empty($gambar_tiket)): ?>
                                        <img src="<?= htmlspecialchars($gambar_tiket) ?>"
                                            alt="<?= htmlspecialchars($h['title']) ?>"
                                            class="w-24 h-16 sm:w-28 rounded-xl object-cover shrink-0">
                                    <?php else: ?>
                                        <div
                                            class="bg-thumb-cool w-24 h-16 sm:w-28 rounded-xl flex items-center justify-center shrink-0">
                                            <iconify-icon icon="mdi:ticket-confirmation"
                                                class="text-3xl text-white"></iconify-icon>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-extrabold leading-tight line-clamp-2">
                                            <?= htmlspecialchars($h['title']) ?>
                                        </p>
                                        <p class="mt-1.5 text-[11px] text-meta-gray">
                                            Kursi <?= htmlspecialchars($h['seat_number'] ?? '-') ?>
                                        </p>
                                        <p class="mt-1 text-[11px] text-meta-gray">
                                            <?= htmlspecialchars($waktu) ?>
                                        </p>
                                    </div>
                                    <p class="hidden sm:block text-sm font-bold shrink-0">
                                        <?= rupiah($total) ?>
                                    </p>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </form>
                    <?php if (!empty($history)): ?>
                        <button type="submit" form="cancel-form" id="cancel-btn" name="cancel_tickets" value="1" disabled
                            class="mt-4 w-full rounded-full bg-red-600 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50">
                            Cancel Selected Tickets
                        </button>
                    <?php endif; ?>

                </section>

                <section
                    class="lg:col-span-2 bg-card-white rounded-3xl shadow-lg shadow-brand-ink/10 p-5 sm:p-6 w-full">
                    <h3 class="flex items-center justify-center gap-3 text-lg font-bold mb-4"><iconify-icon
                            icon="mdi:cog" class="text-3xl text-majorelle"></iconify-icon>Account Settings</h3>
                    <ul class="text-sm font-semibold">
                        <li><a href="#"
                                class="flex items-center gap-3 py-3 hover:text-majorelle transition"><iconify-icon
                                    icon="mdi:account-outline" class="text-2xl"></iconify-icon><span
                                    class="flex-1">Privacy &amp; Security</span><iconify-icon icon="mdi:chevron-right"
                                    class="text-2xl"></iconify-icon></a></li>
                        <li><a href="#"
                                class="flex items-center gap-3 py-3 hover:text-majorelle transition"><iconify-icon
                                    icon="mdi:bell-cog-outline" class="text-2xl"></iconify-icon><span
                                    class="flex-1">Notification Preferences</span><iconify-icon icon="mdi:chevron-right"
                                    class="text-2xl"></iconify-icon></a></li>
                        <li><a href="profile.php?switch=1"
                                class="flex items-center gap-3 py-3 hover:text-majorelle transition"><iconify-icon
                                    icon="mdi:account-switch-outline" class="text-2xl"></iconify-icon><span
                                    class="flex-1">Switch Account</span><iconify-icon icon="mdi:chevron-right"
                                    class="text-2xl"></iconify-icon></a></li>
                    </ul>
                </section>
            </div>

            <!-- HELP + LOGOUT -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-5 gap-6 items-center">
                <section
                    class="lg:col-span-3 bg-card-white rounded-3xl shadow-lg shadow-brand-ink/10 px-5 sm:px-6 py-4 flex items-center gap-4 flex-wrap sm:flex-nowrap">
                    <iconify-icon icon="mdi:face-agent" class="text-5xl text-majorelle shrink-0"></iconify-icon>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-bold leading-tight">Need Help?</h3>
                        <p class="text-xs font-semibold text-meta-gray">Have questions? We're here to help!</p>
                    </div>
                    <a href="#"
                        class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-majorelle border border-majorelle rounded-full hover:bg-majorelle-50 transition">Contact
                        Support<iconify-icon icon="mdi:arrow-right" class="text-lg"></iconify-icon></a>
                </section>
                <a href="../../actions/auth/logout.php"
                    class="lg:col-span-2 inline-flex items-center justify-center gap-3 py-3 text-lg font-bold text-red-600 bg-red-100 hover:bg-red-200 border border-red-300 rounded-full transition">
                    <iconify-icon icon="mdi:logout-variant" class="text-2xl"></iconify-icon>Log Out
                </a>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <script>
        const selectAll = document.getElementById('select-all');
        const checks = [...document.querySelectorAll('.ticket-check')];
        const cancelBtn = document.getElementById('cancel-btn');

        const refreshSelection = () => {
            const selectedCount = checks.filter(check => check.checked).length;

            if (cancelBtn) {
                cancelBtn.disabled = selectedCount === 0;
            }

            if (selectAll) {
                selectAll.checked = checks.length > 0 && selectedCount === checks.length;
                selectAll.indeterminate = selectedCount > 0 && selectedCount < checks.length;
            }
        };

        if (selectAll) {
            selectAll.addEventListener('change', () => {
                checks.forEach(check => {
                    check.checked = selectAll.checked;
                });
                refreshSelection();
            });
        }

        checks.forEach(check => {
            check.addEventListener('change', refreshSelection);
        });

        refreshSelection();
    </script>
</body>

</html>
<?php mysqli_close($conn); ?>