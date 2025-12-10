<?php
if (session_status() === PHP_SESSION_NONE) {
    require_once __DIR__ . '/../scripts/auth.php';
}
?>
<header>
  <h1>Sal's Sneaky Snake — WEB215</h1>
  <nav>
    <ul>
      <li><a href="/index.php">Home</a></li>
      <li><a href="/contract.php">Contract</a></li>
      <li><a href="/introduction.php">Introduction</a></li>
      <li><a href="/introductionform.php">Introduction Form</a></li>
      <li><a href="https://mern-tutorial-1-kpah.onrender.com/">MERN Tutorial</a></li>
    </ul>
  </nav>

  <div style="font-size: 0.8em; margin-top: 5px;">
    <?php if (isset($_SESSION['user_id'])): ?>
      Logged in as: <b><?php echo htmlspecialchars($_SESSION['username']); ?></b> | 
      <a href="/scripts/login.php?action=logout&redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" style="color:#FF4500;">Logout</a>
    <?php else: ?>
      Logged in as: <b>Guest</b> | 
      <a href="/scripts/login.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" style="color:#FF4500;">Login</a> |
      <a href="/scripts/signup.php?redirect=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" style="color:#FF4500;">Signup</a>
    <?php endif; ?>
  </div>
</header>
