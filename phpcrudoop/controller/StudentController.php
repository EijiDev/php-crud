<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Student.php';

class StudentController
{
    private $db;
    private $student;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
        $this->student = new Student($this->db);
    }

    public function create($name, $email)
    {
        $this->student->name = $name;
        $this->student->email = $email;
        return $this->student->create();
    }

    public function readAll()
    {
        return $this->student->read();
    }

    public function readOne($id)
    {
        $this->student->id = $id;
        return $this->student->readOne();
    }

    public function update($id, $name, $email)
    {
        $this->student->id = $id;
        $this->student->name = $name;
        $this->student->email = $email;
        return $this->student->update();
    }

    public function delete($id)
    {
        $this->student->id = $id;
        return $this->student->delete();
    }
}
