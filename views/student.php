<?php
// views/create.php
require_once __DIR__ . '/../controller/StudentController.php';
$controller = new StudentController();

if ($_POST) {
    if($controller->create($_POST['name'], $_POST['email'])){
        header("Location: ../public/index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/student.css">
    <title>CampusConnect - Add Student</title>
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
                    <a href="../public/index.php" class="nav-link active">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Students
                    </a>
                </li>
                <li class="nav-item">
                    <a href="course.php" class="nav-link">
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
            <h1>Add New Student</h1>
            <p>Create a new student record in the system.</p>
        </div>

        <div class="content-card">
            <div class="card-header">
                <h2>Student Information</h2>
            </div>
            <div class="form-content">
                <form method="POST">
                    <div class="form-group">
                        <label for="name">Student Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter student name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Enter email address" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Save Student</button>
                        <a href="../public/index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>