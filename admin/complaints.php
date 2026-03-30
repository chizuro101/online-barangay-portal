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
$complaints = []; // This would come from database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $complaint_id = $_POST['complaint_id'] ?? '';
    $resolution = $_POST['resolution'] ?? '';
    
    if ($action === 'resolve') {
        // Resolve complaint logic
        $complaint_resolved = true;
    } elseif ($action === 'escalate') {
        // Escalate complaint logic
        $complaint_escalated = true;
    }
}

$pageTitle = 'Complaint Management - Admin Portal';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Complaints Management Styles */
.complaints-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.complaints-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.complaint-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    border: 1px solid #ddd;
    background: white;
    color: #7f8c8d;
    transition: all 0.3s ease;
    cursor: pointer;
}

.filter-btn.active {
    background: #3498db;
    color: white;
    border-color: #3498db;
}

.complaint-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #e74c3c;
    transition: all 0.3s ease;
}

.complaint-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.complaint-item.pending {
    border-left-color: #f39c12;
}

.complaint-item.in-progress {
    border-left-color: #3498db;
}

.complaint-item.resolved {
    border-left-color: #27ae60;
}

.complaint-item.escalated {
    border-left-color: #e74c3c;
}

.complaint-header {
    display: flex;
    justify-content-between;
    align-items-start;
    margin-bottom: 1rem;
}

