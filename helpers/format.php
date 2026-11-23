<?php
if (!function_exists('rupiah')) {
    function rupiah($n): string
    {
        return 'Rp ' . number_format((float) $n, 0, ',', '.');
    }
}

if (!function_exists('concert_status_badge')) {
    function concert_status_badge(array $c): array
    {
        $is_past = !empty($c['concert_date']) && strtotime($c['concert_date']) < time();
        $status = trim((string) ($c['concert_status'] ?? ''));
        return [
            'label' => $status !== '' ? $status : ($is_past ? 'Finished' : 'Upcoming'),
            'is_past' => $is_past,
        ];
    }
}

if (!function_exists('ticket_code')) {
    function ticket_code(int $id): string
    {
        return 'TKT-' . str_pad((string) $id, 5, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime(?string $value): string
    {
        return !empty($value) ? date('d M Y, H:i', strtotime($value)) : '-';
    }
}

if (!function_exists('build_page_url')) {
    // URL halaman dengan filter aktif, lalu timpa sebagian parameter
    function build_page_url(string $base, array $override): string
    {
        $params = array_merge($_GET, $override);
        $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
        return $base . ($params ? '?' . http_build_query($params) : '');
    }
}

if (!function_exists('category_style')) {
    // Warna ring & dot kursi berdasarkan nama kategori
    function category_style(string $name): array
    {
        static $styles = [
        'vip' => ['ring' => 'ring-2 ring-inset ring-amber-400', 'dot' => 'bg-amber-400'],
        'reguler' => ['ring' => 'ring-2 ring-inset ring-sky-400', 'dot' => 'bg-sky-400'],
        'regular' => ['ring' => 'ring-2 ring-inset ring-sky-400', 'dot' => 'bg-sky-400'],
        'tribune' => ['ring' => 'ring-2 ring-inset ring-emerald-400', 'dot' => 'bg-emerald-400'],
        ];
        static $default = ['ring' => 'ring-2 ring-inset ring-gray-400', 'dot' => 'bg-gray-400'];

        return $styles[strtolower(trim($name))] ?? $default;
    }
}

if (!function_exists('group_seats')) {
    function group_seats($result_seats): array
    {
        $rows = [];
        $categories_present = [];

        while ($s = mysqli_fetch_assoc($result_seats)) {
            $rows[strtoupper(substr($s['seat_number'], 0, 1))][] = $s;

            $name = $s['category_name'];
            $price = (float) $s['price'];
            if (!isset($categories_present[$name]) || $price < $categories_present[$name]) {
                $categories_present[$name] = $price;
            }
        }

        return [$rows, $categories_present];
    }
}