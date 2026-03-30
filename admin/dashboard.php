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
$isCaptain = $userObj->isCaptain();

// Handle different actions from dropdown menus
$action = $_GET['action'] ?? '';
$actionContent = '';

switch ($action) {
    case 'create_staff':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-user-plus"></i> Create Staff Account</h3>
                <p>Create new staff accounts for barangay officials and employees.</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> This feature allows you to create new staff accounts with appropriate permissions.
                </div>
                <a href="officials.php" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Go to Officials Management
                </a>
            </div>';
        break;
        
    case 'appointments':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-calendar"></i> Appointment Management</h3>
                <p>View and manage resident appointments and schedule barangay meetings.</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> This section will show all scheduled appointments and allow you to manage them.
                </div>
                <div class="text-center">
                    <p><em>Appointment management system coming soon...</em></p>
                </div>
            </div>';
        break;
        
    case 'requests':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-clipboard-list"></i> Service Requests</h3>
                <p>Review and process resident service requests for clearances, certificates, and other services.</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Track all pending and completed service requests from residents.
                </div>
                <div class="text-center">
                    <p><em>Service request management system coming soon...</em></p>
                </div>
            </div>';
        break;
        
    case 'complaints':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-exclamation-triangle"></i> Complaint Management</h3>
                <p>Review and address resident complaints and concerns.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Handle complaints with appropriate urgency and follow-up.
                </div>
                <div class="text-center">
                    <p><em>Complaint management system coming soon...</em></p>
                </div>
            </div>';
        break;
        
    case 'inventory':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-warehouse"></i> Inventory Management</h3>
                <p>Manage barangay assets, supplies, and equipment inventory.</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Track barangay property, supplies, and maintain inventory records.
                </div>
                <div class="text-center">
                    <p><em>Inventory management system coming soon...</em></p>
                </div>
            </div>';
        break;
        
    case 'financial':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-money-bill-wave"></i> Financial Management</h3>
                <p>Manage barangay budget, expenses, and financial reports.</p>
                <div class="alert alert-success">
                    <i class="fas fa-money-bill-wave"></i> Track income, expenses, and generate financial reports.
                </div>
                <div class="text-center">
                    <p><em>Financial management system coming soon...</em></p>
                </div>
            </div>';
        break;
        
    case 'projects':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-project-diagram"></i> Project Management</h3>
                <p>Manage barangay development projects and initiatives.</p>
                <div class="alert alert-info">
                    <i class="fas fa-project-diagram"></i> Track project progress, budgets, and timelines.
                </div>
                <div class="text-center">
                    <p><em>Project management system coming soon...</em></p>
                </div>
            </div>';
        break;
        
    case 'meetings':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-users"></i> Meeting Management</h3>
                <p>Schedule and manage barangay meetings and assemblies.</p>
                <div class="alert alert-info">
                    <i class="fas fa-users"></i> Organize barangay assemblies and official meetings.
                </div>
                <div class="text-center">
                    <p><em>Meeting management system coming soon...</em></p>
                </div>
            </div>';
        break;
        
    case 'notifications':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-bell"></i> Notification System</h3>
                <p>Send notifications and announcements to residents.</p>
                <div class="alert alert-info">
                    <i class="fas fa-bell"></i> Send SMS notifications and system alerts to residents.
                </div>
                <div class="text-center">
                    <p><em>Notification system coming soon...</em></p>
                </div>
            </div>';
        break;
        
    case 'settings':
        $actionContent = '
            <div class="action-section">
                <h3><i class="fas fa-cog"></i> System Settings</h3>
                <p>Configure system settings and barangay information.</p>
                <div class="alert alert-warning">
                    <i class="fas fa-cog"></i> System configuration and administrative settings.
                </div>
                <div class="text-center">
                    <p><em>Settings panel coming soon...</em></p>
                </div>
            </div>';
        break;
}

