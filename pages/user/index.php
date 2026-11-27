<?php
include_once "../../actions/auth/session_init.php";
require_once "../../config/database.php";

$hide_auth = false;

$e = fn($value) => htmlspecialchars((string) ($value ?? ""), ENT_QUOTES, "UTF-8");

function formatDate($date)
{
    return date("d M Y", strtotime($date));
}

function formatPrice($price)
{
    return "Rp " . number_format((float) $price, 0, ",", ".");
}

function imagePath($path)
{
    if (empty($path)) {
        return "";
    }

    if (preg_match('/^https?:\/\//i', $path)) {
        return $path;
    }

    return "../../" . ltrim($path, "/");
}

// Authentication
$is_logged_in = !empty($_SESSION["user_id"]);

function ticketLink($event_id, $is_logged_in)
{
    if (!$is_logged_in) {
        return "loginpage_user.php";
    }

    return "event.php?id=" . (int) $event_id;
}

$search = trim($_GET["q"] ?? "");

$selected_genre = isset($_GET["genre_id"])
    ? max(0, (int) $_GET["genre_id"])
    : 0;

// Genre dari database
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

// Event dari database
$sql = "SELECT c.*, g.genre_name
        FROM concerts c
        INNER JOIN genres g ON c.genre_id = g.id
        WHERE 1=1";

$params = [];
$types = "";

if ($search !== "") {
    $sql .= " AND (
        c.title LIKE ?
        OR c.artist LIKE ?
        OR c.venue LIKE ?
    )";

    $keyword = "%" . $search . "%";

    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;

    $types .= "sss";
}

if ($selected_genre > 0) {
    $sql .= " AND c.genre_id = ?";

    $params[] = $selected_genre;
    $types .= "i";
}

$sql .= " ORDER BY
          (c.concert_date >= CURDATE()) DESC,
          c.concert_date ASC";

$stmt = mysqli_prepare($conn, $sql);

if ($params) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);

$result_concerts = mysqli_stmt_get_result($stmt);
$concerts = mysqli_fetch_all($result_concerts, MYSQLI_ASSOC);

mysqli_stmt_close($stmt);

// Special Concerts: 3 event terdekat
$sql_special = "
    SELECT c.*, g.genre_name
    FROM concerts c
    INNER JOIN genres g ON c.genre_id = g.id
    WHERE c.concert_date >= CURDATE()
    ORDER BY c.concert_date ASC
    LIMIT 3
";

$result_special = mysqli_query($conn, $sql_special);

$special_concerts = $result_special
    ? mysqli_fetch_all($result_special, MYSQLI_ASSOC)
    : [];

// Featured Countdown: event terdekat
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

if (!$featured_event) {
    $result_featured = mysqli_query(
        $conn,
        "SELECT c.*, g.genre_name
         FROM concerts c
         INNER JOIN genres g ON c.genre_id = g.id
         ORDER BY c.concert_date DESC
         LIMIT 1"
    );

    $featured_event = $result_featured
        ? mysqli_fetch_assoc($result_featured)
        : null;
}

