<?php
include_once "../../actions/auth/session_init.php";
require_once "../../config/database.php";

$hide_auth = false;

$e = fn($value) => htmlspecialchars((string) ($value ?? ""), ENT_QUOTES, "UTF-8");

function eventImage($path)
{
    if (empty($path)) {
        return "../../assets/img/slider1.jpg";
    }

    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }

    return "../../" . ltrim($path, "/");
}

function eventPrice($price)
{
    return "Rp " . number_format((float) $price, 0, ",", ".");
}

function eventDate($date)
{
    return date("d M Y", strtotime($date));
}

// BANNER SLIDER

$slider_images = [
    "../../assets/img/slider1.jpg",
    "../../assets/img/lider2.jpg",
    "../../assets/img/slider3.jpg",
    "../../assets/img/slider4.jpg"
];

// POPULAR CATEGORIES

$genres = [];

$result_genres = mysqli_query(
    $conn,
    "SELECT id, genre_name FROM genres ORDER BY genre_name ASC"
);

if ($result_genres) {
    while ($row = mysqli_fetch_assoc($result_genres)) {
        $genres[] = $row;
    }
}

$category_images = [
    "../../assets/img/slider1.jpg",
    "../../assets/img/lider2.jpg",
    "../../assets/img/slider3.jpg",
    "../../assets/img/slider4.jpg"
];

// HOT EVENTS

$sql_hots = "
    SELECT c.*, g.genre_name
    FROM concerts c
    INNER JOIN genres g ON c.genre_id = g.id
    WHERE c.concert_date >= CURDATE()
    ORDER BY c.concert_date ASC
    LIMIT 4
";

$result_hots = mysqli_query($conn, $sql_hots);

$hot_events = $result_hots
    ? mysqli_fetch_all($result_hots, MYSQLI_ASSOC)
    : [];

// RECOMMENDATION

$sql_recommend = "
    SELECT c.*, g.genre_name
    FROM concerts c
    INNER JOIN genres g ON c.genre_id = g.id
    WHERE c.concert_date >= CURDATE()
    ORDER BY RAND()
    LIMIT 4
";

$result_recommend = mysqli_query($conn, $sql_recommend);

$recommend_events = $result_recommend
    ? mysqli_fetch_all($result_recommend, MYSQLI_ASSOC)
    : [];

// FEATURED EVENT

$sql_featured = "
    SELECT c.*, g.genre_name
    FROM concerts c
    INNER JOIN genres g ON c.genre_id = g.id
    WHERE c.concert_date >= CURDATE()
    ORDER BY c.concert_date ASC
    LIMIT 1
";

$result_featured = mysqli_query($conn, $sql_featured);

$featured_event = $result_featured
    ? mysqli_fetch_assoc($result_featured)
    : null;

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Eventify - Events</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="stylesheet" href="../../style/design-system.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

</head>

