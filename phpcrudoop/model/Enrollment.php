<?php
class Enrollment
{
    private $conn;
    private $table = 'enrollment';

    public $id;
    public $student_id;
    public $course_name;
    public $enrollment_date;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (student_id, course_name, enrollment_date) 
                  VALUES (:student_id, :course_name, :enrollment_date)";
        $stmt = $this->conn->prepare($query);

        $this->course_name = htmlspecialchars(strip_tags($this->course_name));

        $stmt->bindParam(':student_id', $this->student_id);
        $stmt->bindParam(':course_name', $this->course_name);
        $stmt->bindParam(':enrollment_date', $this->enrollment_date);

        return $stmt->execute();
    }

    public function readByStudent($student_id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE student_id = :student_id ORDER BY enrollment_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt;
    }

    public function readAll()
    {
        $query = "SELECT e.*, s.name as student_name, s.email 
                  FROM " . $this->table . " e 
                  LEFT JOIN students s ON e.student_id = s.id 
                  ORDER BY e.enrollment_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne()
    {
        $query = "SELECT e.*, s.name as student_name 
                  FROM " . $this->table . " e 
                  LEFT JOIN students s ON e.student_id = s.id 
                  WHERE e.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET course_name = :course_name, enrollment_date = :enrollment_date 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->course_name = htmlspecialchars(strip_tags($this->course_name));

        $stmt->bindParam(':course_name', $this->course_name);
        $stmt->bindParam(':enrollment_date', $this->enrollment_date);
        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }

    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }
}
