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
    <title>CampusConnect - Students</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #e8edf2;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: white;
            border-right: 1px solid #e2e8f0;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: #9333ea;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .logo-text {
            font-size: 18px;
            font-weight: 600;
            color: #1a202c;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-link:hover {
            background: #f7fafc;
            color: #2d3748;
        }

        .nav-link.active {
            background: #9333ea;
            color: white;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
        }

        .main-content {
            margin-left: 250px;
            flex: 1;
            padding: 40px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 32px;
            color: #1a202c;
            margin-bottom: 8px;
        }

        .header p {
            color: #718096;
            font-size: 15px;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-header h2 {
            font-size: 20px;
            color: #1a202c;
        }

        .add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #9333ea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .add-btn:hover {
            background: #7e22ce;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f7fafc;
        }

        th {
            padding: 16px 24px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 16px 24px;
            border-top: 1px solid #e2e8f0;
            color: #2d3748;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f7fafc;
        }

        .actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            color: #718096;
            text-decoration: none;
            font-size: 18px;
            transition: color 0.2s;
        }

        .action-btn:hover {
            color: #2d3748;
        }
    </style>
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
                    <a href="index.php" class="nav-link active">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        Students
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../views/course.php" class="nav-link">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../views/enrollment.php" class="nav-link">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Enrollments
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <div class="header">
            <h1>Students</h1>
            <p>Manage student information and enrollments.</p>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h2>All Students</h2>
                <a href="../views/student.php" class="add-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Student
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td>
                                <div class="actions">
                                    <a href="../views/edit.php?id=<?= $row['id'] ?>" class="action-btn" title="Edit">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="../views/home.php?student_id=<?= $row['id'] ?>" class="action-btn" title="View Enrollments">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="?delete_student=<?= $row['id'] ?>" class="action-btn" title="Delete"
                                        onclick="return confirm('Delete this student and all enrollments?')">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h2>Recent Enrollments</h2>
                <a href="../views/enrollment.php" class="add-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Enrollment
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Enrollment Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $enrollments->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['course_name']) ?></td>
                            <td><?= htmlspecialchars($row['enrollment_date']) ?></td>
                            <td>
                                <div class="actions">
                                    <a href="../views/enrollment.php?id=<?= $row['id'] ?>" class="action-btn" title="Edit">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <a href="?delete_enrollment=<?= $row['id'] ?>" class="action-btn" title="Delete"
                                        onclick="return confirm('Delete this enrollment?')">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
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