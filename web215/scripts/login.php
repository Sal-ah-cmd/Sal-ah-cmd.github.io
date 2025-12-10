<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    echo "<b>Error [$errno]</b> $errstr in <b>$errfile</b> on line <b>$errline</b><br>";
    return true;
});

require_once __DIR__ . '/auth.php';
$redirect = '/index.php';
if (!empty($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    logout();
    header('Location: ' . $redirect);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_username'])) {
    $msg = attempt_login($_POST['login_username'], $_POST['login_password']);
    if ($msg === '') {
        header('Location: ' . $redirect);
        exit;
    } else {
        $feedback_message = "<h3 style='color:red;'>$msg</h3>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="/styles/style.css">
    </head>
    <body>
        <?php include __DIR__ . '/../components/header.php'; ?>

        <main>
            <?php if (isset($feedback_message)) echo "<div style='text-align:center; margin-bottom:20px;'>{$feedback_message}</div>"; ?>

            <div style="max-width:400px; margin:20px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);">
                <h3 style="text-align:center;">Login to Continue</h3>
                <form method="POST" action="/scripts/login.php?redirect=<?php echo urlencode($redirect); ?>" style="margin-top:15px;">
                    <label for="login_username">Username:</label><br>
                    <input type="text" id="login_username" name="login_username" required style="width:100%; padding:6px; margin-top:4px;"><br><br>

                    <label for="login_password">Password:</label><br>
                    <input type="password" id="login_password" name="login_password" required style="width:100%; padding:6px; margin-top:4px;"><br><br>

                    <input type="submit" value="Login" style="width:100%; padding:8px; background:#ff3636; border:none; color:#fff; cursor:pointer; border-radius:4px;">
                </form>
            </div>
        </main>

        <?php include __DIR__ . '/../components/footer.php'; ?>
    </body>
</html>
