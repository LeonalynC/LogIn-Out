<?php
session_start();

$loggedInUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$hashedPassword = isset($_SESSION['hashedPassword']) ? $_SESSION['hashedPassword'] : null;

$message = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($loggedInUser) {
        $message = "$loggedInUser is already logged in. Wait for them to log out first.";
    } else {
        $_SESSION['username'] = $username;
        $_SESSION['hashedPassword'] = password_hash($password, PASSWORD_BCRYPT);
        $loggedInUser = $username;
        $hashedPassword = $_SESSION['hashedPassword'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-size: 30px;
            margin-top: 20px;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"], input[type="submit"] {
            font-size: 18px;
            padding: 5px;
            width: 50%;
            max-width: 300px;
        }
        input[type="submit"] {
            width: auto;
        }
    </style>
</head>
<body>
    <form method="POST" action="">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <input type="submit" name="login" value="Login">
    </form>

    <form method="POST" action="">
        <input type="submit" name="logout" value="Logout">
    </form>

    <?php if ($loggedInUser && !$message): ?>
        <p><strong>User logged in:</strong> <?php echo $loggedInUser; ?></p>
        <p><strong>Password:</strong> <?php echo $hashedPassword; ?></p>
    <?php endif; ?>

    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>