<?php
require_once __DIR__ . "/../../config/database.php";
include "../../middleware/check_admin_login.php";
require_once __DIR__ . "/../../repo/admin.php";
require_once __DIR__ . "/../../helpers/admin/genre_parts.php";

$q = trim($_GET['q'] ?? '');
$data = Admin::getPageData($conn, $q);

$flash_status = $_GET['status'] ?? '';
$flash_msg = $_GET['msg'] ?? '';
$admin_email = $_SESSION["email"] ?? "admin";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Genres - Admin Eventify</title>
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
        <?php $active_menu = 'genres';
        include __DIR__ . "/../../components/admin/sidebar.php"; ?>

        <!-- KONTEN -->
        <main class="flex-1 min-w-0 p-6 md:p-10 flex flex-col gap-8">

            <header class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h1 class="text-3xl font-bold">Genres</h1>
                    <p class="text-sm opacity-65 mt-1">Welcome, <?= htmlspecialchars($admin_email) ?></p>
                </div>
                <a href="concerts.php"
                    class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold px-6 py-3 rounded-full">
                    View Concerts
                </a>
            </header>

            <?php render_genre_column_warning($data['has_genre_column']); ?>
            <?php render_genre_summary($data); ?>
            <?php render_flash_message($flash_status, $flash_msg); ?>
            <?php render_genre_add_form(); ?>
            <?php render_genre_table($data, $q); ?>

        </main>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>