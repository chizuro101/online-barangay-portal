-- Complete System Enhancement - Works with Existing Database Structure

-- 1. Add missing columns to official_info table for enhanced functionality
ALTER TABLE `official_info` 
ADD COLUMN `is_captain` TINYINT(1) DEFAULT 0 AFTER `official_password`,
ADD COLUMN `position_level` INT(11) DEFAULT 1 AFTER `is_captain`,
ADD COLUMN `staff_position_id` INT(11) DEFAULT NULL AFTER `position_level`,
ADD COLUMN `created_by_captain` TINYINT(1) DEFAULT 0 AFTER `staff_position_id`,
ADD COLUMN `date_hired` DATE DEFAULT NULL AFTER `official_contact_info`,
ADD COLUMN `salary_grade` VARCHAR(10) DEFAULT NULL AFTER `date_hired`,
ADD COLUMN `employment_status` ENUM('Active', 'Inactive', 'Suspended', 'On Leave') DEFAULT 'Active' AFTER `salary_grade`;

-- 2. Enhance resident_info table with additional fields
ALTER TABLE `resident_info` 
ADD COLUMN `date_registered` DATE DEFAULT NULL AFTER `voter_stat`,
ADD COLUMN `household_number` VARCHAR(20) DEFAULT NULL AFTER `date_registered`,
ADD COLUMN `zone_number` INT(11) DEFAULT NULL AFTER `household_number`,
ADD COLUMN `precinct_number` VARCHAR(10) DEFAULT NULL AFTER `zone_number`,
ADD COLUMN `is_active_voter` TINYINT(1) DEFAULT 1 AFTER `precinct_number`,
ADD COLUMN `family_head` TINYINT(1) DEFAULT 0 AFTER `is_active_voter`,
ADD COLUMN `education_level` VARCHAR(50) DEFAULT NULL AFTER `family_head`,
ADD COLUMN `occupation` VARCHAR(100) DEFAULT NULL AFTER `education_level`,
ADD COLUMN `monthly_income` DECIMAL(10,2) DEFAULT NULL AFTER `occupation`;

