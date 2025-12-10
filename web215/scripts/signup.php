<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/auth.php';
global $mysqli;

$redirect = '/index.php';
if (!empty($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
}

$feedback_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($username === '' || $password === '' || $confirm === '') {
        $feedback_message = "<h3 style='color:red;'>Username and password fields are required.</h3>";
    } elseif ($password !== $confirm) {
        $feedback_message = "<h3 style='color:red;'>Passwords do not match.</h3>";
    } else {
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();

        if ($existing) {
            $feedback_message = "<h3 style='color:red;'>Username is already taken.</h3>";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $mysqli->prepare(
                "INSERT INTO users (username, password, first_name, last_name) VALUES (?, ?, ?, ?)"
            );
            $stmt->bind_param("ssss", $username, $hashed, $first, $last);

            if ($stmt->execute()) {
                // Auto-login new user
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;

                header("Location: $redirect");
                exit;
            } else {
                $feedback_message = "<h3 style='color:red;'>Signup failed. Try again.</h3>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body>

<?php include __DIR__ . '/../components/header.php'; ?>

<main>
    <?php if (!empty($feedback_message)) echo "<div style='text-align:center; margin-bottom:20px;'>{$feedback_message}</div>"; ?>

    <div style="max-width:400px; margin:20px auto; background:#fff; padding:20px; border-radius:8px; box-shadow:0 0 10px rgba(0,0,0,0.1);">
        <h3 style="text-align:center;">Create Your Account</h3>

        <form method="POST" action="/scripts/signup.php?redirect=<?php echo urlencode($redirect); ?>" style="margin-top:15px;">

            <label for="first_name">First Name (optional):</label><br>
            <input type="text" id="first_name" name="first_name"
                   style="width:100%; padding:6px; margin-top:4px;"><br><br>

            <label for="last_name">Last Name (optional):</label><br>
            <input type="text" id="last_name" name="last_name"
                   style="width:100%; padding:6px; margin-top:4px;"><br><br>

            <label for="username">Username:</label><br>
            <input type="text" id="username" name="username" required
                   style="width:100%; padding:6px; margin-top:4px;"><br><br>

            <label for="password">Password:</label><br>
            <input type="password" id="password" name="password" required
                   style="width:100%; padding:6px; margin-top:4px;"><br><br>

            <label for="confirm_password">Confirm Password:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required
                   style="width:100%; padding:6px; margin-top:4px;"><br><br>

            <input type="submit" value="Create Account"
                   style="width:100%; padding:8px; background:#ff3636; border:none; color:#fff; cursor:pointer; border-radius:4px;">
        </form>

        <p style="text-align:center; margin-top:10px;">
            Already have an account?  
            <a href="/scripts/login.php">Login here</a>
        </p>
    </div>
</main>

<?php include __DIR__ . '/../components/footer.php'; ?>
</body>
</html>
