<?php
// helpers/user/home_parts.php
// Potongan tampilan halaman "Find your genres" (desain Figma).

if (!function_exists('rupiah')) {
    function rupiah($n)
    {
        return 'Rp ' . number_format((float) $n, 0, ',', '.');
    }
}

// Buat URL halaman ini dengan filter yang sedang aktif, lalu timpa sebagian parameter
function build_filter_url(array $override): string
{
    $params = array_merge($_GET, $override);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null && $v !== '0' && $v !== 0);
    return 'home_page_user.php' . ($params ? '?' . http_build_query($params) : '');
}

/** Ikon SVG kecil (pin, calendar, search). */
function home_icon(string $name, string $class = 'w-4 h-4'): string
{
    $open = '<svg class="' . htmlspecialchars($class) . '" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">';
    switch ($name) {
        case 'fire':
            return '<svg class="' . htmlspecialchars($class) . '" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.963 2.286a.75.75 0 0 0-1.071-.136 9.742 9.742 0 0 0-3.539 6.176 7.547 7.547 0 0 1-1.705-1.715.75.75 0 0 0-1.152-.082A9 9 0 1 0 15.68 4.534a7.46 7.46 0 0 1-2.717-2.248ZM15.75 14.25a3.75 3.75 0 1 1-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 0 1 1.925-3.546 3.75 3.75 0 0 1 3.255 3.718Z"/></svg>';
        case 'star':
            return '<svg class="' . htmlspecialchars($class) . '" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005Z"/></svg>';
        case 'heart':
            return '<svg class="' . htmlspecialchars($class) . '" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"/></svg>';
        case 'pin':
            return $open . '<path d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>';
        case 'calendar':
            return $open . '<path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>';
        case 'search':
            return $open . '<path d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>';
    }
    return '';
}

/** Judul "Find your genres" + kolom search di kanan (filter lain ikut terbawa lewat input hidden). */
function render_home_title_search(string $q): void
{
    ?>
    <section class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">Find your genres</h1>

        <form action="home_page_user.php" method="GET" class="relative w-full sm:w-72">
            <?php foreach (['genre', 'min_date', 'max_date', 'location'] as $key): ?>
                <?php if (!empty($_GET[$key])): ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($_GET[$key]) ?>">
                <?php endif; ?>
            <?php endforeach; ?>
            <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari event..."
                class="w-full bg-white text-sm rounded-full pl-5 pr-11 py-2.5 border border-indigo-300 outline-none focus:ring-2 focus:ring-indigo-400 placeholder-slate-400">
            <button type="submit" aria-label="Cari" class="absolute right-4 top-1/2 -translate-y-1/2 text-indigo-600">
                <?= home_icon('search', 'w-5 h-5') ?>
            </button>
        </form>
    </section>
    <?php
}

/** Deretan pill genre (All Genre + tiap genre). */
function render_genre_pills(array $genres, int $genre): void
{
    $on = 'bg-indigo-600 text-white border-indigo-600';
    $off = 'bg-white text-indigo-700 border-indigo-300 hover:bg-indigo-50';
    ?>
    <section class="flex flex-wrap gap-2">
        <a href="<?= htmlspecialchars(build_filter_url(['genre' => ''])) ?>"
            class="text-xs font-medium rounded-full px-5 py-1.5 border transition <?= $genre === 0 ? $on : $off ?>">All
            Genre</a>
        <?php foreach ($genres as $g): ?>
            <a href="<?= htmlspecialchars(build_filter_url(['genre' => (int) $g['id']])) ?>"
                class="text-xs font-medium rounded-full px-5 py-1.5 border transition <?= $genre === (int) $g['id'] ? $on : $off ?>">
                <?= htmlspecialchars($g['genre_name']) ?>
            </a>
        <?php endforeach; ?>
    </section>
    <?php
}

/** Satu kartu konser: poster + chip genre, judul, lokasi, tanggal, harga, sisa kursi. */
function render_home_concert_card(array $c): void
{
    $left = isset($c['seats_left']) ? (int) $c['seats_left'] : null;
    ?>
    <a href="concertdetail.php?id=<?= (int) $c['id'] ?>"
        class="group bg-[#111018] rounded-2xl overflow-hidden flex flex-col hover:-translate-y-1 hover:shadow-xl hover:shadow-indigo-300/40 transition">
        <div class="relative aspect-[6/5] overflow-hidden bg-[#2a2740]">
            <?php if (!empty($c['img'])): ?>
                <img src="<?= htmlspecialchars($c['img']) ?>" alt="<?= htmlspecialchars($c['title']) ?>"
                    class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-300">
            <?php endif; ?>
            <?php if (!empty($c['genre_name'])): ?>
                <span
                    class="absolute bottom-2 left-2 text-[10px] font-semibold bg-white/90 text-indigo-700 rounded-full px-2.5 py-0.5">
                    <?= htmlspecialchars($c['genre_name']) ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="p-3 sm:p-4 flex flex-col gap-1.5 flex-grow">
            <p class="font-bold text-white text-sm uppercase leading-snug line-clamp-2 min-h-[2.5rem]">
                <?= htmlspecialchars($c['title']) ?></p>
            <p class="text-[11px] text-gray-300 truncate flex items-center gap-1.5">
                <?= home_icon('pin', 'w-3.5 h-3.5 shrink-0 text-indigo-400') ?>    <?= htmlspecialchars($c['venue']) ?>
            </p>
            <p class="text-[11px] text-gray-300 flex items-center gap-1.5">
                <?= home_icon('calendar', 'w-3.5 h-3.5 shrink-0 text-indigo-400') ?>
                <?= !empty($c['concert_date']) ? date('d F Y', strtotime($c['concert_date'])) : '-' ?>
            </p>
            <div class="flex items-center justify-between gap-2 mt-auto pt-2">
                <span class="text-sm font-semibold text-orange-400"><?= rupiah($c['price']) ?></span>
                <?php if ($left !== null): ?>
                    <span
                        class="text-[10px] font-semibold text-white rounded-md px-2 py-0.5 whitespace-nowrap <?= $left > 0 ? 'bg-pink-500' : 'bg-gray-500' ?>">
                        <?= $left > 0 ? $left . ' Tersisa' : 'Sold out' ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </a>
    <?php
}

