<?php

include_once("includes/db.php");

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    die();
}

if (isset($_POST['submit']) && $user['docent'] == 1) {
    $name = $_POST['name'];
    $info = $_POST['info'];
    $date_deadline = $_POST['date-deadline'];

    $query = "INSERT INTO `challenges` (`challengeId`, `name`, `info`, `date`) VALUES (NULL, :name, :info, :date);";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':info', $info, PDO::PARAM_STR);
    $stmt->bindParam(':date', $date_deadline, PDO::PARAM_STR);

    try {
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    header("Location: opdrachten.php");
    die();
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
        <?php if ($user['docent'] == 1) { ?>
            <form class="form-toevoegen" method="POST">
                <h2>Opdracht maken</h2>
                <div class="form-group">
                    <input type="text" class="form-control" name="name" required placeholder="Titel">
                    <textarea type="text" class="form-control" name="info" required placeholder="Beschrijving"></textarea>
                    <input type="date" class="form-control" name="date-deadline" required>
                    <input type="submit" name="submit" class="submit-toevoegen" value="Opdracht maken">
                </div>
            </form>
        <?php } else { ?>
            <p>Alleen een docent kan een opdracht aanmaken.</p>
        <?php } ?>
    </main>
</body>

</html>