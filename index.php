<?php

include_once("includes/db.php");

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    die();
}

$query = $pdo->prepare("SELECT c.challengeId, c.name, c.info, c.date, c.klas FROM inschrijven i JOIN challenges c ON i.challengeId = c.challengeId JOIN users u ON i.userId = u.userId WHERE u.userId = :id LIMIT 2;");
$query->bindParam(':id', $_SESSION['loggedInUser']);
$query->execute();
$ingeschrevenopdrachten = $query->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("includes/head.php"); ?>
</head>

<body>
    <?php include_once("includes/nav.php"); ?>
    <main>
        <section class="aside">
            <h3>Welkom, <?= $user['username'] ?>!</h3>
            <div>
                <p><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>U bent een</p>
                <span>
                    <?php

                    if ($user['docent'] == 1) {
                        echo "Docent";
                    } else {
                        echo "Student";
                    }

                    ?>
                </span>
            </div>

            <div>
                <p><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
                    </svg>Aantal opdrachten</p>
                <span><?php
                        try {
                            $count = $pdo->prepare("SELECT COUNT(*) FROM challenges;");
                            $count->execute();
                            $challenges = $count->fetchAll();
                            echo $challenges[0][0];
                        } catch (PDOException $e) {
                            error_log("Error: " . $e->getMessage());
                            die("Unknown error");
                        }
                        ?></span>
            </div>

            <div>
                <p><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>Klas:</p>
                <span><?= $user['klas'] ?></span>
            </div>
        </section>

        <section class="dash-container">
            <div class="dash-opdrachten">
                <?php
                foreach ($ingeschrevenopdrachten as $opdracht) {
                ?>
                    <div onclick="window.location.href='opdrachten.php?view=<?= $opdracht['challengeId'] ?>';" class="challenge">
                        <div id="btn">
                            <h2><?= htmlspecialchars($opdracht['name']) ?></h2>
                            <a onclick="opdrachten.php?id=<?= $opdracht['challengeId'] ?>">Bekijk</a>
                        </div>
                        <p id="date"><?= htmlspecialchars($opdracht['date']) ?></p>
                    </div>
                <?php
                }
                ?>
            </div>

            <div class="deadlines-container">
                <h2>Deadlines</h2>

                <?php

                $query = $pdo->prepare("SELECT c.challengeId, name, date FROM inschrijven i JOIN challenges c ON i.challengeId = c.challengeId WHERE i.userId = :id ORDER BY date ASC;");
                $query->bindParam(':id', $_SESSION['loggedInUser']);
                $query->execute();
                $opdrachten = $query->fetchAll();

                $currentDate = null;

                foreach ($opdrachten as $opdracht) {

                    echo '<li><a href="opdrachten.php?view=' . $opdracht['challengeId'] . '">' . htmlspecialchars($opdracht['name']) . '</a>-<p>' . htmlspecialchars($opdracht['date']) . '</p></li>';
                }

                ?>

            </div>

            <div class="quicklinks">
                <a href="index.php"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>Dashboard</a>
                <a href="toevoegen.php"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>Toevoegen</a>
                <a href="opdrachten.php"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>Opdrachten</a>
                <a href="account.php"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>Account</a>
            </div>
        </section>
    </main>

</body>

</html>