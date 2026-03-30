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
    $complaint_type = $_POST['complaint_type'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $description = $_POST['description'] ?? '';
    $location = $_POST['location'] ?? '';
    $urgency = $_POST['urgency'] ?? '';
    $anonymous = isset($_POST['anonymous']) ? 1 : 0;
    
    // Here you would typically save to database
    // For now, we'll just show a success message
    $complaint_submitted = true;
}

$userObj = new User();
$userProfile = $_SESSION['session_login'];

$pageTitle = 'Submit Complaint - Resident Portal';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint - Online Barangay Portal</title>
    
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
            max-width: 900px;
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

        .urgency-high {
            color: #e74c3c;
            font-weight: 600;
        }

        .urgency-medium {
            color: #f39c12;
            font-weight: 600;
        }

        .urgency-low {
            color: #27ae60;
            font-weight: 600;
        }

        .complaint-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .complaint-info h5 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .complaint-info p {
            color: #7f8c8d;
            margin: 0.5rem 0;
        }

        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
            text-align: center;
        }

        .form-check {
            margin-bottom: 1rem;
        }

        .form-check-input:checked {
            background-color: #27ae60;
            border-color: #27ae60;
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
            <h1><i class="fas fa-exclamation-triangle"></i> Submit Complaint</h1>
            <p>Report issues and concerns to the barangay for proper action</p>
        </div>
    </div>

    <div class="container">
        <div class="form-container">
            <a href="services.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Services
            </a>

            <?php if (isset($complaint_submitted) && $complaint_submitted): ?>
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i> Your complaint has been submitted successfully! The barangay will review your complaint and take appropriate action. You will be contacted within 3-5 working days.
                </div>
            <?php endif; ?>

            <div class="form-card">
                <h3 class="mb-4"><i class="fas fa-exclamation-triangle"></i> Complaint Form</h3>

                <!-- Complaint Information -->
                <div class="complaint-info">
                    <h5><i class="fas fa-info-circle"></i> Important Information:</h5>
                    <p>• All complaints are treated with confidentiality</p>
                    <p>• False or malicious complaints may be subject to legal action</p>
                    <p>• Emergency complaints should be reported directly to the barangay hall or call emergency numbers</p>
                    <p>• You may choose to submit anonymously (contact information not required)</p>
                </div>

                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="complaint_type">Complaint Type *</label>
                                <select class="form-control" id="complaint_type" name="complaint_type" required>
                                    <option value="">Select Complaint Type</option>
                                    <option value="noise">Noise Complaint</option>
                                    <option value="garbage">Garbage/Environmental Issue</option>
                                    <option value="public_disturbance">Public Disturbance</option>
                                    <option value="property_dispute">Property Dispute</option>
                                    <option value="neighbor_dispute">Neighbor Dispute</option>
                                    <option value="infrastructure">Infrastructure Issue</option>
                                    <option value="security">Security Concern</option>
                                    <option value="others">Others</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="urgency">Urgency Level *</label>
                                <select class="form-control" id="urgency" name="urgency" required>
                                    <option value="">Select Urgency</option>
                                    <option value="high" class="urgency-high">High - Immediate Action Required</option>
                                    <option value="medium" class="urgency-medium">Medium - Action Within 24 Hours</option>
                                    <option value="low" class="urgency-low">Low - Action Within 3-5 Days</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject/Title *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required 
                               placeholder="Brief title of your complaint">
                    </div>

                    <div class="form-group">
                        <label for="description">Detailed Description *</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required 
                                  placeholder="Please provide a detailed description of your complaint, including specific details, dates, times, and any relevant information..."></textarea>
                    </div>

                    <div class="form-group">
                        <label for="location">Location of Issue *</label>
                        <input type="text" class="form-control" id="location" name="location" required 
                               placeholder="Specific address or location where the issue occurred">
                    </div>

                    <!-- Personal Information (if not anonymous) -->
                    <div id="personalInfo">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Your Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required 
                                           value="<?php echo htmlspecialchars($userProfile['first_name'] . ' ' . $userProfile['last_name'] ?? ''); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact">Contact Number *</label>
                                    <input type="tel" class="form-control" id="contact" name="contact" required 
                                           placeholder="09XXXXXXXXX" value="<?php echo htmlspecialchars($userProfile['mobile_no'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="anonymous" name="anonymous">
                        <label class="form-check-label" for="anonymous">
                            Submit this complaint anonymously
                        </label>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-submit">
                            <i class="fas fa-paper-plane"></i> Submit Complaint
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

    <script>
        // Toggle personal information based on anonymous checkbox
        document.getElementById('anonymous').addEventListener('change', function() {
            const personalInfo = document.getElementById('personalInfo');
            const nameInput = document.getElementById('name');
            const contactInput = document.getElementById('contact');
            
            if (this.checked) {
                personalInfo.style.display = 'none';
                nameInput.removeAttribute('required');
                contactInput.removeAttribute('required');
            } else {
                personalInfo.style.display = 'block';
                nameInput.setAttribute('required', 'required');
                contactInput.setAttribute('required', 'required');
            }
        });
    </script>

</body>
</html>
