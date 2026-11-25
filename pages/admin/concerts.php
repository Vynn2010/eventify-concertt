<?php
require_once __DIR__ . "/../../config/database.php";
include "../../middleware/check_admin_login.php";
require_once __DIR__ . "/../../repo/booking.php";
require_once __DIR__ . "/../../repo/concert.php";
require_once __DIR__ . "/../../helpers/format.php";
require_once __DIR__ . "/../../repo/admin.php";
require_once __DIR__ . "/../../helpers/admin/concert_forms.php";
require_once __DIR__ . "/../../helpers/admin/concert_parts.php";

// ---------- DATA ----------
$q = trim($_GET['q'] ?? '');
$summary = Admin::getSummary($conn);
$concerts = Concert::searchForAdmin($conn, $q);

// Data form Add Concert dan modal Generate Seats
$has_genre_column = Admin::hasGenreColumn($conn);
$genre_options = $has_genre_column ? Admin::getOptions($conn) : [];
$concert_options = Booking::getConcertOptions($conn);
$categories = Booking::getCategoryOptions($conn);

// ---------- PESAN FLASH ----------
// form=seat -> pesan dari proses generate kursi; selain itu dari proses konser
$flash_status = $_GET['status'] ?? '';
$flash_msg = $_GET['msg'] ?? '';
$is_seat_flash = (($_GET['form'] ?? '') === 'seat');
$seat_total = (int) ($_GET['total'] ?? 0);
$seat_disabled = (int) ($_GET['disabled'] ?? 0);
$seat_concert = (int) ($_GET['concert_id'] ?? 0);
$flash_error = ($flash_status === 'error');

$admin_email = $_SESSION["email"] ?? "admin";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concerts - Admin Eventify</title>
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
        <?php $active_menu = 'concerts';
        include __DIR__ . "/../../components/admin/sidebar.php"; ?>

        <!-- KONTEN -->
        <main class="flex-1 min-w-0 p-6 md:p-10 flex flex-col gap-8">

            <header class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold">Concerts</h1>
                    <p class="text-sm opacity-65 mt-1">Welcome, <?= htmlspecialchars($admin_email) ?></p>
                </div>
                <button type="button" id="add-toggle"
                    class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold px-6 py-3 rounded-full">
                    + Add Concert
                </button>
            </header>

            <?php render_concert_flash($is_seat_flash, $flash_status, $flash_msg, $seat_total, $seat_disabled); ?>
            <?php render_add_concert_panel(!$is_seat_flash && $flash_error, $has_genre_column, $genre_options); ?>
            <?php render_concert_summary($summary); ?>

            <!-- TABEL KONSER -->
            <section class="bg-white rounded-2xl p-6 flex flex-col gap-4">
                <?php render_concert_search($q); ?>
                <?php render_concert_table($concerts, $q); ?>
            </section>

        </main>
    </div>

    <?php
    render_seat_modal($is_seat_flash && $flash_error, $flash_msg, $concert_options, $categories, $seat_concert);
    render_concert_scripts($is_seat_flash && $flash_error);
    ?>
</body>

</html>
<?php mysqli_close($conn); ?>