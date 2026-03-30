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
$projects = []; // This would come from database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $project_name = $_POST['project_name'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? '';
    
    if ($action === 'add_project') {
        // Add project logic
        $project_added = true;
    }
}

$pageTitle = 'Project Management - Admin Portal';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Project Management Styles */
.projects-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.projects-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.project-filters {
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

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.project-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.project-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.project-header-info {
    display: flex;
    justify-content: space-between;
    align-items-start;
    margin-bottom: 1rem;
}

.project-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.project-status {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-planning {
    background: #f39c12;
    color: white;
}

.status-ongoing {
    background: #3498db;
    color: white;
}

.status-completed {
    background: #27ae60;
    color: white;
}

.status-delayed {
    background: #e74c3c;
    color: white;
}

.project-description {
    color: #7f8c8d;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.project-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    color: #7f8c8d;
}

.project-progress {
    margin-bottom: 1rem;
}

.progress-label {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.progress-bar {
    height: 8px;
    background: #ecf0f1;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3498db, #2980b9);
    border-radius: 4px;
    transition: width 0.3s ease;
}

.project-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.add-project-form {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 2rem;
    margin-bottom: 2rem;
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

.project-budget {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.budget-amount {
    font-weight: bold;
    color: #2c3e50;
}
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="projects-header">
                <h1><i class="fas fa-project-diagram"></i> Project Management</h1>
                <p class="lead">Manage barangay development projects and initiatives</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="projects-container">
                <!-- Statistics -->
                <div class="stats-row">
                    <div class="stat-card">
                        <h4>12</h4>
                        <p>Total Projects</p>
                    </div>
                    <div class="stat-card">
                        <h4>5</h4>
                        <p>Ongoing</p>
                    </div>
                    <div class="stat-card">
                        <h4>6</h4>
                        <p>Completed</p>
                    </div>
                    <div class="stat-card">
                        <h4>₱2.5M</h4>
                        <p>Total Budget</p>
                    </div>
                </div>

                <!-- Add Project Form -->
                <div class="add-project-form">
                    <h4><i class="fas fa-plus"></i> Add New Project</h4>
                    <?php if (isset($project_added) && $project_added): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Project added successfully!
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add_project">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="project_name">Project Name</label>
                                    <input type="text" class="form-control" id="project_name" name="project_name" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="planning">Planning</option>
                                        <option value="ongoing">Ongoing</option>
                                        <option value="completed">Completed</option>
                                        <option value="delayed">Delayed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <input type="text" class="form-control" id="description" name="description" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Filters -->
                <div class="project-filters">
                    <button class="filter-btn active" data-filter="all">
                        <i class="fas fa-list"></i> All Projects
                    </button>
                    <button class="filter-btn" data-filter="planning">
                        <i class="fas fa-lightbulb"></i> Planning
                    </button>
                    <button class="filter-btn" data-filter="ongoing">
                        <i class="fas fa-spinner"></i> Ongoing
                    </button>
                    <button class="filter-btn" data-filter="completed">
                        <i class="fas fa-check"></i> Completed
                    </button>
                    <button class="filter-btn" data-filter="delayed">
                        <i class="fas fa-exclamation-triangle"></i> Delayed
                    </button>
                </div>

                <!-- Projects Grid -->
                <div class="projects-grid">
                    <div class="project-card" data-status="ongoing">
                        <div class="project-header-info">
                            <div>
                                <div class="project-title">Road Repair - Main Street</div>
                                <div class="project-budget">
                                    Budget: <span class="budget-amount">₱250,000</span>
                                </div>
                            </div>
                            <div class="project-status status-ongoing">Ongoing</div>
                        </div>
                        <div class="project-description">
                            Repair and resurfacing of main street to improve road safety and traffic flow.
                        </div>
                        <div class="project-meta">
                            <span><i class="fas fa-calendar"></i> Started: Feb 15, 2024</span>
                            <span><i class="fas fa-flag-checkered"></i> Target: Apr 30, 2024</span>
                        </div>
                        <div class="project-progress">
                            <div class="progress-label">
                                <span>Progress</span>
                                <span>65%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 65%"></div>
                            </div>
                        </div>
                        <div class="project-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="project-card" data-status="completed">
                        <div class="project-header-info">
                            <div>
                                <div class="project-title">Community Center Renovation</div>
                                <div class="project-budget">
                                    Budget: <span class="budget-amount">₱150,000</span>
                                </div>
                            </div>
                            <div class="project-status status-completed">Completed</div>
                        </div>
                        <div class="project-description">
                            Complete renovation of community center including new flooring, lighting, and furniture.
                        </div>
                        <div class="project-meta">
                            <span><i class="fas fa-calendar"></i> Started: Jan 10, 2024</span>
                            <span><i class="fas fa-check-circle"></i> Completed: Mar 15, 2024</span>
                        </div>
                        <div class="project-progress">
                            <div class="progress-label">
                                <span>Progress</span>
                                <span>100%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 100%"></div>
                            </div>
                        </div>
                        <div class="project-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-success btn-action">
                                <i class="fas fa-download"></i> Report
                            </button>
                        </div>
                    </div>

                    <div class="project-card" data-status="planning">
                        <div class="project-header-info">
                            <div>
                                <div class="project-title">Solar Street Lighting</div>
                                <div class="project-budget">
                                    Budget: <span class="budget-amount">₱500,000</span>
                                </div>
                            </div>
                            <div class="project-status status-planning">Planning</div>
                        </div>
                        <div class="project-description">
                            Installation of solar-powered street lights in all major roads for energy efficiency.
                        </div>
                        <div class="project-meta">
                            <span><i class="fas fa-calendar"></i> Planned: May 1, 2024</span>
                            <span><i class="fas fa-flag-checkered"></i> Target: Aug 31, 2024</span>
                        </div>
                        <div class="project-progress">
                            <div class="progress-label">
                                <span>Progress</span>
                                <span>15%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 15%"></div>
                            </div>
                        </div>
                        <div class="project-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="project-card" data-status="ongoing">
                        <div class="project-header-info">
                            <div>
                                <div class="project-title">Drainage System Improvement</div>
                                <div class="project-budget">
                                    Budget: <span class="budget-amount">₱350,000</span>
                                </div>
                            </div>
                            <div class="project-status status-ongoing">Ongoing</div>
                        </div>
                        <div class="project-description">
                            Upgrade and repair of drainage system to prevent flooding during rainy season.
                        </div>
                        <div class="project-meta">
                            <span><i class="fas fa-calendar"></i> Started: Mar 1, 2024</span>
                            <span><i class="fas fa-flag-checkered"></i> Target: May 15, 2024</span>
                        </div>
                        <div class="project-progress">
                            <div class="progress-label">
                                <span>Progress</span>
                                <span>40%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 40%"></div>
                            </div>
                        </div>
                        <div class="project-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="project-card" data-status="delayed">
                        <div class="project-header-info">
                            <div>
                                <div class="project-title">Basketball Court Renovation</div>
                                <div class="project-budget">
                                    Budget: <span class="budget-amount">₱80,000</span>
                                </div>
                            </div>
                            <div class="project-status status-delayed">Delayed</div>
                        </div>
                        <div class="project-description">
                            Renovation of basketball court including new flooring, hoops, and lighting.
                        </div>
                        <div class="project-meta">
                            <span><i class="fas fa-calendar"></i> Started: Feb 1, 2024</span>
                            <span><i class="fas fa-exclamation-triangle"></i> Delayed to: May 1, 2024</span>
                        </div>
                        <div class="project-progress">
                            <div class="progress-label">
                                <span>Progress</span>
                                <span>25%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 25%"></div>
                            </div>
                        </div>
                        <div class="project-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>

                <?php if (empty($projects)): ?>
                    <div class="empty-state">
                        <i class="fas fa-project-diagram"></i>
                        <h5>No Projects Found</h5>
                        <p>No projects have been created yet.</p>
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
    const projectCards = document.querySelectorAll('.project-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            // Show/hide projects based on filter
            projectCards.forEach(card => {
                if (filter === 'all') {
                    card.style.display = 'block';
                } else {
                    card.dataset.status === filter ? 
                        card.style.display = 'block' : 
                        card.style.display = 'none';
                }
            });
        });
    });
});
</script>
