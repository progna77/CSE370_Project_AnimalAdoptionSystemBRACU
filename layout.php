<?php

require_once __DIR__ . '/auth.php';

function page_top(string $title): void
{
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width,initial-scale=1"
    >

    <title>
        <?= h($title) ?> - Cat Adoption Center
    </title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600&family=Nunito:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="assets/style.css"
    >

</head>

<body class="app-body">

    <header class="topbar">

        <a
            class="brand"
            href="dashboard.php"
        >

            <img
                src="assets/cat-logo.png"
                alt="logo"
            >

            <span>
                Cat Adoption Center
            </span>

        </a>

        <nav>

            <a href="animals.php">
                Animals
            </a>

            <a href="reports.php">
                Reports
            </a>

            <a href="adoptions.php">
                Adoptions
            </a>

            <a href="feeding.php">
                Feeding
            </a>

            <a href="treatments.php">
                Treatments
            </a>

            <a href="donations.php">
                Donations
            </a>

            <a href="expenses.php">
                Expenses
            </a>

            <a href="profile.php">
                Profile
            </a>

            <a
                class="logout"
                href="logout.php"
            >
                Logout
            </a>

        </nav>

    </header>

    <main class="page-wrap">

        <?php if (!empty($_GET['msg'])): ?>

            <div class="notice">
                <?= h($_GET['msg']) ?>
            </div>

        <?php endif; ?>

<?php
}

function page_bottom(): void
{
?>

</main>

<footer>
    Made for campus animal rescue & adoption 🐾
</footer>

</body>

</html>

<?php
}
?>