<?php

$active_menu = $active_menu ?? '';

$admin_menus = [
    'home' => ['href' => 'home_page_admin.php', 'label' => 'Home'],
    'booking' => ['href' => 'booking.php', 'label' => 'Booking'],
    'concerts' => ['href' => 'concerts.php', 'label' => 'Concerts'],
    'genres' => ['href' => 'genres.php', 'label' => 'Genres'],
];
?>
<!-- SIDEBAR -->
<aside class="md:w-60 shrink-0 bg-[#17143D] text-[#F4F3FE] p-6 flex flex-col gap-8">
    <div class="text-2xl font-bold">
        Eventify<span class="text-[#8F9BFB]">.</span>
        <div class="text-xs font-normal opacity-60 mt-1">Admin panel</div>
    </div>

    <nav class="flex flex-col gap-2 text-sm">
        <?php foreach ($admin_menus as $key => $menu): ?>
            <a href="<?= $menu['href'] ?>"
                class="px-4 py-3 rounded-xl <?= $active_menu === $key ? 'bg-[#5835FF] text-white font-semibold' : 'hover:bg-white/10' ?>">
                <?= $menu['label'] ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <a href="../../actions/auth/logout.php"
        class="md:mt-auto text-center text-sm px-4 py-3 rounded-full border border-white/30 hover:bg-white/10">
        Log out
    </a>
</aside>