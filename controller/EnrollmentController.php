<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Enrollment.php';

class EnrollmentController
{
    private $db;
    private $enrollment;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->enrollment = new Enrollment($this->db);
    }

    public function create($student_id, $course_id, $enrollment_date)
    {
        $this->enrollment->student_id = $student_id;
        $this->enrollment->course_id = $course_id;
        $this->enrollment->enrollment_date = $enrollment_date;
        return $this->enrollment->create();
    }

    public function readAll()
    {
        return $this->enrollment->readAll();
    }

    public function readByStudent($student_id)
    {
        return $this->enrollment->readByStudent($student_id);
    }

    public function readOne($id)
    {
        $this->enrollment->id = $id;
        return $this->enrollment->readOne();
    }

    public function update($id, $course_id, $enrollment_date)
    {
        $this->enrollment->id = $id;
        $this->enrollment->course_id = $course_id;
        $this->enrollment->enrollment_date = $enrollment_date;
        return $this->enrollment->update();
    }

    public function delete($id)
    {
        $this->enrollment->id = $id;
        return $this->enrollment->delete();
    }
}
