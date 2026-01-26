<?php

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;
    private $connection;

    public function __construct() {
        $this->db = new Database();
        $this->connection = $this->db->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function findById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function create($data) {
        $id = $this->generateUUID();
        $email = $data['email'];
        $password = isset($data['password']) ? password_hash($data['password'], PASSWORD_DEFAULT) : null;
        $avatar = $data['avatar'] ?? null;
        $firstName = $data['firstName'] ?? null;
        $lastName = $data['lastName'] ?? null;

        $stmt = $this->connection->prepare("
            INSERT INTO users (id, email, password, avatar, firstName, lastName) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param("ssssss", $id, $email, $password, $avatar, $firstName, $lastName);
        
        if ($stmt->execute()) {
            return $this->findById($id);
        }
        return false;
    }

    public function updateProfile($id, $data) {
        $fields = [];
        $values = [];
        $types = "";

        foreach ($data as $key => $value) {
            if (in_array($key, ['avatar', 'firstName', 'lastName']) && $value !== null) {
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
        $values[] = $id;
        $types .= "s";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param($types, ...$values);

        return $stmt->execute();
    }

    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    private function generateUUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}