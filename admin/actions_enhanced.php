<?php

require_once ('dbConfig.php');
require_once ('functions.php');

$userObj = new User();

// ==================== CAPTAIN LOGIN DETECTION ====================

//Ajax Call for Login with captain detection
if(isset($_POST['current_username']) && isset($_POST['current_password']))
{
    session_start();

    $username = $_POST['current_username'];
    $password = $_POST['current_password'];

    $admin_query = "SELECT * FROM official_info WHERE official_username = '$username' and official_password = '$password' ";
    $admin_stmt = $userObj->runQuery($admin_query);
    $admin_result = $admin_stmt->execute();
    
    $user_query = "SELECT * FROM resident_info WHERE username = '$username' and password = '$password' ";
    $user_stmt = $userObj->runQuery($user_query);
    $user_result = $user_stmt->execute();

    if($admin_result and $user_result)
    {
        if ($admin_stmt->rowCount() == 1)
        {
            $user = $admin_stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['session_login'] = $user;
            
            // Check if this is the captain
            if($userObj->isCaptain($username)) {
                // Log captain login
                $userObj->logActivity('admin', $user['official_id'], $username, 'CAPTAIN_LOGIN', 'Barangay Captain logged in');
                echo '2'; // Captain login
            } else {
                // Log official login
                $userObj->logActivity('admin', $user['official_id'], $username, 'OFFICIAL_LOGIN', 'Barangay Official logged in');
                echo '0'; // Official login
            }
        }

        elseif ($user_stmt->rowCount() == 1)
        {
            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['session_login'] = $user;
            
            // Log resident login
            $userObj->logActivity('resident', $user['resident_id'], $username, 'RESIDENT_LOGIN', 'Resident logged in');
            echo '1'; // Resident login
        }

        else
        {
            echo 'Account Not Found';
        }
        
    }

    else
    {
        echo 'Error';
    }
}

// ==================== STAFF MANAGEMENT ====================

// Create Staff Account (Captain only)
if(isset($_POST['btn_create_staff']))
{
    $username = $_POST['staff_username'];
    $password = $_POST['staff_password'];
    $first_name = $_POST['staff_first_name'];
    $middle_name = $_POST['staff_middle_name'];
    $last_name = $_POST['staff_last_name'];
    $sex = $_POST['staff_sex'];
    $contact_info = $_POST['staff_contact'];
    $position_id = $_POST['staff_position'];
    $date_hired = $_POST['staff_date_hired'] ?? null;
    $salary_grade = $_POST['staff_salary_grade'] ?? null;

    if($userObj->createStaffAccount($username, $password, $first_name, $middle_name, $last_name, $sex, $contact_info, $position_id, $date_hired, $salary_grade))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'CREATE_STAFF', "Created staff account: $username");
        $userObj->redirect('admin_Home.php?staffCreated');
    }
    else
    {
        echo "Error creating staff account";
    }
}

// Update Staff Account
if(isset($_POST['btn_update_staff']))
{
    $official_id = $_POST['edit_staff_id'];
    $first_name = $_POST['edit_staff_first_name'];
    $middle_name = $_POST['edit_staff_middle_name'];
    $last_name = $_POST['edit_staff_last_name'];
    $sex = $_POST['edit_staff_sex'];
    $contact_info = $_POST['edit_staff_contact'];
    $position_id = $_POST['edit_staff_position'];
    $salary_grade = $_POST['edit_staff_salary_grade'] ?? null;
    $employment_status = $_POST['edit_staff_employment_status'] ?? 'Active';
    
    try {
        $stmt = $userObj->runQuery("
            UPDATE official_info SET 
                official_first_name = :first_name, 
                official_middle_name = :middle_name, 
                official_last_name = :last_name, 
                official_sex = :sex, 
                official_contact_info = :contact_info, 
                staff_position_id = :position_id,
                salary_grade = :salary_grade,
                employment_status = :employment_status
            WHERE official_id = :official_id
        ");
        
        $stmt->bindParam(":official_id", $official_id);
        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":middle_name", $middle_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":sex", $sex);
        $stmt->bindParam(":contact_info", $contact_info);
        $stmt->bindParam(":position_id", $position_id);
        $stmt->bindParam(":salary_grade", $salary_grade);
        $stmt->bindParam(":employment_status", $employment_status);
        
        $stmt->execute();
        
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'UPDATE_STAFF', "Updated staff ID: $official_id");
        $userObj->redirect('admin_Home.php?staffUpdated');
        
    } catch (PDOException $e) {
        echo "Error updating staff: " . $e->getMessage();
    }
}

