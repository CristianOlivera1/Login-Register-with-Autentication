<?php

require_once __DIR__ . '/../config/database.php';

class Profile {
    private $db;
    private $connection;

    public function __construct() {
        $this->db = new Database();
        $this->connection = $this->db->getConnection();
    }

    public function getUserProfile($userId) {
        $stmt = $this->connection->prepare("
            SELECT u.*, 
                   COUNT(cv.id) as total_cvs,
                   SUM(cv.view_count) as total_views,
                   MAX(cv.is_public) as has_public_cv
            FROM users u 
            LEFT JOIN user_cvs cv ON u.id = cv.user_id 
            WHERE u.id = ? 
            GROUP BY u.id
        ");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function getTopPerformingCV($userId) {
        $stmt = $this->connection->prepare("
            SELECT cv.*, t.name as template_name 
            FROM user_cvs cv 
            LEFT JOIN cv_templates t ON cv.template_id = t.id 
            WHERE cv.user_id = ? 
            ORDER BY cv.view_count DESC 
            LIMIT 1
        ");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function updateUserProfile($userId, $data) {
        $fields = [];
        $values = [];
        $types = "";
        
        $allowedFields = ['firstName', 'lastName', 'email'];
        
        foreach ($data as $key => $value) {
            if (in_array($key, $allowedFields) && $value !== null) {
                $fields[] = "$key = ?";
                $values[] = $value;
                $types .= "s";
            }
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $fields[] = "updated_at = CURRENT_TIMESTAMP";
        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
        $values[] = $userId;
        $types .= "s";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param($types, ...$values);
        
        return $stmt->execute();
    }

    public function updateAvatar($userId, $avatarUrl) {
        $stmt = $this->connection->prepare("UPDATE users SET avatar = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("ss", $avatarUrl, $userId);
        return $stmt->execute();
    }

    public function updateCVVisibility($userId, $isPublic) {
        $stmt = $this->connection->prepare("UPDATE user_cvs SET is_public = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?");
        $stmt->bind_param("is", $isPublic, $userId);
        return $stmt->execute();
    }

    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("ss", $hashedPassword, $userId);
        return $stmt->execute();
    }

    public function getUserCVStats($userId) {
        $stmt = $this->connection->prepare("
            SELECT 
                COUNT(*) as total_cvs,
                SUM(view_count) as total_views,
                SUM(CASE WHEN is_public = 1 THEN 1 ELSE 0 END) as public_cvs,
                MAX(view_count) as max_views
            FROM user_cvs 
            WHERE user_id = ?
        ");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return [
            'total_cvs' => 0,
            'total_views' => 0,
            'public_cvs' => 0,
            'max_views' => 0
        ];
    }

    public function checkEmailExists($email, $excludeUserId = null) {
        $sql = "SELECT id FROM users WHERE email = ?";
        $params = [$email];
        $types = "s";
        
        if ($excludeUserId) {
            $sql .= " AND id != ?";
            $params[] = $excludeUserId;
            $types .= "s";
        }
        
        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows > 0;
    }

    public function getAuthProvider($userId) {
        $stmt = $this->connection->prepare("SELECT auth_provider FROM users WHERE id = ?");
        $stmt->bind_param("s", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['auth_provider'] ?? 'manual';
        }
        return 'manual';
    }

    public function updateLastPasswordChange($userId) {
        $stmt = $this->connection->prepare("UPDATE users SET last_password_change = CURRENT_TIMESTAMP WHERE id = ?");
        $stmt->bind_param("s", $userId);
        return $stmt->execute();
    }
}