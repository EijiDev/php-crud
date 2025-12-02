<?php
// views/home.php
require_once __DIR__ . '/../controller/StudentController.php';
require_once __DIR__ . '/../controller/EnrollmentController.php';

$studentController = new StudentController();
$enrollmentController = new EnrollmentController();

$student_id = $_GET['student_id'];
$student = $studentController->readOne($student_id);

if (!$student) {
    header("Location: ../public/index.php");
    exit;
}

$enrollments = $enrollmentController->readByStudent($student_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2, h3 {
            color: #333;
        }
        .student-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .action-links a {
            color: #007bff;
            text-decoration: none;
            margin-right: 10px;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
        .delete-link {
            color: #dc3545;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .no-enrollments {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Enrollments</h2>
        
        <div class="student-info">
            <h3><?= htmlspecialchars($student['name']) ?></h3>
            <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
        </div>

        <h3>Enrolled Courses</h3>
        
        <?php if ($enrollments->rowCount() > 0): ?>
            <table>
                <tr>
                    <th>Course Name</th>
                    <th>Enrollment Date</th>
                    <th>Actions</th>
                </tr>
                <?php while($row = $enrollments->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['course_name']) ?></td>
                    <td><?= htmlspecialchars($row['enrollment_date']) ?></td>
                    <td class="action-links">
                        <a href="enrollment.php?id=<?= $row['id'] ?>">Edit</a>
                        <a href="../public/index.php?delete_enrollment=<?= $row['id'] ?>" class="delete-link" 
                           onclick="return confirm('Delete this enrollment?')">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <div class="no-enrollments">
                <p>No enrollments found for this student.</p>
            </div>
        <?php endif; ?>

        <a href="../public/index.php" class="back-link">← Back to List</a>
    </div>
</body>
</html>