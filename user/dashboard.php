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

$userObj = new User();
$announcements = $userObj->getAllAnnouncements();

$pageTitle = 'User Dashboard';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Dashboard - Online Barangay Portal</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
    
    <style>
        /* User Dashboard Styles */
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%) !important;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
        }

        .hero-section {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.2);
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 1rem;
        }

        .hero-section p {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            opacity: 0.9;
        }

        .user-info-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .user-info-card h3 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .user-info-card p {
            color: #7f8c8d;
            margin: 0;
        }

        .announcements-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .announcements-section h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .announcement-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #27ae60;
            transition: all 0.3s ease;
        }

        .announcement-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .announcement-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .announcement-content {
            color: #7f8c8d;
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }

        .announcement-date {
            color: #bdc3c7;
            font-size: 0.85rem;
        }

        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .quick-actions h4 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .action-btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            margin: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
            color: white;
            text-decoration: none;
        }

        .action-btn.success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .action-btn.success:hover {
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
        }

        .action-btn.warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .action-btn.warning:hover {
            box-shadow: 0 8px 20px rgba(243, 156, 18, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }

        .empty-state i {
            font-size: 3rem;
            color: #bdc3c7;
            margin-bottom: 1rem;
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }

        .footer p {
            margin: 0;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <img src="../assets/images/Logo.png" alt="logo" style="width: 60px; height: auto; margin-right: 1rem;">
        
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        <i class="fas fa-concierge-bell"></i> Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">
                        <i class="fas fa-envelope"></i> Contact
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="navbar-nav ml-auto">
            <span class="navbar-text mr-3">
                <i class="fas fa-user"></i> 
                <?php echo htmlspecialchars($_SESSION['session_login']['first_name'] ?? 'User'); ?>
            </span>
            <a href="dashboard.php?logout=true" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1><i class="fas fa-home"></i> Resident Dashboard</h1>
            <p>Welcome to your barangay services portal</p>
        </div>
    </div>

    <div class="container">
        <!-- User Info Card -->
        <div class="row">
            <div class="col-md-4">
                <div class="user-info-card">
                    <i class="fas fa-user-circle" style="font-size: 3rem; color: #27ae60; margin-bottom: 1rem;"></i>
                    <h3><?php echo htmlspecialchars($_SESSION['session_login']['first_name'] . ' ' . $_SESSION['session_login']['last_name']); ?></h3>
                    <p>Resident ID: <?php echo htmlspecialchars($_SESSION['session_login']['resident_id'] ?? 'N/A'); ?></p>
                    <p><?php echo htmlspecialchars($_SESSION['session_login']['email'] ?? 'No email'); ?></p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="col-md-8">
                <div class="quick-actions">
                    <h4><i class="fas fa-bolt"></i> Quick Actions</h4>
                    <div class="text-center">
                        <a href="request_clearance.php" class="action-btn">
                            <i class="fas fa-file-alt"></i> Request Clearance
                        </a>
                        <a href="request_certificate.php" class="action-btn success">
                            <i class="fas fa-certificate"></i> Request Certificate
                        </a>
                        <a href="book_appointment.php" class="action-btn warning">
                            <i class="fas fa-calendar"></i> Book Appointment
                        </a>
                        <a href="submit_complaint.php" class="action-btn">
                            <i class="fas fa-exclamation-triangle"></i> Submit Complaint
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Announcements Section -->
        <div class="row">
            <div class="col-12">
                <div class="announcements-section">
                    <h4><i class="fas fa-bullhorn"></i> Latest Announcements</h4>
                    <?php if (!empty($announcements)): ?>
                        <?php foreach (array_slice($announcements, 0, 3) as $announcement): ?>
                            <div class="announcement-card">
                                <div class="announcement-title">
                                    <?php echo htmlspecialchars($announcement['post_title']); ?>
                                </div>
                                <div class="announcement-content">
                                    <?php echo htmlspecialchars(substr($announcement['post_body'], 0, 200)) . '...'; ?>
                                </div>
                                <div class="announcement-date">
                                    <i class="fas fa-calendar"></i> 
                                    <?php echo date('M d, Y', strtotime($announcement['post_date_time'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-bullhorn"></i>
                            <h4>No Announcements</h4>
                            <p>No announcements have been posted yet.</p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="text-center mt-3">
                        <a href="announcements.php" class="btn btn-outline-success">
                            <i class="fas fa-list"></i> View All Announcements
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <p>&copy; 2024 Barangay Sta. Cruz Viejo. All rights reserved.</p>
            <p>Powered by Online Barangay Portal System</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
    $(document).ready(function() {
        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top - 70
                }, 1000);
            }
        });
        
        // Add hover effects to cards
        $('.user-info-card, .quick-actions, .announcements-section').hover(
            function() {
                $(this).addClass('shadow-lg');
            },
            function() {
                $(this).removeClass('shadow-lg');
            }
        );
    });
    </script>

</body>
</html>