// Get real stats from database
try {
    $residentsCount = count($userObj->getAllResidents());
    $officialsCount = count($userObj->getAllOfficials());
    $announcementsCount = count($userObj->getAllAnnouncements());
    $documentsCount = count($userObj->getAllDocuments());
} catch (Exception $e) {
    $residentsCount = 0;
    $officialsCount = 0;
    $announcementsCount = 0;
    $documentsCount = 0;
}

$pageTitle = 'Admin Dashboard';

?>
<style>
/* Professional Dashboard Styles */
.welcome-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.captain-badge {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    border-color: rgba(52, 152, 219, 0.2);
}

.stat-card i {
    font-size: 2.2rem;
    color: #3498db;
    margin-bottom: 1rem;
    opacity: 0.8;
}

.stat-card h3 {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.stat-card p {
    color: #7f8c8d;
    margin: 0;
    font-weight: 400;
    font-size: 0.9rem;
}

.quick-actions {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.quick-actions h5 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-weight: 600;
    font-size: 1.1rem;
}

.quick-actions .btn-group {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.quick-actions .btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    font-size: 0.9rem;
}

.quick-actions .btn-primary {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
}

.quick-actions .btn-info {
    background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
    box-shadow: 0 4px 10px rgba(26, 188, 156, 0.3);
}

.quick-actions .btn-success {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
    box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3);
}

.quick-actions .btn-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    box-shadow: 0 4px 10px rgba(243, 156, 18, 0.3);
    color: white;
}

.quick-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.activity-feed {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.activity-feed h5 {
    color: #2c3e50;
    margin-bottom: 1.5rem;
    font-weight: 600;
    font-size: 1.1rem;
}

.activity-item {
    padding: 1.25rem 0;
    border-bottom: 1px solid #ecf0f1;
    transition: background-color 0.3s ease;
}

.activity-item:hover {
    background-color: #f8f9fa;
    margin: 0 -1rem;
    padding-left: 1rem;
    padding-right: 1rem;
    border-radius: 8px;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item strong {
    color: #2c3e50;
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 0.95rem;
}

.activity-item p {
    color: #7f8c8d;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.activity-item small {
    color: #bdc3c7;
    font-size: 0.8rem;
    font-weight: 400;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    font-family: 'Montserrat', sans-serif;
    min-height: 100vh;
}

.navbar-captain {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
}

.navbar-admin {
    background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%) !important;
    box-shadow: 0 4px 15px rgba(52, 73, 94, 0.3);
}

.badge-captain {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white;
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(52, 152, 219, 0.3);
}

.action-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.action-section h3 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1rem;
}

.action-section p {
    color: #7f8c8d;
    margin-bottom: 1.5rem;
}

.action-section .alert {
    margin-bottom: 1.5rem;
}

/* Professional animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.welcome-header, .stat-card, .quick-actions, .activity-feed {
    animation: fadeInUp 0.6s ease-out;
}

.stat-card:nth-child(2) {
    animation-delay: 0.1s;
}

.stat-card:nth-child(3) {
    animation-delay: 0.2s;
}

.stat-card:nth-child(4) {
    animation-delay: 0.3s;
}

/* Improved typography */
h1 {
    font-weight: 300;
    letter-spacing: -0.5px;
}

.lead {
    font-weight: 400;
    opacity: 0.9;
}
</style>

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
                        <span class="badge badge-captain ml-3">
                            <i class="fas fa-crown"></i> Captain
                        </span>
                    <?php endif; ?>
                </h1>
                <p class="lead">Welcome back, <?php echo htmlspecialchars($_SESSION['session_login']['official_first_name'] ?? 'User'); ?>!</p>
            </div>
        </div>
    </div>
    
    <!-- Action Content (if any action is selected) -->
    <?php if (!empty($actionContent)): ?>
        <div class="row">
            <div class="col-12">
                <?php echo $actionContent; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Quick Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3><?php echo $residentsCount; ?></h3>
            <p>Total Residents</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-user-tie"></i>
            <h3><?php echo $officialsCount; ?></h3>
            <p>Officials</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-bullhorn"></i>
            <h3><?php echo $announcementsCount; ?></h3>
            <p>Announcements</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-file-alt"></i>
            <h3><?php echo $documentsCount; ?></h3>
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