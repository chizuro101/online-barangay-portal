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
$transactions = []; // This would come from database

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $transaction_type = $_POST['transaction_type'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if ($action === 'add_transaction') {
        // Add transaction logic
        $transaction_added = true;
    }
}

$pageTitle = 'Financial Management - Admin Portal';

?>
<?php include 'includes/header.php'; ?>

<style>
/* Financial Management Styles */
.financial-header {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    color: white;
    padding: 2.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
    text-align: center;
    box-shadow: 0 8px 25px rgba(44, 62, 80, 0.15);
}

.financial-container {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.financial-overview {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.overview-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.overview-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.overview-card.income {
    border-left: 4px solid #27ae60;
}

.overview-card.expense {
    border-left: 4px solid #e74c3c;
}

.overview-card.balance {
    border-left: 4px solid #3498db;
}

.overview-card h4 {
    color: #2c3e50;
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.overview-card p {
    color: #7f8c8d;
    margin: 0;
}

.overview-card.income h4 {
    color: #27ae60;
}

.overview-card.expense h4 {
    color: #e74c3c;
}

.overview-card.balance h4 {
    color: #3498db;
}

.transaction-form {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.recent-transactions {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    border-bottom: 1px solid #ecf0f1;
    transition: all 0.3s ease;
}

.transaction-item:hover {
    background: #f8f9fa;
    margin: 0 -1rem;
    padding-left: 1rem;
    padding-right: 1rem;
    border-radius: 8px;
}

.transaction-item:last-child {
    border-bottom: none;
}

.transaction-info {
    flex: 1;
}

.transaction-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.25rem;
}

.transaction-date {
    color: #7f8c8d;
    font-size: 0.9rem;
}

.transaction-amount {
    font-size: 1.2rem;
    font-weight: bold;
    padding: 0.5rem 1rem;
    border-radius: 6px;
}

.amount-income {
    color: #27ae60;
    background: #d5f4e6;
}

.amount-expense {
    color: #e74c3c;
    background: #fadbd8;
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

.chart-container {
    background: white;
    border-radius: 10px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.chart-placeholder {
    height: 300px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #7f8c8d;
    font-style: italic;
}
</style>

<!-- Main Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="financial-header">
                <h1><i class="fas fa-money-bill-wave"></i> Financial Management</h1>
                <p class="lead">Manage barangay budget, expenses, and financial reports</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="financial-container">
                <!-- Financial Overview -->
                <div class="financial-overview">
                    <div class="overview-card income">
                        <h4>₱125,450</h4>
                        <p>Total Income</p>
                        <small>This Month</small>
                    </div>
                    <div class="overview-card expense">
                        <h4>₱87,320</h4>
                        <p>Total Expenses</p>
                        <small>This Month</small>
                    </div>
                    <div class="overview-card balance">
                        <h4>₱38,130</h4>
                        <p>Current Balance</p>
                        <small>Available Funds</small>
                    </div>
                </div>

                <!-- Transaction Form -->
                <div class="transaction-form">
                    <h4><i class="fas fa-plus"></i> Add Transaction</h4>
                    <?php if (isset($transaction_added) && $transaction_added): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Transaction added successfully!
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add_transaction">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="transaction_type">Transaction Type</label>
                                    <select class="form-control" id="transaction_type" name="transaction_type" required>
                                        <option value="">Select Type</option>
                                        <option value="income">Income</option>
                                        <option value="expense">Expense</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="amount">Amount (₱)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" step="0.01" required>
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

                <!-- Chart Section -->
                <div class="chart-container">
                    <h4><i class="fas fa-chart-line"></i> Financial Overview</h4>
                    <div class="chart-placeholder">
                        <i class="fas fa-chart-line" style="font-size: 3rem; margin-bottom: 1rem;"></i>
                        <div>Financial chart will be displayed here</div>
                        <small>Monthly income and expenses trend</small>
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="recent-transactions">
                    <h4><i class="fas fa-list"></i> Recent Transactions</h4>
                    
                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-title">Community Service Fees</div>
                            <div class="transaction-date">March 28, 2024 - 10:30 AM</div>
                        </div>
                        <div class="transaction-amount amount-income">+₱5,000</div>
                        <button class="btn btn-sm btn-info btn-action">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-title">Office Supplies Purchase</div>
                            <div class="transaction-date">March 27, 2024 - 2:15 PM</div>
                        </div>
                        <div class="transaction-amount amount-expense">-₱2,350</div>
                        <button class="btn btn-sm btn-info btn-action">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-title">Document Processing Fees</div>
                            <div class="transaction-date">March 27, 2024 - 9:45 AM</div>
                        </div>
                        <div class="transaction-amount amount-income">+₱3,200</div>
                        <button class="btn btn-sm btn-info btn-action">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-title">Utilities Payment</div>
                            <div class="transaction-date">March 26, 2024 - 3:30 PM</div>
                        </div>
                        <div class="transaction-amount amount-expense">-₱8,500</div>
                        <button class="btn btn-sm btn-info btn-action">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-title">Business Permit Fees</div>
                            <div class="transaction-date">March 25, 2024 - 11:20 AM</div>
                        </div>
                        <div class="transaction-amount amount-income">+₱12,000</div>
                        <button class="btn btn-sm btn-info btn-action">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="transaction-item">
                        <div class="transaction-info">
                            <div class="transaction-title">Maintenance Services</div>
                            <div class="transaction-date">March 24, 2024 - 1:00 PM</div>
                        </div>
                        <div class="transaction-amount amount-expense">-₱4,800</div>
                        <button class="btn btn-sm btn-info btn-action">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <?php if (empty($transactions)): ?>
                        <div class="empty-state">
                            <i class="fas fa-money-bill-wave"></i>
                            <h5>No Transactions Found</h5>
                            <p>No transactions have been recorded yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