.complaint-info h6 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.complaint-meta {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.complaint-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.urgency-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.urgency-high {
    background: #e74c3c;
    color: white;
}

.urgency-medium {
    background: #f39c12;
    color: white;
}

.urgency-low {
    background: #27ae60;
    color: white;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-pending {
    background: #f39c12;
    color: white;
}

.status-in-progress {
    background: #3498db;
    color: white;
}

.status-resolved {
    background: #27ae60;
    color: white;
}

.status-escalated {
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

.complaint-details {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.complaint-type {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    background: #ecf0f1;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.resolution-section {
    background: #e8f5e8;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
    border-left: 4px solid #27ae60;
}

.resolution-section h6 {
    color: #27ae60;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.anonymous-badge {
    background: #95a5a6;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="complaints-header">
                <h1><i class="fas fa-exclamation-triangle"></i> Complaint Management</h1>
                <p class="lead">Review and address resident complaints and concerns</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="complaints-container">
                <!-- Filters -->
                <div class="complaint-filters">
                    <button class="filter-btn active" data-filter="all">
                        <i class="fas fa-list"></i> All Complaints
                    </button>
                    <button class="filter-btn" data-filter="pending">
                        <i class="fas fa-clock"></i> Pending
                    </button>
                    <button class="filter-btn" data-filter="in-progress">
                        <i class="fas fa-spinner"></i> In Progress
                    </button>
                    <button class="filter-btn" data-filter="resolved">
                        <i class="fas fa-check"></i> Resolved
                    </button>
                    <button class="filter-btn" data-filter="escalated">
                        <i class="fas fa-arrow-up"></i> Escalated
                    </button>
                </div>

                <?php if (isset($complaint_resolved) && $complaint_resolved): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Complaint marked as resolved!
                    </div>
                <?php endif; ?>

                <?php if (isset($complaint_escalated) && $complaint_escalated): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Complaint escalated to higher authority.
                    </div>
                <?php endif; ?>

                <!-- Sample Complaints -->
                <div class="complaint-item pending">
                    <div class="complaint-header">
                        <div class="complaint-info">
                            <h6>Noise Complaint - Late Night Parties</h6>
                            <div class="complaint-type">Noise</div>
                            <div class="urgency-badge urgency-medium">Medium Urgency</div>
                            <div class="complaint-meta">
                                <i class="fas fa-user"></i> Juan Dela Cruz
                                <span class="mx-2">•</span>
                                <i class="fas fa-map-marker-alt"></i> Block 3, Lot 15
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y'); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> 1 hour ago
                            </div>
                        </div>
                        <div class="complaint-actions">
                            <span class="status-badge status-pending">Pending</span>
                        </div>
                    </div>
                    <div class="complaint-details">
                        <p><strong>Subject:</strong> Frequent loud parties until 2 AM</p>
                        <p><strong>Description:</strong> Neighbor frequently hosts loud parties on weekends that last until early morning hours. Multiple residents affected.</p>
                        <p><strong>Location:</strong> Block 3, Lot 15 - Residential Area</p>
                    </div>
                    <div class="complaint-actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="resolve">
                            <input type="hidden" name="complaint_id" value="1">
                            <button type="submit" class="btn btn-success btn-action">
                                <i class="fas fa-check"></i> Resolve
                            </button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="escalate">
                            <input type="hidden" name="complaint_id" value="1">
                            <button type="submit" class="btn btn-warning btn-action">
                                <i class="fas fa-arrow-up"></i> Escalate
                            </button>
                        </form>
                        <button class="btn btn-info btn-action">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>

                <div class="complaint-item in-progress">
                    <div class="complaint-header">
                        <div class="complaint-info">
                            <h6>Garbage Collection Issue</h6>
                            <div class="complaint-type">Environmental</div>
                            <div class="urgency-badge urgency-high">High Urgency</div>
                            <div class="complaint-meta">
                                <i class="fas fa-user"></i> Maria Santos
                                <span class="mx-2">•</span>
                                <i class="fas fa-map-marker-alt"></i> Block 5, Area B
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime('-1 day')); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> 1 day ago
                            </div>
                        </div>
                        <div class="complaint-actions">
                            <span class="status-badge status-in-progress">In Progress</span>
                        </div>
                    </div>
                    <div class="complaint-details">
                        <p><strong>Subject:</strong> Uncollected garbage for 3 days</p>
                        <p><strong>Description:</strong> Garbage in Block 5 has not been collected for 3 consecutive days. Foul odor and pest concerns.</p>
                        <p><strong>Location:</strong> Block 5, Area B - Collection Point</p>
                    </div>
                    <div class="complaint-actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="resolve">
                            <input type="hidden" name="complaint_id" value="2">
                            <button type="submit" class="btn btn-success btn-action">
                                <i class="fas fa-check"></i> Mark Resolved
                            </button>
                        </form>
                        <button class="btn btn-info btn-action">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>

                <div class="complaint-item resolved">
                    <div class="complaint-header">
                        <div class="complaint-info">
                            <h6>Street Light Malfunction</h6>
                            <div class="complaint-type">Infrastructure</div>
                            <div class="urgency-badge urgency-low">Low Urgency</div>
                            <div class="complaint-meta">
                                <i class="fas fa-user"></i> Pedro Reyes
                                <span class="mx-2">•</span>
                                <i class="fas fa-map-marker-alt"></i> Main Street
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime('-3 days')); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> 3 days ago
                            </div>
                        </div>
                        <div class="complaint-actions">
                            <span class="status-badge status-resolved">Resolved</span>
                        </div>
                    </div>
                    <div class="complaint-details">
                        <p><strong>Subject:</strong> Broken street light</p>
                        <p><strong>Description:</strong> Street light at Main Street corner not working for 2 weeks.</p>
                        <p><strong>Location:</strong> Main Street - Corner with Avenue A</p>
                    </div>
                    <div class="resolution-section">
                        <h6><i class="fas fa-check-circle"></i> Resolution</h6>
                        <p>Street light has been repaired by barangay maintenance team on <?php echo date('M d, Y', strtotime('-1 day')); ?>.</p>
                    </div>
                    <div class="complaint-actions">
                        <button class="btn btn-success btn-action">
                            <i class="fas fa-download"></i> Download Report
                        </button>
                        <button class="btn btn-info btn-action">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>

                <div class="complaint-item pending">
                    <div class="complaint-header">
                        <div class="complaint-info">
                            <h6>Anonymous - Water Supply Issue</h6>
                            <div class="complaint-type">Utilities</div>
                            <div class="urgency-badge urgency-high">High Urgency</div>
                            <div class="anonymous-badge">Anonymous</div>
                            <div class="complaint-meta">
                                <i class="fas fa-map-marker-alt"></i> Block 2, Area A
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y'); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> 3 hours ago
                            </div>
                        </div>
                        <div class="complaint-actions">
                            <span class="status-badge status-pending">Pending</span>
                        </div>
                    </div>
                    <div class="complaint-details">
                        <p><strong>Subject:</strong> No water supply for 2 days</p>
                        <p><strong>Description:</strong> Several households in Block 2 experiencing water supply interruption for 2 days now.</p>
                        <p><strong>Location:</strong> Block 2, Area A - Multiple households affected</p>
                    </div>
                    <div class="complaint-actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="resolve">
                            <input type="hidden" name="complaint_id" value="4">
                            <button type="submit" class="btn btn-success btn-action">
                                <i class="fas fa-check"></i> Resolve
                            </button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="escalate">
                            <input type="hidden" name="complaint_id" value="4">
                            <button type="submit" class="btn btn-warning btn-action">
                                <i class="fas fa-arrow-up"></i> Escalate
                            </button>
                        </form>
                        <button class="btn btn-info btn-action">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>

                <?php if (empty($complaints)): ?>
                    <div class="empty-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h5>No Complaints Found</h5>
                        <p>No complaints have been submitted yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
// Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const complaintItems = document.querySelectorAll('.complaint-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            // Show/hide complaints based on filter
            complaintItems.forEach(item => {
                if (filter === 'all') {
                    item.style.display = 'block';
                } else {
                    item.classList.contains(filter) ? 
                        item.style.display = 'block' : 
                        item.style.display = 'none';
                }
            });
        });
    });
});
</script>
