<?php
function render_genre_column_warning(bool $has_genre_column): void
{
    if ($has_genre_column)
        return;
    ?>
    <div class="bg-amber-100 text-amber-800 text-sm rounded-xl px-4 py-3">
        Kolom <code>genre_id</code> tidak ditemukan di tabel <code>concerts</code>, jadi jumlah konser
        per genre belum bisa ditampilkan. Daftar genre tetap tampil di bawah.
    </div>
    <?php
}

/** Pesan sukses / error dari proses tambah atau hapus genre. */
function render_flash_message(string $status, string $msg): void
{
    if ($status === 'ok'): ?>
        <div class="bg-green-100 text-green-800 text-sm rounded-xl px-4 py-3"><?= htmlspecialchars($msg) ?></div>
    <?php elseif ($status === 'error'): ?>
        <div class="bg-red-100 text-red-800 text-sm rounded-xl px-4 py-3"><?= htmlspecialchars($msg) ?></div>
    <?php endif;
}

/** Empat kartu ringkasan di atas tabel. */
function render_genre_summary(array $data): void
{
    $has = $data['has_genre_column'];
    ?>
    <section class="grid gap-4 grid-cols-1 sm:grid-cols-2 xl:grid-cols-4">
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Total genres</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $data['total_genres'] ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Total concerts</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $data['total_concerts'] ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Genres in use</div>
            <div class="text-3xl font-bold text-[#5835FF] mt-2"><?= $has ? $data['genres_in_use'] : '-' ?></div>
        </div>
        <div class="bg-white rounded-2xl p-6">
            <div class="text-sm opacity-65">Most concerts</div>
            <div class="text-2xl xl:text-3xl font-bold text-[#5835FF] mt-2 truncate">
                <?= $has ? htmlspecialchars($data['top_genre']) : '-' ?>
            </div>
        </div>
    </section>
    <?php
}

/** Form tambah genre. */
function render_genre_add_form(): void
{
    ?>
    <section class="bg-white rounded-2xl p-6">
        <h2 class="text-lg font-semibold mb-3">Add genre</h2>
        <form action="../../actions/admin/genre_add_process.php" method="POST" class="flex flex-wrap items-center gap-2">
            <input type="text" name="genre_name" required maxlength="50" placeholder="e.g. K-Pop"
                class="w-64 bg-[#F4F3FE] text-sm rounded-full px-5 py-2.5 outline-none focus:ring-2 focus:ring-[#5835FF]">
            <button type="submit"
                class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold rounded-full px-6 py-2.5">Add
                genre</button>
        </form>
    </section>
    <?php
}

/** Satu baris tabel genre (termasuk tombol Delete). */
function render_genre_row(array $g, bool $has_genre_column, int $max_concerts): void
{
    $count = (int) $g['concert_count'];
    $share = $max_concerts > 0 ? round($count / $max_concerts * 100) : 0;
    ?>
    <tr class="border-b border-[#17143D]/10 last:border-0">
        <td class="py-3 pr-3 opacity-60"><?= (int) $g['id'] ?></td>

        <td class="py-3 pr-3 font-semibold"><?= htmlspecialchars($g['genre_name']) ?></td>

        <td class="py-3 pr-3">
            <?php if ($has_genre_column): ?>
                <div class="flex items-center gap-3">
                    <span class="w-6"><?= $count ?></span>
                    <div class="hidden sm:block w-28 h-2 rounded-full bg-[#F4F3FE] overflow-hidden">
                        <div class="h-2 rounded-full bg-[#5835FF]" style="width: <?= $share ?>%"></div>
                    </div>
                </div>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>

        <td class="py-3 pr-3"><?= $has_genre_column ? (int) $g['upcoming_count'] : '-' ?></td>
        <td class="py-3 pr-3"><?= $has_genre_column ? (int) $g['sold_count'] : '-' ?></td>

        <td class="py-3 pr-3">
            <a href="../user/home_page_user.php?genre=<?= (int) $g['id'] ?>" target="_blank"
                class="text-[#5835FF] underline">View</a>
        </td>

        <!-- Hapus genre (diblokir kalau masih dipakai konser) -->
        <td class="py-3">
            <div class="flex justify-end">
                <form action="../../actions/admin/genre_delete_process.php" method="POST"
                    onsubmit="return confirm('Hapus genre &quot;<?= htmlspecialchars(addslashes($g['genre_name']), ENT_QUOTES) ?>&quot;? Tindakan ini tidak bisa dibatalkan.');">
                    <input type="hidden" name="genre_id" value="<?= (int) $g['id'] ?>">
                    <button type="submit" <?= ($has_genre_column && $count > 0) ? 'disabled title="Tidak bisa dihapus: genre masih dipakai konser."' : '' ?>
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

/** Kartu tabel "All genres": pencarian, tabel, dan jumlah data. */
function render_genre_table(array $data, string $q): void
{
    $genres = $data['genres'];
    ?>
    <section class="bg-white rounded-2xl p-6 flex flex-col gap-4">

        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-semibold">All genres</h2>

            <form action="genres.php" method="GET" class="flex items-center gap-2">
                <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search genre"
                    class="w-48 bg-[#F4F3FE] text-sm rounded-full px-5 py-2.5 outline-none focus:ring-2 focus:ring-[#5835FF]">
                <button type="submit"
                    class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold rounded-full px-5 py-2.5">Search</button>
                <?php if ($q !== ''): ?>
                    <a href="genres.php" class="text-sm underline opacity-70 hover:opacity-100">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-sm text-left">
                <thead>
                    <tr class="border-b border-[#17143D]/15 opacity-60">
                        <th class="py-2 pr-3 font-semibold">ID</th>
                        <th class="py-2 pr-3 font-semibold">Genre</th>
                        <th class="py-2 pr-3 font-semibold">Concerts</th>
                        <th class="py-2 pr-3 font-semibold">Upcoming</th>
                        <th class="py-2 pr-3 font-semibold">Tickets sold</th>
                        <th class="py-2 pr-3 font-semibold">On website</th>
                        <th class="py-2 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($genres)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center opacity-60">
                                <?= $q !== '' ? 'Tidak ada genre yang cocok dengan pencarian.' : 'Belum ada data genre.' ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($genres as $g): ?>
                            <?php render_genre_row($g, $data['has_genre_column'], $data['max_concerts']); ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <p class="text-sm opacity-65">Showing <?= count($genres) ?> of <?= $data['total_genres'] ?> genres</p>
    </section>
    <?php
}