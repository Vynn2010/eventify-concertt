<?php
require_once __DIR__ . "/../../config/database.php";
include "../../middleware/check_admin_login.php";
require_once __DIR__ . "/../../repo/booking.php";
require_once __DIR__ . "/../../repo/admin.php";
require_once __DIR__ . "/../../helpers/format.php";


// ---------- RINGKASAN (sesuaikan nama tabel/kolom jika berbeda) ----------
$summary = Admin::getSummary($conn);
$active_concerts = $summary['upcoming_concerts'];
$available_seats = $summary['available_seats'];
$tickets_sold = $summary['tickets_sold'];
$tickets_scanned = $summary['tickets_scanned'];

$latest_tickets = Booking::findUserTicketfromAdmin($conn);

$admin_email = $_SESSION["email"] ?? "admin";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Eventify</title>
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
        <?php $active_menu = 'home';
        include __DIR__ . "/../../components/admin/sidebar.php"; ?>

        <!-- KONTEN -->
        <main class="flex-1 min-w-0 p-6 md:p-10 flex flex-col gap-8">
            <header class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold">Dashboard</h1>
                    <p class="text-sm opacity-65 mt-1">Welcome, <?= htmlspecialchars($admin_email) ?></p>
                </div>
            </header>

            <!-- KARTU RINGKASAN -->
            <section class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
                <div class="bg-white rounded-2xl p-6">
                    <div class="text-sm opacity-65">Active concerts</div>
                    <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $active_concerts ?></div>
                </div>
                <div class="bg-white rounded-2xl p-6">
                    <div class="text-sm opacity-65">Seats available</div>
                    <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $available_seats ?></div>
                </div>
                <div class="bg-white rounded-2xl p-6">
                    <div class="text-sm opacity-65">Tickets sold</div>
                    <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $tickets_sold ?></div>
                </div>
                <div class="bg-white rounded-2xl p-6">
                    <div class="text-sm opacity-65">Tickets scanned</div>
                    <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $tickets_scanned ?></div>
                </div>
            </section>

            <!-- TIKET TERBARU -->
            <section class="bg-white rounded-2xl p-6 overflow-x-auto">
                <h2 class="text-lg font-semibold mb-4">Latest tickets</h2>

                <table class="w-full min-w-[560px] text-sm text-left">
                    <thead>
                        <tr class="border-b border-[#17143D]/15 opacity-60">
                            <th class="py-2 font-semibold">Ticket code</th>
                            <th class="py-2 font-semibold">Concert</th>
                            <th class="py-2 font-semibold">Seat</th>
                            <th class="py-2 font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($latest_tickets) === 0): ?>
                            <tr>
                                <td colspan="4" class="py-6 text-center opacity-60">Belum ada tiket terjual.</td>
                            </tr>
                        <?php else: ?>
                            <?php while ($t = mysqli_fetch_assoc($latest_tickets)): ?>
                                <tr class="border-b border-[#17143D]/10 last:border-0">
                                    <td class="py-3"><?= ticket_code((int) $t['id']) ?></td>
                                    <td class="py-3"><?= htmlspecialchars($t['title']) ?></td>
                                    <td class="py-3"><?= htmlspecialchars($t['seat_number']) ?></td>
                                    <td class="py-3">
                                        <?php if ($t['status'] === 'used'): ?>
                                            <span class="text-green-700">Scanned</span>
                                        <?php else: ?>
                                            <span class="text-amber-700">Not scanned</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

        </main>
    </div>
</body>

</html>