/**
 * Satu baris konser (Hots / Recommendation / Favorite).
 * $icon: fire | star | heart.
 * $heading opsional: kalau diisi, $label tampil kecil dan $heading jadi judul besar.
 */
function render_home_concert_row(string $icon, string $label, ?string $heading, array $concerts): void
{
    if (empty($concerts))
        return;
    ?>
    <section>
        <?php if ($heading): ?>
            <p class="text-sm font-semibold text-indigo-600 flex items-center gap-2">
                <?= home_icon($icon, 'w-5 h-5 text-indigo-600') ?>        <?= htmlspecialchars($label) ?>
            </p>
            <h2 class="text-xl font-bold text-slate-900 mb-4"><?= htmlspecialchars($heading) ?></h2>
        <?php else: ?>
            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <?= home_icon($icon, 'w-6 h-6 text-indigo-600') ?>        <?= htmlspecialchars($label) ?>
            </h2>
        <?php endif; ?>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
            <?php foreach ($concerts as $c)
                render_home_concert_card($c); ?>
        </div>
    </section>
    <?php
}

/** Grid kartu konser (4 kolom di desktop) atau pesan kalau hasil filter kosong. */
function render_home_concerts(array $concerts): void
{
    ?>
    <section>
        <?php if (empty($concerts)): ?>
            <p class="text-slate-500 py-10 text-center">Tidak ada konser yang cocok dengan filter.</p>
        <?php else: ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
                <?php foreach ($concerts as $c)
                    render_home_concert_card($c); ?>
            </div>
        <?php endif; ?>
    </section>
    <?php
}

/** Banner konser terdekat + countdown (id cd-* dipakai countdown_boxes.js). */
function render_next_concert_banner(array $next_concert): void
{
    ?>
    <section class="rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-5 sm:p-7 shadow-lg shadow-indigo-300/40
                        grid grid-cols-1 lg:grid-cols-[1.3fr_auto_1fr] gap-6 items-center">

        <div class="flex gap-4 sm:gap-5 items-center">
            <div class="relative w-24 h-32 sm:w-28 sm:h-36 shrink-0 rounded-xl overflow-hidden bg-white/20">
                <?php if (!empty($next_concert['img'])): ?>
                    <img src="<?= htmlspecialchars($next_concert['img']) ?>"
                        alt="<?= htmlspecialchars($next_concert['title']) ?>" class="w-full h-full object-cover object-top">
                <?php endif; ?>
                <?php if (!empty($next_concert['genre_name'])): ?>
                    <span
                        class="absolute bottom-2 left-1/2 -translate-x-1/2 text-[10px] font-semibold bg-white/90 text-indigo-700 rounded-full px-3 py-0.5">
                        <?= htmlspecialchars($next_concert['genre_name']) ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="min-w-0">
                <span class="inline-block text-[10px] font-semibold bg-white/20 rounded-full px-3 py-1 mb-2">Near
                    Event</span>
                <h2 class="text-xl sm:text-2xl font-bold leading-tight"><?= htmlspecialchars($next_concert['title']) ?></h2>
                <p class="text-xs sm:text-sm text-indigo-100 mt-1 truncate flex items-center gap-1.5">
                    <?= home_icon('pin', 'w-4 h-4 shrink-0') ?>    <?= htmlspecialchars($next_concert['venue']) ?>
                </p>
                <?php if (!empty($next_concert['concert_description'])): ?>
                    <p class="text-xs text-indigo-100/90 mt-2 line-clamp-2">
                        <?= htmlspecialchars($next_concert['concert_description']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="hidden lg:block w-px self-stretch bg-white/30"></div>

        <div class="flex flex-col items-center gap-4">
            <p class="text-xs font-semibold tracking-widest">EVENT START IN</p>

            <div id="countdown" class="flex gap-2 sm:gap-3 text-center"
                data-target="<?= date('c', strtotime($next_concert['concert_date'])) ?>">
                <?php foreach (['days' => 'Hari', 'hours' => 'Jam', 'minutes' => 'Menit', 'seconds' => 'Detik'] as $id => $label): ?>
                    <div class="bg-white text-indigo-700 rounded-xl px-3 sm:px-4 py-2.5 min-w-14 sm:min-w-16">
                        <div id="cd-<?= $id ?>" class="text-xl sm:text-2xl font-bold">--</div>
                        <div class="text-[10px] text-slate-500"><?= $label ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <a href="seat.php?id=<?= (int) $next_concert['id'] ?>"
                class="w-full max-w-xs text-center bg-white hover:bg-indigo-50 text-indigo-700 text-sm font-semibold rounded-full px-10 py-2.5 transition">
                Get Ticket
            </a>
        </div>
    </section>
    <?php
}