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
$notifications = []; // This would come from database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $message = $_POST['message'] ?? '';
    $recipient_type = $_POST['recipient_type'] ?? '';
    
    if ($action === 'send_notification') {
        // Send notification logic
        $notification_sent = true;
    }
}

$pageTitle = 'Notification System - Admin Portal';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Notification System Styles */
.notifications-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.notifications-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.notification-form {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.notifications-list {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.notification-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #3498db;
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.notification-item.sent {
    border-left-color: #27ae60;
}

.notification-item.pending {
    border-left-color: #f39c12;
}

.notification-item.failed {
    border-left-color: #e74c3c;
}

.notification-header-info {
    display: flex;
    justify-content: space-between;
    align-items-start;
    margin-bottom: 1rem;
}

.notification-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.notification-meta {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.notification-actions {
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

.status-sent {
    background: #27ae60;
    color: white;
}

.status-pending {
    background: #f39c12;
    color: white;
}

.status-failed {
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

.notification-details {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.notification-type {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    background: #ecf0f1;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.type-announcement {
    background: #e3f2fd;
    color: #1976d2;
}

.type-emergency {
    background: #ffebee;
    color: #c62828;
}

.type-reminder {
    background: #f3e5f5;
    color: #7b1fa2;
}

.type-general {
    background: #e8f5e8;
    color: #388e3c;
}

.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.stat-card h4 {
    color: #2c3e50;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.stat-card p {
    color: #7f8c8d;
    margin: 0;
}

.recipients-info {
    background: #e8f5e8;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.recipients-info h6 {
    color: #27ae60;
    font-weight: 600;
    margin-bottom: 0.5rem;
}
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="notifications-header">
                <h1><i class="fas fa-bell"></i> Notification System</h1>
                <p class="lead">Send notifications and announcements to residents</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="notifications-container">
                <!-- Statistics -->
                <div class="stats-row">
                    <div class="stat-card">
                        <h4>245</h4>
                        <p>Total Sent</p>
                    </div>
                    <div class="stat-card">
                        <h4>18</h4>
                        <p>Pending</p>
                    </div>
                    <div class="stat-card">
                        <h4>2</h4>
                        <p>Failed</p>
                    </div>
                    <div class="stat-card">
                        <h4>1,250</h4>
                        <p>Total Recipients</p>
                    </div>
                </div>

                <!-- Notification Form -->
                <div class="notification-form">
                    <h4><i class="fas fa-paper-plane"></i> Send New Notification</h4>
                    <?php if (isset($notification_sent) && $notification_sent): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Notification sent successfully!
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="send_notification">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required placeholder="Enter your notification message..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="recipient_type">Recipient Type</label>
                                    <select class="form-control" id="recipient_type" name="recipient_type" required>
                                        <option value="">Select Recipients</option>
                                        <option value="all">All Residents</option>
                                        <option value="officials">Officials Only</option>
                                        <option value="senior_citizens">Senior Citizens</option>
                                        <option value="youth">Youth</option>
                                        <option value="business">Business Owners</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="notification_type">Type</label>
                                    <select class="form-control" id="notification_type" name="notification_type" required>
                                        <option value="">Select Type</option>
                                        <option value="announcement">Announcement</option>
                                        <option value="emergency">Emergency</option>
                                        <option value="reminder">Reminder</option>
                                        <option value="general">General</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Delivery Method</label>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="sms" name="delivery_methods[]" value="sms" checked>
                                        <label class="form-check-label" for="sms">SMS</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="email" name="delivery_methods[]" value="email">
                                        <label class="form-check-label" for="email">Email</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="system" name="delivery_methods[]" value="system" checked>
                                        <label class="form-check-label" for="system">System Alert</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-paper-plane"></i> Send
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="recipients-info">
                                    <h6><i class="fas fa-users"></i> Recipients</h6>
                                    <p id="recipient_count">Select recipients to see count</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Notifications List -->
                <div class="notifications-list">
                    <h4><i class="fas fa-list"></i> Recent Notifications</h4>
                    
                    <!-- Sample Notifications -->
                    <div class="notification-item sent">
                        <div class="notification-header-info">
                            <div>
                                <div class="notification-title">Barangay Assembly Reminder</div>
                                <div class="notification-type type-reminder">Reminder</div>
                                <div class="notification-meta">
                                    <i class="fas fa-users"></i> All Residents
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-calendar"></i> <?php echo date('M d, Y'); ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> 10:30 AM
                                </div>
                            </div>
                            <div class="notification-actions">
                                <span class="status-badge status-sent">Sent</span>
                            </div>
                        </div>
                        <div class="notification-details">
                            <p><strong>Message:</strong> Reminder: Monthly barangay assembly scheduled for this Saturday at 9:00 AM at the barangay hall.</p>
                            <p><strong>Delivery:</strong> SMS (245), System (245)</p>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-success btn-action">
                                <i class="fas fa-redo"></i> Resend
                            </button>
                        </div>
                    </div>

                    <div class="notification-item sent">
                        <div class="notification-header-info">
                            <div>
                                <div class="notification-title">Water Service Interruption</div>
                                <div class="notification-type type-emergency">Emergency</div>
                                <div class="notification-meta">
                                    <i class="fas fa-users"></i> Block 2 Residents
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime('-2 days')); ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> 2:15 PM
                                </div>
                            </div>
                            <div class="notification-actions">
                                <span class="status-badge status-sent">Sent</span>
                            </div>
                        </div>
                        <div class="notification-details">
                            <p><strong>Message:</strong> Water service interruption scheduled for tomorrow 8 AM - 5 PM for pipe maintenance.</p>
                            <p><strong>Delivery:</strong> SMS (45), System (45)</p>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-success btn-action">
                                <i class="fas fa-redo"></i> Resend
                            </button>
                        </div>
                    </div>

                    <div class="notification-item pending">
                        <div class="notification-header-info">
                            <div>
                                <div class="notification-title">New Community Program Launch</div>
                                <div class="notification-type type-announcement">Announcement</div>
                                <div class="notification-meta">
                                    <i class="fas fa-users"></i> Youth Residents
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-calendar"></i> <?php echo date('M d, Y'); ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> 3:45 PM
                                </div>
                            </div>
                            <div class="notification-actions">
                                <span class="status-badge status-pending">Pending</span>
                            </div>
                        </div>
                        <div class="notification-details">
                            <p><strong>Message:</strong> Exciting news! We're launching a new youth development program next month.</p>
                            <p><strong>Delivery:</strong> SMS (78), Email (78)</p>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-pause"></i> Pause
                            </button>
                        </div>
                    </div>

                    <div class="notification-item failed">
                        <div class="notification-header-info">
                            <div>
                                <div class="notification-title">Meeting Schedule Change</div>
                                <div class="notification-type type-general">General</div>
                                <div class="notification-meta">
                                    <i class="fas fa-users"></i> Officials
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime('-1 day')); ?>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-clock"></i> 11:20 AM
                                </div>
                            </div>
                            <div class="notification-actions">
                                <span class="status-badge status-failed">Failed</span>
                            </div>
                        </div>
                        <div class="notification-details">
                            <p><strong>Message:</strong> Tomorrow's meeting rescheduled to 3:00 PM due to conflict.</p>
                            <p><strong>Error:</strong> SMS gateway timeout - Email sent successfully</p>
                        </div>
                        <div class="notification-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View Details
                            </button>
                            <button class="btn btn-danger btn-action">
                                <i class="fas fa-redo"></i> Retry
                            </button>
                        </div>
                    </div>

                    <?php if (empty($notifications)): ?>
                        <div class="empty-state">
                            <i class="fas fa-bell"></i>
                            <h5>No Notifications Sent</h5>
                            <p>No notifications have been sent yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
// Update recipient count based on selection
document.addEventListener('DOMContentLoaded', function() {
    const recipientSelect = document.getElementById('recipient_type');
    const recipientCount = document.getElementById('recipient_count');
    
    const recipientCounts = {
        'all': '1,250 residents',
        'officials': '12 officials',
        'senior_citizens': '180 residents',
        'youth': '320 residents',
        'business': '85 residents'
    };
    
    recipientSelect.addEventListener('change', function() {
        const count = recipientCounts[this.value] || 'Select recipients to see count';
        recipientCount.textContent = count;
    });
});
</script>
