<?php
require_once __DIR__ . "/../../config/database.php";
include "../../middleware/check_admin_login.php";
require_once __DIR__ . "/../../repo/booking.php";
require_once __DIR__ . "/../../helpers/admin/booking_parts.php";

// ---------- FILTER ----------
$q = trim($_GET['q'] ?? '');
$status_filter = $_GET['status'] ?? '';
if (!in_array($status_filter, ['valid', 'used'], true)) {
    $status_filter = '';
}
$page = max(1, (int) ($_GET['page'] ?? 1));

// ---------- DATA (ringkasan + daftar pesanan) ----------
$data = Booking::getBookingPageData($conn, $q, $status_filter, $page);
$admin_email = $_SESSION["email"] ?? "admin";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking - Admin Eventify</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&display=swap">
    <style>
        body {
            font-family: 'Sora', sans-serif;
        }
    </style>
</head>

<body class="bg-[#F4F3FE] text-[#17143D]">
    <div class="flex flex-col md:flex-row min-h-screen">

        <!-- SIDEBAR -->
        <?php $active_menu = 'booking';
        include __DIR__ . "/../../components/admin/sidebar.php"; ?>

        <!-- KONTEN -->
        <main class="flex-1 min-w-0 p-6 md:p-10 flex flex-col gap-8">

            <header class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold">Booking</h1>
                    <p class="text-sm opacity-65 mt-1">Welcome, <?= htmlspecialchars($admin_email) ?></p>
                </div>
                <a href="scan.php"
                    class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold px-6 py-3 rounded-full">
                    Scan Ticket
                </a>
            </header>

            <?php render_booking_summary($data); ?>

            <!-- TABEL PESANAN -->
            <section class="bg-white rounded-2xl p-6 flex flex-col gap-4">
                <?php render_booking_filter($q, $status_filter); ?>
                <?php render_booking_table($data['bookings'], $q, $status_filter); ?>
                <?php render_booking_pagination($data); ?>
            </section>

        </main>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>