<?php
include 'config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid student ID.");
}

// Fetch student details
$stmt = $conn->prepare("SELECT student.*, classes.name AS class_name 
                        FROM student 
                        LEFT JOIN classes ON student.class_id = classes.class_id 
                        WHERE student.id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Student not found.");
}

$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h1>View Student</h1>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <img src="<?= $student['image'] ?>" alt="Student Image" class="img-fluid rounded shadow" style="max-width: 100%; height: auto;">
                </div>
                <div class="col-md-8">
                    <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($student['email']) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($student['address']) ?></p>
                    <p><strong>Class:</strong> <?= htmlspecialchars($student['class_name'] ?: 'No Class') ?></p>
                    <p><strong>Created At:</strong> <?= htmlspecialchars($student['created_at']) ?></p>
                </div>
            </div>
            <div class="text-center">
                <a href="index.php" class="btn btn-primary">Back to Home</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

