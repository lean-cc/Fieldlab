<?php

include_once("includes/db.php");

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    die();
}

$query = $pdo->prepare("SELECT * FROM challenges");
$query->execute();
$challenges = $query->fetchAll();

if (isset($_GET['id']) && isset($_GET['type'])) {
    if ($_GET['type'] == 'inschrijven') {
        $query = $pdo->prepare("INSERT INTO `inschrijven` (`userId`, `challengeId`) VALUES (:user, :challenge);");
    } elseif ($_GET['type'] == 'uitschrijven') {
        $query = $pdo->prepare("DELETE FROM `inschrijven` WHERE `userId` = :user AND `challengeId` = :challenge;");
    }

    $query->bindParam(':user', $_SESSION['loggedInUser']);
    $query->bindParam(':challenge', $_GET['id']);
    try {
        $query->execute();
        header("Location: opdrachten.php");
    } catch (PDOException $th) {
        error_log($th);
        die("Unknown error");
    }
}

if (isset($_POST['delete']) && isset($_POST['opdracht']) && $user['docent'] == 1) {
    $query1 = $pdo->prepare("DELETE FROM inschrijven WHERE challengeId = :opdracht");
    $query1->bindParam(':opdracht', $_POST['opdracht']);

    $query2 = $pdo->prepare("DELETE FROM challenges WHERE challengeId = :opdracht");
    $query2->bindParam(':opdracht', $_POST['opdracht']);

    try {
        $pdo->beginTransaction();
        $query1->execute();
        $query2->execute();
        $pdo->commit();
        header("Location: opdrachten.php");
    } catch (PDOException $th) {
        error_log($th);
        die("Unknown error");
    }
}

if (isset($_POST['edit']) && isset($_POST['opdracht']) && $user['docent'] == 1) {

    $query = $pdo->prepare("UPDATE `challenges` SET `name` = :title, `info` = :description WHERE `challenges`.`challengeId` = :opdracht;");
    $query->bindParam(':opdracht', $_POST['opdracht']);
    $query->bindParam(':title', $_POST['title']);
    $query->bindParam(':description', $_POST['description']);

    try {
        $query->execute();
        header("Location: opdrachten.php");
    } catch (PDOException $th) {
        error_log($th);
        die("Unknown error");
    }
}

if (isset($_GET['view'])) {
    $query = $pdo->prepare("SELECT * FROM challenges WHERE challengeId = :id");
    $query->bindParam(':id', $_GET['view']);
    $query->execute();
    $modaldetails = $query->fetchAll();

    foreach ($modaldetails as $detail) { ?>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>

                <?php if ($user['docent'] == 1) { ?>

                    <form method="post" class="opdracht-details">
                        <span>
                            <div>
                                <h2><input name="title" type="text" value="<?= htmlspecialchars($detail['name']) ?>"></h2>
                                <h4><?= htmlspecialchars($detail['date']) ?></h4>
                            </div>
                            <div>
                                <input type="hidden" name="opdracht" value="<?= $detail['challengeId'] ?>">
                                <button type="submit" name="delete">Verwijder</button>
                                <button type="submit" name="edit">Wijzig</button>
                            </div>
                        </span>
                        <span>
                            Ingescheven studenten:
                            <?php

                            $query = $pdo->prepare("SELECT i.inschrijvId, i.challengeId, u.*
                                                    FROM inschrijven i
                                                    JOIN users u ON i.userId = u.userId
                                                    WHERE i.challengeId = :challenge;");
                            $query->bindParam(':challenge', $detail['challengeId']);
                            $query->execute();
                            $studenten = $query->fetchAll();

                            foreach ($studenten as $student) {
                                echo $student['username'] . ", ";
                            }

                            ?>
                        </span>
                        <p><textarea rows="30" name="description" type="text"><?= htmlspecialchars($detail['info']) ?></textarea></p>
                    </form>

                <?php } else { ?>

                    <div class="opdracht-details">
                        <span>
                            <div>
                                <h2><?= htmlspecialchars($detail['name']) ?></h2>
                                <h4><?= htmlspecialchars($detail['date']) ?></h4>
                            </div>
                        </span>
                        <p><?= nl2br(htmlspecialchars($detail['info'])) ?></p>
                    </div>

                <?php } ?>

            </div>
        </div>
        <script src="assets/modal.js"></script>

<?php }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("includes/head.php"); ?>
</head>

<body>
    <?php include_once("includes/nav.php"); ?>
    <main>
        <?php
        foreach ($challenges as $challenge) {
            $query = $pdo->prepare("SELECT COUNT(*) FROM `inschrijven` WHERE `userId` = :user AND `challengeId` = :challenge");
            $query->bindParam(':user', $_SESSION['loggedInUser']);
            $query->bindParam(':challenge', $challenge['challengeId']);
            $query->execute();
            $isSubscribed = $query->fetchColumn() > 0;
        ?>
            <div onclick="window.location.href='opdrachten.php?view=<?= $challenge['challengeId'] ?>';" id="myBtn" class="challenge">
                <div id="btn">
                    <h2><?= htmlspecialchars($challenge['name']) ?></h2>
                    <?php
                    if ($isSubscribed) {
                        echo "<a href='opdrachten.php?id=" . $challenge['challengeId'] . "&type=uitschrijven'>Uitschrijven</a>";
                    } else {
                        echo "<a href='opdrachten.php?id=" . $challenge['challengeId'] . "&type=inschrijven'>Inschrijven</a>";
                    }
                    ?>
                </div>
                <p><?= htmlspecialchars($challenge['info']) ?></p>
                <p id="date"><?= htmlspecialchars($challenge['date']) ?></p>
            </div>
        <?php
        }
        ?>
    </main>
</body>

</html>