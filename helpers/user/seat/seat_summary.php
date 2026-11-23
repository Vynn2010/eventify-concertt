<?php function render_seat_summary(array $konser): void { ?>
<aside class="flex flex-col gap-4">
    <div class="bg-[#d9d9d9] rounded-[28px] p-5 sm:p-6 flex-grow min-h-64">
        <h2 class="text-lg font-semibold text-center mb-4">Selection Detail</h2>
 
        <p class="text-sm font-medium"><?= htmlspecialchars($konser['title']) ?></p>
        <p class="text-xs text-gray-600 mb-1">📍 <?= htmlspecialchars($konser['venue']) ?></p>
        <p class="text-xs text-gray-600 mb-4">
            🗓 <?= format_datetime($konser['concert_date'] ?? null) ?>
        </p>
 
        <p id="emptyText" class="text-sm text-gray-600 text-center py-6">Belum ada kursi dipilih</p>
        <ul id="selectedList" class="text-sm flex flex-col gap-2"></ul>
 
        <div class="border-t border-gray-500/40 mt-4 pt-4 flex justify-between text-sm">
            <span>Total (<span id="totalSeats">0</span> kursi)</span>
            <span id="totalPrice" class="font-semibold">Rp 0</span>
        </div>
    </div>
 
    <button type="submit" id="confirmBtn" disabled
            class="bg-[#6b6b6b] hover:bg-[#555] disabled:bg-[#9a9a9a] disabled:cursor-not-allowed text-white text-lg font-medium rounded-2xl py-4 transition">
        Confirm
    </button>
</aside>
<?php } ?>