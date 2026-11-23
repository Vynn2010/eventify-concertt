<?php
// helpers/user/seat/seat_map.php
// Butuh variabel: $rows, $categories_present
?>
<section class="bg-[#d9d9d9] rounded-[28px] p-5 sm:p-8">
    <h2 class="text-lg font-semibold text-center mb-1">Seat Selection</h2>
    <p class="text-xs text-gray-600 text-center mb-6">Centang kursi yang ingin kamu pesan</p>
 
    <div class="bg-[#6b6b6b] text-white text-xs tracking-widest text-center rounded-full py-2 mb-8 max-w-md mx-auto">STAGE</div>
 
    <?php if (empty($rows)): ?>
        <p class="text-center text-gray-600 py-10">Belum ada kursi untuk konser ini.</p>
    <?php else: ?>
        <div class="flex flex-col gap-3 items-center overflow-x-auto">
            <?php foreach ($rows as $huruf => $kursi):
                $row_categories = array_values(array_unique(array_column($kursi, 'category_name')));
            ?>
                <div class="flex items-center gap-3">
                    <span class="w-5 text-sm font-semibold text-gray-700"><?= htmlspecialchars($huruf) ?></span>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($kursi as $k):
                            $available = strcasecmp(trim($k['seat_status']), 'Available') === 0;
                            $style = category_style($k['category_name']);
                        ?>
                            <label class="<?= $available ? 'cursor-pointer' : 'cursor-not-allowed' ?>"
                                   title="<?= htmlspecialchars($k['category_name']) ?> - <?= $available ? 'Tersedia' : 'Tidak tersedia' ?> - <?= rupiah($k['price']) ?>">
                                <input type="checkbox" name="seats[]"
                                       value="<?= (int)$k['id'] ?>"
                                       data-seat="<?= htmlspecialchars($k['seat_number']) ?>"
                                       data-price="<?= (float)$k['price'] ?>"
                                       data-category="<?= htmlspecialchars($k['category_name']) ?>"
                                       class="seat-input peer sr-only"
                                       <?= $available ? '' : 'disabled' ?>>
                                <span class="flex items-center justify-center w-12 h-12 rounded-xl text-sm font-medium select-none transition
                                             bg-white text-gray-800 hover:bg-gray-100 <?= $style['ring'] ?>
                                             peer-checked:bg-[#6b6b6b] peer-checked:text-white
                                             peer-focus-visible:ring-2 peer-focus-visible:ring-gray-700
                                             peer-disabled:bg-[#9a9a9a] peer-disabled:text-gray-600 peer-disabled:line-through peer-disabled:hover:bg-[#9a9a9a] peer-disabled:ring-0">
                                    <?= htmlspecialchars($k['seat_number']) ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($row_categories) === 1):
                        $row_style = category_style($row_categories[0]);
                    ?>
                        <span class="flex items-center gap-1.5 text-xs text-gray-700 shrink-0">
                            <span class="w-2.5 h-2.5 rounded-full <?= $row_style['dot'] ?>"></span>
                            <?= htmlspecialchars($row_categories[0]) ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
 
    <?php if (!empty($categories_present)): ?>
        <div class="flex flex-wrap justify-center gap-x-5 gap-y-2 mt-8 text-xs text-gray-700">
            <?php foreach ($categories_present as $name => $min_price):
                $legend_style = category_style($name);
            ?>
                <span class="flex items-center gap-2">
                    <span class="w-4 h-4 rounded bg-white inline-block <?= $legend_style['ring'] ?>"></span>
                    <?= htmlspecialchars($name) ?> · <?= rupiah($min_price) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
 
    <div class="flex flex-wrap justify-center gap-5 mt-3 text-xs text-gray-700">
        <span class="flex items-center gap-2"><span class="w-4 h-4 rounded bg-white inline-block"></span>Tersedia</span>
        <span class="flex items-center gap-2"><span class="w-4 h-4 rounded bg-[#6b6b6b] inline-block"></span>Dipilih</span>
        <span class="flex items-center gap-2"><span class="w-4 h-4 rounded bg-[#9a9a9a] inline-block"></span>Tidak tersedia</span>
    </div>
</section>