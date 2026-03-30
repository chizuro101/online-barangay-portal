<?php

require_once ROOT_PATH . 'config/config.php';

// Check if user is logged in
if (!isset($_SESSION['session_login'])) {
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

// Check if user is admin
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: " . USER_URL . "dashboard.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    $userObj = new User();
    $userObj->logout();
    header("Location: http://localhost/xampp/Online_Barangay_Portal-master/Online_Barangay_Portal-master");
    exit();
}

$userObj = new User();
$isCaptain = $userObj->isCaptain();
$currentUser = isset($_SESSION['session_login']) ? $_SESSION['session_login'] : null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo APP_NAME; ?>">
    <meta name="author" content="Barangay Management System">
    
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo APP_NAME; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>global.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>admin/components.css">
    
    <?php if (isset($customCSS)): ?>
        <style><?php echo $customCSS; ?></style>
    <?php endif; ?>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 <?php echo $isCaptain ? 'navbar-captain' : 'navbar-admin'; ?>">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <img src="<?php echo IMAGES_URL; ?>Logo.png" alt="logo" class="ml-md-3" style="width: 80px; height: auto; background: transparent; mix-blend-mode: multiply;">
        
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo ADMIN_URL; ?>dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                
                <?php if ($isCaptain): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="staffDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-users"></i> Staff Management
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>dashboard.php?action=create_staff">
                            <i class="fas fa-user-plus"></i> Create Staff
                        </a>
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>officials.php">
                            <i class="fas fa-list"></i> View All Staff
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-concierge-bell"></i> Services
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>appointments.php">
                            <i class="fas fa-calendar"></i> Appointments
                        </a>
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>service_requests.php">
                            <i class="fas fa-clipboard-list"></i> Service Requests
                        </a>
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>complaints.php">
                            <i class="fas fa-exclamation-triangle"></i> Complaints
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="resourcesDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-boxes"></i> Resources
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>inventory.php">
                            <i class="fas fa-warehouse"></i> Inventory
                        </a>
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>financial.php">
                            <i class="fas fa-money-bill-wave"></i> Financial
                        </a>
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>projects.php">
                            <i class="fas fa-project-diagram"></i> Projects
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="communityDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-users"></i> Community
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>meetings.php">
                            <i class="fas fa-users"></i> Meetings
                        </a>
                        <a class="dropdown-item" href="<?php echo ADMIN_URL; ?>notifications.php">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </div>
                </li>
                <?php endif; ?>
                
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'announcements.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo ADMIN_URL; ?>announcements.php">
                        <i class="fas fa-bullhorn"></i> Announcements
                    </a>
                </li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'documents.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo ADMIN_URL; ?>documents.php">
                        <i class="fas fa-file-alt"></i> Documents
                    </a>
                </li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'residents.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo ADMIN_URL; ?>residents.php">
                        <i class="fas fa-user-friends"></i> Residents
                    </a>
                </li>
                <li class="nav-item <?php echo basename($_SERVER['PHP_SELF']) === 'officials.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="<?php echo ADMIN_URL; ?>officials.php">
                        <i class="fas fa-user-tie"></i> Officials
                    </a>
                </li>
                
                <?php if ($isCaptain): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo ADMIN_URL; ?>dashboard.php?action=settings">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        
        <div class="navbar-nav ml-auto">
            <span class="navbar-text mr-3">
                <?php if ($isCaptain): ?>
                    <i class="fas fa-crown"></i> 
                    <span class="badge badge-captain">Barangay Captain</span>
                <?php else: ?>
                    <i class="fas fa-user-tie"></i> Barangay Official
                <?php endif; ?>
            </span>
            <a href="<?php echo ADMIN_URL; ?>dashboard.php?logout=true" class="btn btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="container-fluid">