// ==================== APPOINTMENTS ====================

// Create Appointment
if(isset($_POST['btn_create_appointment']))
{
    $resident_id = $_POST['appointment_resident_id'];
    $official_id = $_POST['appointment_official_id'];
    $appointment_type = $_POST['appointment_type'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $purpose = $_POST['appointment_purpose'];

    if($userObj->createAppointment($resident_id, $official_id, $appointment_type, $appointment_date, $appointment_time, $purpose))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'CREATE_APPOINTMENT', "Created appointment for resident ID: $resident_id");
        $userObj->redirect('admin_Home.php?appointmentCreated');
    }
    else
    {
        echo "Error creating appointment";
    }
}

// Update Appointment Status
if(isset($_POST['btn_update_appointment_status']))
{
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['appointment_status'];
    $notes = $_POST['appointment_notes'] ?? null;
    
    try {
        $stmt = $userObj->runQuery("
            UPDATE appointments SET 
                status = :status,
                notes = :notes,
                updated_at = NOW()
            WHERE appointment_id = :appointment_id
        ");
        
        $stmt->bindParam(":appointment_id", $appointment_id);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":notes", $notes);
        
        $stmt->execute();
        
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'UPDATE_APPOINTMENT', "Updated appointment ID: $appointment_id to status: $status");
        $userObj->redirect('admin_Home.php?appointmentUpdated');
        
    } catch (PDOException $e) {
        echo "Error updating appointment: " . $e->getMessage();
    }
}

// ==================== SERVICE REQUESTS ====================

// Create Service Request
if(isset($_POST['btn_create_request']))
{
    $resident_id = $_POST['request_resident_id'];
    $request_type = $_POST['request_type'];
    $request_title = $_POST['request_title'];
    $request_description = $_POST['request_description'];
    $priority = $_POST['request_priority'];

    if($userObj->createServiceRequest($resident_id, $request_type, $request_title, $request_description, $priority))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'CREATE_REQUEST', "Created service request: $request_title");
        $userObj->redirect('admin_Home.php?requestCreated');
    }
    else
    {
        echo "Error creating service request";
    }
}

// Update Service Request
if(isset($_POST['btn_update_request']))
{
    $request_id = $_POST['request_id'];
    $status = $_POST['request_status'];
    $assigned_official_id = $_POST['assigned_official_id'] ?? null;
    $resolution_notes = $_POST['resolution_notes'] ?? null;
    
    try {
        $stmt = $userObj->runQuery("
            UPDATE service_requests SET 
                status = :status,
                assigned_official_id = :assigned_official_id,
                resolution_notes = :resolution_notes,
                updated_at = NOW()
            WHERE request_id = :request_id
        ");
        
        $stmt->bindParam(":request_id", $request_id);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":assigned_official_id", $assigned_official_id);
        $stmt->bindParam(":resolution_notes", $resolution_notes);
        
        $stmt->execute();
        
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'UPDATE_REQUEST', "Updated request ID: $request_id to status: $status");
        $userObj->redirect('admin_Home.php?requestUpdated');
        
    } catch (PDOException $e) {
        echo "Error updating request: " . $e->getMessage();
    }
}

// ==================== INVENTORY MANAGEMENT ====================

// Add Inventory Item
if(isset($_POST['btn_add_inventory']))
{
    $item_name = $_POST['item_name'];
    $category = $_POST['item_category'];
    $description = $_POST['item_description'];
    $quantity = $_POST['item_quantity'];
    $unit = $_POST['item_unit'];
    $unit_cost = $_POST['item_unit_cost'];
    $reorder_level = $_POST['reorder_level'];
    $supplier = $_POST['supplier'] ?? null;

    if($userObj->addInventoryItem($item_name, $category, $description, $quantity, $unit, $unit_cost, $reorder_level, $supplier))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'ADD_INVENTORY', "Added inventory item: $item_name");
        $userObj->redirect('admin_Home.php?inventoryAdded');
    }
    else
    {
        echo "Error adding inventory item";
    }
}

