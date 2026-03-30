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
$appointments = []; // This would come from database
$allAppointments = []; // This would come from database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_name = $_POST['resident_name'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';
    $purpose = $_POST['purpose'] ?? '';
    $status = $_POST['status'] ?? 'scheduled';
    
    // Here you would save to database
    $appointment_saved = true;
}

$pageTitle = 'Appointment Management - Admin Portal';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Appointments Management Styles */
.appointments-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.appointment-form {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.appointments-list {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.appointment-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #3498db;
    transition: all 0.3s ease;
}

.appointment-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-scheduled {
    background: #3498db;
    color: white;
}

.status-completed {
    background: #27ae60;
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
    margin: 0.25rem;
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
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="appointments-header">
                <h1><i class="fas fa-calendar"></i> Appointment Management</h1>
                <p class="lead">Manage resident appointments and barangay meetings</p>
            </div>
        </div>
    </div>

    <!-- Appointment Form -->
    <div class="row">
        <div class="col-md-6">
            <div class="appointment-form">
                <h4><i class="fas fa-plus"></i> Schedule New Appointment</h4>
                <?php if (isset($appointment_saved) && $appointment_saved): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Appointment scheduled successfully!
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="resident_name">Resident Name</label>
                        <input type="text" class="form-control" id="resident_name" name="resident_name" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="appointment_date">Date</label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="appointment_time">Time</label>
                                <select class="form-control" id="appointment_time" name="appointment_time" required>
                                    <option value="">Select Time</option>
                                    <option value="08:00">8:00 AM</option>
                                    <option value="09:00">9:00 AM</option>
                                    <option value="10:00">10:00 AM</option>
                                    <option value="11:00">11:00 AM</option>
                                    <option value="13:00">1:00 PM</option>
                                    <option value="14:00">2:00 PM</option>
                                    <option value="15:00">3:00 PM</option>
                                    <option value="16:00">4:00 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="purpose">Purpose</label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="3" required placeholder="Purpose of appointment..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Schedule Appointment
                    </button>
                </form>
            </div>
        </div>

        <!-- Appointments List -->
        <div class="col-md-6">
            <div class="appointments-list">
                <h4><i class="fas fa-list"></i> Scheduled Appointments</h4>
                
                <?php if (!empty($allAppointments)): ?>
                    <?php foreach ($allAppointments as $appointment): ?>
                        <div class="appointment-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6><?php echo htmlspecialchars($appointment['resident_name'] ?? 'John Doe'); ?></h6>
                                    <p class="mb-1"><?php echo htmlspecialchars($appointment['purpose'] ?? 'General consultation'); ?></p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($appointment['appointment_date'] ?? 'today')); ?>
                                        <i class="fas fa-clock ml-2"></i> <?php echo htmlspecialchars($appointment['appointment_time'] ?? '10:00'); ?>
                                    </small>
                                </div>
                                <div class="text-right">
                                    <span class="status-badge status-<?php echo htmlspecialchars($appointment['status'] ?? 'scheduled'); ?>">
                                        <?php echo ucfirst(htmlspecialchars($appointment['status'] ?? 'Scheduled')); ?>
                                    </span>
                                    <div class="mt-2">
                                        <button class="btn btn-sm btn-info btn-action">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger btn-action">
                                            <i class="fas fa-trash"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h5>No Appointments Scheduled</h5>
                        <p>No appointments have been scheduled yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