<body class="m-0 bg-[#fffaff] font-['Outfit'] text-[#17183f]">

    <?php include "../../components/web_part/header.php"; ?>

    <main class="mx-auto max-w-[1500px] px-3 pb-8 sm:px-5 lg:px-[35px] lg:pb-12">

        <!-- BANNER -->

        <section
            class="relative mt-3 h-[175px] overflow-hidden rounded-[10px] bg-[#e9e4ff] sm:mt-[22px] sm:h-[260px] sm:rounded-[17px] lg:h-[340px]">

            <?php foreach ($slider_images as $index => $image): ?>

                <div
                    class="banner-slide absolute inset-0 transition-all duration-700 <?= $index === 0 ? 'visible opacity-100' : 'invisible opacity-0' ?>">

                    <img src="<?= $e($image) ?>" alt="Event Banner <?= $index + 1 ?>" class="h-full w-full object-cover">

                </div>

            <?php endforeach; ?>

            <div
                class="absolute left-0 top-0 z-[2] flex h-full w-[60%] flex-col justify-center bg-gradient-to-r from-white via-white/90 to-transparent p-[15px] sm:w-[55%] sm:p-8 lg:w-[48%] lg:p-10">

                <h1 class="text-[20px] font-extrabold leading-tight sm:text-[28px] lg:text-[43px]">

                    Find Your Next

                    <span class="block text-[#5541f5]">
                        Unforgettable Event
                    </span>

                </h1>

                <p class="mt-2 max-w-[390px] text-[20px] leading-snug text-[#77758d] sm:mt-4 sm:leading-relaxed">

                    From electrifying concerts to inspiring festivals and exciting experiences, find the event that
                    matches your vibe.

                </p>

            </div>

            <div class="absolute bottom-3 left-0 right-0 z-[5] flex justify-center gap-2 sm:bottom-[17px]">

                <?php foreach ($slider_images as $index => $image): ?>

                    <button type="button"
                        class="banner-dot h-[9px] rounded-full bg-white/60 transition-all duration-300 <?= $index === 0 ? 'w-[25px] !bg-white' : 'w-[9px]' ?>"
                        data-slide="<?= $index ?>" aria-label="Slide <?= $index + 1 ?>"></button>

                <?php endforeach; ?>

            </div>

        </section>

        <!-- POPULAR CATEGORIES -->

        <section class="mt-5 sm:mt-8">

            <h2 class="text-[20px] font-extrabold leading-tight sm:text-[25px]">
                Popular Categories
            </h2>

            <div class="mt-3 grid grid-cols-5 gap-[7px] sm:mt-[17px] sm:gap-3 lg:gap-[15px]">

                <?php foreach ($genres as $index => $genre): ?>

                    <?php
                    $category_image = $category_images[
                        $index % count($category_images)
                    ];

                    $category_link = "index.php?genre_id=" .
                        (int) $genre["id"] . "#categories";
                    ?>

                    <a href="<?= $e($category_link) ?>"
                        class="group relative h-[58px] overflow-hidden rounded-lg bg-[#e9e5ff] shadow-sm sm:h-[110px] sm:rounded-xl lg:h-[155px] lg:rounded-[15px]">

                        <img src="<?= $e($category_image) ?>" alt="<?= $e($genre["genre_name"]) ?>" loading="lazy"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-110">

                        <div class="absolute inset-0 bg-gradient-to-t from-[#0c0a23]/85 to-transparent"></div>

                        <span
                            class="absolute bottom-1 left-1 right-1 text-[20px] font-extrabold leading-tight text-white [overflow-wrap:anywhere] sm:bottom-2 sm:left-2 lg:bottom-[13px] lg:left-[13px]">

                            <?= $e($genre["genre_name"]) ?>

                        </span>

                    </a>

                <?php endforeach; ?>

            </div>

        </section>

        <!-- HOTS -->

        <section class="mt-5 sm:mt-8">

            <div class="flex items-center justify-between">

                <h2 class="flex items-center gap-2 text-[20px] font-extrabold sm:text-[25px]">
                    🔥 Hots
                </h2>

                <a href="index.php" class="text-[20px] font-bold text-[#5541f5]">
                    See All
                </a>

            </div>

            <?php if ($hot_events): ?>

                <div class="mt-3 grid grid-cols-4 gap-[6px] sm:mt-[17px] sm:gap-3 lg:gap-[18px]">

                    <?php foreach ($hot_events as $event): ?>

                        <a href="transaction.php?id=<?= (int) $event["id"] ?>"
                            class="min-w-0 overflow-hidden rounded-xl border border-[#eee9f6] bg-white text-inherit shadow-sm transition hover:-translate-y-1 hover:shadow-lg sm:rounded-[15px]">

                            <div class="h-[82px] overflow-hidden bg-[#ece9ff] sm:h-[145px] lg:h-[180px]">

                                <img src="<?= $e(eventImage($event["img"])) ?>" alt="<?= $e($event["title"]) ?>" loading="lazy"
                                    class="h-full w-full object-cover transition duration-300 hover:scale-105">

                            </div>

                            <div class="p-[6px] sm:p-3">

                                <h3 class="line-clamp-2 min-h-[48px] text-[20px] font-extrabold leading-tight">

                                    <?= $e($event["title"]) ?>

                                </h3>

                                <p class="mt-1 text-[20px] text-[#77758d]">

                                    <?= $e($event["artist"]) ?>

                                </p>

                                <p class="mt-2 text-[20px] text-[#85849a]">

                                    📅 <?= eventDate($event["concert_date"]) ?>

                                </p>

                                <p class="mt-1 text-[20px] text-[#85849a]">

                                    📍 <?= $e($event["venue"]) ?>

                                </p>

                                <div class="mt-2 flex flex-wrap items-center justify-between gap-1">

                                    <span class="text-[20px] font-extrabold text-[#5541f5]">

                                        <?= eventPrice($event["price"]) ?>

                                    </span>

                                    <span class="rounded-full bg-[#ff4ca0] px-2 py-1 text-[16px] font-bold text-white">

                                        View Event

                                    </span>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div
                    class="mt-3 rounded-[15px] border border-[#eee9f6] bg-white p-6 text-center text-[20px] text-[#85849a]">

                    Belum ada event mendatang.

                </div>

            <?php endif; ?>

        </section>

        <!-- RECOMMENDATION -->

        <section class="mt-5 sm:mt-8">

            <div class="flex items-center justify-between">

                <div>

                    <h2 class="text-[20px] font-extrabold sm:text-[25px]">
                        ⭐ Recommendation
                    </h2>

                    <p class="mt-1 text-[20px] text-[#85849a]">
                        Top Picks for You
                    </p>

                </div>

                <a href="index.php" class="text-[20px] font-bold text-[#5541f5]">
                    See All
                </a>

            </div>

            <?php if ($recommend_events): ?>

                <div class="mt-3 grid grid-cols-4 gap-[6px] sm:mt-[17px] sm:gap-3 lg:gap-[18px]">

                    <?php foreach ($recommend_events as $event): ?>

                        <a href="transaction.php?id=<?= (int) $event["id"] ?>"
                            class="min-w-0 overflow-hidden rounded-xl border border-[#eee9f6] bg-white text-inherit shadow-sm transition hover:-translate-y-1 hover:shadow-lg sm:rounded-[15px]">

                            <div class="h-[82px] overflow-hidden bg-[#ece9ff] sm:h-[145px] lg:h-[180px]">

                                <img src="<?= $e(eventImage($event["img"])) ?>" alt="<?= $e($event["title"]) ?>" loading="lazy"
                                    class="h-full w-full object-cover transition duration-300 hover:scale-105">

                            </div>

                            <div class="p-[6px] sm:p-3">

                                <h3 class="line-clamp-2 min-h-[48px] text-[20px] font-extrabold leading-tight">

                                    <?= $e($event["title"]) ?>

                                </h3>

                                <p class="mt-1 text-[20px] text-[#77758d]">

                                    <?= $e($event["artist"]) ?>

                                </p>

                                <p class="mt-2 text-[20px] text-[#85849a]">

                                    📅 <?= eventDate($event["concert_date"]) ?>

                                </p>

                                <p class="mt-1 text-[20px] text-[#85849a]">

                                    📍 <?= $e($event["venue"]) ?>

                                </p>

                                <div class="mt-2 flex flex-wrap items-center justify-between gap-1">

                                    <span class="text-[20px] font-extrabold text-[#5541f5]">

                                        <?= eventPrice($event["price"]) ?>

                                    </span>

                                    <span class="rounded-full bg-[#ff4ca0] px-2 py-1 text-[16px] font-bold text-white">

                                        View Event

                                    </span>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div
                    class="mt-3 rounded-[15px] border border-[#eee9f6] bg-white p-6 text-center text-[20px] text-[#85849a]">

                    Belum ada rekomendasi event.

                </div>

            <?php endif; ?>

        </section>

        <!-- FEATURED COUNTDOWN -->

        <?php if ($featured_event): ?>

            <section class="mt-5 sm:mt-8">

                <div
                    class="grid grid-cols-[65px_1fr] items-center gap-2 rounded-xl border border-[#eee9f6] bg-white p-[10px] shadow-sm sm:grid-cols-[100px_1fr] sm:gap-4 sm:p-5 lg:grid-cols-[130px_1fr_330px] lg:gap-[22px] lg:rounded-[20px]">

                    <img src="<?= $e(eventImage($featured_event["img"])) ?>" alt="<?= $e($featured_event["title"]) ?>"
                        class="h-[65px] w-[65px] rounded-full object-cover sm:h-[100px] sm:w-[100px] lg:h-[130px] lg:w-[130px]">

                    <div>

                        <h3 class="text-[20px] font-extrabold sm:text-[23px]">

                            <?= $e($featured_event["title"]) ?>

                        </h3>

                        <p class="mt-1 text-[20px] text-[#85849a]">

                            🎤 <?= $e($featured_event["artist"]) ?>

                        </p>

                        <p class="mt-1 text-[20px] text-[#85849a]">

                            📍 <?= $e($featured_event["venue"]) ?>

                        </p>

                        <p class="mt-1 text-[20px] text-[#85849a]">

                            📅 <?= eventDate($featured_event["concert_date"]) ?>

                        </p>

                        <p class="mt-1 text-[20px] font-bold text-[#5541f5]">

                            <?= eventPrice($featured_event["price"]) ?>

                        </p>

                    </div>

                    <div
                        class="col-span-2 rounded-xl bg-gradient-to-br from-[#4169ff] to-[#5541f5] p-[10px] text-center text-white sm:p-4 lg:col-span-1 lg:rounded-[15px]">

                        <h3 class="mb-2 text-[20px] font-extrabold tracking-wide">

                            EVENT START IN

                        </h3>

                        <div class="grid grid-cols-4 gap-[7px]" id="eventCountdown"
                            data-date="<?= $e($featured_event["concert_date"]) ?>">

                            <div class="rounded-lg bg-white px-1 py-2 text-[#5541f5]">

                                <div class="text-[23px] font-extrabold" data-unit="days">
                                    00
                                </div>

                                <div class="text-[20px] font-semibold">
                                    Hari
                                </div>

                            </div>

                            <div class="rounded-lg bg-white px-1 py-2 text-[#5541f5]">

                                <div class="text-[23px] font-extrabold" data-unit="hours">
                                    00
                                </div>

                                <div class="text-[20px] font-semibold">
                                    Jam
                                </div>

                            </div>

                            <div class="rounded-lg bg-white px-1 py-2 text-[#5541f5]">

                                <div class="text-[23px] font-extrabold" data-unit="minutes">
                                    00
                                </div>

                                <div class="text-[20px] font-semibold">
                                    Menit
                                </div>

                            </div>

                            <div class="rounded-lg bg-white px-1 py-2 text-[#5541f5]">

                                <div class="text-[23px] font-extrabold" data-unit="seconds">
                                    00
                                </div>

                                <div class="text-[20px] font-semibold">
                                    Detik
                                </div>

                            </div>

                        </div>

                        <a href="transaction.php?id=<?= (int) $featured_event["id"] ?>"
                            class="mt-3 inline-block rounded-full bg-[#17183f] px-7 py-2 text-[20px] font-bold text-white transition hover:bg-[#29295c]">

                            Get Ticket

                        </a>

                    </div>

                </div>

            </section>

        <?php endif; ?>

    </main>

    <?php include "../../components/web_part/footer.php"; ?>

    <script>

        // BANNER SLIDER

        const slides = document.querySelectorAll(".banner-slide");
        const dots = document.querySelectorAll(".banner-dot");

        let currentSlide = 0;
        let bannerInterval;

        function showSlide(index) {

            slides.forEach((slide, i) => {

                slide.classList.toggle("visible", i === index);
                slide.classList.toggle("invisible", i !== index);

                slide.classList.toggle("opacity-100", i === index);
                slide.classList.toggle("opacity-0", i !== index);

            });

            dots.forEach((dot, i) => {

                dot.classList.toggle("w-[25px]", i === index);
                dot.classList.toggle("w-[9px]", i !== index);

                dot.classList.toggle("!bg-white", i === index);
                dot.classList.toggle("bg-white/60", i !== index);

            });

            currentSlide = index;
        }

        function startSlider() {

            clearInterval(bannerInterval);

            if (slides.length > 1) {

                bannerInterval = setInterval(() => {

                    showSlide((currentSlide + 1) % slides.length);

                }, 4000);

            }
        }

        dots.forEach((dot, index) => {

            dot.addEventListener("click", () => {

                showSlide(index);
                startSlider();

            });

        });

        startSlider();


        // COUNTDOWN

        const countdown = document.getElementById("eventCountdown");

        if (countdown) {

            const targetDate = new Date(
                countdown.dataset.date + "T00:00:00"
            ).getTime();

            function updateCountdown() {

                let distance = Math.max(0, targetDate - Date.now());

                const values = {

                    days: Math.floor(
                        distance / (1000 * 60 * 60 * 24)
                    ),

                    hours: Math.floor(
                        (distance / (1000 * 60 * 60)) % 24
                    ),

                    minutes: Math.floor(
                        (distance / (1000 * 60)) % 60
                    ),

                    seconds: Math.floor(
                        (distance / 1000) % 60
                    )

                };

                Object.entries(values).forEach(([unit, value]) => {

                    const element = countdown.querySelector(
                        `[data-unit="${unit}"]`
                    );

                    if (element) {
                        element.textContent = String(value).padStart(2, "0");
                    }

                });

            }

            updateCountdown();
            setInterval(updateCountdown, 1000);

        }

    </script>

</body>

</html>