// Update Inventory Item
if(isset($_POST['btn_update_inventory']))
{
    $item_id = $_POST['inventory_item_id'];
    $quantity = $_POST['edit_quantity'];
    $unit_cost = $_POST['edit_unit_cost'];
    $status = $_POST['edit_status'];
    
    try {
        $total_value = $quantity * $unit_cost;
        $stmt = $userObj->runQuery("
            UPDATE inventory SET 
                quantity = :quantity,
                unit_cost = :unit_cost,
                total_value = :total_value,
                status = :status,
                updated_at = NOW()
            WHERE item_id = :item_id
        ");
        
        $stmt->bindParam(":item_id", $item_id);
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":unit_cost", $unit_cost);
        $stmt->bindParam(":total_value", $total_value);
        $stmt->bindParam(":status", $status);
        
        $stmt->execute();
        
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'UPDATE_INVENTORY', "Updated inventory item ID: $item_id");
        $userObj->redirect('admin_Home.php?inventoryUpdated');
        
    } catch (PDOException $e) {
        echo "Error updating inventory: " . $e->getMessage();
    }
}

// ==================== MEETINGS ====================

// Create Meeting
if(isset($_POST['btn_create_meeting']))
{
    $title = $_POST['meeting_title'];
    $type = $_POST['meeting_type'];
    $date = $_POST['meeting_date'];
    $time = $_POST['meeting_time'];
    $venue = $_POST['meeting_venue'] ?? null;
    $agenda = $_POST['meeting_agenda'] ?? null;

    if($userObj->createMeeting($title, $type, $date, $time, $venue, $agenda))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'CREATE_MEETING', "Created meeting: $title");
        $userObj->redirect('admin_Home.php?meetingCreated');
    }
    else
    {
        echo "Error creating meeting";
    }
}

// Update Meeting
if(isset($_POST['btn_update_meeting']))
{
    $meeting_id = $_POST['meeting_id'];
    $status = $_POST['meeting_status'];
    $minutes = $_POST['meeting_minutes'] ?? null;
    $attendees = $_POST['meeting_attendees'] ?? null;
    $absentees = $_POST['meeting_absentees'] ?? null;
    
    try {
        $stmt = $userObj->runQuery("
            UPDATE meetings SET 
                status = :status,
                minutes = :minutes,
                attendees = :attendees,
                absentees = :absentees,
                updated_at = NOW()
            WHERE meeting_id = :meeting_id
        ");
        
        $stmt->bindParam(":meeting_id", $meeting_id);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":minutes", $minutes);
        $stmt->bindParam(":attendees", $attendees);
        $stmt->bindParam(":absentees", $absentees);
        
        $stmt->execute();
        
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'UPDATE_MEETING', "Updated meeting ID: $meeting_id");
        $userObj->redirect('admin_Home.php?meetingUpdated');
        
    } catch (PDOException $e) {
        echo "Error updating meeting: " . $e->getMessage();
    }
}

// ==================== FINANCIAL MANAGEMENT ====================

// Add Financial Record
if(isset($_POST['btn_add_financial']))
{
    $transaction_type = $_POST['transaction_type'];
    $category = $_POST['financial_category'];
    $description = $_POST['financial_description'];
    $amount = $_POST['financial_amount'];
    $transaction_date = $_POST['transaction_date'];
    $payment_method = $_POST['payment_method'];
    $reference_number = $_POST['reference_number'] ?? null;
    $received_from = $_POST['received_from'] ?? null;
    $paid_to = $_POST['paid_to'] ?? null;

    if($userObj->addFinancialRecord($transaction_type, $category, $description, $amount, $transaction_date, $payment_method, $reference_number, $received_from, $paid_to))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'ADD_FINANCIAL', "Added $transaction_type: $description");
        $userObj->redirect('admin_Home.php?financialAdded');
    }
    else
    {
        echo "Error adding financial record";
    }
}

// ==================== PROJECTS ====================

// Create Project
if(isset($_POST['btn_create_project']))
{
    $project_name = $_POST['project_name'];
    $project_description = $_POST['project_description'];
    $project_type = $_POST['project_type'];
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $budget = $_POST['project_budget'];
    $funding_source = $_POST['funding_source'] ?? null;

    if($userObj->createProject($project_name, $project_description, $project_type, $start_date, $end_date, $budget, $funding_source))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'CREATE_PROJECT', "Created project: $project_name");
        $userObj->redirect('admin_Home.php?projectCreated');
    }
    else
    {
        echo "Error creating project";
    }
}

