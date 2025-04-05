<?php
/**
 * Navigation Bar Component
 * 
 * This component provides the main navigation interface for the government projects portal.
 * It displays different navigation options based on user authentication status and role.
 * 
 * Connected Pages:
 * - index.php: Home page
 * - login_page.php: User authentication
 * - register_page.php: New user registration
 * - add_project_page.php: Project creation (PM only)
 * - pm_dashboard.php: Project management (PM only)
 * - admin.php: Administration panel (Admin only)
 * - logout.php: Session termination
 * 
 * User Roles:
 * - Project Manager (PM)
 * - Administrator
 * - Public/Guest
 */

// Session management initialization
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- Bootstrap navigation component -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <!-- Site branding and home link -->
    <a class="navbar-brand" href="index.php">Govt Projects</a>
    
    <!-- Responsive navigation toggle button -->
    <button class="navbar-toggler" type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#navMenu" 
            aria-controls="navMenu" 
            aria-expanded="false" 
            aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navigation menu container -->
    <div class="collapse navbar-collapse justify-content-end" id="navMenu">
      <ul class="navbar-nav">
        <!-- Universal navigation link -->
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Project Manager specific navigation -->
          <?php if ($_SESSION['role'] === 'project_manager'): ?>
            <li class="nav-item">
              <a class="nav-link" href="add_project_page.php">Post Project</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="pm_dashboard.php">Manage Projects</a>
            </li>
          <?php endif; ?>

          <!-- Administrator specific navigation -->
          <?php if ($_SESSION['role'] === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="admin.php">Admin Panel</a>
            </li>
          <?php endif; ?>

          <!-- Authenticated user information display -->
          <li class="nav-item">
            <span class="nav-link disabled text-light">Hi, <?= $_SESSION['full_name'] ?></span>
          </li>
          <!-- Session termination link -->
          <li class="nav-item">
            <a class="nav-link text-warning" href="logout.php">Logout</a>
          </li>

        <?php else: ?>
          <!-- Guest user authentication options -->
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
