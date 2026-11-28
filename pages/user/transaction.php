<?php
include_once "../../actions/auth/session_init.php";
require_once "../../config/database.php";

if (empty($_SESSION["user_id"])) {
    header("Location: loginpage_user.php");
    exit;
}

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$stmt = mysqli_prepare($conn, "
    SELECT c.*, g.genre_name
    FROM concerts c
    INNER JOIN genres g ON c.genre_id = g.id
    WHERE c.id = ?
    LIMIT 1
");

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$concert = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$concert) {
    header("Location: index.php");
    exit;
}

$e = fn($value) => htmlspecialchars((string) ($value ?? ""), ENT_QUOTES, "UTF-8");

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
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Transaction - Eventify</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="stylesheet" href="../../style/design-system.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-[var(--ds-background)] font-['Outfit'] text-[var(--ds-text)] antialiased">

    <?php include "../../components/web_part/header.php"; ?>

    <main class="mx-auto max-w-6xl px-4 py-10 sm:px-8">

        <h1 class="text-3xl font-extrabold mb-2">
            Transaction
        </h1>

        <p class="text-[var(--ds-text-muted)] mb-8">
            Review your selected concert before continuing.
        </p>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <section class="overflow-hidden rounded-2xl bg-white shadow-sm border border-[#eeeaf8]">

                <?php if (!empty($concert["img"])): ?>

                    <img src="<?= $e(imagePath($concert["img"])) ?>" alt="<?= $e($concert["title"]) ?>"
                        class="w-full h-64 object-cover">

                <?php endif; ?>

                <div class="p-6">

                    <span
                        class="inline-block rounded-full bg-[#efedff] px-4 py-1.5 text-sm font-semibold text-[#5541f5]">
                        <?= $e($concert["genre_name"]) ?>
                    </span>

                    <h2 class="mt-4 text-2xl font-bold">
                        <?= $e($concert["title"]) ?>
                    </h2>

                    <p class="mt-2 text-[var(--ds-text-muted)]">
                        <?= $e($concert["artist"]) ?>
                    </p>

                    <div class="mt-6 space-y-3 text-sm">

                        <p>
                            <span class="font-semibold">Date:</span>
                            <?= $e($concert["concert_date"]) ?>
                        </p>

                        <p>
                            <span class="font-semibold">Venue:</span>
                            <?= $e($concert["venue"]) ?>
                        </p>

                        <p>
                            <span class="font-semibold">Status:</span>
                            <?= $e($concert["concert_status"]) ?>
                        </p>

                    </div>

                    <?php if (!empty($concert["concert_description"])): ?>

                        <div class="mt-6 border-t pt-5">

                            <h3 class="font-bold mb-2">
                                About Event
                            </h3>

                            <p class="text-sm leading-relaxed text-[var(--ds-text-muted)]">
                                <?= nl2br($e($concert["concert_description"])) ?>
                            </p>

                        </div>

                    <?php endif; ?>

                </div>

            </section>

            <section class="h-fit rounded-2xl bg-white p-6 shadow-sm border border-[#eeeaf8]">

                <h2 class="text-xl font-bold mb-6">
                    Order Summary
                </h2>

                <div class="flex justify-between gap-4 py-4 border-b border-[#eeeaf8]">

                    <span class="text-[var(--ds-text-muted)]">
                        Concert
                    </span>

                    <span class="font-semibold text-right">
                        <?= $e($concert["title"]) ?>
                    </span>

                </div>

                <div class="flex justify-between gap-4 py-4 border-b border-[#eeeaf8]">

                    <span class="text-[var(--ds-text-muted)]">
                        Ticket Price
                    </span>

                    <span class="font-semibold">
                        Rp <?= number_format((float) $concert["price"], 0, ",", ".") ?>
                    </span>

                </div>

                <div class="flex justify-between gap-4 py-5">

                    <span class="font-bold">
                        Total
                    </span>

                    <span class="text-xl font-extrabold text-[#5541f5]">
                        Rp <?= number_format((float) $concert["price"], 0, ",", ".") ?>
                    </span>

                </div>

                <button type="button"
                    class="w-full rounded-xl bg-gradient-to-r from-[#4169ff] to-[#5541f5] py-3.5 font-bold text-white opacity-60 cursor-not-allowed">
                    Continue to Booking
                </button>

            </section>

        </div>

    </main>

    <?php include "../../components/web_part/footer.php"; ?>

</body>

</html>

<?php mysqli_close($conn); ?>