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
$userProfile = $_SESSION['session_login'];

$pageTitle = 'Services - Resident Portal';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services - Online Barangay Portal</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,500;0,800;1,500&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/4aee20adf0.js" crossorigin="anonymous"></script>
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%) !important;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
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

        .page-header {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.2);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .service-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .service-icon {
            font-size: 3rem;
            color: #27ae60;
            margin-bottom: 1.5rem;
        }

        .service-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .service-description {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }

        .service-btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .service-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
            color: white;
            text-decoration: none;
        }

        .service-btn.success {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }

        .service-btn.success:hover {
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
        }

        .service-btn.warning {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }

        .service-btn.warning:hover {
            box-shadow: 0 8px 20px rgba(243, 156, 18, 0.3);
        }

        .service-btn.danger {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
        }

        .service-btn.danger:hover {
            box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }

        .back-btn {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 2rem;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(52, 152, 219, 0.3);
            color: white;
            text-decoration: none;
        }

        .service-status {
            font-size: 0.85rem;
            color: #27ae60;
            font-weight: 500;
            margin-top: 0.5rem;
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
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                </li>
                <li class="nav-item active">
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

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-concierge-bell"></i> Barangay Services</h1>
            <p>Access various barangay services and request documents online</p>
        </div>
    </div>

    <div class="container">
        <a href="dashboard.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <div class="services-grid">
            <!-- Request Clearance -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="service-title">Barangay Clearance</div>
                <div class="service-description">
                    Request a barangay clearance for various purposes such as employment, business permits, and other legal requirements.
                </div>
                <a href="request_clearance.php" class="service-btn">
                    <i class="fas fa-file-alt"></i> Request Clearance
                </a>
                <div class="service-status">
                    <i class="fas fa-check-circle"></i> Available
                </div>
            </div>

            <!-- Request Certificate -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="service-title">Certificate Request</div>
                <div class="service-description">
                    Apply for various certificates including residency, indigency, and other barangay certifications.
                </div>
                <a href="request_certificate.php" class="service-btn success">
                    <i class="fas fa-certificate"></i> Request Certificate
                </a>
                <div class="service-status">
                    <i class="fas fa-check-circle"></i> Available
                </div>
            </div>

            <!-- Book Appointment -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="service-title">Book Appointment</div>
                <div class="service-description">
                    Schedule an appointment with barangay officials for consultations, meetings, or document processing.
                </div>
                <a href="book_appointment.php" class="service-btn warning">
                    <i class="fas fa-calendar"></i> Book Appointment
                </a>
                <div class="service-status">
                    <i class="fas fa-check-circle"></i> Available
                </div>
            </div>

            <!-- Submit Complaint -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="service-title">File Complaint</div>
                <div class="service-description">
                    Report issues, concerns, or complaints to the barangay for proper action and resolution.
                </div>
                <a href="submit_complaint.php" class="service-btn danger">
                    <i class="fas fa-exclamation-triangle"></i> Submit Complaint
                </a>
                <div class="service-status">
                    <i class="fas fa-check-circle"></i> Available
                </div>
            </div>

            <!-- Request Assistance -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <div class="service-title">Request Assistance</div>
                <div class="service-description">
                    Get assistance for various community services, social welfare programs, and barangay support.
                </div>
                <a href="request_assistance.php" class="service-btn">
                    <i class="fas fa-hands-helping"></i> Request Assistance
                </a>
                <div class="service-status">
                    <i class="fas fa-clock"></i> Coming Soon
                </div>
            </div>

            <!-- View Services -->
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="service-title">Service Status</div>
                <div class="service-description">
                    Track the status of your requests, appointments, and other barangay service applications.
                </div>
                <a href="service_status.php" class="service-btn">
                    <i class="fas fa-list"></i> View Status
                </a>
                <div class="service-status">
                    <i class="fas fa-clock"></i> Coming Soon
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

</body>
</html>
