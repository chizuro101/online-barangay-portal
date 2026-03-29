<?php

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function runQuery($sql, $params = []) {
        return $this->db->query($sql, $params);
    }
    
    public function redirect($url) {
        header("Location: $url");
        exit();
    }
    
    // ==================== AUTHENTICATION ====================
    
    public function login($username, $password) {
        try {
            // Check officials table
            $stmt = $this->db->prepare("SELECT * FROM " . TABLE_OFFICIALS . " WHERE official_username = ?");
            $stmt->execute([$username]);
            $official = $stmt->fetch();
            
            if ($official && $official['official_password'] === $password) {
                $_SESSION['session_login'] = $official;
                $_SESSION['user_type'] = 'admin';
                return $official['is_captain'] ? 2 : 0; // 2 for captain, 0 for official
            }
            
            // Check residents table
            $stmt = $this->db->prepare("SELECT * FROM " . TABLE_RESIDENTS . " WHERE username = ?");
            $stmt->execute([$username]);
            $resident = $stmt->fetch();
            
            if ($resident && $resident['password'] === $password) {
                $_SESSION['session_login'] = $resident;
                $_SESSION['user_type'] = 'resident';
                return 1; // 1 for resident
            }
            
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['session_login']);
    }
    
    public function isAdmin() {
        return $this->isLoggedIn() && $_SESSION['user_type'] === 'admin';
    }
    
    public function isResident() {
        return $this->isLoggedIn() && $_SESSION['user_type'] === 'resident';
    }
    
    public function isCaptain($username = null) {
        if ($username === null) {
            $username = $_SESSION['session_login']['official_username'] ?? '';
        }
        
        try {
            $stmt = $this->db->prepare("SELECT is_captain FROM " . TABLE_OFFICIALS . " WHERE official_username = ?");
            $stmt->execute([$username]);
            $result = $stmt->fetch();
            return $result && $result['is_captain'] == 1;
        } catch (PDOException $e) {
            error_log("Captain check error: " . $e->getMessage());
            return false;
        }
    }
    
    public function logout() {
        session_destroy();
        unset($_SESSION);
    }
    
    // ==================== RESIDENT MANAGEMENT ====================
    
    public function add_resident($first_name, $middle_name, $last_name, $suffix, $birthday, $alias, $sex, $civil_stat, $mobile_no, $email, $religion, $voter_stat, $username, $password) {
        try {
            $data = [
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' => $last_name,
                'suffix' => $suffix,
                'birthday' => $birthday,
                'alias' => $alias,
                'sex' => $sex,
                'civil_stat' => $civil_stat,
                'mobile_no' => $mobile_no,
                'email' => $email,
                'religion' => $religion,
                'voter_stat' => $voter_stat,
                'username' => $username,
                'password' => $password
            ];
            
            return $this->db->insert(TABLE_RESIDENTS, $data);
        } catch (PDOException $e) {
            error_log("Add resident error: " . $e->getMessage());
            return false;
        }
    }
    
    public function edit_resident($resident_id, $first_name, $middle_name, $last_name, $suffix, $birthday, $alias, $sex, $civil_stat, $mobile_no, $email, $religion, $voter_stat, $username, $password) {
        try {
            $data = [
                'first_name' => $first_name,
                'middle_name' => $middle_name,
                'last_name' => $last_name,
                'suffix' => $suffix,
                'birthday' => $birthday,
                'alias' => $alias,
                'sex' => $sex,
                'civil_stat' => $civil_stat,
                'mobile_no' => $mobile_no,
                'email' => $email,
                'religion' => $religion,
                'voter_stat' => $voter_stat,
                'username' => $username,
                'password' => $password
            ];
            
            return $this->db->update(TABLE_RESIDENTS, $data, 'resident_id = ?', [$resident_id]);
        } catch (PDOException $e) {
            error_log("Edit resident error: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete_resident($resident_id) {
        try {
            return $this->db->delete(TABLE_RESIDENTS, 'resident_id = ?', [$resident_id]);
        } catch (PDOException $e) {
            error_log("Delete resident error: " . $e->getMessage());
            return false;
        }
    }
    
    // ==================== OFFICIAL MANAGEMENT ====================
    
    public function add_official($first_name, $middle_name, $last_name, $suffix, $birthday, $alias, $sex, $civil_stat, $mobile_no, $email, $religion, $voter_stat, $username, $password) {
        try {
            $data = [
                'official_first_name' => $first_name,
                'official_middle_name' => $middle_name,
                'official_last_name' => $last_name,
                'official_suffix' => $suffix,
                'official_birthday' => $birthday,
                'official_alias' => $alias,
                'official_sex' => $sex,
                'official_civil_stat' => $civil_stat,
                'official_mobile_no' => $mobile_no,
                'official_email' => $email,
                'official_religion' => $religion,
                'official_voter_stat' => $voter_stat,
                'official_username' => $username,
                'official_password' => $password
            ];
            
            return $this->db->insert(TABLE_OFFICIALS, $data);
        } catch (PDOException $e) {
            error_log("Add official error: " . $e->getMessage());
            return false;
        }
    }
    
    public function edit_official($official_id, $first_name, $middle_name, $last_name, $suffix, $birthday, $alias, $sex, $civil_stat, $mobile_no, $email, $religion, $voter_stat, $username, $password) {
        try {
            $data = [
                'official_first_name' => $first_name,
                'official_middle_name' => $middle_name,
                'official_last_name' => $last_name,
                'official_suffix' => $suffix,
                'official_birthday' => $birthday,
                'official_alias' => $alias,
                'official_sex' => $sex,
                'official_civil_stat' => $civil_stat,
                'official_mobile_no' => $mobile_no,
                'official_email' => $email,
                'official_religion' => $religion,
                'official_voter_stat' => $voter_stat,
                'official_username' => $username,
                'official_password' => $password
            ];
            
            return $this->db->update(TABLE_OFFICIALS, $data, 'official_id = ?', [$official_id]);
        } catch (PDOException $e) {
            error_log("Edit official error: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete_official($official_id) {
        try {
            return $this->db->delete(TABLE_OFFICIALS, 'official_id = ?', [$official_id]);
        } catch (PDOException $e) {
            error_log("Delete official error: " . $e->getMessage());
            return false;
        }
    }
    
    // ==================== ANNOUNCEMENT MANAGEMENT ====================
    
    public function add_post($post_title, $post_body, $post_date_time) {
        try {
            $data = [
                'post_title' => $post_title,
                'post_body' => $post_body,
                'post_date_time' => $post_date_time
            ];
            
            return $this->db->insert(TABLE_ANNOUNCEMENTS, $data);
        } catch (PDOException $e) {
            error_log("Add post error: " . $e->getMessage());
            return false;
        }
    }
    
    public function edit_post($post_id, $post_title, $post_body, $post_date_time) {
        try {
            $data = [
                'post_title' => $post_title,
                'post_body' => $post_body,
                'post_date_time' => $post_date_time
            ];
            
            return $this->db->update(TABLE_ANNOUNCEMENTS, $data, 'post_id = ?', [$post_id]);
        } catch (PDOException $e) {
            error_log("Edit post error: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete_post($post_id) {
        try {
            return $this->db->delete(TABLE_ANNOUNCEMENTS, 'post_id = ?', [$post_id]);
        } catch (PDOException $e) {
            error_log("Delete post error: " . $e->getMessage());
            return false;
        }
    }
    
    // ==================== DOCUMENT MANAGEMENT ====================
    
    public function upload_documents($doc_name, $doc_type, $doc_file) {
        try {
            $data = [
                'doc_name' => $doc_name,
                'doc_type' => $doc_type,
                'doc_file' => $doc_file
            ];
            
            return $this->db->insert(TABLE_DOCUMENTS, $data);
        } catch (PDOException $e) {
            error_log("Upload document error: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete_document($doc_id) {
        try {
            return $this->db->delete(TABLE_DOCUMENTS, 'doc_id = ?', [$doc_id]);
        } catch (PDOException $e) {
            error_log("Delete document error: " . $e->getMessage());
            return false;
        }
    }
    
    // ==================== UTILITY METHODS ====================
    
    public function getAllResidents() {
        try {
            return $this->db->fetchAll("SELECT * FROM " . TABLE_RESIDENTS . " ORDER BY last_name ASC");
        } catch (PDOException $e) {
            error_log("Get residents error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAllOfficials() {
        try {
            return $this->db->fetchAll("SELECT * FROM " . TABLE_OFFICIALS . " ORDER BY official_last_name ASC");
        } catch (PDOException $e) {
            error_log("Get officials error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAllAnnouncements() {
        try {
            return $this->db->fetchAll("SELECT * FROM " . TABLE_ANNOUNCEMENTS . " ORDER BY post_date_time DESC");
        } catch (PDOException $e) {
            error_log("Get announcements error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAllDocuments() {
        try {
            return $this->db->fetchAll("SELECT * FROM " . TABLE_DOCUMENTS . " ORDER BY doc_name ASC");
        } catch (PDOException $e) {
            error_log("Get documents error: " . $e->getMessage());
            return [];
        }
    }
    
    public function getResidentById($resident_id) {
        try {
            return $this->db->fetch("SELECT * FROM " . TABLE_RESIDENTS . " WHERE resident_id = ?", [$resident_id]);
        } catch (PDOException $e) {
            error_log("Get resident error: " . $e->getMessage());
            return null;
        }
    }
    
    public function getOfficialById($official_id) {
        try {
            return $this->db->fetch("SELECT * FROM " . TABLE_OFFICIALS . " WHERE official_id = ?", [$official_id]);
        } catch (PDOException $e) {
            error_log("Get official error: " . $e->getMessage());
            return null;
        }
    }
    
    public function getAnnouncementById($post_id) {
        try {
            return $this->db->fetch("SELECT * FROM " . TABLE_ANNOUNCEMENTS . " WHERE post_id = ?", [$post_id]);
        } catch (PDOException $e) {
            error_log("Get announcement error: " . $e->getMessage());
            return null;
        }
    }
    
    public function getDocumentById($doc_id) {
        try {
            return $this->db->fetch("SELECT * FROM " . TABLE_DOCUMENTS . " WHERE doc_id = ?", [$doc_id]);
        } catch (PDOException $e) {
            error_log("Get document error: " . $e->getMessage());
            return null;
        }
    }
}

?>
