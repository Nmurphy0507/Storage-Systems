<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $email = $_POST["Email"];
    
    if (substr($email, -14) !== "@frostburg.edu") {
        $is_invalid = true;
    } else {
        $mysqli = require __DIR__ . "/database.php";

        $sql = sprintf("SELECT * FROM user
                        WHERE Email = '%s'",
                        $mysqli->real_escape_string($email));
        
        $result = $mysqli->query($sql);

        $user = $result->fetch_assoc();

        if ($user) {
            if (password_verify($_POST["password"], $user["password_hash"])) {
               
                session_start();

                session_regenerate_id();

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_type"] = $user["user_type"];

                header("Location: home.php");
                exit;
            }
        }

        $is_invalid = true;
    }
}
?>

<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./src/main.css">
</head>

<body>
    <div class="container">
        <form class="form" id="loginForm" method="POST" action="login.php">
            <h1 class="form__title">Login</h1>

            <?php if ($is_invalid): ?>
                <em> Invalid login. Email must be @frostburg.edu </em>
            <?php endif; ?>

            <div class="form__input-group">
                <input type="text" name="Email" class="form__input" autofocus placeholder="Email"
                 value="<?= htmlspecialchars($_POST["Email"] ?? "") ?>">
                <div class="form__input-error-message"></div>
            </div>
            <div class="form__input-group">
                <input type="password" name="password" class="form__input" placeholder="Password">
                <div class="form__input-error-message"></div>
            </div>
            <button class="form__button" type="submit">Login</button>
        </form>
    </div>

    <script src="./src/main.js"></script>
</body>
</html>