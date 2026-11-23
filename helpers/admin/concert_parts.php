<?php
/** Pesan sukses / error dari proses tambah konser, hapus, ubah status, atau generate kursi. */
function render_concert_flash(bool $is_seat_flash, string $status, string $msg, int $seat_total, int $seat_disabled): void
{
    if (!$is_seat_flash && $status === 'ok'): ?>
        <div class="bg-green-100 text-green-800 text-sm rounded-xl px-4 py-3"><?= htmlspecialchars($msg) ?></div>
    <?php elseif (!$is_seat_flash && $status === 'error'): ?>
        <div class="bg-red-100 text-red-800 text-sm rounded-xl px-4 py-3"><?= htmlspecialchars($msg) ?></div>
    <?php elseif ($is_seat_flash && $status === 'ok'): ?>
        <div class="bg-green-100 text-green-800 text-sm rounded-xl px-4 py-3">
            Berhasil membuat <?= $seat_total ?> kursi<?= $seat_disabled > 0 ? " ($seat_disabled di antaranya disabled)" : "" ?>.
        </div>
    <?php endif;
}

/** Empat kartu ringkasan di atas tabel. */
function render_concert_summary(array $summary): void
{
    ?>
    <section class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Total concerts</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $summary['total_concerts'] ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Upcoming concerts</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $summary['upcoming_concerts'] ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Seats available</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $summary['available_seats'] ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Tickets sold</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $summary['tickets_sold'] ?></div>
        </div>
    </section>
    <?php
}

/** Judul tabel + form pencarian. */
function render_concert_search(string $q): void
{
    ?>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h2 class="text-lg font-semibold">All concerts</h2>

        <form action="concerts.php" method="GET" class="flex items-center gap-2">
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search title, artist, venue"
                class="w-56 bg-[#F4F3FE] text-sm rounded-full px-5 py-2.5 outline-none focus:ring-2 focus:ring-[#5835FF]">
            <button type="submit"
                class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold rounded-full px-5 py-2.5">Search</button>
            <?php if ($q !== ''): ?>
                <a href="concerts.php" class="text-sm underline opacity-70 hover:opacity-100">Reset</a>
            <?php endif; ?>
        </form>
    </div>
    <?php
}

