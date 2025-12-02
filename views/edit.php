<?php
require_once __DIR__ . '/../controllers/StudentController.php';
$controller = new StudentController();

if ($_POST) {
    $controller->update($_POST['id'], $_POST['name'], $_POST['email']);
    header("Location: ../public/index.php");
    exit;
}

$id = $_GET['id'];
$current = $controller->readOne($id);

if (!$current) {
    header("Location: ../public/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
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
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
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
        <h2>Edit Student</h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($current['id']) ?>">
            <div class="form-group">
                <label for="name">Student Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($current['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($current['email']) ?>" required>
            </div>
            <button type="submit">Update Student</button>
        </form>
        <a href="../public/index.php" class="back-link">← Back to List</a>
    </div>
</body>
</html>