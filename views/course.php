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
    <link rel="stylesheet" href="../public/css/course.css">
    <title>CampusConnect - Courses</title>
</head>
<body>
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">C</div>
            <span class="logo-text">CampusConnect</span>
        </div>
        <nav>
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="../public/index.php" class="nav-link">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Students
                    </a>
                </li>
                <li class="nav-item">
                    <a href="course.php" class="nav-link active">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a href="enrollment.php" class="nav-link">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Enrollments
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Courses</h1>
            <p>Manage the course catalog, including descriptions and credits.</p>
        </div>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="content-card">
            <div class="card-header">
                <h2><?= $isEdit ? 'Edit' : 'Add New' ?> Course</h2>
            </div>
            <div class="form-content">
                <form method="POST">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($current['id']) ?>">
                    <?php endif; ?>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="course_name">Course Name</label>
                            <input type="text" id="course_name" name="course_name" 
                                   value="<?= $isEdit ? htmlspecialchars($current['course_name']) : '' ?>" 
                                   placeholder="Introduction to Programming" required>
                        </div>
                        <div class="form-group">
                            <label for="course_code">Course Code</label>
                            <input type="text" id="course_code" name="course_code" 
                                   value="<?= $isEdit ? htmlspecialchars($current['course_code']) : '' ?>" 
                                   placeholder="CS101" required>
                        </div>
                        <div class="form-group">
                            <label for="units">Units</label>
                            <input type="number" id="units" name="units" min="1" max="10"
                                   value="<?= $isEdit ? htmlspecialchars($current['units']) : '3' ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" placeholder="Course description..."><?= $isEdit ? htmlspecialchars($current['description']) : '' ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? 'Update' : 'Add' ?> Course
                        </button>
                        <?php if ($isEdit): ?>
                            <a href="course.php" class="btn btn-secondary">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h2>All Courses</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Description</th>
                        <th>Units</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $courses->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['course_code']) ?></td>
                        <td><?= htmlspecialchars($row['course_name']) ?></td>
                        <td><?= htmlspecialchars(substr($row['description'], 0, 100)) ?><?= strlen($row['description']) > 100 ? '...' : '' ?></td>
                        <td><?= htmlspecialchars($row['units']) ?></td>
                        <td>
                            <div class="actions">
                                <a href="?id=<?= $row['id'] ?>" class="action-btn" title="Edit">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <a href="?delete=<?= $row['id'] ?>" class="action-btn" title="Delete" 
                                   onclick="return confirm('Delete this course? This will fail if the course has enrollments.')">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>