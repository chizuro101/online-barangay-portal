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
$requests = []; // This would come from database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $request_id = $_POST['request_id'] ?? '';
    
    if ($action === 'approve') {
        // Approve request logic
        $request_approved = true;
    } elseif ($action === 'reject') {
        // Reject request logic
        $request_rejected = true;
    }
}

$pageTitle = 'Service Requests - Admin Portal';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Service Requests Styles */
.requests-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.requests-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.request-filters {
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

.request-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #3498db;
    transition: all 0.3s ease;
}

.request-item:hover {
    transform: translateX(5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.request-item.pending {
    border-left-color: #f39c12;
}

.request-item.approved {
    border-left-color: #27ae60;
}

.request-item.rejected {
    border-left-color: #e74c3c;
}

.request-header {
    display: flex;
    justify-content-between;
    align-items-start;
    margin-bottom: 1rem;
}

.request-info h6 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.request-meta {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.request-actions {
    display: flex;
    gap: 0.5rem;
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

.status-approved {
    background: #27ae60;
    color: white;
}

.status-rejected {
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

.request-details {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.request-type {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    background: #ecf0f1;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="requests-header">
                <h1><i class="fas fa-clipboard-list"></i> Service Requests</h1>
                <p class="lead">Review and process resident service requests</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="requests-container">
                <!-- Filters -->
                <div class="request-filters">
                    <button class="filter-btn active" data-filter="all">
                        <i class="fas fa-list"></i> All Requests
                    </button>
                    <button class="filter-btn" data-filter="pending">
                        <i class="fas fa-clock"></i> Pending
                    </button>
                    <button class="filter-btn" data-filter="approved">
                        <i class="fas fa-check"></i> Approved
                    </button>
                    <button class="filter-btn" data-filter="rejected">
                        <i class="fas fa-times"></i> Rejected
                    </button>
                </div>

                <?php if (isset($request_approved) && $request_approved): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Request approved successfully!
                    </div>
                <?php endif; ?>

                <?php if (isset($request_rejected) && $request_rejected): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Request rejected.
                    </div>
                <?php endif; ?>

                <!-- Sample Requests -->
                <div class="request-item pending">
                    <div class="request-header">
                        <div class="request-info">
                            <h6>Barangay Clearance Request</h6>
                            <div class="request-type">Clearance</div>
                            <div class="request-meta">
                                <i class="fas fa-user"></i> Juan Dela Cruz
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y'); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> 2 hours ago
                            </div>
                        </div>
                        <div class="request-actions">
                            <span class="status-badge status-pending">Pending</span>
                        </div>
                    </div>
                    <div class="request-details">
                        <p><strong>Purpose:</strong> Employment requirement</p>
                        <p><strong>Details:</strong> Requesting barangay clearance for job application at ABC Company.</p>
                    </div>
                    <div class="request-actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="request_id" value="1">
                            <button type="submit" class="btn btn-success btn-action">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="reject">
                            <input type="hidden" name="request_id" value="1">
                            <button type="submit" class="btn btn-danger btn-action">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                        <button class="btn btn-info btn-action">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>

                <div class="request-item pending">
                    <div class="request-header">
                        <div class="request-info">
                            <h6>Certificate of Residency</h6>
                            <div class="request-type">Certificate</div>
                            <div class="request-meta">
                                <i class="fas fa-user"></i> Maria Santos
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y'); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> 5 hours ago
                            </div>
                        </div>
                        <div class="request-actions">
                            <span class="status-badge status-pending">Pending</span>
                        </div>
                    </div>
                    <div class="request-details">
                        <p><strong>Purpose:</strong> School enrollment</p>
                        <p><strong>Details:</strong> Certificate needed for child's school enrollment at San Pascual Elementary.</p>
                    </div>
                    <div class="request-actions">
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="approve">
                            <input type="hidden" name="request_id" value="2">
                            <button type="submit" class="btn btn-success btn-action">
                                <i class="fas fa-check"></i> Approve
                            </button>
                        </form>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="reject">
                            <input type="hidden" name="request_id" value="2">
                            <button type="submit" class="btn btn-danger btn-action">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </form>
                        <button class="btn btn-info btn-action">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>

                <div class="request-item approved">
                    <div class="request-header">
                        <div class="request-info">
                            <h6>Indigency Certificate</h6>
                            <div class="request-type">Certificate</div>
                            <div class="request-meta">
                                <i class="fas fa-user"></i> Pedro Reyes
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime('-2 days')); ?>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock"></i> 2 days ago
                            </div>
                        </div>
                        <div class="request-actions">
                            <span class="status-badge status-approved">Approved</span>
                        </div>
                    </div>
                    <div class="request-details">
                        <p><strong>Purpose:</strong> Medical assistance</p>
                        <p><strong>Details:</strong> Approved for medical assistance program. Certificate ready for pickup.</p>
                    </div>
                    <div class="request-actions">
                        <button class="btn btn-success btn-action">
                            <i class="fas fa-download"></i> Download Certificate
                        </button>
                        <button class="btn btn-info btn-action">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>

                <?php if (empty($requests)): ?>
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <h5>No Service Requests Found</h5>
                        <p>No service requests have been submitted yet.</p>
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
    const requestItems = document.querySelectorAll('.request-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            // Show/hide requests based on filter
            requestItems.forEach(item => {
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
