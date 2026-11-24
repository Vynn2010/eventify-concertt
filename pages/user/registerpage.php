<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../repo/user.php";

session_start();

$pesan = "";
$sukses = false;

if (isset($_POST["daftar"])) {
    $email = trim($_POST["email"] ?? "");
    $passwords = $_POST["password"] ?? "";

    if ($email === "" || $passwords === "") {
        $pesan = "Email dan password tidak boleh kosong.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pesan = "Format email tidak valid.";
    } else {
        $hasil = UserRegister::findUserRegister($conn, $email);

        if (mysqli_num_rows($hasil) > 0) {
            $pesan = "Email sudah terdaftar.";
        } else {
            $ins = UserRegister::getUserRegister($conn, $email, $passwords);

            if (mysqli_stmt_execute($ins)) {
                $sukses = true;
                $pesan = "Registrasi berhasil. Silakan login.";
            } else {
                $pesan = "Registrasi gagal, coba lagi.";
            }

            mysqli_stmt_close($ins);
        }
    }
}

$hide_auth = true;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registration - Eventify</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Centralized Color System -->
    <link rel="stylesheet" href="../../style/design-system.css">

    <!-- Outfit Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen bg-[var(--ds-background)] font-['Outfit'] text-[var(--ds-text)] antialiased">

    <?php include "../../components/web_part/header.php"; ?>

    <main class="relative flex min-h-[calc(100vh-72px)] items-center justify-center overflow-hidden px-4 py-10 sm:px-6">

        <!-- Background Circle Top Right -->
        <div class="pointer-events-none absolute -right-20 -top-20 h-80 w-80 rounded-full bg-[var(--ds-decoration)]">
        </div>

        <!-- Background Circle Bottom Left -->
        <div class="pointer-events-none absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-[var(--ds-decoration)]">
        </div>

        <!-- Registration Card -->
        <section
            class="relative z-10 w-full max-w-[510px] rounded-[30px] border border-white/80 bg-[var(--ds-surface)] px-8 py-9 shadow-[0_3px_4px_rgba(25,25,50,0.25)] sm:px-12 sm:py-10 lg:px-[54px]">

            <!-- Registration Label -->
            <div class="mb-2 flex justify-center">
                <span
                    class="rounded-full bg-[var(--ds-decoration)] px-4 py-1 text-base font-bold text-[var(--ds-text)]">
                    REGISTRATION
                </span>
            </div>

            <!-- Heading -->
            <div class="mb-7 text-center">
                <h1 class="text-3xl font-extrabold tracking-tight text-[var(--ds-text)] sm:text-4xl">
                    Create your account
                </h1>

                <p class="mx-auto mt-2 max-w-sm text-base font-medium leading-snug text-[var(--ds-text-muted)]">
                    Make account to start your journey with Eventify for best moments
                </p>
            </div>

            <!-- Registration Message -->
            <?php if ($pesan !== ""): ?>
                <div class="mb-5 rounded-xl border px-4 py-3 text-center text-base
                    <?= $sukses
                        ? 'border-[var(--ds-success)]/20 bg-[var(--ds-success)]/5 text-[var(--ds-success)]'
                        : 'border-[var(--ds-danger)]/20 bg-[var(--ds-danger)]/5 text-[var(--ds-danger)]' ?>"
                    role="alert">
                    <?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?>

                    <?php if ($sukses): ?>
                        <div class="mt-2">
                            <a href="loginpage_user.php" class="font-bold underline">
                                Login sekarang
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form method="POST" class="space-y-5">

                <!-- Email -->
                <div>
                    <label for="email" class="mb-2 block text-base font-semibold text-[var(--ds-text)]">
                        Email Address
                    </label>

                    <div class="relative">

                        <!-- Email Icon -->
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-5 w-5 text-[var(--ds-text)]" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path d="m3 7 9 6 9-6" />
                            </svg>
                        </div>

                        <input id="email" type="email" name="email" placeholder="nama@gmail.com" autocomplete="email"
                            required
                            value="<?= $sukses ? '' : htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                            class="w-full rounded-xl border border-[var(--ds-input-border)] bg-[var(--ds-input-bg)] py-3 pl-12 pr-4 text-base font-medium text-[var(--ds-text)] outline-none transition placeholder:text-[var(--ds-placeholder)] focus:border-[var(--ds-primary)] focus:ring-2 focus:ring-[var(--ds-primary)]/15">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="mb-2 block text-base font-semibold text-[var(--ds-text)]">
                        Password
                    </label>

                    <div class="relative">

                        <!-- Lock Icon -->
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="h-5 w-5 text-[var(--ds-text)]" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <rect x="5" y="10" width="14" height="11" rx="2" />
                                <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                                <circle cx="12" cy="15" r="1" />
                            </svg>
                        </div>

                        <input id="password" type="password" name="password" placeholder="Enter your password"
                            autocomplete="new-password" required
                            class="w-full rounded-xl border border-[var(--ds-input-border)] bg-[var(--ds-input-bg)] py-3 pl-12 pr-12 text-base font-medium text-[var(--ds-text)] outline-none transition placeholder:text-[var(--ds-placeholder)] focus:border-[var(--ds-primary)] focus:ring-2 focus:ring-[var(--ds-primary)]/15">

                        <!-- Show Password -->
                        <button type="button" id="togglePassword" aria-label="Show password"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-[var(--ds-text-muted)] transition hover:text-[var(--ds-primary)]">
                            <svg id="eyeIcon" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Register Button -->
                <div class="pt-3">
                    <button type="submit" name="daftar" value="daftar"
                        class="w-full rounded-xl bg-gradient-to-r from-[var(--ds-gradient-start)] to-[var(--ds-gradient-end)] py-3.5 text-base font-bold text-white shadow-md shadow-[var(--ds-primary)]/20 transition duration-200 hover:-translate-y-0.5 hover:brightness-105 hover:shadow-lg active:translate-y-0">
                        Register
                    </button>
                </div>

                <!-- Login Link -->
                <p class="pt-3 text-center text-base font-medium text-[var(--ds-text)]">
                    already have account?
                    <a href="loginpage_user.php"
                        class="ml-1 font-bold text-[var(--ds-primary)] transition hover:underline">
                        Login
                    </a>
                </p>

            </form>

        </section>
    </main>

    <!-- Show / Hide Password -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';

            passwordInput.type = isPassword ? 'text' : 'password';

            togglePassword.setAttribute(
                'aria-label',
                isPassword ? 'Hide password' : 'Show password'
            );

            eyeIcon.innerHTML = isPassword
                ? `
                    <path d="M3 3l18 18"/>
                    <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                    <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.5 0 10 7 10 7a15 15 0 0 1-3 3.8"/>
                    <path d="M6.6 6.6C3.7 8.5 2 12 2 12s3.5 7 10 7c1 0 2-.2 2.9-.5"/>
                `
                : `
                    <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z"/>
                    <circle cx="12" cy="12" r="3"/>
                `;
        });
    </script>

</body>

</html>

<?php
mysqli_close($conn);
?>