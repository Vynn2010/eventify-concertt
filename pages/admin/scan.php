<?php
require_once __DIR__ . "/../../config/database.php";
include "../../middleware/check_admin_login.php";
require_once __DIR__ . "/../../repo/booking.php";
require_once __DIR__ . "/../../helpers/admin/scan_parts.php";

$concerts = Booking::findTicket($conn);
$admin_email = $_SESSION["email"] ?? "admin";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Ticket - Admin Eventify</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
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
        <?php $active_menu = 'scan';
        include __DIR__ . "/../../components/admin/sidebar.php"; ?>

        <!-- KONTEN -->
        <main id="scan-page" data-endpoint="../../actions/admin/scan_process.php"
            class="flex-1 min-w-0 p-6 md:p-10 flex flex-col gap-8">

            <header class="flex items-center gap-4">
                <a href="booking.php" aria-label="Kembali ke booking"
                    class="shrink-0 w-11 h-11 rounded-full bg-[#5835FF] hover:bg-[#4D4FFF] text-white flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold">Scan Ticket</h1>
                    <p class="text-sm opacity-65 mt-1">Welcome, <?= htmlspecialchars($admin_email) ?></p>
                </div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                <?php render_scan_camera($concerts); ?>
                <?php render_scan_result(); ?>
            </div>
        </main>
    </div>

    <script src="../../assets/js/admin/scan.js"></script>
</body>

</html>