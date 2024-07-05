<?php

include_once("includes/db.php");

if (!isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    die();
}

unset($_SESSION['error']);

function generateRandomPassword($length = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';

    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomPassword;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register']) && $user['docent'] == 1) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $klas = $_POST['klas'];
    $password = generateRandomPassword(12);

    $checkQuery = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $checkQuery->bindParam(':email', $email);
    $checkQuery->execute();
    $emailExists = $checkQuery->fetchColumn();

    if ($emailExists) {
        $_SESSION['error'] = "E-mailadres al geregistreerd";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = $pdo->prepare("INSERT INTO users (username, email, password, docent, klas) VALUES (:username, :email, :password, 0, :klas)");
        $query->bindParam(':username', $name);
        $query->bindParam(':email', $email);
        $query->bindParam(':password', $hashedPassword);
        $query->bindParam(':klas', $klas);

        if ($query->execute()) {
            $_SESSION['error'] = "Succes! Het tijdelijke wachtwoord voor $name is: $password";
        } else {
            $_SESSION['error'] = "Unknown error";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_klas']) && $user['docent'] == 1) {
    $new_klas = $_POST['edit_klas'];
    $user = $_POST['user'];

    $stmt = $pdo->prepare("UPDATE `users` SET `klas` = :new_klas WHERE `users`.`userId` = :user;");
    $stmt->bindParam(':new_klas', $new_klas);
    $stmt->bindParam(':user', $user);

    try {
        $stmt->execute();
        header("Location: account.php");
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
        die("Error");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["change_password"]) && isset($_SESSION['loggedInUser'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $repeat_password = $_POST['repeat'];

    if ($new_password === $repeat_password) {
        $query = $pdo->prepare("SELECT `password` FROM `users` WHERE `userId` = :userId;");
        $query->execute([':userId' => $user['userId']]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (password_verify($current_password, $result['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_query = $pdo->prepare("UPDATE `users` SET `password` = :password WHERE `userId` = :userId;");
            if ($update_query->execute([':password' => $hashed_password, ':userId' => $user['userId']])) {
                header("Location: logout.php");
                exit();
            } else {
                $_SESSION['error'] = "Er ging iets fout";
            }
        } else {
            $_SESSION['error'] = "Onjuist huidig wachtwoord.";
        }
    } else {
        $_SESSION['error'] = "Nieuw wachtwoord en herhaalwachtwoord komen niet overeen.";
    }
}

$users = $pdo->prepare("SELECT * FROM users LIMIT 50");
$users->execute();
$users = $users->fetchAll();

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
        if ($user['docent'] == 1) { ?>
            <div class="account-tabs">
                <div class="tab">
                    <button class="tablinks" onclick="openTab(event, 'Beheer')" id="defaultOpen">Beheer</button>
                    <button class="tablinks" onclick="openTab(event, 'Account')">Account</button>
                    <button style="color: rgb(203, 72, 72); font-weight: 600;" class="tablinks" onclick="window.location.href='logout.php';">Uitloggen</button>
                </div>

                <div id="Beheer" class="tabcontent">
                    <h3>Beheer</h3>
                    <table>
                        <tr>
                            <th>Naam</th>
                            <th>Email</th>
                            <th>Klas</th>
                        </tr>
                        <?php foreach ($users as $user) { ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <form class="beheer-input" method="post"><input minlength="1" maxlength="15" name="edit_klas" type="text" placeholder="Klas" value="<?= htmlspecialchars($user['klas']) ?>"><input type="hidden" name="user" value="<?= $user['userId'] ?>"><button type="submit">Go</button></form>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>

                <div id="Account" class="tabcontent">
                    <h3>Account</h3>
                    <div id="acc-con">
                        <form class="wwchange" method="post">
                            <input minlength="2" maxlength="55" type="password" placeholder="Huidige Wachtwoord" name="current_password" required>
                            <input minlength="5" maxlength="256" type="password" placeholder="Nieuw Wachtwoord" name="new_password" required>
                            <input minlength="5" maxlength="256" type="password" placeholder="Herhaal Nieuw Wachtwoord" name="repeat" required>
                            <button type="submit" name="change_password">Verander Wachtwoord</button>
                        </form>

                        <form class="wwchange" method="post">
                            <input minlength="2" maxlength="128" type="text" placeholder="Naam" name="name" required>
                            <input minlength="3" maxlength="256" type="email" placeholder="Email" name="email" required>
                            <input minlength="1" maxlength="15" type="text" placeholder="Klas" name="klas" required>
                            <button type="submit" name="register">Registreer Student</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="account-tabs">
                <div class="tab">
                    <button class="tablinks" onclick="openTab(event, 'Account')" id="defaultOpen">Account</button>
                    <button style="color: rgb(203, 72, 72); font-weight: 600;" class="tablinks" onclick="window.location.href='logout.php';">Uitloggen</button>
                </div>

                <div id="Account" class="tabcontent">
                    <h3>Account</h3>
                    <div id="acc-con">
                        <form class="wwchange" method="post">
                            <input minlength="2" maxlength="55" type="password" placeholder="Huidige Wachtwoord" name="current_password" required>
                            <input minlength="5" maxlength="256" type="password" placeholder="Nieuw Wachtwoord" name="new_password" required>
                            <input minlength="5" maxlength="256" type="password" placeholder="Herhaal Nieuw Wachtwoord" name="repeat" required>
                            <button type="submit" name="change_password">Verander Wachtwoord</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </main>

    <?php
    if (isset($_SESSION['error'])) { ?>
        <div id="myModal" class="modal">
            <div style="width:fit-content;" class="modal-content">
                <span class="close">&times;</span>
                <div>
                    <h3><?= $_SESSION['error'] ?></h3>
                    <p>Raad de student aan om het wachtwoord gelijk te veranderen!</p>
                </div>
            </div>
        </div>
        <script src="assets/modal.js"></script>
    <?php } ?>

    <script src="assets/tabs.js"></script>

</body>

</html>