<?php
// helpers/admin/booking_parts.php
// Potongan tampilan untuk halaman admin Booking. Dipanggil dari pages/admin/booking.php.

if (!function_exists('rupiah')) {
    function rupiah($n)
    {
        return 'Rp ' . number_format((float) $n, 0, ',', '.');
    }
}

// Buat URL halaman ini dengan filter aktif, lalu timpa sebagian parameter
function build_page_url(array $override): string
{
    $params = array_merge($_GET, $override);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return 'booking.php' . ($params ? '?' . http_build_query($params) : '');
}

/** Empat kartu ringkasan di atas tabel. */
function render_booking_summary(array $d): void
{
    ?>
    <section class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Total bookings</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $d['total_bookings'] ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Total revenue</div>
            <div class="text-2xl xl:text-3xl font-bold text-[#5835FF] mt-2"><?= rupiah($d['total_revenue']) ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Valid (not scanned)</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $d['valid_tickets'] ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Used (scanned)</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $d['used_tickets'] ?></div>
        </div>
    </section>
    <?php
}

/** Judul tabel + form pencarian dan filter status. */
function render_booking_filter(string $q, string $status_filter): void
{
    ?>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg font-semibold">All bookings</h2>

        <form action="booking.php" method="GET" class="flex flex-wrap items-center gap-2">
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search email, concert, seat"
                class="w-56 bg-[#F4F3FE] text-sm rounded-full px-5 py-2.5 outline-none focus:ring-2 focus:ring-[#5835FF]">
            <select name="status"
                class="bg-[#F4F3FE] text-sm rounded-full px-4 py-2.5 outline-none focus:ring-2 focus:ring-[#5835FF]">
                <option value="">All status</option>
                <option value="valid" <?= $status_filter === 'valid' ? 'selected' : '' ?>>Valid</option>
                <option value="used" <?= $status_filter === 'used' ? 'selected' : '' ?>>Used</option>
            </select>
            <button type="submit"
                class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold rounded-full px-5 py-2.5">Filter</button>
            <?php if ($q !== '' || $status_filter !== ''): ?>
                <a href="booking.php" class="text-sm underline opacity-70 hover:opacity-100">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    <?php
}

/** Satu baris tabel pesanan. */
function render_booking_row(array $b): void
{
    $total = (float) $b['concert_price'] + (float) $b['seat_price'];
    $is_used = $b['status'] === 'used';
    ?>
    <tr class="border-b border-[#17143D]/10 last:border-0">
        <td class="py-3 pr-3 whitespace-nowrap font-semibold">
            TKT-<?= str_pad($b['id'], 5, '0', STR_PAD_LEFT) ?>
        </td>

        <td class="py-3 pr-3 break-all"><?= htmlspecialchars($b['email']) ?></td>

        <td class="py-3 pr-3"><?= htmlspecialchars($b['title']) ?></td>

        <td class="py-3 pr-3 whitespace-nowrap">
            <?= htmlspecialchars($b['seat_number']) ?>
            <?php if (!empty($b['category_name'])): ?>
                <span class="text-xs opacity-60">· <?= htmlspecialchars($b['category_name']) ?></span>
            <?php endif; ?>
        </td>

        <td class="py-3 pr-3 whitespace-nowrap"><?= rupiah($total) ?></td>

        <td class="py-3 pr-3 whitespace-nowrap">
            <?= !empty($b['created_at']) ? date('d M Y, H:i', strtotime($b['created_at'])) : '-' ?>
        </td>

        <td class="py-3">
            <span
                class="inline-block rounded-full px-3 py-1 text-xs font-semibold whitespace-nowrap
                                             <?= $is_used ? 'bg-gray-100 text-gray-600' : 'bg-[#5835FF]/10 text-[#5835FF]' ?>">
                <?= $is_used ? 'Used' : 'Valid' ?>
            </span>
        </td>
    </tr>
    <?php
}

/** Tabel pesanan (atau pesan kosong). */
function render_booking_table(array $bookings, string $q, string $status_filter): void
{
    ?>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm text-left">
            <thead>
                <tr class="border-b border-[#17143D]/15 opacity-60">
                    <th class="py-2 pr-3 font-semibold">Booking</th>
                    <th class="py-2 pr-3 font-semibold">Customer</th>
                    <th class="py-2 pr-3 font-semibold">Concert</th>
                    <th class="py-2 pr-3 font-semibold">Seat</th>
                    <th class="py-2 pr-3 font-semibold">Total</th>
                    <th class="py-2 pr-3 font-semibold">Booked at</th>
                    <th class="py-2 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="7" class="py-8 text-center opacity-60">
                            <?= ($q !== '' || $status_filter !== '') ? 'Tidak ada pesanan yang cocok dengan filter.' : 'Belum ada pesanan.' ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $b): ?>
                        <?php render_booking_row($b); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

/** Keterangan "Showing x–y of z" dan tombol Prev/Next. */
function render_booking_pagination(array $d): void
{
    $page = $d['page'];
    $total_pages = $d['total_pages'];
    ?>
    <div class="flex flex-wrap items-center justify-between gap-3 text-sm">
        <p class="opacity-65">
            Showing <?= $d['showing_from'] ?>–<?= $d['showing_to'] ?> of <?= $d['filtered_total'] ?> bookings
        </p>

        <?php if ($total_pages > 1): ?>
            <div class="flex items-center gap-2">
                <?php if ($page > 1): ?>
                    <a href="<?= htmlspecialchars(build_page_url(['page' => $page - 1])) ?>"
                        class="px-4 py-2 rounded-full bg-[#F4F3FE] hover:bg-[#8F9BFB]/30">Prev</a>
                <?php endif; ?>
                <span class="px-2 opacity-65">Page <?= $page ?> of <?= $total_pages ?></span>
                <?php if ($page < $total_pages): ?>
                    <a href="<?= htmlspecialchars(build_page_url(['page' => $page + 1])) ?>"
                        class="px-4 py-2 rounded-full bg-[#F4F3FE] hover:bg-[#8F9BFB]/30">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php
}