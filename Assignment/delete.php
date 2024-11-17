<?php
include 'config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid student ID.");
}

// Fetch student data
$stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

// Delete the student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (file_exists($student['image'])) {
        unlink($student['image']); // Delete the image file
    }
    $stmt = $conn->prepare("DELETE FROM student WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

</head>
<body>
<div class="container">
    <h1>Delete Student</h1>
    <p>Are you sure you want to delete <?= htmlspecialchars($student['name']) ?>?</p>
    <form method="POST">
        <button type="submit">Yes, Delete</button>
        <a href="index.php">Cancel</a>
    </form>
</div>
</body>
</html>
