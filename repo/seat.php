<?php
class Seat
{
    public static function findSelectedSeats($conn, $seat_ids, $concert_id)
    {
        $placeholders = implode(',', array_fill(0, count($seat_ids), '?'));
        $tipe = 'i' . str_repeat('i', count($seat_ids));
        $sql = "SELECT id, seat_number, price, seat_status FROM seats
                    WHERE concert_id = ? AND id IN ($placeholders)
                    ORDER BY LEFT(seat_number, 1), LENGTH(seat_number), seat_number";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, $tipe, $concert_id, ...$seat_ids);
        mysqli_stmt_execute($stmt);
        $hasil_kursi = mysqli_stmt_get_result($stmt);
        return $hasil_kursi;
    }

    public static function findAllUserSeat($conn, $id)
    {
        // ---------- KURSI (beserta nama kategori: VIP / Reguler / Tribune) ----------
        $stmt = mysqli_prepare($conn, "SELECT s.id, s.seat_number, s.category_id, s.price, s.seat_status,
                                COALESCE(ca.category_name, 'Umum') AS category_name
                                FROM seats s
                                LEFT JOIN categories ca ON ca.id = s.category_id
                                WHERE s.concert_id = ?
                                ORDER BY LEFT(s.seat_number, 1), LENGTH(s.seat_number), s.seat_number");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result_seats = mysqli_stmt_get_result($stmt);
        return $result_seats;
    }
}
?>