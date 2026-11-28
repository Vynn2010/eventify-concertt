<?php
include_once "../../actions/auth/session_init.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Eventify</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../style/design-system.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-[#f8f7ff] font-['Outfit'] text-[#17183f] antialiased">

    <?php include "../../components/web_part/header.php"; ?>

    <main>
        <section class="relative overflow-hidden bg-gradient-to-br from-[#faf9ff] via-[#f0efff] to-[#e6e9ff]">

            <!-- Dekorasi bulat -->
            <div class="pointer-events-none absolute -right-32 -top-32 h-96 w-96 rounded-full bg-[#6f00ff]/10 blur-3xl">
            </div>
            <div
                class="pointer-events-none absolute -bottom-40 -left-32 h-[450px] w-[450px] rounded-full bg-[#4169ff]/10 blur-3xl">
            </div>
            <div
                class="pointer-events-none absolute right-[35%] top-[40%] h-64 w-64 rounded-full bg-[#6f00ff]/5 blur-3xl">
            </div>

            <div class="relative mx-auto max-w-6xl px-4 py-10 sm:px-6 sm:py-12">

                <!-- Judul -->
                <div class="mb-10 text-center">
                    <span
                        class="mb-3 inline-block rounded-full bg-[#6f00ff]/10 px-5 py-2 text-xl font-semibold text-[#6f00ff]">
                        Get to Know Us
                    </span>

                    <h1 class="text-4xl font-extrabold tracking-tight text-[#17183f] sm:text-5xl lg:text-6xl">
                        About <span class="text-[#6f00ff]">Eventify</span>
                    </h1>

                    <p class="mx-auto mt-4 max-w-3xl text-xl leading-relaxed text-gray-600">
                        Your Gateway to Unforgettable Events and Experiences.
                    </p>
                </div>

                <!-- Tentang Eventify -->
                <div class="grid grid-cols-1 items-center gap-6 md:grid-cols-2 md:gap-8">

                    <div class="overflow-hidden rounded-3xl shadow-xl">
                        <img src="../../assets/img/about2.png" alt="Tentang Eventify"
                            class="h-[300px] w-full object-cover sm:h-[380px] lg:h-[430px]">
                    </div>

                    <div class="rounded-3xl bg-white/80 p-7 shadow-lg sm:p-9">
                        <span class="text-xl font-bold text-[#6f00ff]">
                            Who We Are
                        </span>

                        <h2 class="mt-3 text-3xl font-bold text-[#17183f] sm:text-4xl">
                            Tentang Eventify
                        </h2>

                        <p class="mt-5 text-xl leading-relaxed text-gray-600">
                            Eventify merupakan platform pemesanan tiket acara yang memudahkan pengguna
                            menemukan berbagai event menarik, mulai dari konser musik, festival,
                            pertunjukan, hingga berbagai acara lainnya.
                        </p>

                        <p class="mt-4 text-xl leading-relaxed text-gray-600">
                            Kami hadir untuk memberikan pengalaman pemesanan tiket yang praktis,
                            nyaman, dan mudah diakses dalam satu platform.
                        </p>
                    </div>

                    <!-- Hubungi Kami -->
                    <div class="rounded-3xl bg-white/80 p-7 shadow-lg sm:p-9">
                        <span class="text-xl font-bold text-[#6f00ff]">
                            Get in Touch
                        </span>

                        <h2 class="mt-3 text-3xl font-bold text-[#17183f] sm:text-4xl">
                            Hubungi Kami
                        </h2>

                        <p class="mt-4 text-xl leading-relaxed text-gray-600">
                            Punya pertanyaan atau membutuhkan bantuan? Jangan ragu untuk
                            menghubungi tim Eventify melalui informasi berikut.
                        </p>

                        <div class="mt-6 space-y-5">

                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#6f00ff]/10 text-2xl">
                                    ✉
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold">Email</h3>
                                    <p class="mt-1 text-xl text-gray-600">Hello@eventify.id</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#6f00ff]/10 text-2xl">
                                    ☎
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold">Telepon</h3>
                                    <p class="mt-1 text-xl text-gray-600">+62 851 0000 0000</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-[#6f00ff]/10 text-2xl">
                                    📍
                                </div>

                                <div>
                                    <h3 class="text-xl font-bold">Lokasi</h3>
                                    <p class="mt-1 text-xl text-gray-600">
                                        Pontianak, Kalimantan Barat
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="overflow-hidden rounded-3xl shadow-xl">
                        <img src="../../assets/img/about1.png" alt="Pengalaman Eventify"
                            class="h-[300px] w-full object-cover sm:h-[380px] lg:h-[430px]">
                    </div>

                </div>

                <!-- Mengapa Eventify -->
                <div class="mt-12">
                    <div class="mb-8 text-center">
                        <span class="text-xl font-bold text-[#6f00ff]">
                            Why Choose Us
                        </span>

                        <h2 class="mt-2 text-3xl font-extrabold text-[#17183f] sm:text-4xl">
                            Mengapa Eventify?
                        </h2>

                        <p class="mx-auto mt-3 max-w-2xl text-xl leading-relaxed text-gray-600">
                            Kami berkomitmen memberikan pengalaman pemesanan tiket yang lebih mudah
                            dan menyenangkan.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                        <div
                            class="rounded-3xl bg-white/80 p-7 text-center shadow-lg transition duration-300 hover:-translate-y-2">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#6f00ff]/10 text-3xl">
                                🎟️
                            </div>

                            <h3 class="mt-5 text-2xl font-bold">Pesanan Mudah</h3>

                            <p class="mt-3 text-xl leading-relaxed text-gray-600">
                                Pesan tiket acara favorit dengan proses yang praktis dan sederhana.
                            </p>
                        </div>

                        <div
                            class="rounded-3xl bg-white/80 p-7 text-center shadow-lg transition duration-300 hover:-translate-y-2">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#4169ff]/10 text-3xl">
                                💺
                            </div>

                            <h3 class="mt-5 text-2xl font-bold">Pilih Kursi Sendiri</h3>

                            <p class="mt-3 text-xl leading-relaxed text-gray-600">
                                Tentukan pilihan tempat duduk sesuai kategori yang tersedia.
                            </p>
                        </div>

                        <div
                            class="rounded-3xl bg-white/80 p-7 text-center shadow-lg transition duration-300 hover:-translate-y-2">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-[#6f00ff]/10 text-3xl">
                                🔔
                            </div>

                            <h3 class="mt-5 text-2xl font-bold">Info Terbaru</h3>

                            <p class="mt-3 text-xl leading-relaxed text-gray-600">
                                Temukan informasi berbagai acara dan event menarik dalam satu platform.
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </main>

    <?php include "../../components/web_part/footer.php"; ?>

</body>

</html>