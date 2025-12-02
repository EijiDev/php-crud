<?php
// public/index.php
require_once __DIR__ . '/../controller/StudentController.php';
require_once __DIR__ . '/../controller/EnrollmentController.php';

$studentController = new StudentController();
$enrollmentController = new EnrollmentController();

if (isset($_GET['delete_student'])) {
    $studentController->delete($_GET['delete_student']);
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete_enrollment'])) {
    $enrollmentController->delete($_GET['delete_enrollment']);
    header("Location: index.php");
    exit;
}

$students = $studentController->readAll();
$enrollments = $enrollmentController->readAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Student Management System</title>
</head>

<body>
    <div class="container">
        <h1>Student Management System</h1>

        <div class="nav-links">
            <a href="../views/create.php">Add New Student</a>
            <a href="../views/enrollment.php">Add New Enrollment</a>
            <a href="../views/course.php">Manage Courses</a>
        </div>

        <!-- Students Section -->
        <div class="section">
            <h2>Students</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $students->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td class="action-links">
                            <a href="../views/edit.php?id=<?= $row['id'] ?>">Edit</a>
                            <a href="../views/home.php?student_id=<?= $row['id'] ?>">View Enrollments</a>
                            <a href="?delete_student=<?= $row['id'] ?>" class="delete-link"
                                onclick="return confirm('Delete this student and all enrollments?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>

        <!-- Enrollments Section -->
        <div class="section">
            <h2>Recent Enrollments</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Student Name</th>
                    <th>Email</th>
                    <th>Course</th>
                    <th>Enrollment Date</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $enrollments->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                        <td><?= htmlspecialchars($row['enrollment_date']) ?></td>
                        <td class="action-links">
                            <a href="../views/enrollment.php?id=<?= $row['id'] ?>">Edit</a>
                            <a href="?delete_enrollment=<?= $row['id'] ?>" class="delete-link"
                                onclick="return confirm('Delete this enrollment?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>

</html>