// Update Project
if(isset($_POST['btn_update_project']))
{
    $project_id = $_POST['project_id'];
    $status = $_POST['project_status'];
    $progress_percentage = $_POST['progress_percentage'];
    $actual_cost = $_POST['actual_cost'] ?? 0;
    
    try {
        $stmt = $userObj->runQuery("
            UPDATE projects SET 
                status = :status,
                progress_percentage = :progress_percentage,
                actual_cost = :actual_cost,
                updated_at = NOW()
            WHERE project_id = :project_id
        ");
        
        $stmt->bindParam(":project_id", $project_id);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":progress_percentage", $progress_percentage);
        $stmt->bindParam(":actual_cost", $actual_cost);
        
        $stmt->execute();
        
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'UPDATE_PROJECT', "Updated project ID: $project_id");
        $userObj->redirect('admin_Home.php?projectUpdated');
        
    } catch (PDOException $e) {
        echo "Error updating project: " . $e->getMessage();
    }
}

// ==================== COMPLAINTS ====================

// Create Complaint
if(isset($_POST['btn_create_complaint']))
{
    $complainant_id = $_POST['complainant_id'] ?? null;
    $complainant_name = $_POST['complainant_name'] ?? null;
    $complainant_contact = $_POST['complainant_contact'] ?? null;
    $complaint_type = $_POST['complaint_type'];
    $complaint_title = $_POST['complaint_title'];
    $complaint_description = $_POST['complaint_description'];
    $severity = $_POST['severity'];
    $anonymous = isset($_POST['anonymous']) ? 1 : 0;

    if($userObj->createComplaint($complainant_id, $complainant_name, $complainant_contact, $complaint_type, $complaint_title, $complaint_description, $severity, $anonymous))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'CREATE_COMPLAINT', "Created complaint: $complaint_title");
        $userObj->redirect('admin_Home.php?complaintCreated');
    }
    else
    {
        echo "Error creating complaint";
    }
}

// Update Complaint
if(isset($_POST['btn_update_complaint']))
{
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['complaint_status'];
    $assigned_to = $_POST['assigned_to'] ?? null;
    $resolution_details = $_POST['resolution_details'] ?? null;
    
    try {
        $date_resolved = ($status == 'Resolved') ? date('Y-m-d') : null;
        
        $stmt = $userObj->runQuery("
            UPDATE complaints SET 
                status = :status,
                assigned_to = :assigned_to,
                resolution_details = :resolution_details,
                date_resolved = :date_resolved,
                updated_at = NOW()
            WHERE complaint_id = :complaint_id
        ");
        
        $stmt->bindParam(":complaint_id", $complaint_id);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":assigned_to", $assigned_to);
        $stmt->bindParam(":resolution_details", $resolution_details);
        $stmt->bindParam(":date_resolved", $date_resolved);
        
        $stmt->execute();
        
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'UPDATE_COMPLAINT', "Updated complaint ID: $complaint_id");
        $userObj->redirect('admin_Home.php?complaintUpdated');
        
    } catch (PDOException $e) {
        echo "Error updating complaint: " . $e->getMessage();
    }
}

// ==================== NOTIFICATIONS ====================

// Create Notification
if(isset($_POST['btn_create_notification']))
{
    $title = $_POST['notification_title'];
    $message = $_POST['notification_message'];
    $type = $_POST['notification_type'];
    $target_audience = $_POST['target_audience'];
    $target_ids = $_POST['target_ids'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    if($userObj->createNotification($title, $message, $type, $target_audience, $target_ids, $start_date, $end_date))
    {
        // Log activity
        $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'CREATE_NOTIFICATION', "Created notification: $title");
        $userObj->redirect('admin_Home.php?notificationCreated');
    }
    else
    {
        echo "Error creating notification";
    }
}

// ==================== BARANGAY SETTINGS ====================

