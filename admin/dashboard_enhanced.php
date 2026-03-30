<?php

require_once '../config/config.php';
require_once '../classes/User.php';

// Check if user is logged in
if (!isset($_SESSION['session_login'])) {
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

// Handle logout
if (isset($_GET['logout'])) {
    $userObj = new User();
    $userObj->logout();
    header("Location: http://localhost/xampp/Online_Barangay_Portal-master/Online_Barangay_Portal-master");
    exit();
}

// Check if user is captain
$userObj = new User();
$isCaptain = $userObj->isCaptain($_SESSION['session_login']['official_username']);
$barangaySettings = $userObj->getBarangaySettings();
$allOfficials = $userObj->getAllOfficials();
$recentActivities = $userObj->getRecentActivities(5);
$dashboardStats = $userObj->getDashboardStats();
$activeNotifications = $userObj->getActiveNotifications();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Enhanced Barangay Management System</title>
    
    <!-- Montserrat Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="stylesheets/admin_Home.css">
    <style>
        .dashboard-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .captain-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .feature-card {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }
        .activity-item {
            border-left: 3px solid #28a745;
            padding-left: 15px;
            margin-bottom: 10px;
        }
        .navbar-captain {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4 <?php echo $isCaptain ? 'navbar-captain' : 'bg-danger'; ?>">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <img src="img/Logo.png" alt="logo" class="ml-md-3" style="width: 60px; height: auto;">
        
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="enhanced_admin_home.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <?php if($isCaptain): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="staffDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-users"></i> Staff Management
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#createStaffModal">
                            <i class="fas fa-user-plus"></i> Create Staff
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#viewStaffModal">
                            <i class="fas fa-list"></i> View All Staff
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-concierge-bell"></i> Services
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#appointmentsModal">
                            <i class="fas fa-calendar"></i> Appointments
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#requestsModal">
                            <i class="fas fa-clipboard-list"></i> Service Requests
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#complaintsModal">
                            <i class="fas fa-exclamation-triangle"></i> Complaints
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="resourcesDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-boxes"></i> Resources
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#inventoryModal">
                            <i class="fas fa-warehouse"></i> Inventory
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#financialModal">
                            <i class="fas fa-money-bill-wave"></i> Financial
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#projectsModal">
                            <i class="fas fa-project-diagram"></i> Projects
                        </a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="communityDropdown" role="button" data-toggle="dropdown">
                        <i class="fas fa-users"></i> Community
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#meetingsModal">
                            <i class="fas fa-users"></i> Meetings
                        </a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#notificationsModal">
                            <i class="fas fa-bell"></i> Notifications
                        </a>
                    </div>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin_Announcements.php">
                        <i class="fas fa-bullhorn"></i> Announcements
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_Documents.php">
                        <i class="fas fa-file-alt"></i> Documents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_Residents.php">
                        <i class="fas fa-user-friends"></i> Residents
                    </a>
                </li>
                <?php if($isCaptain): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#settingsModal">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        
        <div class="navbar-nav ml-auto">
            <span class="navbar-text mr-3">
                <?php if($isCaptain): ?>
                    <i class="fas fa-crown"></i> 
                    <span class="badge badge-warning captain-badge">Barangay Captain</span>
                <?php else: ?>
                    <i class="fas fa-user-tie"></i> Barangay Official
                <?php endif; ?>
            </span>
            <?php if(!empty($activeNotifications)): ?>
                <span class="navbar-text mr-3">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge"><?php echo count($activeNotifications); ?></span>
                </span>
            <?php endif; ?>
            <a href="enhanced_admin_home.php?logout=true" class="btn btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <div class="container-fluid">
        <!-- Welcome Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-body bg-gradient-primary text-white">
                        <?php if($isCaptain): ?>
                            <h1 class="display-4 mb-0">
                                <i class="fas fa-crown"></i> 
                                Welcome, Captain <?php echo htmlspecialchars($_SESSION['session_login']['official_first_name'] . ' ' . $_SESSION['session_login']['official_last_name']); ?>
                            </h1>
                            <p class="lead mb-0">
                                <?php echo htmlspecialchars($barangaySettings['barangay_name'] ?? 'Barangay Portal'); ?> - Complete Management System
                            </p>
                        <?php else: ?>
                            <h1 class="display-4 mb-0">
                                <i class="fas fa-user-tie"></i> 
                                Welcome, <?php echo htmlspecialchars($_SESSION['session_login']['official_first_name'] . ' ' . $_SESSION['session_login']['official_last_name']); ?>
                            </h1>
                            <p class="lead mb-0">
                                <?php echo htmlspecialchars($barangaySettings['barangay_name'] ?? 'Barangay Portal'); ?> - Official Dashboard
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Messages -->
        <?php if(isset($_GET['staffCreated'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Staff account created successfully!
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_GET['appointmentCreated'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> Appointment created successfully!
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card stats-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h3 class="card-title"><?php echo $dashboardStats['total_residents']; ?></h3>
                        <p class="card-text">Total Residents</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); color: white;">
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie fa-3x mb-3"></i>
                        <h3 class="card-title"><?php echo $dashboardStats['total_officials']; ?></h3>
                        <p class="card-text">Active Officials</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%); color: white;">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar-check fa-3x mb-3"></i>
                        <h3 class="card-title"><?php echo $dashboardStats['pending_appointments']; ?></h3>
                        <p class="card-text">Pending Appointments</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); color: #333;">
                    <div class="card-body text-center">
                        <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                        <h3 class="card-title"><?php echo $dashboardStats['pending_requests']; ?></h3>
                        <p class="card-text">Service Requests</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Statistics Row -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); color: #333;">
                    <div class="card-body text-center">
                        <i class="fas fa-warehouse fa-3x mb-3"></i>
                        <h3 class="card-title"><?php echo $dashboardStats['low_stock_items']; ?></h3>
                        <p class="card-text">Low Stock Items</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white;">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h3 class="card-title"><?php echo $dashboardStats['pending_complaints']; ?></h3>
                        <p class="card-text">Pending Complaints</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white;">
                    <div class="card-body text-center">
                        <i class="fas fa-project-diagram fa-3x mb-3"></i>
                        <h3 class="card-title"><?php echo $dashboardStats['ongoing_projects']; ?></h3>
                        <p class="card-text">Ongoing Projects</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card dashboard-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white;">
                    <div class="card-body text-center">
                        <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                        <h3 class="card-title">₱<?php echo number_format($dashboardStats['monthly_income'] - $dashboardStats['monthly_expenses']); ?></h3>
                        <p class="card-text">Net Monthly</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="row">
            <!-- Recent Activity -->
            <div class="col-md-8">
                <div class="card dashboard-card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-history"></i> Recent Activity
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if(empty($recentActivities)): ?>
                            <p class="text-muted">No recent activity found.</p>
                        <?php else: ?>
                            <?php foreach($recentActivities as $activity): ?>
                                <div class="activity-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong><?php echo htmlspecialchars($activity['action']); ?></strong>
                                            <p class="mb-1"><?php echo htmlspecialchars($activity['details']); ?></p>
                                            <small class="text-muted"><?php echo htmlspecialchars($activity['username']); ?></small>
                                        </div>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & Notifications -->
            <div class="col-md-4">
                <div class="card dashboard-card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt"></i> Quick Actions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if($isCaptain): ?>
                                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#createStaffModal">
                                    <i class="fas fa-user-plus"></i> Create Staff
                                </button>
                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#appointmentsModal">
                                    <i class="fas fa-calendar"></i> Appointments
                                </button>
                                <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#inventoryModal">
                                    <i class="fas fa-warehouse"></i> Inventory
                                </button>
                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#notificationsModal">
                                    <i class="fas fa-bell"></i> Notifications
                                </button>
                                <button class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#settingsModal">
                                    <i class="fas fa-cog"></i> Settings
                                </button>
                            <?php else: ?>
                                <a href="admin_Announcements.php" class="btn btn-sm btn-info">
                                    <i class="fas fa-bullhorn"></i> Announcements
                                </a>
                                <a href="admin_Documents.php" class="btn btn-sm btn-warning">
                                    <i class="fas fa-file-alt"></i> Documents
                                </a>
                                <a href="admin_Residents.php" class="btn btn-sm btn-primary">
                                    <i class="fas fa-user-friends"></i> Residents
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Active Notifications -->
                <?php if(!empty($activeNotifications)): ?>
                <div class="card dashboard-card mt-3">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-bell"></i> Active Notifications
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach($activeNotifications as $notification): ?>
                            <div class="alert alert-<?php echo $notification['type'] == 'Urgent' ? 'danger' : ($notification['type'] == 'Alert' ? 'warning' : 'info'); ?> alert-sm mb-2">
                                <strong><?php echo htmlspecialchars($notification['title']); ?></strong>
                                <p class="mb-0 small"><?php echo htmlspecialchars(substr($notification['message'], 0, 100)) . '...'; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Barangay Information -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card dashboard-card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle"></i> Barangay Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($barangaySettings['barangay_name'] ?? 'Not Set'); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($barangaySettings['barangay_address'] ?? 'Not Set'); ?></p>
                                <p><strong>Contact:</strong> <?php echo htmlspecialchars($barangaySettings['barangay_contact'] ?? 'Not Set'); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($barangaySettings['barangay_email'] ?? 'Not Set'); ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Captain:</strong> <?php echo htmlspecialchars($barangaySettings['captain_name'] ?? 'Not Set'); ?></p>
                                <p><strong>Term:</strong> 
                                    <?php 
                                    $termStart = $barangaySettings['term_start'] ?? '';
                                    $termEnd = $barangaySettings['term_end'] ?? '';
                                    echo $termStart && $termEnd ? date('Y', strtotime($termStart)) . ' - ' . date('Y', strtotime($termEnd)) : 'Not Set';
                                    ?>
                                </p>
                                <p><strong>Population:</strong> <?php echo htmlspecialchars($barangaySettings['population_count'] ?? 'N/A'); ?></p>
                                <p><strong>Households:</strong> <?php echo htmlspecialchars($barangaySettings['household_count'] ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include all modals here -->
    <?php include 'modals/enhanced_modals.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Auto-refresh notifications every 30 seconds
            setInterval(function() {
                // You can add AJAX call to refresh notifications here
            }, 30000);
        });
    </script>
</body>
</html>
