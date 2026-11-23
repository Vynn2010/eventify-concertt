<?php
// components/landing/footer.php
$footer_links = [
    'Jelajahi' => [
        'Semua Event' => 'event.php',
        'Genre'       => 'home_page_user.php#kategori',
        'Kota'        => 'event.php',
    ],
    'Bantuan' => [
        'Cara Beli Tiket'   => 'about.php',
        'Pengembalian dana' => '#',
        'Kontak'            => 'about.php',
    ],
    'Perusahaan' => [
        'Tentang' => 'about.php',
        'Syarat'  => '#',
        'Privasi' => '#',
    ],
];
?>
<footer class="bg-white mt-12">
    <div class="max-w-screen-2xl mx-auto px-4 sm:px-6 lg:px-10 pt-12 pb-8">

        <!-- Logo + 3 kolom: sama rata -->
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-10 lg:px-12">

            <!-- Kiri: logo + tagline -->
            <div class="flex flex-col gap-3 md:w-1/3 lg:w-1/4 shrink-0">
                <a href="home_page_user.php" class="flex items-center gap-2.5 sm:gap-3">
                    <!-- Isi src dengan path gambar logo kamu -->
                    <img src="../../assets/icon/logo.jpeg" alt="Logo Eventify" name="logo-eventify"
                         class="w-11 h-9 sm:w-14 sm:h-11 object-contain rounded-md bg-[#5b4cf5]/10 shrink-0">
                    <span class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#14123b]">Eventify</span>
                </a>
                <p class="text-base font-bold leading-snug text-[#8e8aa6] max-w-[14rem]">
                    Tiket konser terverifikasi dan pilih kursi secara real-time.
                </p>
            </div>

            <!-- Kanan: 3 kolom, dibagi rata -->
            <div class="flex flex-1 justify-around gap-6">
                <?php foreach ($footer_links as $judul => $links): ?>
                    <div>
                        <h2 class="mb-4 text-base font-semibold text-[#14123b]"><?= htmlspecialchars($judul) ?></h2>
                        <ul class="flex flex-col gap-3 text-[13px] font-semibold text-[#8e8aa6]">
                            <?php foreach ($links as $label => $href): ?>
                                <li>
                                    <a href="<?= htmlspecialchars($href) ?>"
                                       class="hover:text-[#5b4cf5] transition-colors"><?= htmlspecialchars($label) ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Garis pemisah -->
        <hr class="my-8 h-0.5 border-0 bg-gray-400">

        <!-- Copyright -->
        <p class="max-w-4xl mx-auto text-center text-[13px] leading-relaxed text-[#8e8aa6]">
            &copy; <?= date('Y') ?> Eventify Hak Cipta Dilindungi Undang-Undang. Penggunaan situs web ini
            menandakan persetujuan Anda terhadap Persetujuan Pengguna, Pemberitahuan Privasi, Pemberitahuan
            Cookie kami. Anda membeli tiket dari pihak ketiga; Eventify bukan penjual tiket. Harga ditetapkan
            oleh penjual dan mungkin di atas nilai nominal.
        </p>
    </div>
</footer>