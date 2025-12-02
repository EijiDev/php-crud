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

// Load enrollment data if editing
if ($isEdit) {
    $current = $enrollmentController->readOne($_GET['id']);
    if (!$current) {
        header("Location: ../public/index.php");
        exit;
    }
}

// Load students and courses for dropdowns
$students = $studentController->readAll();
$courses  = $courseController->readAll();

// Initialize error array
$errors = [];

// Handle form submission
if ($_POST) {
    $student_id = $_POST['student_id'] ?? null;
    $course_id  = $_POST['course_id']  ?? null;
    $enrollment_date = $_POST['enrollment_date'] ?? null;

    // Validation
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
    <title><?= $isEdit ? 'Edit' : 'Add New' ?> Enrollment</title>
    <style>
        body { font-family: Arial, sans-serif; margin:20px; background:#f4f4f4; }
        .container { max-width:600px; margin:0 auto; background:white; padding:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
        .form-group { margin-bottom:15px; }
        label { display:block; margin-bottom:5px; font-weight:bold; }
        input[type="text"], input[type="date"], select { width:100%; padding:10px; border:1px solid #ddd; border-radius:4px; box-sizing:border-box; }
        .readonly { background:#e9ecef; }
        button { padding:10px 20px; background:<?= $isEdit ? '#28a745' : '#007bff' ?>; color:white; border:none; border-radius:4px; cursor:pointer; }
        button:hover { opacity:0.9; }
        .back-link { display:inline-block; margin-top:15px; color:#007bff; text-decoration:none; }
        .errors { background:#ffe6e6; color:#900; padding:10px; margin-bottom:15px; border-radius:4px; }
    </style>
</head>
<body>
<div class="container">
    <h2><?= $isEdit ? 'Edit' : 'Add New' ?> Enrollment</h2>

    <?php if (!empty($errors)): ?>
        <div class="errors">
            <ul>
                <?php foreach ($errors as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

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
                    <?php while ($s = $students->fetch(PDO::FETCH_ASSOC)): ?>
                        <option value="<?= (int)$s['id'] ?>">
                            <?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['email']) ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="course_id">Select Course:</label>
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
