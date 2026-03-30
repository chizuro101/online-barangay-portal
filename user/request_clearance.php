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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $purpose = $_POST['purpose'] ?? '';
    $reason = $_POST['reason'] ?? '';
    $request_date = $_POST['request_date'] ?? date('Y-m-d');
    
    // Here you would typically save to database
    // For now, we'll just show a success message
    $request_submitted = true;
}

$userObj = new User();
$userProfile = $_SESSION['session_login'];

$pageTitle = 'Request Clearance - Resident Portal';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Clearance - Online Barangay Portal</title>
    
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

        .page-header {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            padding: 3rem 0;
            text-align: center;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(39, 174, 96, 0.2);
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .form-group label {
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #27ae60;
            box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.25);
        }

        .btn-submit {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
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

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border: 1px solid #c3e6cb;
            color: #155724;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .requirements-list {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .requirements-list h5 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .requirements-list ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .requirements-list li {
            color: #7f8c8d;
            margin-bottom: 0.5rem;
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
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

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-file-alt"></i> Request Barangay Clearance</h1>
            <p>Apply for barangay clearance for various purposes</p>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <a href="services.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Services
            </a>

            <?php if (isset($request_submitted) && $request_submitted): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> Your clearance request has been submitted successfully! Please visit the barangay hall after 2-3 working days to claim your clearance.
                </div>
            <?php endif; ?>

            <div class="form-card">
                <h3 class="mb-4"><i class="fas fa-file-alt"></i> Clearance Application Form</h3>

                <!-- Requirements -->
                <div class="requirements-list">
                    <h5><i class="fas fa-info-circle"></i> Requirements:</h5>
                    <ul>
                        <li>Valid ID (Government-issued)</li>
                        <li>Proof of Residence (e.g., Utility Bill)</li>
                        <li>2x2 ID Picture (recent)</li>
                        <li>Processing Fee: ₱50.00</li>
                    </ul>
                </div>

                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required 
                                       value="<?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name'] ?? ''); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="resident_id">Resident ID *</label>
                                <input type="text" class="form-control" id="resident_id" name="resident_id" required 
                                       value="<?php echo htmlspecialchars($userProfile['resident_id'] ?? ''); ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purpose">Purpose of Clearance *</label>
                                <select class="form-control" id="purpose" name="purpose" required>
                                    <option value="">Select Purpose</option>
                                    <option value="employment">Employment</option>
                                    <option value="business_permit">Business Permit</option>
                                    <option value="school_enrollment">School Enrollment</option>
                                    <option value="bank_account">Bank Account Opening</option>
                                    <option value="travel">Travel/Passport</option>
                                    <option value="loan">Loan Application</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="request_date">Request Date *</label>
                                <input type="date" class="form-control" id="request_date" name="request_date" required 
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason/Additional Details *</label>
                        <textarea class="form-control" id="reason" name="reason" rows="4" required 
                                  placeholder="Please provide additional details about your clearance request..."></textarea>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i> Submit Request
                        </button>
                    </div>
                </form>
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
