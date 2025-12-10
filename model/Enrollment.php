<?php
class Enrollment
{
    private $conn;
    private $table = 'enrollments';

    public $id;
    public $student_id;
    public $course_id;
    public $enrollment_date;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Create enrollment
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (student_id, course_id, enrollment_date) 
                  VALUES (:student_id, :course_id, :enrollment_date)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':student_id', $this->student_id, PDO::PARAM_INT);
        $stmt->bindParam(':course_id', $this->course_id, PDO::PARAM_INT);
        $stmt->bindParam(':enrollment_date', $this->enrollment_date);

        return $stmt->execute();
    }

    public function readAll()
    {
        $query = "SELECT 
                e.*, 
                s.name AS name, 
                s.email AS email, 
                c.course_name AS course_name
              FROM " . $this->table . " e
              LEFT JOIN students s ON e.student_id = s.id
              LEFT JOIN courses c ON e.course_id = c.id
              ORDER BY e.enrollment_date DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Read enrollments by student
    public function readByStudent($student_id)
    {
        $query = "SELECT e.*, c.id AS course_id, c.course_name AS course_name
                  FROM " . $this->table . " e
                  LEFT JOIN courses c ON e.course_id = c.id
                  WHERE e.student_id = :student_id
                  ORDER BY e.enrollment_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Read single enrollment
    public function readOne()
    {
        $query = "SELECT e.*, 
                         s.name AS student_name, s.email AS student_email,
                         c.id AS course_id, c.course_name AS course_name
                  FROM " . $this->table . " e
                  LEFT JOIN students s ON e.student_id = s.id
                  LEFT JOIN courses c ON e.course_id = c.id
                  WHERE e.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update enrollment
    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET course_id = :course_id, enrollment_date = :enrollment_date 
                  WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':course_id', $this->course_id, PDO::PARAM_INT);
        $stmt->bindParam(':enrollment_date', $this->enrollment_date);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Delete enrollment
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
