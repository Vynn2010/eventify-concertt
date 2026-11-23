<?php
require_once __DIR__ . "/../../config/database.php";
include __DIR__ . "/../../middleware/check_admin_login.php";

function back_to_concerts(string $status, string $msg): void
{
    header("Location: ../../pages/admin/concerts.php?status=" . $status . "&msg=" . urlencode($msg));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    back_to_concerts('error', 'Request tidak valid.');
}

$concert_id = (int) ($_POST['concert_id'] ?? 0);
if ($concert_id <= 0) {
    back_to_concerts('error', 'Konser tidak ditemukan.');
}

// 1. Blokir kalau sudah ada tiket terjual
$stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM tickets WHERE concert_id = ?");
mysqli_stmt_bind_param($stmt, "i", $concert_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $ticket_count);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if ((int) $ticket_count > 0) {
    back_to_concerts('error', 'Konser tidak bisa dihapus karena sudah ada tiket terjual. Nonaktifkan saja.');
}

// 2. Hapus kursi lalu konser dalam satu transaksi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$deleted = 0;

try {
    mysqli_begin_transaction($conn);

    $stmt = mysqli_prepare($conn, "DELETE FROM seats WHERE concert_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $concert_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $stmt = mysqli_prepare($conn, "DELETE FROM concerts WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $concert_id);
    mysqli_stmt_execute($stmt);
    $deleted = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);
} catch (Throwable $e) {
    mysqli_rollback($conn);
    back_to_concerts('error', 'Gagal menghapus konser. Data lain mungkin masih terhubung ke konser ini.');
}

if ($deleted < 1) {
    back_to_concerts('error', 'Konser tidak ditemukan.');
}

back_to_concerts('ok', 'Konser berhasil dihapus.');