<?php
function render_scan_camera($concerts): void
{
    ?>
    <section class="bg-white rounded-2xl p-6 flex flex-col gap-4">
        <h2 class="text-lg font-semibold">Camera</h2>

        <div>
            <label for="concert-filter" class="block text-sm mb-1.5">Concert being checked</label>
            <select id="concert-filter"
                class="w-full bg-[#F4F3FE] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                <option value="0">All concerts</option>
                <?php while ($c = mysqli_fetch_assoc($concerts)): ?>
                    <option value="<?= (int) $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div id="reader" class="w-full max-w-md mx-auto rounded-2xl overflow-hidden bg-[#17143D] min-h-64"></div>

        <div class="flex flex-wrap gap-3">
            <button type="button" id="start-btn"
                class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold rounded-full px-6 py-3 transition">
                Start camera
            </button>
            <button type="button" id="stop-btn" disabled
                class="bg-[#F4F3FE] hover:bg-[#8F9BFB]/30 text-sm font-semibold rounded-full px-6 py-3 transition disabled:opacity-50 disabled:cursor-not-allowed">
                Stop camera
            </button>
        </div>
        <p class="text-xs opacity-60">Kamera hanya bisa dipakai lewat <code>localhost</code> atau HTTPS.</p>

        <!-- Input manual -->
        <form id="manual-form" class="border-t border-[#17143D]/10 pt-4 flex flex-col gap-2">
            <label for="manual-token" class="block text-sm">Or enter the ticket token manually</label>
            <div class="flex gap-2">
                <input type="text" id="manual-token" placeholder="Paste qr_token"
                    class="flex-1 min-w-0 bg-[#F4F3FE] rounded-full px-5 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                <button type="submit"
                    class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold rounded-full px-6 py-2.5 transition">Check</button>
            </div>
        </form>
    </section>
    <?php
}

/** Kolom kanan: kotak hasil scan dan riwayat scan terbaru. */
function render_scan_result(): void
{
    ?>
    <section class="flex flex-col gap-6">

        <div id="result" class="rounded-2xl p-6 bg-white">
            <p class="text-lg font-semibold">Waiting for a ticket…</p>
            <p class="text-sm opacity-65 mt-1">Arahkan kamera ke QR tiket atau masukkan token manual.</p>
        </div>

        <div class="bg-white rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-3">Recent scans</h2>
            <ul id="history" class="flex flex-col gap-2 text-sm">
                <li class="opacity-60">Belum ada scan.</li>
            </ul>
        </div>
    </section>
    <?php
}