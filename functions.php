<?php

    require_once ('dbConfig.php');

    class User
    {
        private $conn;

        //Function to construct database
        public function __construct()
        {
            $database = new Database();
            $db = $database->dbConnection();
            $this->conn = $db;
        }
    
        //Run query that is passed on parameter
        public function runQuery($sql)
        {
            $stmt = $this->conn->prepare($sql);
            return $stmt;
        }

        //Redirect to url that is passed on parameter
        public function redirect($url)
        {
            header("Location: $url");
        }

        // ==================== CAPTAIN & USER MANAGEMENT ====================
        
        // Check if user is captain
        public function isCaptain($username)
        {
            try {
                $stmt = $this->conn->prepare("SELECT is_captain FROM official_info WHERE official_username = :username");
                $stmt->bindParam(":username", $username);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['is_captain'] == 1;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get captain information
        public function getCaptainInfo()
        {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM official_info WHERE is_captain = 1");
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Create staff account
        public function createStaffAccount($username, $password, $firstName, $middleName, $lastName, $sex, $contactInfo, $positionId, $dateHired = null, $salaryGrade = null)
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO official_info 
                    (official_username, official_password, official_first_name, official_middle_name, 
                     official_last_name, official_sex, official_contact_info, staff_position_id, 
                     created_by_captain, date_hired, salary_grade, employment_status) 
                    VALUES (:username, :password, :first_name, :middle_name, :last_name, :sex, :contact_info, 
                           :position_id, 1, :date_hired, :salary_grade, 'Active')
                ");
                
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $password);
                $stmt->bindParam(":first_name", $firstName);
                $stmt->bindParam(":middle_name", $middleName);
                $stmt->bindParam(":last_name", $lastName);
                $stmt->bindParam(":sex", $sex);
                $stmt->bindParam(":contact_info", $contactInfo);
                $stmt->bindParam(":position_id", $positionId);
                $stmt->bindParam(":date_hired", $dateHired);
                $stmt->bindParam(":salary_grade", $salaryGrade);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get all staff positions
        public function getStaffPositions()
        {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM staff_positions WHERE is_active = 1 ORDER BY position_level DESC");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get all officials with position info
        public function getAllOfficials()
        {
            try {
                $stmt = $this->conn->prepare("
                    SELECT oi.*, sp.position_name, sp.position_code 
                    FROM official_info oi 
                    LEFT JOIN staff_positions sp ON oi.staff_position_id = sp.position_id 
                    ORDER BY oi.is_captain DESC, sp.position_level DESC, oi.official_last_name ASC
                ");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== APPOINTMENT SYSTEM ====================
        
        // Create appointment
        public function createAppointment($residentId, $officialId, $appointmentType, $appointmentDate, $appointmentTime, $purpose = null)
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO appointments (resident_id, official_id, appointment_type, appointment_date, appointment_time, purpose) 
                    VALUES (:resident_id, :official_id, :appointment_type, :appointment_date, :appointment_time, :purpose)
                ");
                
                $stmt->bindParam(":resident_id", $residentId);
                $stmt->bindParam(":official_id", $officialId);
                $stmt->bindParam(":appointment_type", $appointmentType);
                $stmt->bindParam(":appointment_date", $appointmentDate);
                $stmt->bindParam(":appointment_time", $appointmentTime);
                $stmt->bindParam(":purpose", $purpose);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get appointments for official
        public function getOfficialAppointments($officialId, $status = null)
        {
            try {
                $sql = "
                    SELECT a.*, r.first_name, r.last_name, r.contact_number as resident_contact 
                    FROM appointments a 
                    LEFT JOIN resident_info r ON a.resident_id = r.resident_id 
                    WHERE a.official_id = :official_id
                ";
                
                if ($status) {
                    $sql .= " AND a.status = :status";
                }
                
                $sql .= " ORDER BY a.appointment_date ASC, a.appointment_time ASC";
                
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(":official_id", $officialId);
                
                if ($status) {
                    $stmt->bindParam(":status", $status);
                }
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== SERVICE REQUESTS ====================
        
        // Create service request
        public function createServiceRequest($residentId, $requestType, $requestTitle, $requestDescription, $priority = 'Medium')
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO service_requests (resident_id, request_type, request_title, request_description, priority) 
                    VALUES (:resident_id, :request_type, :request_title, :request_description, :priority)
                ");
                
                $stmt->bindParam(":resident_id", $residentId);
                $stmt->bindParam(":request_type", $requestType);
                $stmt->bindParam(":request_title", $requestTitle);
                $stmt->bindParam(":request_description", $requestDescription);
                $stmt->bindParam(":priority", $priority);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get all service requests
        public function getAllServiceRequests($status = null)
        {
            try {
                $sql = "
                    SELECT sr.*, r.first_name, r.last_name, r.contact_number as resident_contact,
                           oi.first_name as official_first_name, oi.last_name as official_last_name
                    FROM service_requests sr 
                    LEFT JOIN resident_info r ON sr.resident_id = r.resident_id 
                    LEFT JOIN official_info oi ON sr.assigned_official_id = oi.official_id
                ";
                
                if ($status) {
                    $sql .= " WHERE sr.status = :status";
                }
                
                $sql .= " ORDER BY sr.created_at DESC";
                
                $stmt = $this->conn->prepare($sql);
                
                if ($status) {
                    $stmt->bindParam(":status", $status);
                }
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== INVENTORY MANAGEMENT ====================
        
        // Add inventory item
        public function addInventoryItem($itemName, $category, $description, $quantity, $unit, $unitCost, $reorderLevel, $supplier = null)
        {
            try {
                $totalValue = $quantity * $unitCost;
                $stmt = $this->conn->prepare("
                    INSERT INTO inventory (item_name, item_category, description, quantity, unit, unit_cost, total_value, reorder_level, supplier, date_acquired) 
                    VALUES (:item_name, :category, :description, :quantity, :unit, :unit_cost, :total_value, :reorder_level, :supplier, CURDATE())
                ");
                
                $stmt->bindParam(":item_name", $itemName);
                $stmt->bindParam(":category", $category);
                $stmt->bindParam(":description", $description);
                $stmt->bindParam(":quantity", $quantity);
                $stmt->bindParam(":unit", $unit);
                $stmt->bindParam(":unit_cost", $unitCost);
                $stmt->bindParam(":total_value", $totalValue);
                $stmt->bindParam(":reorder_level", $reorderLevel);
                $stmt->bindParam(":supplier", $supplier);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get all inventory items
        public function getAllInventory($category = null)
        {
            try {
                $sql = "SELECT * FROM inventory";
                
                if ($category) {
                    $sql .= " WHERE item_category = :category";
                }
                
                $sql .= " ORDER BY item_name ASC";
                
                $stmt = $this->conn->prepare($sql);
                
                if ($category) {
                    $stmt->bindParam(":category", $category);
                }
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== MEETINGS MANAGEMENT ====================
        
        // Create meeting
        public function createMeeting($title, $type, $date, $time, $venue = null, $agenda = null)
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO meetings (meeting_title, meeting_type, meeting_date, meeting_time, venue, agenda, called_by) 
                    VALUES (:title, :type, :date, :time, :venue, :agenda, :called_by)
                ");
                
                $calledBy = $_SESSION['session_login']['official_id'] ?? null;
                
                $stmt->bindParam(":title", $title);
                $stmt->bindParam(":type", $type);
                $stmt->bindParam(":date", $date);
                $stmt->bindParam(":time", $time);
                $stmt->bindParam(":venue", $venue);
                $stmt->bindParam(":agenda", $agenda);
                $stmt->bindParam(":called_by", $calledBy);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get all meetings
        public function getAllMeetings($status = null)
        {
            try {
                $sql = "SELECT * FROM meetings";
                
                if ($status) {
                    $sql .= " WHERE status = :status";
                }
                
                $sql .= " ORDER BY meeting_date DESC, meeting_time DESC";
                
                $stmt = $this->conn->prepare($sql);
                
                if ($status) {
                    $stmt->bindParam(":status", $status);
                }
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== FINANCIAL MANAGEMENT ====================
        
        // Add financial record
        public function addFinancialRecord($transactionType, $category, $description, $amount, $transactionDate, $paymentMethod = 'Cash', $referenceNumber = null, $receivedFrom = null, $paidTo = null)
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO financial_records (transaction_type, category, description, amount, transaction_date, payment_method, reference_number, received_from, paid_to, created_by) 
                    VALUES (:transaction_type, :category, :description, :amount, :transaction_date, :payment_method, :reference_number, :received_from, :paid_to, :created_by)
                ");
                
                $createdBy = $_SESSION['session_login']['official_id'] ?? null;
                
                $stmt->bindParam(":transaction_type", $transactionType);
                $stmt->bindParam(":category", $category);
                $stmt->bindParam(":description", $description);
                $stmt->bindParam(":amount", $amount);
                $stmt->bindParam(":transaction_date", $transactionDate);
                $stmt->bindParam(":payment_method", $paymentMethod);
                $stmt->bindParam(":reference_number", $referenceNumber);
                $stmt->bindParam(":received_from", $receivedFrom);
                $stmt->bindParam(":paid_to", $paidTo);
                $stmt->bindParam(":created_by", $createdBy);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get financial records
        public function getFinancialRecords($transactionType = null, $startDate = null, $endDate = null)
        {
            try {
                $sql = "SELECT * FROM financial_records WHERE 1=1";
                
                if ($transactionType) {
                    $sql .= " AND transaction_type = :transaction_type";
                }
                
                if ($startDate) {
                    $sql .= " AND transaction_date >= :start_date";
                }
                
                if ($endDate) {
                    $sql .= " AND transaction_date <= :end_date";
                }
                
                $sql .= " ORDER BY transaction_date DESC, created_at DESC";
                
                $stmt = $this->conn->prepare($sql);
                
                if ($transactionType) {
                    $stmt->bindParam(":transaction_type", $transactionType);
                }
                
                if ($startDate) {
                    $stmt->bindParam(":start_date", $startDate);
                }
                
                if ($endDate) {
                    $stmt->bindParam(":end_date", $endDate);
                }
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== PROJECTS MANAGEMENT ====================
        
        // Create project
        public function createProject($projectName, $projectDescription, $projectType, $startDate = null, $endDate = null, $budget = 0, $fundingSource = null)
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO projects (project_name, project_description, project_type, start_date, end_date, budget, funding_source, created_by) 
                    VALUES (:project_name, :project_description, :project_type, :start_date, :end_date, :budget, :funding_source, :created_by)
                ");
                
                $createdBy = $_SESSION['session_login']['official_id'] ?? null;
                
                $stmt->bindParam(":project_name", $projectName);
                $stmt->bindParam(":project_description", $projectDescription);
                $stmt->bindParam(":project_type", $projectType);
                $stmt->bindParam(":start_date", $startDate);
                $stmt->bindParam(":end_date", $endDate);
                $stmt->bindParam(":budget", $budget);
                $stmt->bindParam(":funding_source", $fundingSource);
                $stmt->bindParam(":created_by", $createdBy);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get all projects
        public function getAllProjects($status = null)
        {
            try {
                $sql = "SELECT * FROM projects";
                
                if ($status) {
                    $sql .= " WHERE status = :status";
                }
                
                $sql .= " ORDER BY created_at DESC";
                
                $stmt = $this->conn->prepare($sql);
                
                if ($status) {
                    $stmt->bindParam(":status", $status);
                }
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== COMPLAINTS SYSTEM ====================
        
        // Create complaint
        public function createComplaint($complainantId, $complainantName, $complainantContact, $complaintType, $complaintTitle, $complaintDescription, $severity = 'Medium', $anonymous = 0)
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO complaints (complainant_id, complainant_name, complainant_contact, complaint_type, complaint_title, complaint_description, severity, anonymous) 
                    VALUES (:complainant_id, :complainant_name, :complainant_contact, :complaint_type, :complaint_title, :complaint_description, :severity, :anonymous)
                ");
                
                $stmt->bindParam(":complainant_id", $complainantId);
                $stmt->bindParam(":complainant_name", $complainantName);
                $stmt->bindParam(":complainant_contact", $complainantContact);
                $stmt->bindParam(":complaint_type", $complaintType);
                $stmt->bindParam(":complaint_title", $complaintTitle);
                $stmt->bindParam(":complaint_description", $complaintDescription);
                $stmt->bindParam(":severity", $severity);
                $stmt->bindParam(":anonymous", $anonymous);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get all complaints
        public function getAllComplaints($status = null)
        {
            try {
                $sql = "SELECT * FROM complaints";
                
                if ($status) {
                    $sql .= " WHERE status = :status";
                }
                
                $sql .= " ORDER BY created_at DESC";
                
                $stmt = $this->conn->prepare($sql);
                
                if ($status) {
                    $stmt->bindParam(":status", $status);
                }
                
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== NOTIFICATIONS ====================
        
        // Create notification
        public function createNotification($title, $message, $type = 'General', $targetAudience = 'All', $targetIds = null, $startDate = null, $endDate = null)
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO notifications (title, message, type, target_audience, target_ids, start_date, end_date, created_by) 
                    VALUES (:title, :message, :type, :target_audience, :target_ids, :start_date, :end_date, :created_by)
                ");
                
                $createdBy = $_SESSION['session_login']['official_id'] ?? null;
                
                $stmt->bindParam(":title", $title);
                $stmt->bindParam(":message", $message);
                $stmt->bindParam(":type", $type);
                $stmt->bindParam(":target_audience", $targetAudience);
                $stmt->bindParam(":target_ids", $targetIds);
                $stmt->bindParam(":start_date", $startDate);
                $stmt->bindParam(":end_date", $endDate);
                $stmt->bindParam(":created_by", $createdBy);
                
                $stmt->execute();
                return $this->conn->lastInsertId();
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get active notifications
        public function getActiveNotifications()
        {
            try {
                $stmt = $this->conn->prepare("
                    SELECT * FROM notifications 
                    WHERE is_active = 1 
                    AND (start_date IS NULL OR start_date <= CURDATE()) 
                    AND (end_date IS NULL OR end_date >= CURDATE())
                    ORDER BY created_at DESC
                ");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // ==================== UTILITY FUNCTIONS ====================
        
        // Log activity (works with existing system)
        public function logActivity($userType, $userId, $username, $action, $details = '')
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO activity_log (user_type, user_id, username, action, details, ip_address, user_agent) 
                    VALUES (:user_type, :user_id, :username, :action, :details, :ip_address, :user_agent)
                ");
                
                $stmt->bindParam(":user_type", $userType);
                $stmt->bindParam(":user_id", $userId);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":action", $action);
                $stmt->bindParam(":details", $details);
                $stmt->bindParam(":ip_address", $_SERVER['REMOTE_ADDR'] ?? null);
                $stmt->bindParam(":user_agent", $_SERVER['HTTP_USER_AGENT'] ?? null);
                
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get barangay settings
        public function getBarangaySettings()
        {
            try {
                $stmt = $this->conn->prepare("SELECT * FROM barangay_settings");
                $stmt->execute();
                $settings = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $settings[$row['setting_key']] = $row['setting_value'];
                }
                return $settings;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Update barangay setting
        public function updateBarangaySetting($key, $value)
        {
            try {
                $stmt = $this->conn->prepare("
                    INSERT INTO barangay_settings (setting_key, setting_value) 
                    VALUES (:setting_key, :setting_value)
                    ON DUPLICATE KEY UPDATE setting_value = :setting_value
                ");
                
                $stmt->bindParam(":setting_key", $key);
                $stmt->bindParam(":setting_value", $value);
                
                $stmt->execute();
                return true;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get recent activities
        public function getRecentActivities($limit = 10)
        {
            try {
                $stmt = $this->conn->prepare("
                    SELECT * FROM activity_log 
                    ORDER BY created_at DESC 
                    LIMIT :limit
                ");
                $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }

        // Get dashboard statistics
        public function getDashboardStats()
        {
            try {
                $stats = [];
                
                // Total residents
                $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM resident_info");
                $stmt->execute();
                $stats['total_residents'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                // Total officials
                $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM official_info WHERE employment_status = 'Active'");
                $stmt->execute();
                $stats['total_officials'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                // Pending appointments
                $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM appointments WHERE status = 'Pending'");
                $stmt->execute();
                $stats['pending_appointments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                // Pending service requests
                $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM service_requests WHERE status = 'Pending'");
                $stmt->execute();
                $stats['pending_requests'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                // Low stock items
                $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM inventory WHERE quantity <= reorder_level AND status = 'Available'");
                $stmt->execute();
                $stats['low_stock_items'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                // Pending complaints
                $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM complaints WHERE status = 'Pending'");
                $stmt->execute();
                $stats['pending_complaints'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                // Ongoing projects
                $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM projects WHERE status = 'Ongoing'");
                $stmt->execute();
                $stats['ongoing_projects'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
                
                // This month's income
                $stmt = $this->conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM financial_records WHERE transaction_type = 'Income' AND MONTH(transaction_date) = MONTH(CURDATE()) AND YEAR(transaction_date) = YEAR(CURDATE())");
                $stmt->execute();
                $stats['monthly_income'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                // This month's expenses
                $stmt = $this->conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM financial_records WHERE transaction_type = 'Expense' AND MONTH(transaction_date) = MONTH(CURDATE()) AND YEAR(transaction_date) = YEAR(CURDATE())");
                $stmt->execute();
                $stats['monthly_expenses'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                return $stats;
            } catch (PDOException $e) {
                echo $e->getMessage();
                return false;
            }
        }   

        //Function to add resident info supplied from parameter
        public function add_resident($first_name, $middle_name, $last_name, $suffix , $birthday , $alias , $sex , $civil_stat , $mobile_no , $email , $religion , $voter_stat, $username, $password)
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "INSERT INTO resident_info (first_name, middle_name, last_name, suffix, birthday, alias, sex, civil_stat, mobile_no, email, religion, voter_stat, username, password) 
                                        VALUES (:first_name, :middle_name, :last_name, :suffix, :birthday, :alias, :sex, :civil_stat, :mobile_no, :email, :religion, :voter_stat, :username, :password)"
                                    );
                $stmt->bindParam(":first_name", $first_name);
                $stmt->bindParam(":middle_name", $middle_name);
                $stmt->bindParam(":last_name", $last_name);
                $stmt->bindParam(":suffix", $suffix);
                $stmt->bindParam(":birthday", $birthday);
                $stmt->bindParam(":alias", $alias);
                $stmt->bindParam(":sex", $sex);
                $stmt->bindParam(":civil_stat", $civil_stat);
                $stmt->bindParam(":mobile_no", $mobile_no);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":religion", $religion);
                $stmt->bindParam(":voter_stat", $voter_stat);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $password);
                $stmt->execute();
                
                return $stmt;
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to add official info supplied from parameter
        public function add_official($official_position,$official_first_name,$official_middle_name,$official_last_name,$official_sex,$official_contact_info,$official_username,$official_password)
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "INSERT INTO official_info (official_position, official_first_name, official_middle_name, official_last_name, official_sex, official_contact_info, official_username, official_password) 
                                        VALUES (:official_position, :official_first_name, :official_middle_name, :official_last_name, :official_sex, :official_contact_info, :official_username, :official_password)"
                                    );
                $stmt->bindParam(":official_position", $official_position);
                $stmt->bindParam(":official_first_name", $official_first_name);
                $stmt->bindParam(":official_middle_name", $official_middle_name);
                $stmt->bindParam(":official_last_name", $official_last_name);
                $stmt->bindParam(":official_sex", $official_sex);
                $stmt->bindParam(":official_contact_info", $official_contact_info);
                $stmt->bindParam(":official_username", $official_username);
                $stmt->bindParam(":official_password", $official_password);
                $stmt->execute();
                
                return $stmt;
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to add post to database from info supplied from parameter
        public function add_post($post_title,$post_body,$post_date_time,$post_image = null,$author_id = null,$author_type = null)
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "INSERT INTO announcement_post ( post_title, post_body, post_date_time, post_image, author_id, author_type) 
                                        VALUES (:post_title, :post_body, :post_date_time, :post_image, :author_id, :author_type)"
                                    );
                
                $stmt->bindParam(":post_title", $post_title);
                $stmt->bindParam(":post_body", $post_body);
                $stmt->bindParam(":post_date_time", $post_date_time);
                $stmt->bindParam(":post_image", $post_image);
                $stmt->bindParam(":author_id", $author_id);
                $stmt->bindParam(":author_type", $author_type);

                $stmt->execute();
                
                return $stmt;
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to edit resident info supplied from parameter
        public function edit_resident($resident_id,$first_name, $middle_name, $last_name, $suffix , $birthday , $alias , $sex , $civil_stat , $mobile_no , $email , $religion , $voter_stat, $username, $password)
        {

            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "UPDATE resident_info 
                                        SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name,
                                            suffix = :suffix, birthday = :birthday, alias = :alias,
                                            sex = :sex, civil_stat = :civil_stat, mobile_no = :mobile_no,
                                            email = :email, religion = :religion, voter_stat = :voter_stat,
                                            username = :username, password = :password
                                        WHERE resident_id = :resident_id"
                                    );
                
                $stmt->bindParam(":resident_id", $resident_id);

                $stmt->bindParam(":first_name", $first_name);
                $stmt->bindParam(":middle_name", $middle_name);
                $stmt->bindParam(":last_name", $last_name);
                $stmt->bindParam(":suffix", $suffix);
                $stmt->bindParam(":birthday", $birthday);
                $stmt->bindParam(":alias", $alias);
                $stmt->bindParam(":sex", $sex);
                $stmt->bindParam(":civil_stat", $civil_stat);
                $stmt->bindParam(":mobile_no", $mobile_no);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":religion", $religion);
                $stmt->bindParam(":voter_stat", $voter_stat);
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $password);
                $stmt->execute();
                return $stmt;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to edit offical info supplied from parameter
        public function edit_official($official_id,$official_position,$official_first_name, $official_middle_name, $official_last_name, $official_sex , $official_contact_info , $official_username , $official_password )
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "   UPDATE  official_info 
                                            SET     official_position = :official_position, official_first_name = :official_first_name, official_middle_name = :official_middle_name,
                                                    official_last_name = :official_last_name, official_sex = :official_sex, official_contact_info = :official_contact_info,
                                                    official_username = :official_username, official_password = :official_password
                                            WHERE   official_id = :official_id"
                                    );
                $stmt->bindParam(":official_id", $official_id);
                $stmt->bindParam(":official_position", $official_position);
                $stmt->bindParam(":official_first_name", $official_first_name);
                $stmt->bindParam(":official_middle_name", $official_middle_name);
                $stmt->bindParam(":official_last_name", $official_last_name);
                $stmt->bindParam(":official_sex", $official_sex);
                $stmt->bindParam(":official_contact_info", $official_contact_info);
                $stmt->bindParam(":official_username", $official_username);
                $stmt->bindParam(":official_password", $official_password);
                
                $stmt->execute();
                return $stmt;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to edit post info supplied from parameter
        public function edit_post($post_id,$post_title,$post_body, $post_date_time)
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "   UPDATE  announcement_post 
                                            SET     post_title = :post_title, post_body = :post_body, post_date_time = :post_date_time
                                            WHERE   post_id = :post_id
                                        "
                                    );
                $stmt->bindParam(":post_id", $post_id);
                $stmt->bindParam(":post_title", $post_title);
                $stmt->bindParam(":post_body", $post_body);
                $stmt->bindParam(":post_date_time", $post_date_time);
                $stmt->execute();
                return $stmt;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to delete resident info supplied from parameter
        public function delete_resident($resident_id)
        {
            try
            {
                $stmt = $this->conn->prepare("DELETE FROM resident_info WHERE resident_id = :resident_id");
                $stmt->bindparam(":resident_id", $resident_id);
                $stmt->execute();
                return $stmt;

            }
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }
        }

        //Function to delete official info supplied from parameter
        public function delete_official($official_id)
        {
            try
            {
                $stmt = $this->conn->prepare("DELETE FROM official_info WHERE official_id = :official_id");
                $stmt->bindparam(":official_id", $official_id);
                $stmt->execute();
                return $stmt;

            }
            catch(PDOException $e)
            {
                  echo $e->getMessage();
            }
        }

        //Function to delete post info supplied from parameter
        public function delete_post($post_id)
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "DELETE FROM announcement_post WHERE post_id = :post_id"
                                    );
                $stmt->bindParam(":post_id", $post_id);
                $stmt->execute();
                return $stmt;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to delete document info supplied from parameter
        public function delete_document($id)
        {
            try
            {
                $stmt = $this->conn->prepare
                                    (
                                        "DELETE FROM documents WHERE id = :id"
                                    );
                $stmt->bindParam(":id", $id);
                $stmt->execute();
                return $stmt;
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to delete document file from directory info supplied from parameter
        public function delete_document_file($filename)
        {
            $path = 'documents/'.$filename;
            if(unlink($path))
            {
                return true;
            }
            else
            {
                return false;
            }
           
            
        }

        //Function to edit announcement
        public function edit_announcement($post_id, $post_title, $post_body, $post_date_time, $post_image = null, $author_id = null, $author_type = null)
        {
            try
            {
                if($post_image) {
                    $stmt = $this->conn->prepare(
                        "UPDATE announcement_post SET post_title = :post_title, post_body = :post_body, post_date_time = :post_date_time, post_image = :post_image, author_id = :author_id, author_type = :author_type WHERE post_id = :post_id"
                    );
                    $stmt->bindParam(":post_image", $post_image);
                } else {
                    $stmt = $this->conn->prepare(
                        "UPDATE announcement_post SET post_title = :post_title, post_body = :post_body, post_date_time = :post_date_time, author_id = :author_id, author_type = :author_type WHERE post_id = :post_id"
                    );
                }
                
                $stmt->bindParam(":post_id", $post_id);
                $stmt->bindParam(":post_title", $post_title);
                $stmt->bindParam(":post_body", $post_body);
                $stmt->bindParam(":post_date_time", $post_date_time);
                $stmt->bindParam(":author_id", $author_id);
                $stmt->bindParam(":author_type", $author_type);

                $stmt->execute();
                
                return $stmt;
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to delete announcement
        public function delete_announcement($post_id)
        {
            try
            {
                $stmt = $this->conn->prepare("DELETE FROM announcement_post WHERE post_id = :post_id");
                $stmt->bindParam(":post_id", $post_id);
                $stmt->execute();
                
                return $stmt;
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to get announcement by ID
        public function get_announcement_by_id($post_id)
        {
            try
            {
                $stmt = $this->conn->prepare("SELECT * FROM announcement_post WHERE post_id = :post_id");
                $stmt->bindParam(":post_id", $post_id);
                $stmt->execute();
                
                return $stmt;
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        //Function to upload documents
        public function upload_documents($file, $destination, $filename,  $description, $date_time, $directory)
        {
            try
            {
                if (move_uploaded_file($file, $destination)) 
                {
                    $stmt = $this->conn->prepare("INSERT INTO documents 
                                                        VALUES ('', ?, ?, ?, ? )");
                    $stmt->bindParam(1, $filename);
                    $stmt->bindParam(2, $description);
                    $stmt->bindParam(3, $date_time);
                    $stmt->bindParam(4, $directory);
                    $stmt->execute();
                    $this->redirect('admin_Documents.php?documentUploaded'); 
                } 
                else 
                {
                    $this->redirect('admin_Documents.php?documentUploadFailed'); 
                }
            }
            catch(PDOException $e)
            {
                echo $e->getMessage();
            }
        }

        
        



    }


?>