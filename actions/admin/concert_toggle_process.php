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
$new_status = (($_POST['new_status'] ?? '') === 'active') ? 'active' : 'non-active';

if ($concert_id <= 0) {
    back_to_concerts('error', 'Konser tidak ditemukan.');
}

// Konser yang tanggalnya sudah lewat berstatus Finished dan tidak bisa diubah
$stmt = mysqli_prepare($conn, "SELECT concert_date FROM concerts WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $concert_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $concert_date);
$found = mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

if (!$found) {
    back_to_concerts('error', 'Konser tidak ditemukan.');
}
if (!empty($concert_date) && strtotime($concert_date) < time()) {
    back_to_concerts('error', 'Konser sudah selesai, statusnya tidak bisa diubah.');
}

$stmt = mysqli_prepare($conn, "UPDATE concerts SET concert_status = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $new_status, $concert_id);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!$ok) {
    back_to_concerts('error', 'Gagal mengubah status konser.');
}

back_to_concerts(
    'ok',
    $new_status === 'active' ? 'Konser berhasil diaktifkan.' : 'Konser berhasil dinonaktifkan.'
);