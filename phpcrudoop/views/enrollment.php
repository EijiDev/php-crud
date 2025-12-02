<?php
// views/enrollment.php
require_once __DIR__ . '/../controllers/EnrollmentController.php';
require_once __DIR__ . '/../controllers/StudentController.php';

$enrollmentController = new EnrollmentController();
$studentController = new StudentController();

$isEdit = isset($_GET['id']);
$current = null;

if ($isEdit) {
    $current = $enrollmentController->readOne($_GET['id']);
    if (!$current) {
        header("Location: ../public/index.php");
        exit;
    }
}

if ($_POST) {
    if ($isEdit) {
        $enrollmentController->update($_POST['id'], $_POST['course_name'], $_POST['enrollment_date']);
    } else {
        $enrollmentController->create($_POST['student_id'], $_POST['course_name'], $_POST['enrollment_date']);
    }
    header("Location: ../public/index.php");
    exit;
}

$students = $studentController->readAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit' : 'Add New' ?> Enrollment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .readonly {
            background-color: #e9ecef;
        }
        button {
            padding: 10px 20px;
            background: <?= $isEdit ? '#28a745' : '#007bff' ?>;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: <?= $isEdit ? '#218838' : '#0056b3' ?>;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?= $isEdit ? 'Edit' : 'Add New' ?> Enrollment</h2>
        <form method="POST">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($current['id']) ?>">
                <div class="form-group">
                    <label>Student Name:</label>
                    <input type="text" value="<?= htmlspecialchars($current['student_name']) ?>" class="readonly" readonly>
                </div>
            <?php else: ?>
                <div class="form-group">
                    <label for="student_id">Select Student:</label>
                    <select id="student_id" name="student_id" required>
                        <option value="">-- Select Student --</option>
                        <?php while($row = $students->fetch(PDO::FETCH_ASSOC)): ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['email']) ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" 
                       value="<?= $isEdit ? htmlspecialchars($current['course_name']) : '' ?>" 
                       placeholder="Enter course name" required>
            </div>
            <div class="form-group">
                <label for="enrollment_date">Enrollment Date:</label>
                <input type="date" id="enrollment_date" name="enrollment_date" 
                       value="<?= $isEdit ? htmlspecialchars($current['enrollment_date']) : date('Y-m-d') ?>" required>
            </div>
            <button type="submit"><?= $isEdit ? 'Update' : 'Save' ?> Enrollment</button>
        </form>
        <a href="../public/index.php" class="back-link">← Back to List</a>
    </div>
</body>
</html>