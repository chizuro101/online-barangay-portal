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
    header("Location: http://localhost/xampp/Online_Barangay_Portal-master/Online_Barangay_Portal-master/");
    exit();
}

$userObj = new User();
$userProfile = $_SESSION['session_login'];

$pageTitle = 'My Profile - Resident Portal';

$extra_css = '
    .page-header {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
        padding: 3rem 0;
        text-align: center;
        border-radius: 0 0 30px 30px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 25px rgba(39, 174, 96, 0.2);
    }

    .content-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 2rem;
    }

    .content-card h4 {
        color: #27ae60;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .user-info p {
        padding: 0.5rem 0;
        border-bottom: 1px solid #f8f9fa;
        color: #2c3e50;
        margin-bottom: 0;
    }

    .user-info p:last-child {
        border-bottom: none;
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

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #7f8c8d;
    }

    .empty-state i {
        font-size: 3rem;
        color: #e74c3c;
        margin-bottom: 1rem;
        display: block;
    }

    .footer {
        background-color: #2c3e50;
        color: white;
        padding: 2rem 0;
        margin-top: 3rem;
        text-align: center;
    }

    .footer p {
        margin-bottom: 0.25rem;
    }
';

?>
<?php include 'includes/header.php'; ?>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="fas fa-user"></i> My Profile</h1>
            <p>Manage your personal information and account settings</p>
        </div>
    </div>

    <div class="container">
        <?php
            $userData = $_SESSION['session_login'];
            error_log("Profile Debug: Session Data = " . print_r($userData, true));

            if ($userData):
        ?>

            <div class="row">
                <!-- Personal Info Card -->
                <div class="col-md-8">
                    <div class="content-card">
                        <h4><i class="fas fa-user"></i> Personal Information</h4>
                        <div class="user-info">
                            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($userData['first_name'] . ' ' . $userData['middle_name'] . ' ' . $userData['last_name']); ?></p>
                            <p><strong>Username:</strong> <?php echo htmlspecialchars($userData['username']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email'] ?? 'Not provided'); ?></p>
                            <p><strong>Mobile:</strong> <?php echo htmlspecialchars($userData['mobile_no'] ?? 'Not provided'); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($userData['address'] ?? 'Not provided'); ?></p>
                            <p><strong>Resident ID:</strong> <?php echo htmlspecialchars($userData['resident_id']); ?></p>
                            <p><strong>Birthday:</strong> <?php echo date('F d, Y', strtotime($userData['birthday'] ?? '2000-01-01')); ?></p>
                            <p><strong>Sex:</strong> <?php echo htmlspecialchars($userData['sex'] ?? 'Not specified'); ?></p>
                            <p><strong>Civil Status:</strong> <?php echo htmlspecialchars($userData['civil_stat'] ?? 'Not specified'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Account Settings Card -->
                <div class="col-md-4">
                    <div class="content-card">
                        <h4><i class="fas fa-cog"></i> Account Settings</h4>
                        <div class="user-info">
                            <p><strong>Account Type:</strong> Resident</p>
                            <p><strong>Member Since:</strong> <?php echo date('F d, Y', strtotime($userData['created_at'] ?? '2024-01-01')); ?></p>
                            <p><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                            <p><strong>Voter Status:</strong> <?php echo htmlspecialchars($userData['voter_stat'] ?? 'Not specified'); ?></p>
                            <p><strong>Religion:</strong> <?php echo htmlspecialchars($userData['religion'] ?? 'Not specified'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>

            <div class="empty-state">
                <i class="fas fa-exclamation-triangle"></i>
                <h5>User Not Found</h5>
                <p>The requested user profile could not be found.</p>
                <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
            </div>

        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Barangay Sta. Cruz Viejo. All rights reserved.</p>
            <p>Powered by Online Barangay Portal System</p>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 70
                    }, 1000);
                }
            });
        });
    </script>

</body>
</html>