// Banner statis
$slider_images = [
    "../../assets/img/slider1.jpg",
    "../../assets/img/lider2.jpg",
    "../../assets/img/slider3.jpg",
    "../../assets/img/slider4.jpg"
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Eventify - Find Your Memory</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="stylesheet" href="../../style/design-system.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style type="text/tailwindcss">
        @theme {
            --font-sans: "Outfit", ui-sans-serif, system-ui, sans-serif;
        }
    </style>

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: #fbf8ff;
            color: #17183f;
        }

        .home-slide {
            opacity: 0;
            visibility: hidden;
            transition: opacity .65s ease, visibility .65s ease;
        }

        .home-slide.active {
            opacity: 1;
            visibility: visible;
        }

        .slider-dot {
            width: 9px;
            height: 9px;
            border-radius: 99px;
            background: rgba(255, 255, 255, .55);
            transition: all .25s ease;
        }

        .slider-dot.active {
            width: 26px;
            background: white;
        }

        .genre-pill {
            border: 1px solid #c9c5ff;
            color: #5541f5;
            background: white;
            border-radius: 999px;
            padding: 8px 20px;
            font-size: 13px;
            white-space: nowrap;
            transition: .2s;
        }

        .genre-pill.active,
        .genre-pill:hover {
            background: #5541f5;
            border-color: #5541f5;
            color: white;
        }

        .concert-card {
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: white;
            border: 1px solid #eeeaf8;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(40, 35, 90, .07);
            transition: .25s ease;
            text-decoration: none;
            color: inherit;
        }

        .concert-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(40, 35, 90, .13);
        }

        .concert-card-image {
            position: relative;
            width: 100%;
            height: 230px;
            overflow: hidden;
            background: #eeecff;
        }

        .concert-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: .35s ease;
        }

        .concert-card:hover .concert-card-image img {
            transform: scale(1.04);
        }

        .concert-card-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 20px;
        }

        .concert-card-description {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .special-card {
            min-width: 0;
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .special-card img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 14px;
            display: block;
            transition: .25s;
        }

        .special-card:hover img {
            transform: scale(1.015);
        }

        .featured-panel {
            background: white;
            border: 1px solid #e7e3f4;
            border-radius: 28px;
            overflow: hidden;
            min-height: 250px;
        }

        .timer-box {
            background: white;
            color: #5541f5;
            border-radius: 13px;
            min-width: 60px;
            padding: 15px 10px;
            text-align: center;
        }

        @media (max-width: 639px) {
            .concert-card-image {
                height: 170px;
            }

            .concert-card-content {
                padding: 15px;
            }

            .special-card img {
                height: 125px;
            }

            .featured-panel {
                border-radius: 20px;
            }

            .timer-box {
                min-width: 43px;
                padding: 10px 5px;
            }
        }
    </style>
</head>

<body class="min-h-screen antialiased">

    <?php include "../../components/web_part/header.php"; ?>

    <main class="mx-auto max-w-[1720px] px-4 sm:px-8 lg:px-[52px]">

        <!-- Banner Slider -->
        <section class="mt-4 sm:mt-6">

            <div id="homeSlider"
                class="relative h-[170px] sm:h-[280px] lg:h-[360px] overflow-hidden rounded-xl sm:rounded-2xl bg-[#e8e6ff] shadow-sm">

                <?php foreach ($slider_images as $index => $image): ?>

                    <div class="home-slide absolute inset-0 <?= $index === 0 ? 'active' : '' ?>">

                        <img src="<?= $e($image) ?>" alt="Eventify Banner <?= $index + 1 ?>"
                            class="h-full w-full object-cover">

                    </div>

                <?php endforeach; ?>

                <div class="absolute inset-x-0 bottom-0 h-14 bg-gradient-to-t from-black/30 to-transparent"></div>

                <div class="absolute bottom-4 left-0 right-0 flex justify-center items-center gap-2">

                    <?php foreach ($slider_images as $index => $image): ?>

                        <button type="button" class="slider-dot <?= $index === 0 ? 'active' : '' ?>"
                            data-slide="<?= $index ?>" aria-label="Slide <?= $index + 1 ?>">
                        </button>

                    <?php endforeach; ?>

                </div>

            </div>

        </section>

        <!-- Find Your Memory -->
        <section class="pt-7 sm:pt-10">

            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">

                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">
                    Find Your Memory
                </h1>

                <?php if ($search !== '' || $selected_genre > 0): ?>

                    <a href="index.php" class="text-sm font-semibold text-[#e83d9a]">
                        Reset Filter
                    </a>

                <?php endif; ?>

            </div>

            <!-- Genre Filter -->
            <div class="flex gap-3 overflow-x-auto pb-3">

                <a href="index.php<?= $search !== '' ? '?q=' . urlencode($search) : '' ?>"
                    class="genre-pill <?= $selected_genre === 0 ? 'active' : '' ?>">
                    All Genre
                </a>

                <?php foreach ($genres as $genre): ?>

                    <?php
                    $query = ["genre_id" => (int) $genre["id"]];

                    if ($search !== "") {
                        $query["q"] = $search;
                    }
                    ?>

                    <a href="index.php?<?= http_build_query($query) ?>"
                        class="genre-pill <?= $selected_genre === (int) $genre["id"] ? 'active' : '' ?>">

                        <?= $e($genre["genre_name"]) ?>

                    </a>

                <?php endforeach; ?>

            </div>

            <?php if ($search !== ""): ?>

                <p class="text-base text-[#77758d] mt-1">
                    Hasil pencarian untuk "<?= $e($search) ?>" · <?= count($concerts) ?> event
                </p>

            <?php endif; ?>

            <!-- Event Cards -->
            <?php if ($concerts): ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 sm:gap-6 mt-5">

                    <?php foreach ($concerts as $concert): ?>

                        <a href="transaction.php?id=<?= (int) $concert["id"] ?>" class="concert-card">

                            <!-- Event Image -->
                            <div class="concert-card-image">

                                <?php if (!empty($concert["img"])): ?>

                                    <img src="<?= $e(imagePath($concert["img"])) ?>" alt="<?= $e($concert["title"]) ?>"
                                        loading="lazy">

                                <?php else: ?>

                                    <div class="h-full w-full bg-gradient-to-br from-[#e5e7ff] to-[#fce7f3]"></div>

                                <?php endif; ?>

                                <!-- Genre -->
                                <span
                                    class="absolute top-4 left-4 rounded-full bg-white/95 px-4 py-2 text-[20px] font-semibold text-[#5541f5] shadow-sm">
                                    <?= $e($concert["genre_name"]) ?>
                                </span>

                            </div>

                            <!-- Event Information -->
                            <div class="concert-card-content">

                                <h2 class="text-[22px] sm:text-[24px] font-bold leading-tight text-[#17183f]">
                                    <?= $e($concert["title"]) ?>
                                </h2>

                                <p class="text-[20px] font-medium text-[#77758d] mt-2">
                                    <?= $e($concert["artist"]) ?>
                                </p>

                                <div class="flex items-center gap-3 mt-5">

                                    <span class="text-[22px]">📅</span>

                                    <span class="text-[20px] font-medium text-[#55536d]">
                                        <?= formatDate($concert["concert_date"]) ?>
                                    </span>

                                </div>

                                <div class="flex items-center gap-3 mt-3">

                                    <span class="text-[22px]">📍</span>

                                    <span class="text-[20px] font-medium text-[#55536d]">
                                        <?= $e($concert["venue"]) ?>
                                    </span>

                                </div>

                                <div
                                    class="mt-6 pt-5 border-t border-[#eeeaf8] flex flex-wrap items-center justify-between gap-3">

                                    <div>
                                        <p class="text-[20px] text-[#85849a]">
                                            Ticket Price
                                        </p>

                                        <p class="text-[22px] font-bold text-[#5541f5] mt-1">
                                            <?= formatPrice($concert["price"]) ?>
                                        </p>
                                    </div>

                                    <span
                                        class="rounded-full bg-gradient-to-r from-[#4169ff] to-[#5541f5] px-5 py-3 text-[20px] font-semibold text-white transition hover:brightness-110">
                                        View Event
                                    </span>

                                </div>

                            </div>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="mt-5 rounded-2xl bg-white border border-[#eeeaf8] py-14 text-center">

                    <h3 class="font-bold text-xl">
                        Event tidak ditemukan
                    </h3>

                    <p class="text-base text-[#85849a] mt-2">
                        Coba kata kunci atau genre lainnya.
                    </p>

                    <a href="index.php"
                        class="inline-block mt-5 rounded-full bg-[#5541f5] px-6 py-3 text-base font-semibold text-white">
                        Lihat Semua Event
                    </a>

                </div>

            <?php endif; ?>

        </section>

        <!-- Special Concerts -->
        <section class="pt-10 sm:pt-14">

            <div class="flex items-center justify-between mb-6">

                <div>
                    <h2 class="text-2xl sm:text-3xl font-bold">
                        🎟 Special Concerts For You
                    </h2>

                    <p class="text-base text-[#85849a] mt-1">
                        Discover upcoming concerts
                    </p>
                </div>

                <a href="event.php" class="text-base font-semibold text-[#5541f5] whitespace-nowrap">
                    See All
                </a>

            </div>

            <?php if ($special_concerts): ?>

                <div class="grid grid-cols-3 gap-4 sm:gap-7 lg:gap-9">

                    <?php foreach ($special_concerts as $concert): ?>

                        <a href="transaction.php?id=<?= (int) $concert["id"] ?>" class="special-card">

                            <?php if (!empty($concert["img"])): ?>

                                <img src="<?= $e(imagePath($concert["img"])) ?>" alt="<?= $e($concert["title"]) ?>" loading="lazy">

                            <?php else: ?>

                                <div class="h-[125px] sm:h-[220px] rounded-xl bg-gradient-to-br from-[#e5e7ff] to-[#fce7f3]"></div>

                            <?php endif; ?>

                            <h3 class="mt-3 text-[20px] sm:text-[22px] font-bold leading-tight line-clamp-2">
                                <?= $e($concert["title"]) ?>
                            </h3>

                            <p class="mt-2 text-[20px] text-[#77758d] truncate">
                                <?= $e($concert["venue"]) ?>
                            </p>

                            <p class="mt-2 text-[20px] text-[#77758d]">
                                <?= formatDate($concert["concert_date"]) ?>
                            </p>

                            <p class="mt-2 text-[20px] font-bold text-[#5541f5]">
                                <?= formatPrice($concert["price"]) ?>
                            </p>

                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <p class="text-base text-[#85849a]">
                    Belum ada special concert.
                </p>

            <?php endif; ?>

        </section>

        <!-- Featured Countdown -->
        <?php if ($featured_event): ?>

            <section class="pt-10 sm:pt-14 pb-12">

                <div
                    class="relative overflow-hidden rounded-[28px] border border-[#e7e3ff] bg-white shadow-[0_12px_40px_rgba(65,73,180,0.08)]">

                    <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_0.8fr]">

                        <!-- Event Information -->
                        <div class="flex flex-col sm:flex-row items-center gap-6 sm:gap-8 p-6 sm:p-10 lg:p-12">

                            <?php if (!empty($featured_event["img"])): ?>

                                <img src="<?= $e(imagePath($featured_event["img"])) ?>"
                                    alt="<?= $e($featured_event["title"]) ?>"
                                    class="w-32 h-32 sm:w-40 sm:h-40 lg:w-44 lg:h-44 rounded-2xl object-cover shrink-0 shadow-lg">

                            <?php else: ?>

                                <div class="w-32 h-32 sm:w-40 sm:h-40 lg:w-44 lg:h-44 rounded-2xl bg-[#eeecff] shrink-0"></div>

                            <?php endif; ?>

                            <div class="min-w-0 w-full text-center sm:text-left">

                                <div class="flex flex-wrap justify-center sm:justify-start gap-2 mb-4">

                                    <span
                                        class="rounded-full bg-[#ffe5f1] text-[#e83d9a] px-4 py-1.5 text-base font-semibold">
                                        Upcoming Event
                                    </span>

                                    <span
                                        class="rounded-full bg-[#efedff] text-[#5541f5] px-4 py-1.5 text-base font-semibold">
                                        <?= $e($featured_event["genre_name"]) ?>
                                    </span>

                                </div>

                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-[#17183f] leading-tight">
                                    <?= $e($featured_event["title"]) ?>
                                </h2>

                                <p class="text-xl text-[#77758d] mt-2">
                                    <?= $e($featured_event["artist"]) ?>
                                </p>

                                <p class="text-xl text-[#77758d] mt-3">
                                    📍 <?= $e($featured_event["venue"]) ?>
                                </p>

                                <p class="text-2xl font-bold text-[#5541f5] mt-4">
                                    <?= formatPrice($featured_event["price"]) ?>
                                </p>

                            </div>

                        </div>

                        <!-- Countdown -->
                        <div
                            class="relative overflow-hidden bg-gradient-to-br from-[#4169ff] to-[#5541f5] text-white px-6 py-9 sm:px-10 sm:py-12 lg:px-8 flex flex-col justify-center items-center">

                            <div class="absolute w-64 h-64 rounded-full border border-white/10 -top-32 -right-20"></div>
                            <div class="absolute w-48 h-48 rounded-full border border-white/10 -bottom-24 -left-16"></div>

                            <div class="relative z-10 w-full text-center">

                                <p class="text-lg sm:text-xl font-bold tracking-widest">
                                    EVENT START IN
                                </p>

                                <p class="text-base text-white/75 mt-2 mb-7">
                                    Don't miss this exciting experience!
                                </p>

                                <div class="flex justify-center gap-2 sm:gap-3" id="countdown"
                                    data-date="<?= $e($featured_event["concert_date"]) ?>">

                                    <div
                                        class="w-[68px] sm:w-[76px] py-4 rounded-2xl bg-white text-[#5541f5] shadow-lg text-center">
                                        <div class="font-bold text-2xl sm:text-3xl" data-unit="days">00</div>
                                        <div class="text-base font-medium mt-1">Hari</div>
                                    </div>

                                    <div
                                        class="w-[68px] sm:w-[76px] py-4 rounded-2xl bg-white text-[#5541f5] shadow-lg text-center">
                                        <div class="font-bold text-2xl sm:text-3xl" data-unit="hours">00</div>
                                        <div class="text-base font-medium mt-1">Jam</div>
                                    </div>

                                    <div
                                        class="w-[68px] sm:w-[76px] py-4 rounded-2xl bg-white text-[#5541f5] shadow-lg text-center">
                                        <div class="font-bold text-2xl sm:text-3xl" data-unit="minutes">00</div>
                                        <div class="text-base font-medium mt-1">Menit</div>
                                    </div>

                                    <div
                                        class="w-[68px] sm:w-[76px] py-4 rounded-2xl bg-white text-[#5541f5] shadow-lg text-center">
                                        <div class="font-bold text-2xl sm:text-3xl" data-unit="seconds">00</div>
                                        <div class="text-base font-medium mt-1">Detik</div>
                                    </div>

                                </div>

                                <a href="<?= $e(ticketLink($featured_event["id"], $is_logged_in)) ?>"
                                    class="inline-flex items-center justify-center mt-8 rounded-full bg-[#17183f] hover:bg-[#29275c] px-12 py-3.5 text-base font-bold text-white shadow-lg transition duration-300 hover:-translate-y-1">
                                    Get Ticket
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </section>

        <?php endif; ?>

    </main>

    <?php include "../../components/web_part/footer.php"; ?>

    <script>
        // Banner Slider
        const homeSlides = document.querySelectorAll(".home-slide");
        const sliderDots = document.querySelectorAll(".slider-dot");

        let activeSlide = 0;
        let sliderTimer;

        function showHomeSlide(index) {
            homeSlides.forEach((slide, i) => {
                slide.classList.toggle("active", i === index);
            });

            sliderDots.forEach((dot, i) => {
                dot.classList.toggle("active", i === index);
            });

            activeSlide = index;
        }

        function startHomeSlider() {
            clearInterval(sliderTimer);

            if (homeSlides.length > 1) {
                sliderTimer = setInterval(() => {
                    showHomeSlide((activeSlide + 1) % homeSlides.length);
                }, 4000);
            }
        }

        sliderDots.forEach((dot, index) => {
            dot.addEventListener("click", () => {
                showHomeSlide(index);
                startHomeSlider();
            });
        });

        startHomeSlider();

        // Countdown
        const countdown = document.getElementById("countdown");

        if (countdown) {
            const targetDate = new Date(
                countdown.dataset.date + "T00:00:00"
            ).getTime();

            function updateCountdown() {
                let distance = targetDate - Date.now();

                if (distance < 0) {
                    distance = 0;
                }

                const values = {
                    days: Math.floor(distance / (1000 * 60 * 60 * 24)),
                    hours: Math.floor((distance / (1000 * 60 * 60)) % 24),
                    minutes: Math.floor((distance / (1000 * 60)) % 60),
                    seconds: Math.floor((distance / 1000) % 60)
                };

                Object.entries(values).forEach(([unit, value]) => {
                    const element = countdown.querySelector(
                        '[data-unit="' + unit + '"]'
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