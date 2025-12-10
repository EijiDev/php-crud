<?php
// views/enrollment.php
require_once __DIR__ . '/../controller/EnrollmentController.php';
require_once __DIR__ . '/../controller/StudentController.php';
require_once __DIR__ . '/../controller/CourseController.php';

$enrollmentController = new EnrollmentController();
$studentController = new StudentController();
$courseController  = new CourseController();

$isEdit = isset($_GET['id']);
$current = null;

if ($isEdit) {
    $current = $enrollmentController->readOne($_GET['id']);
    if (!$current) {
        header("Location: ../public/index.php");
        exit;
    }
}

$students = $studentController->readAll();
$courses  = $courseController->readAll();
$errors = [];

if ($_POST) {
    $student_id = $_POST['student_id'] ?? null;
    $course_id  = $_POST['course_id']  ?? null;
    $enrollment_date = $_POST['enrollment_date'] ?? null;

    if (!$isEdit && empty($student_id)) {
        $errors[] = 'Please select a student.';
    }

    if (empty($course_id)) {
        $errors[] = 'Please select a course.';
    }

    if (empty($enrollment_date)) {
        $errors[] = 'Please enter an enrollment date.';
    }

    if (empty($errors)) {
        $student_id = $student_id !== null ? (int)$student_id : null;
        $course_id  = $course_id  !== null ? (int)$course_id  : null;

        if ($isEdit) {
            $enrollmentController->update((int)$_POST['id'], $course_id, $enrollment_date);
        } else {
            $enrollmentController->create($student_id, $course_id, $enrollment_date);
        }

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
    <title>CampusConnect - <?= $isEdit ? 'Edit' : 'Add' ?> Enrollment</title>
    <link rel="stylesheet" href="../public/css/enrollment.css">
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
                    <a href="course.php" class="nav-link">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Courses
                    </a>
                </li>
                <li class="nav-item">
                    <a href="enrollment.php" class="nav-link active">
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
            <h1><?= $isEdit ? 'Edit' : 'Add New' ?> Enrollment</h1>
            <p><?= $isEdit ? 'Update enrollment information.' : 'Enroll a student in a course.' ?></p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="content-card">
            <div class="card-header">
                <h2>Enrollment Details</h2>
            </div>
            <div class="form-content">
                <form method="POST">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($current['id']) ?>">
                        <div class="form-group">
                            <label>Student Name</label>
                            <input type="text" value="<?= htmlspecialchars($current['student_name']) ?>" class="readonly" readonly>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label for="student_id">Select Student</label>
                            <select id="student_id" name="student_id" required>
                                <option value="">-- Select Student --</option>
                                <?php while ($s = $students->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?= (int)$s['id'] ?>">
                                        <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['email']) ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="course_id">Select Course</label>
                        <select id="course_id" name="course_id" required>
                            <option value="">-- Select Course --</option>
                            <?php while ($c = $courses->fetch(PDO::FETCH_ASSOC)): ?>
                                <?php
                                    $selected = '';
                                    if ($isEdit && isset($current['course_id']) && (int)$current['course_id'] === (int)$c['id']) {
                                        $selected = 'selected';
                                    }
                                ?>
                                <option value="<?= (int)$c['id'] ?>" <?= $selected ?>><?= htmlspecialchars($c['course_name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="enrollment_date">Enrollment Date</label>
                        <input type="date" id="enrollment_date" name="enrollment_date"
                               value="<?= $isEdit ? htmlspecialchars($current['enrollment_date']) : date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <?= $isEdit ? 'Update' : 'Save' ?> Enrollment
                        </button>
                        <a href="../public/index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>