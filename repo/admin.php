<?php
class admin
{
    public static function Creategenres($conn, $has_genre_column, $q)
    {

        if ($has_genre_column) {
            $sql = "SELECT g.id, g.genre_name,
                        (SELECT COUNT(*) FROM concerts c WHERE c.genre_id = g.id) AS concert_count,
                        (SELECT COUNT(*) FROM concerts c WHERE c.genre_id = g.id AND c.concert_date >= CURDATE()) AS upcoming_count,
                        (SELECT COUNT(*) FROM tickets t JOIN concerts c ON c.id = t.concert_id WHERE c.genre_id = g.id) AS sold_count
                    FROM genres g";
        } else {
            $sql = "SELECT g.id, g.genre_name, 0 AS concert_count, 0 AS upcoming_count, 0 AS sold_count FROM genres g";
        }
        if ($q !== '') {
            $sql .= " WHERE g.genre_name LIKE ?";
        }
        $sql .= " ORDER BY g.genre_name";

        $stmt = mysqli_prepare($conn, $sql);
        if ($q !== '') {
            $like = "%$q%";
            mysqli_stmt_bind_param($stmt, "s", $like);
        }
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $genres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $genres[] = $row;
        }
        return $genres;
    }

    public static function hasGenreColumn(mysqli $conn): bool
    {
        $res = mysqli_query($conn, "SHOW COLUMNS FROM concerts LIKE 'genre_id'");
        return $res && mysqli_num_rows($res) > 0;
    }

    public static function getOptions(mysqli $conn): array
    {
        $res = mysqli_query($conn, "SELECT id, genre_name FROM genres ORDER BY genre_name");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    // Ambil satu angka dari query COUNT(*)
    private static function countRows(mysqli $conn, string $sql): int
    {
        $res = mysqli_query($conn, $sql);
        $row = $res ? mysqli_fetch_row($res) : null;
        return (int) ($row[0] ?? 0);
    }

    // Semua data untuk halaman admin Genres (daftar genre + ringkasan)
    public static function getPageData(mysqli $conn, string $q = ''): array
    {
        $has_genre_column = self::hasGenreColumn($conn);
        $genres = self::Creategenres($conn, $has_genre_column, $q);

        $genres_in_use = 0;
        $top_genre = '-';
        $max_concerts = 0;
        foreach ($genres as $g) {
            $n = (int) $g['concert_count'];
            if ($n > 0) {
                $genres_in_use++;
            }
            if ($n > $max_concerts) {
                $max_concerts = $n;
                $top_genre = $g['genre_name'];
            }
        }

        // Ringkasan "genre dipakai" dan "terbanyak" dihitung dari semua genre, bukan hasil pencarian
        if ($q !== '' && $has_genre_column) {
            $genres_in_use = self::countRows($conn, "SELECT COUNT(DISTINCT genre_id) FROM concerts WHERE genre_id IS NOT NULL");
            $res = mysqli_query($conn, "SELECT g.genre_name FROM genres g JOIN concerts c ON c.genre_id = g.id
                                        GROUP BY g.id ORDER BY COUNT(*) DESC LIMIT 1");
            $top_row = $res ? mysqli_fetch_assoc($res) : null;
            $top_genre = $top_row['genre_name'] ?? '-';
        }

        return [
            'has_genre_column' => $has_genre_column,
            'genres' => $genres,
            'total_genres' => self::countRows($conn, "SELECT COUNT(*) FROM genres"),
            'total_concerts' => self::countRows($conn, "SELECT COUNT(*) FROM concerts"),
            'genres_in_use' => $genres_in_use,
            'top_genre' => $top_genre,
            'max_concerts' => $max_concerts,
        ];
    }

    public static function getSummary(mysqli $conn): array
    {
        $sql = "SELECT
                    (SELECT COUNT(*) FROM concerts) AS total_concerts,
                    (SELECT COUNT(*) FROM concerts WHERE concert_date >= CURDATE()) AS upcoming_concerts,
                    (SELECT COUNT(*) FROM seats WHERE seat_status = 'Available') AS available_seats,
                    (SELECT COUNT(*) FROM tickets) AS tickets_sold,
                    (SELECT COUNT(*) FROM tickets WHERE status = 'used') AS tickets_scanned";

        $row = mysqli_fetch_assoc(mysqli_query($conn, $sql)) ?: [];

        return [
            'total_concerts' => (int) ($row['total_concerts'] ?? 0),
            'upcoming_concerts' => (int) ($row['upcoming_concerts'] ?? 0),
            'available_seats' => (int) ($row['available_seats'] ?? 0),
            'tickets_sold' => (int) ($row['tickets_sold'] ?? 0),
            'tickets_scanned' => (int) ($row['tickets_scanned'] ?? 0),
        ];
    }

    public static function findAdminAccount($conn, $email, $password)
    {
        // Pastikan nama kolom sesuai tabel users kamu (role atau roles)
        $sql = mysqli_prepare($conn, "SELECT * FROM users WHERE email=? AND passwords=? AND role='admin'");
        mysqli_stmt_bind_param($sql, "ss", $email, $password);
        mysqli_stmt_execute($sql);

        return $sql;

    }
}

?>