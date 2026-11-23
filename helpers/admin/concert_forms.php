<?php
function render_add_concert_panel(bool $show, bool $has_genre_column, array $genre_options): void
{
    ?>
    <!-- FORM TAMBAH KONSER -->
    <section id="add-form" class="bg-white rounded-2xl p-6 <?= $show ? '' : 'hidden' ?>">
        <h2 class="text-lg font-semibold mb-4">Add concert</h2>

        <form action="../../actions/admin/concert_add_process.php" method="POST" enctype="multipart/form-data"
            class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label for="title" class="block text-sm mb-1.5">Title</label>
                <input type="text" id="title" name="title" required maxlength="255"
                    class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
            </div>

            <div>
                <label for="artist" class="block text-sm mb-1.5">Artist</label>
                <input type="text" id="artist" name="artist" required maxlength="255"
                    class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
            </div>

            <div>
                <label for="venue" class="block text-sm mb-1.5">Venue</label>
                <input type="text" id="venue" name="venue" required maxlength="255" placeholder="e.g. Jakarta"
                    class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
            </div>

            <div>
                <label for="concert_date" class="block text-sm mb-1.5">Date and time</label>
                <input type="datetime-local" id="concert_date" name="concert_date" required
                    class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
            </div>

            <div>
                <label for="price" class="block text-sm mb-1.5">Ticket price (Rp)</label>
                <input type="number" id="price" name="price" required min="0" step="1000" value="250000"
                    class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
            </div>

            <?php if ($has_genre_column): ?>
                <div>
                    <label for="genre_id" class="block text-sm mb-1.5">Genre</label>
                    <select id="genre_id" name="genre_id" required
                        class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                        <option value="">-- choose genre --</option>
                        <?php foreach ($genre_options as $g): ?>
                            <option value="<?= (int) $g['id'] ?>"><?= htmlspecialchars($g['genre_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div>
                <label for="concert_status" class="block text-sm mb-1.5">Status</label>
                <select id="concert_status" name="concert_status" required
                    class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                    <option value="active" selected>Active</option>
                    <option value="non-active">Non-active</option>
                </select>
            </div>

            <div>
                <label for="img" class="block text-sm mb-1.5">Poster (JPG, PNG, WEBP, max 2 MB)</label>
                <input type="file" id="img" name="img" accept="image/jpeg,image/png,image/webp"
                    class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-[#5835FF] file:text-white file:px-4 file:py-1.5 file:text-xs">
            </div>

            <div class="md:col-span-2">
                <label for="concert_description" class="block text-sm mb-1.5">Description</label>
                <textarea id="concert_description" name="concert_description" rows="3" maxlength="5000"
                    class="w-full bg-[#F4F3FE] rounded-xl px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]"></textarea>
            </div>

            <div class="md:col-span-2 flex items-center gap-3">
                <button type="submit"
                    class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold rounded-full px-8 py-3">Save
                    concert</button>
                <button type="button" id="add-cancel" class="text-sm underline opacity-70 hover:opacity-100">Cancel</button>
            </div>
        </form>
    </section>
    <?php
}

/** Modal generate kursi. */
function render_seat_modal(bool $has_error, string $error_msg, array $concert_options, array $categories, int $selected_concert_id): void
{
    ?>
    <!-- MODAL GENERATE SEATS -->
    <div id="seat-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-[#17143D]/60 p-4 overflow-y-auto"
        role="dialog" aria-modal="true" aria-labelledby="seat-modal-title">
        <div class="bg-white rounded-2xl p-6 md:p-8 w-full max-w-2xl my-auto">

            <div class="flex items-start justify-between gap-4 mb-1">
                <h2 id="seat-modal-title" class="text-lg font-semibold">Generate seats</h2>
                <button type="button" id="seat-close" aria-label="Tutup"
                    class="shrink-0 w-9 h-9 rounded-full hover:bg-[#F4F3FE] flex items-center justify-center text-xl leading-none">&times;</button>
            </div>
            <p class="text-sm opacity-65 mb-5">Buat banyak kursi sekaligus untuk satu konser.</p>

            <?php if ($has_error): ?>
                <div class="bg-red-100 text-red-800 text-sm rounded-xl px-4 py-3 mb-5">
                    <?= htmlspecialchars($error_msg) ?>
                </div>
            <?php endif; ?>

            <form action="../../actions/admin/seat_generate_process.php" method="POST"
                class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label for="seat_concert_id" class="block text-sm mb-1.5">Concert</label>
                    <select id="seat_concert_id" name="concert_id" required
                        class="w-full bg-[#F4F3FE] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                        <option value="">-- choose concert --</option>
                        <?php foreach ($concert_options as $concert): ?>
                            <option value="<?= (int) $concert['id'] ?>" <?= ($selected_concert_id === (int) $concert['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($concert['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="seat_rows" class="block text-sm mb-1.5">Rows</label>
                    <input type="text" id="seat_rows" name="rows" required placeholder="A-F or A,B,C"
                        class="w-full bg-[#F4F3FE] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                    <p class="text-xs opacity-60 mt-1.5">Pakai rentang (A-F) atau daftar dipisah koma (A,C,E).</p>
                </div>

                <div>
                    <label for="seat_per_row" class="block text-sm mb-1.5">Seats per row</label>
                    <input type="number" id="seat_per_row" name="seats_per_row" min="1" max="100" value="20" required
                        class="w-full bg-[#F4F3FE] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                    <p class="text-xs opacity-60 mt-1.5">Maksimal 100 kursi per baris.</p>
                </div>

                <div>
                    <label for="seat_category_id" class="block text-sm mb-1.5">Category</label>
                    <select id="seat_category_id" name="category_id" required
                        class="w-full bg-[#F4F3FE] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                        <option value="">-- choose category --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= (int) $category['id'] ?>"><?= htmlspecialchars($category['category_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="seat_price" class="block text-sm mb-1.5">Price per seat (Rp)</label>
                    <input type="number" id="seat_price" name="price" min="0" step="1000" value="200000" required
                        class="w-full bg-[#F4F3FE] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                </div>

                <div class="md:col-span-2">
                    <label for="seat_disabled" class="block text-sm mb-1.5">Disabled seats (optional)</label>
                    <input type="text" id="seat_disabled" name="disabled_seats" placeholder="A5, A6, B10"
                        class="w-full bg-[#F4F3FE] rounded-xl px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[#5835FF]">
                    <p class="text-xs opacity-60 mt-1.5">Pisahkan dengan koma. Kursi ini tetap dibuat tapi tidak bisa
                        dipesan.</p>
                </div>

                <div
                    class="md:col-span-2 bg-[#F4F3FE] rounded-xl px-4 py-3 text-sm flex items-center justify-between gap-3">
                    <span class="opacity-65">Seats to be created</span>
                    <span id="seat_preview" class="font-semibold text-[#5835FF]">0 seats</span>
                </div>

                <div class="md:col-span-2 flex flex-wrap items-center gap-3">
                    <button type="submit"
                        class="bg-[#5835FF] hover:bg-[#4D4FFF] text-white text-sm font-semibold rounded-full px-8 py-3 transition">
                        Generate
                    </button>
                    <button type="button" id="seat-cancel"
                        class="text-sm underline opacity-70 hover:opacity-100">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <?php
}

/** JavaScript untuk panel tambah konser dan modal generate kursi. */
function render_concert_scripts(bool $open_on_error): void
{
    ?>
    <script>
        // ---------- Panel tambah konser ----------
        (function () {
            var panel = document.getElementById('add-form');
            var toggle = document.getElementById('add-toggle');
            var cancel = document.getElementById('add-cancel');
            if (!panel || !toggle) return;

            toggle.addEventListener('click', function () {
                panel.classList.toggle('hidden');
                if (!panel.classList.contains('hidden')) {
                    panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
            if (cancel) {
                cancel.addEventListener('click', function () { panel.classList.add('hidden'); });
            }
        })();

        // ---------- Modal generate kursi ----------
        (function () {
            var modal = document.getElementById('seat-modal');
            var concertSelect = document.getElementById('seat_concert_id');
            var rowsInput = document.getElementById('seat_rows');
            var perRow = document.getElementById('seat_per_row');
            var disabledInput = document.getElementById('seat_disabled');
            var preview = document.getElementById('seat_preview');

            function openModal(concertId) {
                if (concertId) { concertSelect.value = concertId; }
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                (concertId ? rowsInput : concertSelect).focus();
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }

            // Tombol "Generate" / "+ Add" di tabel (konser sudah terpilih)
            document.querySelectorAll('.seat-open').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    openModal(btn.getAttribute('data-concert-id'));
                });
            });

            // Tombol "Generate Seats" di header (opsional, hanya kalau ada di halaman)
            var headerBtn = document.getElementById('seat-open-header');
            if (headerBtn) {
                headerBtn.addEventListener('click', function () { openModal(''); });
            }

            document.getElementById('seat-close').addEventListener('click', closeModal);
            document.getElementById('seat-cancel').addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
            });

            // Buka otomatis kalau proses generate mengembalikan error
            <?php if ($open_on_error): ?>
                openModal('');
            <?php endif; ?>

            // ---------- Ringkasan otomatis ----------
            function countRows(value) {
                value = value.trim().toUpperCase();
                var range = value.match(/^([A-Z])\s*-\s*([A-Z])$/);
                if (range) {
                    return Math.abs(range[2].charCodeAt(0) - range[1].charCodeAt(0)) + 1;
                }
                var list = value.split(',').map(function (v) { return v.trim(); }).filter(Boolean);
                return new Set(list).size;
            }

            function countDisabled(value) {
                var list = value.toUpperCase().split(',')
                    .map(function (v) { return v.trim(); })
                    .filter(function (v) { return /^[A-Z]\d+$/.test(v); });
                return new Set(list).size;
            }

            function update() {
                var rows = countRows(rowsInput.value);
                var seats = rows * (parseInt(perRow.value, 10) || 0);
                var disabled = countDisabled(disabledInput.value);
                preview.textContent = seats + ' seats (' + rows + ' row' + (rows === 1 ? '' : 's') + ')'
                    + (disabled ? ', ' + disabled + ' disabled' : '');
            }

            rowsInput.addEventListener('input', update);
            perRow.addEventListener('input', update);
            disabledInput.addEventListener('input', update);
            update();
        })();
    </script>
    <?php
}