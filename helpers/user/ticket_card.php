<?php
/** @var array $t satu baris tiket dari BookingRepositories::findAllUserTicket() */

$mulai = strtotime($t['concert_date']);
$tanggal = format_datetime($t['concert_date']);
$ics_mulai = $mulai ? date('Ymd\THis', $mulai) : '';
$ics_akhir = $mulai ? date('Ymd\THis', $mulai + 3 * 3600) : '';
$kartu_id = 'ticket-card-' . (int) $t['id'];
$gambar = !empty($t['img']) ? "background-image:url('" . htmlspecialchars($t['img'], ENT_QUOTES) . "')" : '';
?>
<section class="mb-10">

    <div id="<?= $kartu_id ?>" class="grid grid-cols-1 sm:grid-cols-[3fr_1fr] gap-4 bg-white">

        <div class="relative bg-[#d9d9d9] rounded-[28px] overflow-hidden min-h-56 sm:min-h-64 bg-cover bg-top"
            style="<?= $gambar ?>">
            <div class="absolute inset-x-0 bottom-0 px-6 py-5"
                style="background:linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,0))">
                <p class="text-white text-xl sm:text-2xl font-bold leading-tight"><?= htmlspecialchars($t['title']) ?>
                </p>
                <p class="text-gray-200 text-sm"><?= htmlspecialchars($t['artist']) ?></p>
                <p class="text-gray-200 text-sm mt-2">
                    Kursi <strong><?= htmlspecialchars($t['seat_number']) ?></strong>
                    · 📍 <?= htmlspecialchars($t['venue']) ?>
                </p>
                <p class="text-gray-200 text-sm">🗓 <?= htmlspecialchars($tanggal) ?></p>
            </div>
        </div>

        <div class="bg-[#d9d9d9] rounded-[28px] p-5 flex flex-col items-center justify-center text-center">
            <p class="text-lg font-bold mb-3">QR</p>
            <div class="bg-white p-3 rounded-xl">
                <div class="qr" data-token="<?= htmlspecialchars($t['qr_token']) ?>"></div>
            </div>
            <p class="text-xs mt-3 font-medium <?= $t['status'] === 'used' ? 'text-red-700' : 'text-green-800' ?>">
                <?= $t['status'] === 'used' ? 'Sudah dipakai' : 'Valid' ?>
            </p>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 mt-5">
        <button type="button" data-target="<?= $kartu_id ?>"
            data-filename="tiket-<?= htmlspecialchars(preg_replace('/[^A-Za-z0-9_-]/', '', $t['seat_number'])) ?>-<?= (int) $t['id'] ?>.png"
            onclick="saveTicket(this)"
            class="bg-[#6b6b6b] hover:bg-[#555] disabled:opacity-60 text-white text-base sm:text-lg font-bold rounded-xl px-8 py-3 transition">
            Save Ticket
        </button>

        <button type="button" data-uid="<?= (int) $t['id'] ?>" data-title="<?= htmlspecialchars($t['title']) ?>"
            data-location="<?= htmlspecialchars($t['venue']) ?>"
            data-desc="<?= htmlspecialchars('Kursi ' . $t['seat_number'] . ' - ' . $t['artist']) ?>"
            data-start="<?= $ics_mulai ?>" data-end="<?= $ics_akhir ?>" onclick="addToCalendar(this)" <?= $ics_mulai === '' ? 'disabled' : '' ?>
            class="bg-[#6b6b6b] hover:bg-[#555] disabled:opacity-60 disabled:cursor-not-allowed text-white text-base sm:text-lg font-bold rounded-xl px-8 py-3 transition">
            Add to Calender
        </button>
    </div>
</section>