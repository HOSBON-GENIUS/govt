<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Govt Projects</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['role'] === 'project_manager'): ?>
            <li class="nav-item">
              <a class="nav-link" href="add_project_page.php">Post Project</a>
            </li>
          <?php endif; ?>

          <?php if ($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="admin.php">Admin Panel</a>
            </li>
          <?php endif; ?>

          <li class="nav-item">
            <span class="nav-link disabled text-light">Hi, <?= $_SESSION['full_name'] ?></span>
          </li>
          <li class="nav-item">
            <a class="nav-link text-warning" href="logout.php">Logout</a>
          </li>

        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="login_page.php">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="register_page.php">Register</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
