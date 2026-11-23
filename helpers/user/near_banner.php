<?php
function render_near_banner(array $near, string $extra_class = ''): void
{
    ?>
    <!-- BANNER KONSER TERDEKAT -->
    <section class="bg-[#5c5c5c] px-5 pt-5 pb-5 <?= htmlspecialchars($extra_class) ?>">
        <div class="max-w-7xl mx-auto bg-[#d9d9d9] rounded-[22px] overflow-hidden flex flex-col md:flex-row">
            <div class="md:w-1/4 h-40 md:h-auto bg-[#8f8f8f] flex items-center justify-center">
                <?php if (!empty($near['img'])): ?>
                    <img src="<?= htmlspecialchars($near['img']) ?>" alt="<?= htmlspecialchars($near['title']) ?>"
                        class="w-full h-full object-cover object-top">
                <?php else: ?>
                    <span class="text-white text-lg">Concert Image</span>
                <?php endif; ?>
            </div>
            <div class="flex-1 flex flex-col justify-center text-center px-6 py-5">
                <h3 class="text-xl font-semibold"><?= htmlspecialchars($near['title']) ?> -
                    <?= htmlspecialchars($near['artist']) ?></h3>
                <p class="text-sm text-gray-700 mt-1 line-clamp-2"><?= htmlspecialchars($near['concert_description']) ?></p>
                <p class="text-xs text-gray-600 mt-1">📍 <?= htmlspecialchars($near['venue']) ?></p>
            </div>
            <div class="hidden md:block w-px bg-gray-500 my-5"></div>
            <div class="md:w-1/4 flex flex-col items-center justify-center gap-3 px-6 py-5">
                <p class="text-2xl font-medium" id="countdown"
                    data-date="<?= date('c', strtotime($near['concert_date'])) ?>">--</p>
                <a href="ticket.php?id=<?= (int) $near['id'] ?>"
                    class="bg-[#8f8f8f] hover:bg-[#777] text-white text-sm rounded-full px-10 py-2 transition">Get
                    Ticket</a>
            </div>
        </div>
    </section>
    <?php
}