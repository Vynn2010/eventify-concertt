<?php
if (!function_exists('take_concert')) {
    function take_concert($conn, $sql)
    {
        $hasil = mysqli_query($conn, $sql);
        if (!$hasil) {
            die("Gagal mengambil data database: " . mysqli_error($conn));
        }
        $data = [];
        while ($row = mysqli_fetch_assoc($hasil)) {
            $data[] = $row;
        }
        return $data;
    }
}

/** Satu kartu konser (dipakai di semua section). */
function concert_cards(array $c)
{ ?>
    <a href="concertdetail.php?id=<?= (int) $c['id'] ?>"
        class="concert-card rounded-[22px] overflow-hidden flex flex-col shadow-sm hover:shadow-lg hover:-translate-y-1 transition"
        data-search="<?= htmlspecialchars(strtolower($c['title'] . ' ' . $c['artist'])) ?>">

        <!-- Bagian atas (abu terang): gambar + judul -->
        <div class="bg-[#d9d9d9] flex-grow flex flex-col">
            <div class="w-full h-44 overflow-hidden">
                <?php if (!empty($c['img'])): ?>
                    <img src="<?= htmlspecialchars($c['img']) ?>" alt="<?= htmlspecialchars($c['title']) ?>"
                        class="w-full h-full object-cover object-top">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center text-gray-500 text-sm">No Image</div>
                <?php endif; ?>
            </div>
            <div class="px-4 py-3 text-center">
                <h3 class="text-base font-semibold text-gray-900 leading-tight"><?= htmlspecialchars($c['title']) ?></h3>
                <p class="text-xs text-gray-600 mt-1"><?= htmlspecialchars($c['artist']) ?></p>
            </div>
        </div>

        <!-- Bagian bawah (abu gelap): deskripsi -->
        <div class="bg-[#8f8f8f] text-gray-900 px-4 py-3 text-center h-20 flex flex-col justify-center">
            <p class="text-xs line-clamp-2"><?= htmlspecialchars($c['concert_description']) ?></p>
            <span class="text-[11px] mt-1 opacity-80">📍 <?= htmlspecialchars($c['venue']) ?></span>
        </div>
    </a>
<?php }

/** Satu section (judul + grid kartu konser). */
function section_konser($judul, array $daftar)
{ ?>
    <section class="event-section mb-10">
        <h2 class="text-2xl font-medium mb-4"><?= htmlspecialchars($judul) ?></h2>
        <?php if (empty($daftar)): ?>
            <p class="text-gray-500 text-sm py-6">Belum ada konser untuk kategori ini.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <?php foreach ($daftar as $c) {
                    concert_cards($c);
                } ?>
            </div>
        <?php endif; ?>
    </section>
<?php }

/** Banner besar di atas halaman (konser paling hot). $hero boleh null. */
function render_hero_banner(?array $hero): void
{
    if ($hero): ?>
        <a href="concertdetail.php?id=<?= (int) $hero['id'] ?>"
            class="block relative bg-[#d9d9d9] rounded-xl overflow-hidden h-40 sm:h-56 mb-8 group">
            <?php if (!empty($hero['img'])): ?>
                <img src="<?= htmlspecialchars($hero['img']) ?>" alt="<?= htmlspecialchars($hero['title']) ?>"
                    class="w-full h-full object-cover object-center group-hover:scale-105 transition duration-300">
            <?php endif; ?>
            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent px-5 py-4">
                <p class="text-white text-lg sm:text-xl font-semibold"><?= htmlspecialchars($hero['title']) ?></p>
                <p class="text-gray-200 text-sm"><?= htmlspecialchars($hero['artist']) ?></p>
            </div>
        </a>
    <?php else: ?>
        <div class="bg-[#d9d9d9] rounded-xl h-40 sm:h-56 mb-8 flex items-center justify-center text-gray-600">
            Belum ada konser
        </div>
    <?php endif;
}