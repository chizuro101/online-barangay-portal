<!-- ==================== STAFF MANAGEMENT MODALS ==================== -->

<!-- Create Staff Modal -->
<div class="modal fade" id="createStaffModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus"></i> Create Staff Account
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="enhanced_admin_actions.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username *</label>
                                <input type="text" name="staff_username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password *</label>
                                <input type="password" name="staff_password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>First Name *</label>
                                <input type="text" name="staff_first_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Middle Name</label>
                                <input type="text" name="staff_middle_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Last Name *</label>
                                <input type="text" name="staff_last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sex *</label>
                                <select name="staff_sex" class="form-control" required>
                                    <option value="">Select Sex</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Contact Number *</label>
                                <input type="text" name="staff_contact" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Position *</label>
                                <select name="staff_position" class="form-control" required>
                                    <option value="">Select Position</option>
                                    <?php foreach($userObj->getStaffPositions() as $position): ?>
                                        <option value="<?php echo $position['position_id']; ?>">
                                            <?php echo htmlspecialchars($position['position_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date Hired</label>
                                <input type="date" name="staff_date_hired" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Salary Grade</label>
                                <input type="text" name="staff_salary_grade" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btn_create_staff" class="btn btn-primary">Create Staff</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Staff Modal -->
<div class="modal fade" id="viewStaffModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-users"></i> All Staff Members
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($allOfficials as $official): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($official['official_first_name'] . ' ' . $official['official_last_name']); ?></td>
                                <td>
                                    <?php if($official['is_captain']): ?>
                                        <span class="badge badge-warning">Barangay Captain</span>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($official['position_name'] ?? 'Not Assigned'); ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($official['official_contact_info']); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $official['employment_status'] == 'Active' ? 'success' : 'secondary'; ?>">
                                        <?php echo htmlspecialchars($official['employment_status'] ?? 'Active'); ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editStaff(<?php echo $official['official_id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== APPOINTMENTS MODALS ==================== -->

<!-- Appointments Modal -->
<div class="modal fade" id="appointmentsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-calendar"></i> Appointment Management
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Create Appointment Form -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Schedule New Appointment</h6>
                    </div>
                    <div class="card-body">
                        <form action="enhanced_admin_actions.php" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Resident</label>
                                        <select name="appointment_resident_id" class="form-control" required>
                                            <option value="">Select Resident</option>
                                            <?php 
                                            $residents = $userObj->runQuery("SELECT resident_id, first_name, last_name FROM resident_info ORDER BY last_name ASC");
                                            $residents->execute();
                                            while($resident = $residents->fetch(PDO::FETCH_ASSOC)): ?>
                                                <option value="<?php echo $resident['resident_id']; ?>">
                                                    <?php echo htmlspecialchars($resident['first_name'] . ' ' . $resident['last_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Official</label>
                                        <select name="appointment_official_id" class="form-control" required>
                                            <option value="">Select Official</option>
                                            <?php foreach($allOfficials as $official): ?>
                                                <option value="<?php echo $official['official_id']; ?>">
                                                    <?php echo htmlspecialchars($official['official_first_name'] . ' ' . $official['official_last_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Appointment Type</label>
                                        <select name="appointment_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="Consultation">Consultation</option>
                                            <option value="Document Request">Document Request</option>
                                            <option value="Complaint">Complaint</option>
                                            <option value="Business Permit">Business Permit</option>
                                            <option value="Certificate">Certificate Request</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input type="date" name="appointment_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Time</label>
                                        <input type="time" name="appointment_time" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purpose</label>
                                        <input type="text" name="appointment_purpose" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btn_create_appointment" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Schedule Appointment
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Appointments List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Upcoming Appointments</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Resident</th>
                                        <th>Official</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $appointments = $userObj->getOfficialAppointments($_SESSION['session_login']['official_id'], 'Pending');
                                    foreach($appointments as $appointment): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></td>
                                        <td><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['first_name'] . ' ' . $appointment['last_name']); ?></td>
                                        <td>
                                            <?php 
                                            $official = $userObj->runQuery("SELECT first_name, last_name FROM official_info WHERE official_id = ?", [$appointment['official_id']]);
                                            $official->execute();
                                            $official_data = $official->fetch(PDO::FETCH_ASSOC);
                                            echo htmlspecialchars($official_data['first_name'] . ' ' . $official_data['last_name']);
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_type']); ?></td>
                                        <td>
                                            <span class="badge badge-warning"><?php echo htmlspecialchars($appointment['status']); ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success" onclick="updateAppointmentStatus(<?php echo $appointment['appointment_id']; ?>, 'Confirmed')">
                                                Confirm
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="updateAppointmentStatus(<?php echo $appointment['appointment_id']; ?>, 'Cancelled')">
                                                Cancel
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== SERVICE REQUESTS MODALS ==================== -->

<!-- Service Requests Modal -->
<div class="modal fade" id="requestsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-clipboard-list"></i> Service Requests
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Create Request Form -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">New Service Request</h6>
                    </div>
                    <div class="card-body">
                        <form action="enhanced_admin_actions.php" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Resident</label>
                                        <select name="request_resident_id" class="form-control" required>
                                            <option value="">Select Resident</option>
                                            <?php 
                                            $residents = $userObj->runQuery("SELECT resident_id, first_name, last_name FROM resident_info ORDER BY last_name ASC");
                                            $residents->execute();
                                            while($resident = $residents->fetch(PDO::FETCH_ASSOC)): ?>
                                                <option value="<?php echo $resident['resident_id']; ?>">
                                                    <?php echo htmlspecialchars($resident['first_name'] . ' ' . $resident['last_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Request Type</label>
                                        <select name="request_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="Infrastructure">Infrastructure</option>
                                            <option value="Health">Health Services</option>
                                            <option value="Education">Education</option>
                                            <option value="Social Services">Social Services</option>
                                            <option value="Peace and Order">Peace and Order</option>
                                            <option value="Environment">Environmental</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Priority</label>
                                        <select name="request_priority" class="form-control" required>
                                            <option value="Low">Low</option>
                                            <option value="Medium" selected>Medium</option>
                                            <option value="High">High</option>
                                            <option value="Urgent">Urgent</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Request Title</label>
                                <input type="text" name="request_title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="request_description" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="btn_create_request" class="btn btn-warning">
                                <i class="fas fa-plus"></i> Create Request
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Requests List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Pending Requests</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Resident</th>
                                        <th>Type</th>
                                        <th>Priority</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $requests = $userObj->getAllServiceRequests('Pending');
                                    foreach($requests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['request_title']); ?></td>
                                        <td><?php echo htmlspecialchars($request['first_name'] . ' ' . $request['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($request['request_type']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $request['priority'] == 'Urgent' ? 'danger' : ($request['priority'] == 'High' ? 'warning' : 'info'); ?>">
                                                <?php echo htmlspecialchars($request['priority']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($request['created_at'])); ?></td>
                                        <td>
                                            <span class="badge badge-secondary"><?php echo htmlspecialchars($request['status']); ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="assignRequest(<?php echo $request['request_id']; ?>)">
                                                Assign
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== INVENTORY MODALS ==================== -->

<!-- Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-warehouse"></i> Inventory Management
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Add Item Form -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Add New Item</h6>
                    </div>
                    <div class="card-body">
                        <form action="enhanced_admin_actions.php" method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Item Name *</label>
                                        <input type="text" name="item_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Category *</label>
                                        <select name="item_category" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="Office Supplies">Office Supplies</option>
                                            <option value="Medical Supplies">Medical Supplies</option>
                                            <option value="Cleaning Supplies">Cleaning Supplies</option>
                                            <option value="Equipment">Equipment</option>
                                            <option value="Food">Food Supplies</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Quantity *</label>
                                        <input type="number" name="item_quantity" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <label>Unit *</label>
                                        <input type="text" name="item_unit" class="form-control" value="pieces" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Unit Cost *</label>
                                        <input type="number" step="0.01" name="item_unit_cost" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Reorder Level</label>
                                        <input type="number" name="reorder_level" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <input type="text" name="item_description" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Supplier</label>
                                        <input type="text" name="supplier" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btn_add_inventory" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add Item
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Inventory List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Current Inventory</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Unit Cost</th>
                                        <th>Total Value</th>
                                        <th>Reorder Level</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $inventory = $userObj->getAllInventory();
                                    foreach($inventory as $item): ?>
                                    <tr class="<?php echo $item['quantity'] <= $item['reorder_level'] ? 'table-warning' : ''; ?>">
                                        <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                        <td><?php echo htmlspecialchars($item['item_category']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td>₱<?php echo number_format($item['unit_cost'], 2); ?></td>
                                        <td>₱<?php echo number_format($item['total_value'], 2); ?></td>
                                        <td><?php echo $item['reorder_level']; ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $item['status'] == 'Available' ? 'success' : 'danger'; ?>">
                                                <?php echo htmlspecialchars($item['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="editInventory(<?php echo $item['item_id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== FINANCIAL MODALS ==================== -->

<!-- Financial Modal -->
<div class="modal fade" id="financialModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill-wave"></i> Financial Management
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Add Financial Record Form -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Add Financial Record</h6>
                    </div>
                    <div class="card-body">
                        <form action="enhanced_admin_actions.php" method="post">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Type *</label>
                                        <select name="transaction_type" class="form-control" required onchange="toggleFinancialFields(this.value)">
                                            <option value="">Select</option>
                                            <option value="Income">Income</option>
                                            <option value="Expense">Expense</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Category *</label>
                                        <select name="financial_category" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="Taxes">Taxes</option>
                                            <option value="Fees">Fees</option>
                                            <option value="Donations">Donations</option>
                                            <option value="Supplies">Supplies</option>
                                            <option value="Salaries">Salaries</option>
                                            <option value="Utilities">Utilities</option>
                                            <option value="Maintenance">Maintenance</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Description *</label>
                                        <input type="text" name="financial_description" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Amount *</label>
                                        <input type="number" step="0.01" name="financial_amount" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Date *</label>
                                        <input type="date" name="transaction_date" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select name="payment_method" class="form-control">
                                            <option value="Cash">Cash</option>
                                            <option value="Check">Check</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 income-field" style="display:none;">
                                    <div class="form-group">
                                        <label>Received From</label>
                                        <input type="text" name="received_from" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2 expense-field" style="display:none;">
                                    <div class="form-group">
                                        <label>Paid To</label>
                                        <input type="text" name="paid_to" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Reference #</label>
                                        <input type="text" name="reference_number" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btn_add_financial" class="btn btn-info">
                                <i class="fas fa-plus"></i> Add Record
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Financial Records List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Recent Transactions</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $financial = $userObj->getFinancialRecords();
                                    foreach($financial as $record): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($record['transaction_date'])); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $record['transaction_type'] == 'Income' ? 'success' : 'danger'; ?>">
                                                <?php echo htmlspecialchars($record['transaction_type']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($record['category']); ?></td>
                                        <td><?php echo htmlspecialchars($record['description']); ?></td>
                                        <td>₱<?php echo number_format($record['amount'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($record['payment_method']); ?></td>
                                        <td><?php echo htmlspecialchars($record['reference_number'] ?? ''); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== PROJECTS MODALS ==================== -->

<!-- Projects Modal -->
<div class="modal fade" id="projectsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-project-diagram"></i> Project Management
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Create Project Form -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">New Project</h6>
                    </div>
                    <div class="card-body">
                        <form action="enhanced_admin_actions.php" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Project Name *</label>
                                        <input type="text" name="project_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Project Type *</label>
                                        <select name="project_type" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="Infrastructure">Infrastructure</option>
                                            <option value="Social">Social</option>
                                            <option value="Health">Health</option>
                                            <option value="Education">Education</option>
                                            <option value="Environment">Environmental</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Budget *</label>
                                        <input type="number" step="0.01" name="project_budget" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="project_description" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Funding Source</label>
                                        <input type="text" name="funding_source" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" name="btn_create_project" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Project
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Projects List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">All Projects</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Type</th>
                                        <th>Budget</th>
                                        <th>Actual Cost</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $projects = $userObj->getAllProjects();
                                    foreach($projects as $project): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($project['project_name']); ?></td>
                                        <td><?php echo htmlspecialchars($project['project_type']); ?></td>
                                        <td>₱<?php echo number_format($project['budget'], 2); ?></td>
                                        <td>₱<?php echo number_format($project['actual_cost'], 2); ?></td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" style="width: <?php echo $project['progress_percentage']; ?>%">
                                                    <?php echo $project['progress_percentage']; ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?php echo $project['status'] == 'Completed' ? 'success' : ($project['status'] == 'Ongoing' ? 'primary' : 'secondary'); ?>">
                                                <?php echo htmlspecialchars($project['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="editProject(<?php echo $project['project_id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== COMPLAINTS MODALS ==================== -->

<!-- Complaints Modal -->
<div class="modal fade" id="complaintsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i> Complaint Management
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Create Complaint Form -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">New Complaint</h6>
                    </div>
                    <div class="card-body">
                        <form action="enhanced_admin_actions.php" method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Complainant</label>
                                        <select name="complainant_id" class="form-control">
                                            <option value="">Select Resident</option>
                                            <?php 
                                            $residents = $userObj->runQuery("SELECT resident_id, first_name, last_name FROM resident_info ORDER BY last_name ASC");
                                            $residents->execute();
                                            while($resident = $residents->fetch(PDO::FETCH_ASSOC)): ?>
                                                <option value="<?php echo $resident['resident_id']; ?>">
                                                    <?php echo htmlspecialchars($resident['first_name'] . ' ' . $resident['last_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Complaint Type *</label>
                                        <select name="complaint_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="Noise">Noise Complaint</option>
                                            <option value="Sanitation">Sanitation</option>
                                            <option value="Security">Security Issue</option>
                                            <option value="Infrastructure">Infrastructure</option>
                                            <option value="Service">Service Complaint</option>
                                            <option value="Harassment">Harassment</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Severity *</label>
                                        <select name="severity" class="form-control" required>
                                            <option value="Low">Low</option>
                                            <option value="Medium" selected>Medium</option>
                                            <option value="High">High</option>
                                            <option value="Critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" name="complainant_contact" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Complaint Title *</label>
                                <input type="text" name="complaint_title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description *</label>
                                <textarea name="complaint_description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="anonymous" class="form-check-input">
                                <label class="form-check-label">Anonymous Complaint</label>
                            </div>
                            <button type="submit" name="btn_create_complaint" class="btn btn-danger">
                                <i class="fas fa-plus"></i> Submit Complaint
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Complaints List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Pending Complaints</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Severity</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $complaints = $userObj->getAllComplaints('Pending');
                                    foreach($complaints as $complaint): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($complaint['complaint_title']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['complaint_type']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $complaint['severity'] == 'Critical' ? 'danger' : ($complaint['severity'] == 'High' ? 'warning' : 'info'); ?>">
                                                <?php echo htmlspecialchars($complaint['severity']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($complaint['created_at'])); ?></td>
                                        <td>
                                            <span class="badge badge-secondary"><?php echo htmlspecialchars($complaint['status']); ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" onclick="assignComplaint(<?php echo $complaint['complaint_id']; ?>)">
                                                Assign
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== MEETINGS MODALS ==================== -->

<!-- Meetings Modal -->
<div class="modal fade" id="meetingsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="fas fa-users"></i> Meeting Management
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Create Meeting Form -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Schedule Meeting</h6>
                    </div>
                    <div class="card-body">
                        <form action="enhanced_admin_actions.php" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Meeting Title *</label>
                                        <input type="text" name="meeting_title" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Type *</label>
                                        <select name="meeting_type" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="Regular">Regular</option>
                                            <option value="Special">Special</option>
                                            <option value="Emergency">Emergency</option>
                                            <option value="Committee">Committee</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Date *</label>
                                        <input type="date" name="meeting_date" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Time *</label>
                                        <input type="time" name="meeting_time" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Venue</label>
                                        <input type="text" name="meeting_venue" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Agenda</label>
                                <textarea name="meeting_agenda" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" name="btn_create_meeting" class="btn btn-dark">
                                <i class="fas fa-plus"></i> Schedule Meeting
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Meetings List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Upcoming Meetings</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $meetings = $userObj->getAllMeetings('Scheduled');
                                    foreach($meetings as $meeting): ?>
                                    <tr>
                                        <td><?php echo date('M d, Y', strtotime($meeting['meeting_date'])); ?></td>
                                        <td><?php echo date('h:i A', strtotime($meeting['meeting_time'])); ?></td>
                                        <td><?php echo htmlspecialchars($meeting['meeting_title']); ?></td>
                                        <td><?php echo htmlspecialchars($meeting['meeting_type']); ?></td>
                                        <td><?php echo htmlspecialchars($meeting['venue'] ?? 'TBD'); ?></td>
                                        <td>
                                            <span class="badge badge-info"><?php echo htmlspecialchars($meeting['status']); ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success" onclick="updateMeetingStatus(<?php echo $meeting['meeting_id']; ?>, 'Completed')">
                                                Complete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== NOTIFICATIONS MODALS ==================== -->

<!-- Notifications Modal -->
<div class="modal fade" id="notificationsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-bell"></i> Notification Management
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Create Notification Form -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">Create Notification</h6>
                    </div>
                    <div class="card-body">
                        <form action="enhanced_admin_actions.php" method="post">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Title *</label>
                                        <input type="text" name="notification_title" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Type *</label>
                                        <select name="notification_type" class="form-control" required>
                                            <option value="General">General</option>
                                            <option value="Urgent">Urgent</option>
                                            <option value="Reminder">Reminder</option>
                                            <option value="Alert">Alert</option>
                                            <option value="Information">Information</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Target Audience</label>
                                        <select name="target_audience" class="form-control">
                                            <option value="All">All</option>
                                            <option value="Residents">Residents</option>
                                            <option value="Officials">Officials</option>
                                            <option value="Staff">Staff</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Message *</label>
                                <textarea name="notification_message" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" name="btn_create_notification" class="btn btn-warning">
                                <i class="fas fa-bullhorn"></i> Create Notification
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Active Notifications List -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Active Notifications</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Type</th>
                                        <th>Audience</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($activeNotifications as $notification): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($notification['title']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($notification['message'], 0, 100)) . '...'; ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $notification['type'] == 'Urgent' ? 'danger' : ($notification['type'] == 'Alert' ? 'warning' : 'info'); ?>">
                                                <?php echo htmlspecialchars($notification['type']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars($notification['target_audience']); ?></td>
                                        <td><?php echo $notification['start_date'] ? date('M d, Y', strtotime($notification['start_date'])) : 'Immediate'; ?></td>
                                        <td><?php echo $notification['end_date'] ? date('M d, Y', strtotime($notification['end_date'])) : 'No End'; ?></td>
                                        <td>
                                            <span class="badge badge-success">Active</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== SETTINGS MODAL ==================== -->

<!-- Settings Modal -->
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-cog"></i> Barangay Settings
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="enhanced_admin_actions.php" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Basic Information</h6>
                            <div class="form-group">
                                <label>Barangay Name</label>
                                <input type="text" name="barangay_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($barangaySettings['barangay_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Barangay Address</label>
                                <input type="text" name="barangay_address" class="form-control" 
                                       value="<?php echo htmlspecialchars($barangaySettings['barangay_address'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Contact Number</label>
                                <input type="text" name="barangay_contact" class="form-control" 
                                       value="<?php echo htmlspecialchars($barangaySettings['barangay_contact'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" name="barangay_email" class="form-control" 
                                       value="<?php echo htmlspecialchars($barangaySettings['barangay_email'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Leadership Information</h6>
                            <div class="form-group">
                                <label>Captain Name</label>
                                <input type="text" name="captain_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($barangaySettings['captain_name'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Term Start</label>
                                <input type="date" name="term_start" class="form-control" 
                                       value="<?php echo htmlspecialchars($barangaySettings['term_start'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label>Term End</label>
                                <input type="date" name="term_end" class="form-control" 
                                       value="<?php echo htmlspecialchars($barangaySettings['term_end'] ?? ''); ?>">
                            </div>
                            <h6>Demographics</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Population</label>
                                        <input type="number" name="population_count" class="form-control" 
                                               value="<?php echo htmlspecialchars($barangaySettings['population_count'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Households</label>
                                        <input type="number" name="household_count" class="form-control" 
                                               value="<?php echo htmlspecialchars($barangaySettings['household_count'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="btn_update_settings" class="btn btn-primary">Update Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript Functions -->
<script>
function toggleFinancialFields(type) {
    $('.income-field').toggle(type === 'Income');
    $('.expense-field').toggle(type === 'Expense');
}

function updateAppointmentStatus(appointmentId, status) {
    if(confirm('Are you sure you want to ' + status.toLowerCase() + ' this appointment?')) {
        $.ajax({
            url: 'enhanced_admin_actions.php',
            method: 'POST',
            data: {
                btn_update_appointment_status: 1,
                appointment_id: appointmentId,
                appointment_status: status
            },
            success: function(response) {
                location.reload();
            }
        });
    }
}

function assignRequest(requestId) {
    // Load request data and show assignment modal
    $.ajax({
        url: 'enhanced_admin_actions.php',
        method: 'POST',
        data: { load_request: requestId },
        success: function(response) {
            var data = JSON.parse(response);
            // Show assignment modal with data
            $('#assignRequestModal').modal('show');
            // Populate modal fields
        }
    });
}

function editInventory(itemId) {
    $.ajax({
        url: 'enhanced_admin_actions.php',
        method: 'POST',
        data: { load_inventory: itemId },
        success: function(response) {
            var data = JSON.parse(response);
            // Show edit modal with data
            $('#editInventoryModal').modal('show');
            // Populate modal fields
        }
    });
}

function editProject(projectId) {
    $.ajax({
        url: 'enhanced_admin_actions.php',
        method: 'POST',
        data: { load_project: projectId },
        success: function(response) {
            var data = JSON.parse(response);
            // Show edit modal with data
            $('#editProjectModal').modal('show');
            // Populate modal fields
        }
    });
}

function assignComplaint(complaintId) {
    $.ajax({
        url: 'enhanced_admin_actions.php',
        method: 'POST',
        data: { load_complaint: complaintId },
        success: function(response) {
            var data = JSON.parse(response);
            // Show assignment modal with data
            $('#assignComplaintModal').modal('show');
            // Populate modal fields
        }
    });
}

function updateMeetingStatus(meetingId, status) {
    if(confirm('Are you sure you want to mark this meeting as ' + status.toLowerCase() + '?')) {
        $.ajax({
            url: 'enhanced_admin_actions.php',
            method: 'POST',
            data: {
                btn_update_meeting: 1,
                meeting_id: meetingId,
                meeting_status: status
            },
            success: function(response) {
                location.reload();
            }
        });
    }
}

function editStaff(staffId) {
    // Load staff data and show edit modal
    // Implementation similar to other edit functions
}

$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-refresh dashboard stats every 5 minutes
    setInterval(function() {
        // Add AJAX call to refresh stats if needed
    }, 300000);
});
</script>
