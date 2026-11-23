<?php

class Booking
{
    public static function createBooking($conn, $concert_id)
    {
        $stmt = mysqli_prepare($conn, "SELECT id, title, artist, venue, concert_date, img, price FROM concerts WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $concert_id);
        mysqli_stmt_execute($stmt);
        $konser = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        return $konser;
    }

    public static function prepareCheckout($conn, int $concert_id, array $seat_ids): array
    {
        $konser = self::createBooking($conn, $concert_id);
        $kursi = [];
        $semua_tersedia = false;
        $subtotal_kursi = 0.0;

        if ($konser) {
            $hasil_kursi = Seat::findSelectedSeats($conn, $seat_ids, $concert_id);
            while ($k = mysqli_fetch_assoc($hasil_kursi)) {
                $kursi[] = $k;
            }

            // Semua kursi harus ada dan masih Available
            $semua_tersedia = count($kursi) === count($seat_ids);
            foreach ($kursi as $k) {
                if (strcasecmp(trim($k['seat_status']), 'Available') !== 0) {
                    $semua_tersedia = false;
                }
                $subtotal_kursi += (float) $k['price'];
            }
        }

        $harga_konser = $konser ? (float) $konser['price'] : 0.0;   // harga tiket konser per kursi
        $jumlah_kursi = count($kursi);
        $subtotal_konser = $harga_konser * $jumlah_kursi;

        return [
            'konser' => $konser,
            'kursi' => $kursi,
            'semua_tersedia' => $semua_tersedia,
            'harga_konser' => $harga_konser,
            'jumlah_kursi' => $jumlah_kursi,
            'subtotal_konser' => $subtotal_konser,
            'subtotal_kursi' => $subtotal_kursi,
            'total' => $subtotal_konser + $subtotal_kursi,
        ];
    }

    public static function createBookingTotal($conn)
    {
        $sql = "SELECT COUNT(*) FROM tickets";
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($res);
        return (float) ($row[0] ?? 0);

    }

    public static function searchBookings(mysqli $conn, string $q, string $status, int $page, int $per_page = 15): array
    {
        $from_sql = "FROM tickets t
                    JOIN users u    ON u.id = t.user_id
                    JOIN concerts c ON c.id = t.concert_id
                    JOIN seats s    ON s.id = t.seat_id
                    LEFT JOIN categories cat ON cat.id = s.category_id";

        $where = [];
        $types = '';
        $params = [];

        if ($q !== '') {
            $where[] = "(u.email LIKE ? OR c.title LIKE ? OR s.seat_number LIKE ?)";
            $types .= 'sss';
            $like = "%$q%";
            array_push($params, $like, $like, $like);
        }
        if ($status !== '') {
            $where[] = "t.status = ?";
            $types .= 's';
            $params[] = $status;
        }
        $where_sql = $where ? " WHERE " . implode(' AND ', $where) : '';

        // Jumlah hasil (untuk pagination)
        $stmt = mysqli_prepare($conn, "SELECT COUNT(*) $from_sql $where_sql");
        if ($params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $total = (int) (mysqli_fetch_row(mysqli_stmt_get_result($stmt))[0] ?? 0);

        $total_pages = max(1, (int) ceil($total / $per_page));
        $page = min(max(1, $page), $total_pages);
        $offset = ($page - 1) * $per_page;

        // Data pesanan
        $stmt = mysqli_prepare($conn, self::findOrderData($from_sql, $where_sql, $per_page, $offset));
        if ($params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        mysqli_stmt_execute($stmt);
        $rows = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);

        return [
            'rows' => $rows,
            'total' => $total,
            'total_pages' => $total_pages,
            'page' => $page,
            'offset' => $offset,
        ];
    }

    public static function getBookingPageData(mysqli $conn, string $q, string $status_filter, int $page): array
    {
        $revenue_sql = self::findOrderSummary();
        $ticket_counts = self::countTicketsByStatus($conn);
        $result = self::searchBookings($conn, $q, $status_filter, $page);

        return [
            'total_bookings' => self::createBookingTotal($conn),
            'total_revenue' => self::createRevenueTotal($conn, $revenue_sql),
            'valid_tickets' => $ticket_counts['valid'],
            'used_tickets' => $ticket_counts['used'],
            'bookings' => $result['rows'],
            'filtered_total' => $result['total'],
            'total_pages' => $result['total_pages'],
            'page' => $result['page'],
            'showing_from' => $result['total'] ? $result['offset'] + 1 : 0,
            'showing_to' => $result['offset'] + count($result['rows']),
        ];
    }

    public static function findAllUserConcert($conn, $id)
    {
        // ---------- KONSER ----------
        $stmt = mysqli_prepare($conn, "SELECT id, title, artist, venue, concert_date FROM concerts WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $konser = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        return $konser;
    }

    public static function findAllUserTicket($conn, $ticket_id, $user_id)
    {
        $sql = "SELECT t.id, t.qr_token, t.status, s.seat_number,
                c.title, c.artist, c.venue, c.concert_date, c.img
            FROM tickets t
            JOIN seats s    ON s.id = t.seat_id
            JOIN concerts c ON c.id = t.concert_id
            WHERE t.user_id = ?";
        if ($ticket_id > 0) {
            $sql .= " AND t.id = ?";
        }
        $sql .= " ORDER BY t.id DESC";

        $stmt = mysqli_prepare($conn, $sql);
        if ($ticket_id > 0) {
            mysqli_stmt_bind_param($stmt, "ii", $user_id, $ticket_id);
        } else {
            mysqli_stmt_bind_param($stmt, "i", $user_id);
        }
        mysqli_stmt_execute($stmt);

        $hasil = mysqli_stmt_get_result($stmt);
        return $hasil;
    }

    public static function findTicket($conn)
    {
        // Pilihan konser (opsional) agar tiket konser lain ditolak
        $concerts = mysqli_query($conn, "SELECT id, title, concert_date FROM concerts ORDER BY concert_date DESC");
        return $concerts;
    }

    public static function findUserTicketfromAdmin($conn)
    {
        // ---------- 5 TIKET TERBARU ----------
        $latest_tickets = mysqli_query(
            $conn,
            "SELECT t.id, t.status, c.title, s.seat_number
            FROM tickets t
            JOIN concerts c ON c.id = t.concert_id
            JOIN seats s    ON s.id = t.seat_id
            ORDER BY t.id DESC
            LIMIT 5"
        );
        return $latest_tickets;
    }

    public static function findOrderSummary()
    {
        $revenue_sql = "SELECT COALESCE(SUM(c.price + s.price), 0)
                FROM tickets t
                JOIN concerts c ON c.id = t.concert_id
                JOIN seats s    ON s.id = t.seat_id";
        return $revenue_sql;
    }

    public static function findOrderData($from_sql, $where_sql, $per_page, $offset)
    {
        $sql = "SELECT t.id, t.status, t.created_at, u.email, c.title, s.seat_number,
               s.price AS seat_price, c.price AS concert_price, cat.category_name
        $from_sql $where_sql
        ORDER BY t.id DESC
        LIMIT $per_page OFFSET $offset";
        return $sql;
    }

    

    // Helper: ambil satu angka dari query tanpa parameter
    private static function scalar(mysqli $conn, string $sql): float
    {
        $res = mysqli_query($conn, $sql);
        $row = mysqli_fetch_row($res);
        return (float) ($row[0] ?? 0);
    }

    public static function createRevenueTotal($conn, $revenue_sql)
    {
        return self::scalar($conn, $revenue_sql);
    }

    public static function countTicketsByStatus(mysqli $conn): array
    {
        $sql = "SELECT
                    (SELECT COUNT(*) FROM tickets WHERE status = 'valid') AS valid_tickets,
                    (SELECT COUNT(*) FROM tickets WHERE status = 'used')  AS used_tickets";
        $row = mysqli_fetch_assoc(mysqli_query($conn, $sql)) ?: [];
        return [
            'valid' => (int) ($row['valid_tickets'] ?? 0),
            'used' => (int) ($row['used_tickets'] ?? 0),
        ];
    }

    
    

    public static function getConcertOptions(mysqli $conn): array
    {
        $res = mysqli_query($conn, "SELECT id, title FROM concerts ORDER BY id DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function getCategoryOptions(mysqli $conn): array
    {
        $res = mysqli_query($conn, "SELECT id, category_name FROM categories ORDER BY id");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

}
