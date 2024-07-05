<?php

include_once("includes/db.php");

if (isset($_SESSION['loggedInUser'])) {
    header("Location: index.php");
    die();
}

unset($_SESSION['error']);

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();

    $user = $query->fetch();

    if ($user !== false) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedInUser'] = $user['userId'];
            header("Location: index.php");
            die();
        }
    }
    $_SESSION['error'] = "Username of wachtwoord is niet geldig.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once("includes/head.php") ?>
</head>

<body>
    <main class="registerloginmain">

        <?php if (isset($_SESSION['error'])) { ?>
            <div style="color: red;"><?= $_SESSION['error']; ?></div>
        <?php } ?>

        <form method="post">

            <div>
                <video style="width: 60px; height: 60px" autoplay loop muted src="assets/hax.mp4"></video>
                <h1>Inloggen</h1>
                <p class="slogan"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>Je Fieldlab-account gebruiken</p>
            </div>

            <div>
                <input autofocus minlength="2" maxlength="55" type="email" name="email" placeholder="Email" required>
                <input minlength="5" maxlength="256" type="password" name="password" placeholder="Wachtwoord" required>
                <label for="captcha">
                    <input type="radio" name="captcha" id="captcha" required>
                    <img src="assets/captcha.png">
                </label>
                <button type="submit">Inloggen</button>
            </div>

        </form>
        <div class="regfooter">
            <a href="information.php#help">Help</a>
            <a href="information.php#privacy">Privacy</a>
            <a href="information.php#voorwaarden">Voorwaarden</a>
        </div>
    </main>
</body>

</html>