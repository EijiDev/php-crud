<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Course.php';

class CourseController
{
    private $db;
    private $course;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->course = new Course($this->db);
    }

    public function create($course_name, $course_code, $description, $units)
    {
        $this->course->course_name = $course_name;
        $this->course->course_code = $course_code;
        $this->course->description = $description;
        $this->course->units = $units;
        return $this->course->create();
    }

    public function readAll()
    {
        return $this->course->read();
    }

    public function readOne($id)
    {
        $this->course->id = $id;
        return $this->course->readOne();
    }

    public function update($id, $course_name, $course_code, $description, $units)
    {
        $this->course->id = $id;
        $this->course->course_name = $course_name;
        $this->course->course_code = $course_code;
        $this->course->description = $description;
        $this->course->units = $units;
        return $this->course->update();
    }

    public function delete($id)
    {
        $this->course->id = $id;
        return $this->course->delete();
    }
}
