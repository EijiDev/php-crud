<?php
// views/course.php
require_once __DIR__ . '/../controller/CourseController.php';
$controller = new CourseController();

$isEdit = isset($_GET['id']);
$current = null;
$error = '';

if ($isEdit) {
    $current = $controller->readOne($_GET['id']);
    if (!$current) {
        header("Location: course.php");
        exit;
    }
}

if ($_POST) {
    if ($isEdit) {
        if ($controller->update($_POST['id'], $_POST['course_name'], $_POST['course_code'], 
                               $_POST['description'], $_POST['units'])) {
            header("Location: course.php");
            exit;
        }
    } else {
        if ($controller->create($_POST['course_name'], $_POST['course_code'], 
                               $_POST['description'], $_POST['units'])) {
            header("Location: course.php");
            exit;
        }
    }
}

if (isset($_GET['delete'])) {
    if (!$controller->delete($_GET['delete'])) {
        $error = "Cannot delete course. It is being used in enrollments.";
    } else {
        header("Location: course.php");
        exit;
    }
}

$courses = $controller->readAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .form-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        textarea {
            min-height: 80px;
            resize: vertical;
        }
        .form-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
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
        .cancel-btn {
            background: #6c757d;
            margin-left: 10px;
        }
        .cancel-btn:hover {
            background: #5a6268;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../public/index.php" class="back-link">← Back to Dashboard</a>
        
        <h2><?= $isEdit ? 'Edit' : 'Add New' ?> Course</h2>
        
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <div class="form-section">
            <form method="POST">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($current['id']) ?>">
                <?php endif; ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="course_name">Course Name:</label>
                        <input type="text" id="course_name" name="course_name" 
                               value="<?= $isEdit ? htmlspecialchars($current['course_name']) : '' ?>" 
                               placeholder="e.g., Introduction to Programming" required>
                    </div>
                    <div class="form-group">
                        <label for="course_code">Course Code:</label>
                        <input type="text" id="course_code" name="course_code" 
                               value="<?= $isEdit ? htmlspecialchars($current['course_code']) : '' ?>" 
                               placeholder="e.g., CS101" required>
                    </div>
                    <div class="form-group">
                        <label for="credits">Units:</label>
                        <input type="number" id="credits" name="credits" min="1" max="10"
                               value="<?= $isEdit ? htmlspecialchars($current['units']) : '3' ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" placeholder="Course description..."><?= $isEdit ? htmlspecialchars($current['description']) : '' ?></textarea>
                </div>
                
                <button type="submit"><?= $isEdit ? 'Update' : 'Add' ?> Course</button>
                <?php if ($isEdit): ?>
                    <a href="course.php"><button type="button" class="cancel-btn">Cancel</button></a>
                <?php endif; ?>
            </form>
        </div>

        <h2>All Courses</h2>
        <table>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Description</th>
                <th>Units</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $courses->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?= htmlspecialchars($row['course_code']) ?></td>
                <td><?= htmlspecialchars($row['course_name']) ?></td>
                <td><?= htmlspecialchars(substr($row['description'], 0, 100)) ?><?= strlen($row['description']) > 100 ? '...' : '' ?></td>
                <td><?= htmlspecialchars($row['units']) ?></td>
                <td class="action-links">
                    <a href="?id=<?= $row['id'] ?>">Edit</a>
                    <a href="?delete=<?= $row['id'] ?>" class="delete-link" 
                       onclick="return confirm('Delete this course? This will fail if the course has enrollments.')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>