<?php
if (!function_exists('rupiah')) {
    function rupiah($n)
    {
        return 'Rp ' . number_format((float) $n, 0, ',', '.');
    }
}

/** Kartu gambar konser yang dipilih (kiri atas). */
function render_book_concert_card(array $konser): void
{
    ?>
    <div class="relative bg-[#d9d9d9] rounded-[28px] overflow-hidden h-56 sm:h-64">
        <?php if (!empty($konser['img'])): ?>
            <img src="<?= htmlspecialchars($konser['img']) ?>" alt="<?= htmlspecialchars($konser['title']) ?>"
                class="w-full h-full object-cover object-top">
        <?php endif; ?>
        <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent px-6 py-4">
            <p class="text-white text-xl font-semibold"><?= htmlspecialchars($konser['title']) ?></p>
            <p class="text-gray-200 text-sm"><?= htmlspecialchars($konser['artist']) ?></p>
        </div>
    </div>
    <?php
}

/** Daftar pilihan metode pembayaran (radio). */
function render_payment_methods(array $metode_pembayaran): void
{
    ?>
    <section class="bg-[#6b6b6b] rounded-[28px] p-5 sm:p-6">
        <h2 class="text-white text-lg font-semibold text-center mb-4">Payment method</h2>

        <div class="flex flex-col gap-3">
            <?php foreach ($metode_pembayaran as $i => $metode): ?>
                <label class="flex items-center gap-4 cursor-pointer">
                    <input type="radio" name="payment_method" value="<?= htmlspecialchars($metode) ?>"
                        class="payment-input peer sr-only" <?= $i === 0 ? 'required' : '' ?>>
                    <span
                        class="flex-1 bg-[#d9d9d9] hover:bg-white text-gray-900 font-semibold rounded-full px-6 py-2 transition
                                                 peer-checked:bg-white peer-focus-visible:ring-2 peer-focus-visible:ring-white">
                        <?= htmlspecialchars($metode) ?>
                    </span>
                    <!-- Lingkaran centang -->
                    <span class="w-7 h-7 shrink-0 rounded-full bg-[#2b2b2b] border-2 border-[#2b2b2b] flex items-center justify-center transition
                                                 peer-checked:bg-white peer-checked:border-white">
                        <svg class="w-4 h-4 text-[#2b2b2b] hidden check-icon" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

/** Kartu detail harga (kanan): harga konser, daftar kursi, dan total. $checkout dari prepareCheckout(). */
function render_price_detail(array $checkout): void
{
    $konser = $checkout['konser'];
    ?>
    <div class="relative rounded-[28px] overflow-hidden bg-[#6b6b6b] flex-grow min-h-80">
        <?php if (!empty($konser['img'])): ?>
            <img src="<?= htmlspecialchars($konser['img']) ?>" alt="" class="absolute inset-0 w-full h-full object-cover"
                aria-hidden="true">
        <?php endif; ?>
        <div class="absolute inset-0 bg-black/65"></div>

        <div class="relative p-5 sm:p-6 text-white">
            <h2 class="text-lg font-semibold text-center mb-4">Detail Price</h2>

            <div class="text-sm flex flex-col gap-2">
                <div class="flex justify-between gap-3">
                    <span>Harga konser (<?= $checkout['jumlah_kursi'] ?> × <?= rupiah($checkout['harga_konser']) ?>)</span>
                    <span class="shrink-0"><?= rupiah($checkout['subtotal_konser']) ?></span>
                </div>

                <p class="mt-2 text-gray-300 text-xs uppercase tracking-wide">Tempat duduk</p>
                <?php foreach ($checkout['kursi'] as $k): ?>
                    <div class="flex justify-between gap-3">
                        <span>Kursi <?= htmlspecialchars($k['seat_number']) ?></span>
                        <span class="shrink-0"><?= rupiah($k['price']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="border-t border-white/40 mt-4 pt-4 flex justify-between text-base font-semibold">
                <span>Total</span>
                <span><?= rupiah($checkout['total']) ?></span>
            </div>
        </div>
    </div>
    <?php
}