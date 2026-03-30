<?php

require_once '../config/config.php';
require_once '../classes/User.php';

// Check if user is logged in
if (!isset($_SESSION['session_login'])) {
    header("Location: " . ROOT_URL . "index.php");
    exit();
}

// Check if user is admin/captain
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
$meetings = []; // This would come from database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $meeting_title = $_POST['meeting_title'] ?? '';
    $meeting_date = $_POST['meeting_date'] ?? '';
    $meeting_time = $_POST['meeting_time'] ?? '';
    $location = $_POST['location'] ?? '';
    
    if ($action === 'add_meeting') {
        // Add meeting logic
        $meeting_added = true;
    }
}

$pageTitle = 'Meeting Management - Admin Portal';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Meeting Management Styles */
.meetings-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.meetings-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.meeting-form {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.meetings-list {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.meeting-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #3498db;
    transition: all 0.3s ease;
}

.meeting-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.meeting-item.upcoming {
    border-left-color: #27ae60;
}

.meeting-item.completed {
    border-left-color: #95a5a6;
}

.meeting-item.cancelled {
    border-left-color: #e74c3c;
}

.meeting-header-info {
    display: flex;
    justify-content: space-between;
    align-items-start;
    margin-bottom: 1rem;
}

.meeting-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.meeting-meta {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.meeting-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-upcoming {
    background: #27ae60;
    color: white;
}

.status-completed {
    background: #95a5a6;
    color: white;
}

.status-cancelled {
    background: #e74c3c;
    color: white;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
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

.meeting-details {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.meeting-type {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    background: #ecf0f1;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.type-assembly {
    background: #e3f2fd;
    color: #1976d2;
}

.type-official {
    background: #f3e5f5;
    color: #7b1fa2;
}

.type-emergency {
    background: #ffebee;
    color: #c62828;
}
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="meetings-header">
                <h1><i class="fas fa-users"></i> Meeting Management</h1>
                <p class="lead">Schedule and manage barangay meetings and assemblies</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="meetings-container">
                <!-- Meeting Form -->
                <div class="meeting-form">
                    <h4><i class="fas fa-plus"></i> Schedule New Meeting</h4>
                    <?php if (isset($meeting_added) && $meeting_added): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Meeting scheduled successfully!
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add_meeting">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="meeting_title">Meeting Title</label>
                                    <input type="text" class="form-control" id="meeting_title" name="meeting_title" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="meeting_date">Date</label>
                                    <input type="date" class="form-control" id="meeting_date" name="meeting_date" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="meeting_time">Time</label>
                                    <select class="form-control" id="meeting_time" name="meeting_time" required>
                                        <option value="">Select Time</option>
                                        <option value="08:00">8:00 AM</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" required placeholder="Barangay Hall">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus"></i> Schedule
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Meetings List -->
                <div class="meetings-list">
                    <h4><i class="fas fa-list"></i> Scheduled Meetings</h4>
                    
                    <!-- Sample Meetings -->
                    <div class="meeting-item upcoming">
                        <div class="meeting-header-info">
                            <div>
                                <div class="meeting-title">Monthly Barangay Assembly</div>
                                <div class="meeting-type type-assembly">Assembly</div>
                                <div class="meeting-meta">
                                    <i class="fas fa-calendar"></i> <?php echo date('F d, Y', strtotime('+5 days')); ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> 9:00 AM
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-map-marker-alt"></i> Barangay Hall
                                </div>
                            </div>
                            <div class="meeting-actions">
                                <span class="status-badge status-upcoming">Upcoming</span>
                            </div>
                        </div>
                        <div class="meeting-details">
                            <p><strong>Agenda:</strong> Monthly report, upcoming projects, community concerns</p>
                            <p><strong>Attendees:</strong> All residents and barangay officials</p>
                        </div>
                        <div class="meeting-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-success btn-action">
                                <i class="fas fa-bell"></i> Send Reminder
                            </button>
                        </div>
                    </div>

                    <div class="meeting-item upcoming">
                        <div class="meeting-header-info">
                            <div>
                                <div class="meeting-title">Officials Meeting - Budget Review</div>
                                <div class="meeting-type type-official">Official</div>
                                <div class="meeting-meta">
                                    <i class="fas fa-calendar"></i> <?php echo date('F d, Y', strtotime('+2 days')); ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> 2:00 PM
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-map-marker-alt"></i> Conference Room
                                </div>
                            </div>
                            <div class="meeting-actions">
                                <span class="status-badge status-upcoming">Upcoming</span>
                            </div>
                        </div>
                        <div class="meeting-details">
                            <p><strong>Agenda:</strong> Q2 budget review, expense approvals, fund allocation</p>
                            <p><strong>Attendees:</strong> Barangay officials and treasurer</p>
                        </div>
                        <div class="meeting-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="meeting-item completed">
                        <div class="meeting-header-info">
                            <div>
                                <div class="meeting-title">Infrastructure Planning Meeting</div>
                                <div class="meeting-type type-official">Official</div>
                                <div class="meeting-meta">
                                    <i class="fas fa-calendar"></i> <?php echo date('F d, Y', strtotime('-7 days')); ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> 3:00 PM
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-map-marker-alt"></i> Barangay Hall
                                </div>
                            </div>
                            <div class="meeting-actions">
                                <span class="status-badge status-completed">Completed</span>
                            </div>
                        </div>
                        <div class="meeting-details">
                            <p><strong>Agenda:</strong> Road repair project, drainage improvement, solar lighting</p>
                            <p><strong>Attendees:</strong> 12 officials attended</p>
                        </div>
                        <div class="meeting-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-success btn-action">
                                <i class="fas fa-download"></i> Minutes
                            </button>
                        </div>
                    </div>

                    <div class="meeting-item cancelled">
                        <div class="meeting-header-info">
                            <div>
                                <div class="meeting-title">Emergency Meeting - Flood Response</div>
                                <div class="meeting-type type-emergency">Emergency</div>
                                <div class="meeting-meta">
                                    <i class="fas fa-calendar"></i> <?php echo date('F d, Y', strtotime('-3 days')); ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> 10:00 AM
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-map-marker-alt"></i> Barangay Hall
                                </div>
                            </div>
                            <div class="meeting-actions">
                                <span class="status-badge status-cancelled">Cancelled</span>
                            </div>
                        </div>
                        <div class="meeting-details">
                            <p><strong>Reason:</strong> Weather conditions improved, emergency response no longer needed</p>
                            <p><strong>Note:</strong> Rescheduled for next week if needed</p>
                        </div>
                        <div class="meeting-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-primary btn-action">
                                <i class="fas fa-redo"></i> Reschedule
                            </button>
                        </div>
                    </div>

                    <?php if (empty($meetings)): ?>
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <h5>No Meetings Scheduled</h5>
                            <p>No meetings have been scheduled yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
