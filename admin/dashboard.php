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
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

$userObj = new User();
$isCaptain = $userObj->isCaptain();

$pageTitle = 'Admin Dashboard';

?>


<?php include 'includes/header.php'; ?>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="welcome-header">
                <h1>
                    <i class="fas fa-tachometer-alt"></i> 
                    Admin Dashboard
                    <?php if($isCaptain): ?>
                        <span class="badge badge-warning captain-badge ml-3">
                            <i class="fas fa-crown"></i> Captain
                        </span>
                    <?php endif; ?>
                </h1>
                <p class="lead">Welcome back, <?php echo htmlspecialchars($_SESSION['session_login']['official_first_name'] ?? 'User'); ?>!</p>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3>1,234</h3>
            <p>Total Residents</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-user-tie"></i>
            <h3>12</h3>
            <p>Officials</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-bullhorn"></i>
            <h3>45</h3>
            <p>Announcements</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-file-alt"></i>
            <h3>128</h3>
            <p>Documents</p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <h5>Quick Actions</h5>
        <div class="btn-group">
            <a href="residents.php" class="btn btn-primary">
                <i class="fas fa-user-plus"></i> Add Resident
            </a>
            <a href="announcements.php" class="btn btn-info">
                <i class="fas fa-bullhorn"></i> New Announcement
            </a>
            <a href="documents.php" class="btn btn-success">
                <i class="fas fa-file-upload"></i> Upload Document
            </a>
            <?php if($isCaptain): ?>
            <a href="dashboard_enhanced.php" class="btn btn-warning">
                <i class="fas fa-crown"></i> Enhanced Dashboard
            </a>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="activity-feed">
        <h5>Recent Activity</h5>
        <div class="activity-item">
            <strong>New resident registered</strong>
            <p>John Doe was added to the system</p>
            <small>2 hours ago</small>
        </div>
        <div class="activity-item">
            <strong>Announcement posted</strong>
            <p>New barangay meeting scheduled</p>
            <small>5 hours ago</small>
        </div>
        <div class="activity-item">
            <strong>Document uploaded</strong>
            <p>Barangay clearance form added</p>
            <small>1 day ago</small>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
                    </li>
                    <li class="nav-item pl-3 pr-3">
                        <a class="nav-link" href="admin_BarangayOfficials.php">Barangay Officials</a>
                    </li>
                    <li class="nav-item pl-3 pr-3">
                        <a class="nav-link" href="admin_Announcements.php">Announcements</a>
                    </li>
                    
                </ul>
            </div>
            
            <a href="admin_Home.php?logout=true">
                <button class="btn btn-outline-danger logout-btn  mr-md-5" type="button"  id="logout-btn">Logout</button>
            </a>
        </nav>
    </div>

    <br><br>

    <div class="container-fluid ">

        <!-- Hero Area -->
        <div class="row align-items-center justify-content-center m-0" id="heroarea">
            <div class="col-md-8 col-sm-10">
                <h1 class="mt-5 mb-3">Barangay San Antonio</h1>
                <p class="pt-3 pb-2">Barangay San Antonio is a continuously growing barangay that has more than one thousand residents. </p>
                <button id="viewmore-btn" class="btn mt-3 mb-5"><a class="text-light" href="#mission-vision">VIEW MORE</a></button>
                <br><br><br>
            </div>
        </div>

        <!-- Mission / Vision -->
        <div class="row align-items-center justify-content-around bg-danger text-light m-0" id="mission-vision"> 
            <div class="col-md-5 text-center py-md-5 px-md-0 p-sm-5" id="mission">
                <h4 class="py-2">MISSION</h4>
                <p class="py-2">Barangay San Antonio will continuously work in achieving unity and cooperation within the whole barangay to attain of peace, harmony, and progress, together with improving and enhancing the delivery of basic social services and infrastructure facilities. The people’s capacity to manage resources and organizations is strengthened together with coordination and association with several agencies of the government and the private sector.”</p>
            </div>
            <div class="col-md-5 text-center py-md-5 px-md-0 p-sm-5" id="vision">
                <h4 class="py-2">VISION</h4>
                <p class="py-2"> Barangay San Antonio is a barangay that is peaceful, prosperous, and united. The barangay residents are god-fearing, healthy, industrious, happy, and well-off citezens that are united together for a common purpose of achieving food sufficiency, barangay productivity, barangay efficiency, and clean invironment under a a democratic system of management. Fair and human leadership that shall ultimately result in a better quality of life of the people.”</p>
            </div>
        </div>
       
        <!-- Contact -->
        <div class="row m-0 justify-content-center" id="contact">
            <div class="col-12 text-center p-5 pb-lg-5 pb-md-0 pb-sm-0">
                <h4>CONTACT</h4>
            </div>
            <div class="col-lg-4 align-items-center col-sm-10 p-5 clearfix" >
                <h5 class="pb-3 pt-sm-0">Get In Touch</h5>
                <p class="pt-4" id="b-top">brgysanantonio@gmail.com</p>
                <img src="./img/telephone-icon.png" alt="phone-icon">
                <ul class="d-inline-block m-0 p-0 pl-3 clearfix">
                    <li>+ 63 97 5832 1123</li>
                    <li>+ 63 97 5832 1123</li>
                </ul>
                <p class="pt-3" id="b-bot">(02)7576-4567</p>
                <img src="./img/fb-icon.png" class="pr-1" alt="fb-icon">
                <img src="./img/email-icon.png" alt="email-icon">
            </div>
            <div class="col-lg-5 col-md-10 col-sm-10 align-items-center pl-5 pt-lg-5 mb-lg-5 mb-md-0 mb-sm-0" id="b-left">
                <h6 class="pb-3">San Antonio, San Pascual, Batangas</h6> 
                <img src="./img/map.png " alt="location" class="mb-lg-5 mb-sm-0 mb-md-0 img-fluid"> 
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="row m-0 pt-3 footer-div">
        <div class="col-12 text-center text-muted">
            <p class="m-0 pb-3 mt-md-2 mt-sm-2">Brgy. San Antonio, Copyright ©️ 2020 </p>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    

    <script>

    </script>

</body>
</html>