-- 3. Create appointment booking system
CREATE TABLE IF NOT EXISTS `appointments` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `resident_id` int(11) DEFAULT NULL,
  `official_id` int(11) DEFAULT NULL,
  `appointment_type` varchar(100) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `purpose` text,
  `status` enum('Pending', 'Confirmed', 'Completed', 'Cancelled', 'No-Show') DEFAULT 'Pending',
  `notes` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`appointment_id`),
  KEY `resident_id` (`resident_id`),
  KEY `official_id` (`official_id`),
  KEY `appointment_date` (`appointment_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Create service requests system
CREATE TABLE IF NOT EXISTS `service_requests` (
  `request_id` int(11) NOT NULL AUTO_INCREMENT,
  `resident_id` int(11) DEFAULT NULL,
  `request_type` varchar(100) NOT NULL,
  `request_title` varchar(200) NOT NULL,
  `request_description` text,
  `priority` enum('Low', 'Medium', 'High', 'Urgent') DEFAULT 'Medium',
  `status` enum('Pending', 'In Progress', 'Completed', 'Rejected') DEFAULT 'Pending',
  `assigned_official_id` int(11) DEFAULT NULL,
  `resolution_notes` text,
  `date_completed` date DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`),
  KEY `resident_id` (`resident_id`),
  KEY `assigned_official_id` (`assigned_official_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. Create barangay inventory system
CREATE TABLE IF NOT EXISTS `inventory` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(200) NOT NULL,
  `item_category` varchar(100) NOT NULL,
  `description` text,
  `quantity` int(11) DEFAULT 0,
  `unit` varchar(20) DEFAULT 'pieces',
  `unit_cost` decimal(10,2) DEFAULT 0.00,
  `total_value` decimal(10,2) DEFAULT 0.00,
  `reorder_level` int(11) DEFAULT 0,
  `supplier` varchar(200) DEFAULT NULL,
  `date_acquired` date DEFAULT NULL,
  `status` enum('Available', 'Out of Stock', 'Damaged', 'Disposed') DEFAULT 'Available',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`item_id`),
  KEY `item_category` (`item_category`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Create barangay meetings system
CREATE TABLE IF NOT EXISTS `meetings` (
  `meeting_id` int(11) NOT NULL AUTO_INCREMENT,
  `meeting_title` varchar(200) NOT NULL,
  `meeting_type` enum('Regular', 'Special', 'Emergency', 'Committee') DEFAULT 'Regular',
  `meeting_date` date NOT NULL,
  `meeting_time` time NOT NULL,
  `venue` varchar(200) DEFAULT NULL,
  `agenda` text,
  `minutes` text,
  `attendees` text,
  `absentees` text,
  `status` enum('Scheduled', 'Ongoing', 'Completed', 'Cancelled') DEFAULT 'Scheduled',
  `called_by` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`meeting_id`),
  KEY `meeting_date` (`meeting_date`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. Create financial records system
CREATE TABLE IF NOT EXISTS `financial_records` (
  `record_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_type` enum('Income', 'Expense') NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `payment_method` enum('Cash', 'Check', 'Bank Transfer', 'Others') DEFAULT 'Cash',
  `reference_number` varchar(100) DEFAULT NULL,
  `received_from` varchar(200) DEFAULT NULL,
  `paid_to` varchar(200) DEFAULT NULL,
  `receipt_number` varchar(100) DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`record_id`),
  KEY `transaction_type` (`transaction_type`),
  KEY `transaction_date` (`transaction_date`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. Create barangay projects system
CREATE TABLE IF NOT EXISTS `projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(200) NOT NULL,
  `project_description` text,
  `project_type` enum('Infrastructure', 'Social', 'Health', 'Education', 'Environment', 'Others') DEFAULT 'Infrastructure',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(12,2) DEFAULT 0.00,
  `actual_cost` decimal(12,2) DEFAULT 0.00,
  `status` enum('Planning', 'Ongoing', 'Completed', 'Suspended', 'Cancelled') DEFAULT 'Planning',
  `progress_percentage` int(11) DEFAULT 0,
  `project_manager` int(11) DEFAULT NULL,
  `funding_source` varchar(200) DEFAULT NULL,
  `beneficiaries` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`project_id`),
  KEY `project_type` (`project_type`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. Create complaints and feedback system
CREATE TABLE IF NOT EXISTS `complaints` (
  `complaint_id` int(11) NOT NULL AUTO_INCREMENT,
  `complainant_id` int(11) DEFAULT NULL,
  `complainant_name` varchar(200) DEFAULT NULL,
  `complainant_contact` varchar(100) DEFAULT NULL,
  `complaint_type` varchar(100) NOT NULL,
  `complaint_title` varchar(200) NOT NULL,
  `complaint_description` text,
  `severity` enum('Low', 'Medium', 'High', 'Critical') DEFAULT 'Medium',
  `status` enum('Pending', 'Under Investigation', 'Resolved', 'Dismissed') DEFAULT 'Pending',
  `assigned_to` int(11) DEFAULT NULL,
  `resolution_details` text,
  `date_resolved` date DEFAULT NULL,
  `anonymous` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`complaint_id`),
  KEY `complainant_id` (`complainant_id`),
  KEY `status` (`status`),
  KEY `severity` (`severity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. Create barangay notifications system
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `type` enum('General', 'Urgent', 'Reminder', 'Alert', 'Information') DEFAULT 'General',
  `target_audience` enum('All', 'Residents', 'Officials', 'Staff', 'Specific') DEFAULT 'All',
  `target_ids` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `type` (`type`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 11. Create barangay settings table (if not exists)
CREATE TABLE IF NOT EXISTS `barangay_settings` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text,
  `setting_description` varchar(255),
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`setting_key`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 12. Create activity log table (if not exists)
CREATE TABLE IF NOT EXISTS `activity_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin', 'resident') NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `details` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  KEY `user_type` (`user_type`),
  KEY `action` (`action`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 13. Create staff positions table (if not exists)
CREATE TABLE IF NOT EXISTS `staff_positions` (
  `position_id` int(11) NOT NULL AUTO_INCREMENT,
  `position_name` varchar(100) NOT NULL,
  `position_code` varchar(20) NOT NULL,
  `position_level` int(11) NOT NULL DEFAULT 1,
  `description` text,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`position_id`),
  UNIQUE KEY `position_code` (`position_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 14. Insert default staff positions
INSERT INTO `staff_positions` (`position_name`, `position_code`, `position_level`, `description`) VALUES
('Barangay Captain', 'CAPTAIN', 10, 'Overall head of the barangay with full administrative authority'),
('Barangay Secretary', 'SECRETARY', 9, 'Manages barangay records, documents, and official communications'),
('Barangay Treasurer', 'TREASURER', 8, 'Handles barangay finances, budget, and financial reports'),
('Barangay Councilor', 'COUNCILOR', 7, 'Legislative body member, represents constituents'),
('SK Chairman', 'SK_CHAIRMAN', 6, 'Head of Sangguniang Kabataan (Youth Council)'),
('Barangay Health Worker', 'HEALTH_WORKER', 6, 'Provides basic health services and education'),
('Barangay Nutritionist', 'NUTRITIONIST', 6, 'Manages nutrition programs and feeding services'),
('Barangay Engineer', 'ENGINEER', 7, 'Oversees infrastructure projects and maintenance'),
('Barangay Tanod', 'TANOD', 5, 'Peace and order officer, maintains barangay security'),
('Barangay Administrative Assistant', 'ADMIN_ASST', 4, 'Provides administrative support services'),
('Barangay Cleaner', 'CLEANER', 3, 'Maintains barangay cleanliness and sanitation'),
('Barangay Driver', 'DRIVER', 3, 'Provides transportation services for official duties');

-- 15. Insert default barangay settings
INSERT INTO `barangay_settings` (`setting_key`, `setting_value`, `setting_description`) VALUES
('barangay_name', 'Barangay Sta. Cruz Viejo', 'Name of the barangay'),
('barangay_address', 'Sta. Cruz Viejo, Tanjay City, Negros Oriental', 'Barangay address'),
('barangay_contact', '+63 97 5832 1123', 'Primary contact number'),
('barangay_email', 'brgystacruzviejo@gmail.com', 'Official email address'),
('captain_name', 'John Garcia Mercado', 'Current barangay captain name'),
('term_start', '2023-01-01', 'Start date of current term'),
('term_end', '2026-12-31', 'End date of current term'),
('population_count', '1500', 'Total population count'),
('household_count', '350', 'Total household count'),
('voter_count', '800', 'Total registered voters'),
('senior_citizen_count', '200', 'Total senior citizens'),
('pwd_count', '50', 'Total persons with disabilities'),
('youth_count', '400', 'Total youth population (15-30 years old)');

-- 16. Update existing admin account to be captain
UPDATE `official_info` 
SET 
    `is_captain` = 1,
    `position_level` = 10,
    `staff_position_id` = 1,
    `date_hired` = CURDATE(),
    `employment_status` = 'Active'
WHERE `official_username` = 'admin1';

-- 17. Add foreign key constraints (safe approach)
ALTER TABLE `official_info` 
ADD CONSTRAINT `fk_official_position` 
FOREIGN KEY (`staff_position_id`) REFERENCES `staff_positions` (`position_id`) ON DELETE SET NULL;
