<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sneaky Snake's Used Cars App 2</title>
    <link rel="stylesheet" href="styles/default.css">
</head>
<body>
    <header>
        <h1> Sneaky Snake's Used Cars App 2 </h1>
        <div style="font-size: 0.8em; margin-top: 5px;">
            <?php if (isset($_SESSION['user_id'])): ?>
                Logged in as: <b><?php echo htmlspecialchars($_SESSION['username']); ?></b> | 
                <a href="index.php?action=logout" style="color: #FFA500;">Logout</a>
            <?php else: ?>
                Logged in as: <b>Guest</b> | 
                <a href="#login-form" style="color: #FFA500;">Login</a>
            <?php endif; ?>
        </div>
    </header>
    <main>