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
$inventory = []; // This would come from database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $item_name = $_POST['item_name'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $category = $_POST['category'] ?? '';
    
    if ($action === 'add') {
        // Add item logic
        $item_added = true;
    } elseif ($action === 'update') {
        // Update item logic
        $item_updated = true;
    }
}

$pageTitle = 'Inventory Management - Admin Portal';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Inventory Management Styles */
.inventory-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.inventory-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.inventory-filters {
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

.inventory-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.inventory-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.inventory-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.inventory-icon {
    font-size: 2.5rem;
    color: #3498db;
    margin-bottom: 1rem;
}

.inventory-name {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.inventory-quantity {
    font-size: 1.5rem;
    font-weight: bold;
    color: #3498db;
    margin-bottom: 0.5rem;
}

.inventory-category {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.inventory-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-action {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.add-item-form {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.category-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    background: #ecf0f1;
    color: #2c3e50;
}

.category-equipment {
    background: #e3f2fd;
    color: #1976d2;
}

.category-supplies {
    background: #f3e5f5;
    color: #7b1fa2;
}

.category-furniture {
    background: #e8f5e8;
    color: #388e3c;
}

.category-documents {
    background: #fff3e0;
    color: #f57c00;
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
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="inventory-header">
                <h1><i class="fas fa-warehouse"></i> Inventory Management</h1>
                <p class="lead">Manage barangay assets, supplies, and equipment</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="inventory-container">
                <!-- Statistics -->
                <div class="stats-row">
                    <div class="stat-card">
                        <h4>156</h4>
                        <p>Total Items</p>
                    </div>
                    <div class="stat-card">
                        <h4>12</h4>
                        <p>Categories</p>
                    </div>
                    <div class="stat-card">
                        <h4>₱245,500</h4>
                        <p>Total Value</p>
                    </div>
                    <div class="stat-card">
                        <h4>8</h4>
                        <p>Low Stock</p>
                    </div>
                </div>

                <!-- Add Item Form -->
                <div class="add-item-form">
                    <h4><i class="fas fa-plus"></i> Add New Item</h4>
                    <?php if (isset($item_added) && $item_added): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Item added successfully!
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="item_name">Item Name</label>
                                    <input type="text" class="form-control" id="item_name" name="item_name" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control" id="category" name="category" required>
                                        <option value="">Select Category</option>
                                        <option value="equipment">Equipment</option>
                                        <option value="supplies">Supplies</option>
                                        <option value="furniture">Furniture</option>
                                        <option value="documents">Documents</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-plus"></i> Add Item
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Filters -->
                <div class="inventory-filters">
                    <button class="filter-btn active" data-filter="all">
                        <i class="fas fa-list"></i> All Items
                    </button>
                    <button class="filter-btn" data-filter="equipment">
                        <i class="fas fa-tools"></i> Equipment
                    </button>
                    <button class="filter-btn" data-filter="supplies">
                        <i class="fas fa-box"></i> Supplies
                    </button>
                    <button class="filter-btn" data-filter="furniture">
                        <i class="fas fa-couch"></i> Furniture
                    </button>
                    <button class="filter-btn" data-filter="documents">
                        <i class="fas fa-file-alt"></i> Documents
                    </button>
                </div>

                <!-- Inventory Grid -->
                <div class="inventory-grid">
                    <div class="inventory-item" data-category="equipment">
                        <div class="inventory-icon">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div class="inventory-name">Desktop Computer</div>
                        <div class="inventory-quantity">5</div>
                        <div class="inventory-category">Equipment</div>
                        <div class="category-badge category-equipment">Equipment</div>
                        <div class="inventory-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="inventory-item" data-category="supplies">
                        <div class="inventory-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="inventory-name">Bond Paper Ream</div>
                        <div class="inventory-quantity">25</div>
                        <div class="inventory-category">Supplies</div>
                        <div class="category-badge category-supplies">Supplies</div>
                        <div class="inventory-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="inventory-item" data-category="furniture">
                        <div class="inventory-icon">
                            <i class="fas fa-chair"></i>
                        </div>
                        <div class="inventory-name">Office Chair</div>
                        <div class="inventory-quantity">12</div>
                        <div class="inventory-category">Furniture</div>
                        <div class="category-badge category-furniture">Furniture</div>
                        <div class="inventory-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="inventory-item" data-category="documents">
                        <div class="inventory-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="inventory-name">Official Receipts</div>
                        <div class="inventory-quantity">50</div>
                        <div class="inventory-category">Documents</div>
                        <div class="category-badge category-documents">Documents</div>
                        <div class="inventory-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="inventory-item" data-category="equipment">
                        <div class="inventory-icon">
                            <i class="fas fa-print"></i>
                        </div>
                        <div class="inventory-name">Printer</div>
                        <div class="inventory-quantity">3</div>
                        <div class="inventory-category">Equipment</div>
                        <div class="category-badge category-equipment">Equipment</div>
                        <div class="inventory-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="inventory-item" data-category="supplies">
                        <div class="inventory-icon">
                            <i class="fas fa-pen"></i>
                        </div>
                        <div class="inventory-name">Ballpens</div>
                        <div class="inventory-quantity">100</div>
                        <div class="inventory-category">Supplies</div>
                        <div class="category-badge category-supplies">Supplies</div>
                        <div class="inventory-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>

                    <div class="inventory-item" data-category="furniture">
                        <div class="inventory-icon">
                            <i class="fas fa-table"></i>
                        </div>
                        <div class="inventory-name">Conference Table</div>
                        <div class="inventory-quantity">2</div>
                        <div class="inventory-category">Furniture</div>
                        <div class="category-badge category-furniture">Furniture</div>
                        <div class="inventory-actions">
                            <button class="btn btn-info btn-action">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <button class="btn btn-warning btn-action">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                </div>

                <?php if (empty($inventory)): ?>
                    <div class="empty-state">
                        <i class="fas fa-warehouse"></i>
                        <h5>No Inventory Items</h5>
                        <p>No items have been added to the inventory yet.</p>
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
    const inventoryItems = document.querySelectorAll('.inventory-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            filterBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            // Show/hide items based on filter
            inventoryItems.forEach(item => {
                if (filter === 'all') {
                    item.style.display = 'block';
                } else {
                    item.dataset.category === filter ? 
                        item.style.display = 'block' : 
                        item.style.display = 'none';
                }
            });
        });
    });
});
</script>