/** Satu baris tabel konser: poster, kursi, status, dan tombol aksi. */
function render_concert_row(array $c): void
{
    $is_active = strtolower(trim($c['concert_status'] ?? '')) === 'active';
    $is_finished = !empty($c['concert_date']) && date('Y-m-d', strtotime($c['concert_date'])) < date('Y-m-d');
    $has_sold = (int) $c['sold'] > 0;

    if ($is_finished) {
        $status_label = 'Finished';
        $status_class = 'bg-[#17143D]/10 text-[#17143D]/70';
    } elseif ($is_active) {
        $status_label = 'Active';
        $status_class = 'bg-[#5835FF]/10 text-[#5835FF]';
    } else {
        $status_label = 'Non-active';
        $status_class = 'bg-gray-100 text-gray-600';
    }
    ?>
    <tr class="border-b border-[#17143D]/10 last:border-0 align-middle">

        <!-- Poster + judul + artis -->
        <td class="py-3 pr-3">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 shrink-0 rounded-xl overflow-hidden bg-[#8F9BFB]/30">
                    <?php if (!empty($c['img'])): ?>
                        <img src="<?= htmlspecialchars($c['img']) ?>" alt="" class="w-full h-full object-cover object-top">
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold truncate"><?= htmlspecialchars($c['title']) ?></p>
                    <p class="text-xs opacity-65 truncate"><?= htmlspecialchars($c['artist']) ?></p>
                </div>
            </div>
        </td>

        <td class="py-3 pr-3"><?= htmlspecialchars($c['venue']) ?></td>

        <td class="py-3 pr-3 whitespace-nowrap">
            <?= !empty($c['concert_date']) ? date('d M Y', strtotime($c['concert_date'])) : '-' ?>
        </td>

        <td class="py-3 pr-3 whitespace-nowrap"><?= rupiah($c['price']) ?></td>

        <!-- Kolom kursi: tombol membuka modal Generate Seats -->
        <td class="py-3 pr-3 whitespace-nowrap">
            <div class="flex items-baseline leading-6">
                <?php if ((int) $c['total_seats'] === 0): ?>
                    <button type="button" class="seat-open text-[#5835FF] underline"
                        data-concert-id="<?= (int) $c['id'] ?>">Generate</button>
                <?php else: ?>
                    <span class="w-16 tabular-nums"><?= (int) $c['free_seats'] ?> / <?= (int) $c['total_seats'] ?></span>
                    <button type="button" class="seat-open text-[#5835FF] underline" data-concert-id="<?= (int) $c['id'] ?>">+
                        Add</button>
                <?php endif; ?>
            </div>
        </td>

        <td class="py-3 pr-3 leading-6"><?= (int) $c['sold'] ?></td>

        <td class="py-3 pr-3">
            <span class="inline-block rounded-full px-3 py-1 text-xs font-semibold whitespace-nowrap <?= $status_class ?>">
                <?= $status_label ?>
            </span>
        </td>

        <td class="py-3">
            <div class="flex items-center justify-end gap-2">
                <!-- Aktifkan / nonaktifkan (tidak berlaku untuk konser yang sudah selesai) -->
                <?php if (!$is_finished): ?>
                    <form action="../../actions/admin/concert_toggle_process.php" method="POST">
                        <input type="hidden" name="concert_id" value="<?= (int) $c['id'] ?>">
                        <input type="hidden" name="new_status" value="<?= $is_active ? 'non-active' : 'active' ?>">
                        <button type="submit"
                            class="w-24 text-center text-xs font-semibold rounded-full py-1.5 border border-[#5835FF]/30 text-[#5835FF] hover:bg-[#5835FF]/10 whitespace-nowrap">
                            <?= $is_active ? 'Deactivate' : 'Activate' ?>
                        </button>
                    </form>
                <?php endif; ?>

                <!-- Hapus (diblokir kalau sudah ada tiket terjual) -->
                <form action="../../actions/admin/concert_delete_process.php" method="POST"
                    onsubmit="return confirm('Hapus konser &quot;<?= htmlspecialchars(addslashes($c['title']), ENT_QUOTES) ?>&quot; beserta semua kursinya? Tindakan ini tidak bisa dibatalkan.');">
                    <input type="hidden" name="concert_id" value="<?= (int) $c['id'] ?>">
                    <button type="submit" <?= $has_sold ? 'disabled title="Tidak bisa dihapus: sudah ada tiket terjual. Nonaktifkan saja."' : '' ?>
                        class="text-xs font-semibold rounded-full px-4 py-1.5 border border-red-300 text-red-600 hover:bg-red-50 whitespace-nowrap
                                                       disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:bg-transparent">
                        Delete
                    </button>
                </form>
            </div>
        </td>
    </tr>
    <?php
}

/** Tabel konser (atau pesan kosong). */
function render_concert_table(array $concerts, string $q): void
{
    ?>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[1100px] text-sm text-left">
            <thead>
                <tr class="border-b border-[#17143D]/15 opacity-60">
                    <th class="py-2 pr-3 font-semibold">Concert</th>
                    <th class="py-2 pr-3 font-semibold">Venue</th>
                    <th class="py-2 pr-3 font-semibold">Date</th>
                    <th class="py-2 pr-3 font-semibold">Price</th>
                    <th class="py-2 pr-3 font-semibold">Seats (free / total)</th>
                    <th class="py-2 pr-3 font-semibold">Sold</th>
                    <th class="py-2 pr-3 font-semibold">Status</th>
                    <th class="py-2 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($concerts)): ?>
                    <tr>
                        <td colspan="8" class="py-8 text-center opacity-60">
                            <?= $q !== '' ? 'Tidak ada konser yang cocok dengan pencarian.' : 'Belum ada data konser.' ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($concerts as $c): ?>
                        <?php render_concert_row($c); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}