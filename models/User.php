<?php

class User extends BaseModel {
    
    // ===== GET METHODS =====
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        return $this->query($sql, [$id]);
    }

    public function getUserByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = ?";
        return $this->query($sql, [$username]);
    }

    public function getUserByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = ?";
        return $this->query($sql, [$email]);
    }

    public function getAllUsers($limit = null, $offset = 0) {
        $sql = "SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT ? OFFSET ?";
            return $this->query($sql, [$limit, $offset]);
        }
        return $this->query($sql);
    }

    public function getUsersByStatus($status) {
        $sql = "SELECT * FROM users WHERE role = 'customer' AND status = ? ORDER BY created_at DESC";
        return $this->query($sql, [$status]);
    }

    public function searchUsers($keyword) {
        $sql = "SELECT * FROM users 
                WHERE role = 'customer' 
                AND (username LIKE ? OR email LIKE ? OR phone LIKE ?)
                ORDER BY created_at DESC";
        $keyword = "%$keyword%";
        return $this->query($sql, [$keyword, $keyword, $keyword]);
    }

    public function countUsers($status = null) {
        $sql = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
        
        if ($status) {
            $sql .= " AND status = ?";
            $result = $this->query($sql, [$status]);
        } else {
            $result = $this->query($sql);
        }
        
        return $result[0]['total'] ?? 0;
    }

    public function countActiveUsers() {
        return $this->countUsers('active');
    }

    public function countBannedUsers() {
        return $this->countUsers('banned');
    }

    public function getAdminUsers() {
        $sql = "SELECT * FROM users WHERE role = 'admin' ORDER BY created_at DESC";
        return $this->query($sql);
    }

    public function userExists($username) {
        $user = $this->getUserByUsername($username);
        return count($user) > 0;
    }

    public function emailExists($email) {
        $user = $this->getUserByEmail($email);
        return count($user) > 0;
    }

    // ===== CREATE/UPDATE/DELETE METHODS =====
    public function createUser($username, $password, $email, $phone = null, $address = null) {
        // Validation
        if ($this->userExists($username)) {
            return false;
        }

        if ($this->emailExists($email)) {
            return false;
        }

        $hashedPassword = md5($password); // TODO: Use password_hash() in production
        $sql = "INSERT INTO users (username, password, email, phone, address, role, status) 
                VALUES (?, ?, ?, ?, ?, 'customer', 'active')";
        return $this->execute($sql, [$username, $hashedPassword, $email, $phone, $address]);
    }

    public function updateProfile($userId, $email, $phone = null, $address = null, $avatar = null) {
        $sql = "UPDATE users SET email = ?, phone = ?, address = ?";
        $params = [$email, $phone, $address];

        if ($avatar) {
            $sql .= ", avatar = ?";
            $params[] = $avatar;
        }

        $sql .= ", updated_at = NOW() WHERE id = ?";
        $params[] = $userId;

        return $this->execute($sql, $params);
    }

    public function changePassword($userId, $oldPassword, $newPassword) {
        $user = $this->getUserById($userId);
        if (!$user) {
            return false;
        }

        $hashedOldPassword = md5($oldPassword);
        if ($hashedOldPassword !== $user[0]['password']) {
            return false;
        }

        $hashedNewPassword = md5($newPassword);
        $sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$hashedNewPassword, $userId]);
    }

    public function resetPassword($userId, $newPassword) {
        $hashedPassword = md5($newPassword);
        $sql = "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$hashedPassword, $userId]);
    }

    public function updateUserInfo($userId, $username, $email, $phone = null, $address = null) {
        $sql = "UPDATE users SET username = ?, email = ?, phone = ?, address = ?, updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$username, $email, $phone, $address, $userId]);
    }

    public function deleteUser($userId) {
        $sql = "DELETE FROM users WHERE id = ?";
        return $this->execute($sql, [$userId]);
    }

    // ===== STATUS METHODS =====
    public function blockUser($userId) {
        $sql = "UPDATE users SET status = 'banned', updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$userId]);
    }

    public function unblockUser($userId) {
        $sql = "UPDATE users SET status = 'active', updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$userId]);
    }

    public function deactivateUser($userId) {
        $sql = "UPDATE users SET status = 'inactive', updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$userId]);
    }

    public function activateUser($userId) {
        $sql = "UPDATE users SET status = 'active', updated_at = NOW() WHERE id = ?";
        return $this->execute($sql, [$userId]);
    }

    public function isUserActive($userId) {
        $user = $this->getUserById($userId);
        return $user && $user[0]['status'] === 'active';
    }

    public function isUserBlocked($userId) {
        $user = $this->getUserById($userId);
        return $user && $user[0]['status'] === 'banned';
    }

    // ===== AUTHENTICATION METHODS =====
    public function authenticate($username, $password) {
        $user = $this->getUserByUsername($username);
        
        if (!$user) {
            return null;
        }

        $hashedPassword = md5($password);
        if ($hashedPassword !== $user[0]['password']) {
            return null;
        }

        if ($user[0]['status'] !== 'active') {
            return null;
        }

        return $user[0];
    }

    public function authenticateByEmail($email, $password) {
        $user = $this->getUserByEmail($email);
        
        if (!$user) {
            return null;
        }

        $hashedPassword = md5($password);
        if ($hashedPassword !== $user[0]['password']) {
            return null;
        }

        if ($user[0]['status'] !== 'active') {
            return null;
        }

        return $user[0];
    }

    // ===== UTILITY METHODS =====
    public function getUserRole($userId) {
        $user = $this->getUserById($userId);
        return $user ? $user[0]['role'] : null;
    }

    public function isAdmin($userId) {
        return $this->getUserRole($userId) === 'admin';
    }

    public function isCustomer($userId) {
        return $this->getUserRole($userId) === 'customer';
    }

    public function promoteToAdmin($userId) {
        $sql = "UPDATE users SET role = 'admin' WHERE id = ?";
        return $this->execute($sql, [$userId]);
    }

    public function demoteToCustomer($userId) {
        $sql = "UPDATE users SET role = 'customer' WHERE id = ?";
        return $this->execute($sql, [$userId]);
    }

    public function getLastLoginUsers($days = 7) {
        $sql = "SELECT * FROM users 
                WHERE role = 'customer' 
                AND DATE(updated_at) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
                ORDER BY updated_at DESC";
        return $this->query($sql, [$days]);
    }

    public function getInactiveUsers($days = 30) {
        $sql = "SELECT * FROM users 
                WHERE role = 'customer' 
                AND DATE(updated_at) < DATE_SUB(CURDATE(), INTERVAL ? DAY)
                ORDER BY updated_at ASC";
        return $this->query($sql, [$days]);
    }

    // ===== STATS METHODS =====
    public function getNewUsersCount($days = 7) {
        $sql = "SELECT COUNT(*) as total FROM users 
                WHERE role = 'customer' 
                AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
        $result = $this->query($sql, [$days]);
        return $result[0]['total'] ?? 0;
    }

    public function getUserStats() {
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
                    SUM(CASE WHEN status = 'banned' THEN 1 ELSE 0 END) as banned_users,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_users
                FROM users 
                WHERE role = 'customer'";
        return $this->query($sql);
    }

    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function validatePassword($password) {
        return strlen($password) >= 6;
    }

    public function validateUsername($username) {
        return strlen($username) >= 3 && strlen($username) <= 100 && preg_match('/^[a-zA-Z0-9_]+$/', $username);
    }
}
