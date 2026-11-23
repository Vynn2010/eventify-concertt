<?php
require_once __DIR__ . "/../../config/database.php";
include __DIR__ . "/../../middleware/check_admin_login.php";

function back_to_genres(string $status, string $msg): void
{
    header("Location: ../../pages/admin/genres.php?status=" . $status . "&msg=" . urlencode($msg));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    back_to_genres('error', 'Request tidak valid.');
}

$genre_id = (int) ($_POST['genre_id'] ?? 0);
if ($genre_id <= 0) {
    back_to_genres('error', 'Genre tidak ditemukan.');
}

// 1. Blokir kalau masih dipakai konser (kalau kolom genre_id ada di tabel concerts)
$res = mysqli_query($conn, "SHOW COLUMNS FROM concerts LIKE 'genre_id'");
if ($res && mysqli_num_rows($res) > 0) {
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM concerts WHERE genre_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $genre_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $concert_count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ((int) $concert_count > 0) {
        back_to_genres('error', 'Genre tidak bisa dihapus karena masih dipakai ' . (int) $concert_count . ' konser.');
    }
}

// 2. Hapus genre
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$deleted = 0;

try {
    $stmt = mysqli_prepare($conn, "DELETE FROM genres WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $genre_id);
    mysqli_stmt_execute($stmt);
    $deleted = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
} catch (Throwable $e) {
    back_to_genres('error', 'Gagal menghapus genre. Data lain mungkin masih terhubung ke genre ini.');
}

if ($deleted < 1) {
    back_to_genres('error', 'Genre tidak ditemukan.');
}