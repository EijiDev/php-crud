<?php
// model/Course.php
class Course {
    private $conn;
    private $table = 'courses';

    public $id;
    public $course_name;
    public $course_code;
    public $description;
    public $credits;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . " (course_name, course_code, description, credits) 
                  VALUES (:course_name, :course_code, :description, :credits)";
        $stmt = $this->conn->prepare($query);
        
        $this->course_name = htmlspecialchars(strip_tags($this->course_name));
        $this->course_code = htmlspecialchars(strip_tags($this->course_code));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(':course_name', $this->course_name);
        $stmt->bindParam(':course_code', $this->course_code);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':credits', $this->credits);
        
        return $stmt->execute();
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY course_name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update() {
        $query = "UPDATE " . $this->table . " 
                  SET course_name = :course_name, course_code = :course_code, 
                      description = :description, credits = :credits 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $this->course_name = htmlspecialchars(strip_tags($this->course_name));
        $this->course_code = htmlspecialchars(strip_tags($this->course_code));
        $this->description = htmlspecialchars(strip_tags($this->description));
        
        $stmt->bindParam(':course_name', $this->course_name);
        $stmt->bindParam(':course_code', $this->course_code);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':credits', $this->credits);
        $stmt->bindParam(':id', $this->id);
        
        return $stmt->execute();
    }

    public function delete() {
        // First check if course is used in enrollments
        $query = "SELECT COUNT(*) as count FROM enrollments WHERE course_name = (SELECT course_name FROM " . $this->table . " WHERE id = :id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result['count'] > 0) {
            return false; // Cannot delete course with enrollments
        }
        
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}