<?php

class Concert
{
    public static function searchForAdmin(mysqli $conn, string $q = ''): array
    {
        $sql = self::CreateConcertByAdmin();
        if ($q !== '') {
            $sql .= " WHERE c.title LIKE ? OR c.artist LIKE ? OR c.venue LIKE ?";
        }
        $sql .= " ORDER BY c.concert_date DESC";

        $stmt = mysqli_prepare($conn, $sql);
        if ($q !== '') {
            $like = "%$q%";
            mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
        }
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    }
    public static function findConcertNameById($conn)
    {
        $id = $_GET['id'];
        $sql = "SELECT * FROM concerts WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return $result;
    }

    public static function findConcertById(mysqli $conn, int $id): ?array
    {
        $stmt = mysqli_prepare($conn, "SELECT * FROM concerts WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt)) ?: null;
    }

    public static function CreateConcertByAdmin()
    {
        $sql = "SELECT c.id, c.title, c.artist, c.venue, c.concert_date, c.img, c.price, c.concert_status,
               (SELECT COUNT(*) FROM seats s WHERE s.concert_id = c.id) AS total_seats,
               (SELECT COUNT(*) FROM seats s WHERE s.concert_id = c.id AND s.seat_status = 'Available') AS free_seats,
               (SELECT COUNT(*) FROM tickets t WHERE t.concert_id = c.id) AS sold
        FROM concerts c";
        return $sql;
    }

    public static function createRecommendation($conn, $kolom)
    {
        $recommendation = take_concert($conn, "SELECT $kolom FROM concerts c
                                       WHERE c.concert_date >= NOW()
                                       ORDER BY c.concert_date ASC LIMIT 4");
        return $recommendation;
    }

    public static function createHots($conn, $kolom)
    {
        $hots = take_concert($conn, "SELECT $kolom, COUNT(s.id) AS terjual
                             FROM concerts c
                             LEFT JOIN seats s ON s.concert_id = c.id AND s.seat_status <> 'Available'
                             GROUP BY c.id
                             ORDER BY terjual DESC, c.concert_date ASC LIMIT 4");
        return $hots;
    }

    public static function createNear($conn, $kolom)
    {
        $near_list = take_concert($conn, "SELECT $kolom FROM concerts c
                                  WHERE c.concert_date >= NOW()
                                  ORDER BY c.concert_date ASC LIMIT 1");
        return $near_list;
    }
}