// Update Barangay Settings (Captain only)
if(isset($_POST['btn_update_settings']))
{
    $barangay_name = $_POST['barangay_name'];
    $barangay_address = $_POST['barangay_address'];
    $barangay_contact = $_POST['barangay_contact'];
    $barangay_email = $_POST['barangay_email'];
    $captain_name = $_POST['captain_name'];
    $term_start = $_POST['term_start'];
    $term_end = $_POST['term_end'];
    
    $userObj->updateBarangaySetting('barangay_name', $barangay_name);
    $userObj->updateBarangaySetting('barangay_address', $barangay_address);
    $userObj->updateBarangaySetting('barangay_contact', $barangay_contact);
    $userObj->updateBarangaySetting('barangay_email', $barangay_email);
    $userObj->updateBarangaySetting('captain_name', $captain_name);
    $userObj->updateBarangaySetting('term_start', $term_start);
    $userObj->updateBarangaySetting('term_end', $term_end);
    
    // Log activity
    $userObj->logActivity('admin', $_SESSION['session_login']['official_id'], $_SESSION['session_login']['official_username'], 'UPDATE_SETTINGS', 'Updated barangay settings');
    
    $userObj->redirect('admin_Home.php?settingsUpdated');
}

// ==================== AJAX HANDLERS ====================

// Load appointment data for modal
if(isset($_POST['load_appointment']))
{
    $appointment_id = $_POST['load_appointment'];
    
    $stmt = $userObj->runQuery("
        SELECT a.*, r.first_name, r.last_name, r.contact_number as resident_contact 
        FROM appointments a 
        LEFT JOIN resident_info r ON a.resident_id = r.resident_id 
        WHERE a.appointment_id = :appointment_id
    ");
    $stmt->bindParam(":appointment_id", $appointment_id);
    $stmt->execute();
    
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($appointment) {
        echo json_encode($appointment);
    } else {
        echo json_encode(['error' => 'Appointment not found']);
    }
    exit;
}

// Load service request data for modal
if(isset($_POST['load_request']))
{
    $request_id = $_POST['load_request'];
    
    $stmt = $userObj->runQuery("
        SELECT sr.*, r.first_name, r.last_name, r.contact_number as resident_contact 
        FROM service_requests sr 
        LEFT JOIN resident_info r ON sr.resident_id = r.resident_id 
        WHERE sr.request_id = :request_id
    ");
    $stmt->bindParam(":request_id", $request_id);
    $stmt->execute();
    
    $request = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($request) {
        echo json_encode($request);
    } else {
        echo json_encode(['error' => 'Request not found']);
    }
    exit;
}

// Load inventory item data for modal
if(isset($_POST['load_inventory']))
{
    $item_id = $_POST['load_inventory'];
    
    $stmt = $userObj->runQuery("SELECT * FROM inventory WHERE item_id = :item_id");
    $stmt->bindParam(":item_id", $item_id);
    $stmt->execute();
    
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($item) {
        echo json_encode($item);
    } else {
        echo json_encode(['error' => 'Item not found']);
    }
    exit;
}

// Load meeting data for modal
if(isset($_POST['load_meeting']))
{
    $meeting_id = $_POST['load_meeting'];
    
    $stmt = $userObj->runQuery("SELECT * FROM meetings WHERE meeting_id = :meeting_id");
    $stmt->bindParam(":meeting_id", $meeting_id);
    $stmt->execute();
    
    $meeting = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($meeting) {
        echo json_encode($meeting);
    } else {
        echo json_encode(['error' => 'Meeting not found']);
    }
    exit;
}

// Load project data for modal
if(isset($_POST['load_project']))
{
    $project_id = $_POST['load_project'];
    
    $stmt = $userObj->runQuery("SELECT * FROM projects WHERE project_id = :project_id");
    $stmt->bindParam(":project_id", $project_id);
    $stmt->execute();
    
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($project) {
        echo json_encode($project);
    } else {
        echo json_encode(['error' => 'Project not found']);
    }
    exit;
}

// Load complaint data for modal
if(isset($_POST['load_complaint']))
{
    $complaint_id = $_POST['load_complaint'];
    
    $stmt = $userObj->runQuery("SELECT * FROM complaints WHERE complaint_id = :complaint_id");
    $stmt->bindParam(":complaint_id", $complaint_id);
    $stmt->execute();
    
    $complaint = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($complaint) {
        echo json_encode($complaint);
    } else {
        echo json_encode(['error' => 'Complaint not found']);
    }
    exit;
}

?>
