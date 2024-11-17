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

// Fetch classes for dropdown
$classResult = $conn->query("SELECT * FROM classes");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $image = $_FILES['image'];
    $imagePath = $student['image'];

    if (!empty($image['name'])) {
        if (!in_array($image['type'], ['image/jpeg', 'image/png'])) {
            die("Invalid image format (only JPG and PNG allowed).");
        }

        // Upload new image and delete old one
        $imagePath = 'uploads/' . uniqid() . '-' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);
        if (file_exists($student['image'])) {
            unlink($student['image']);
        }
    }

    // Update student
    $stmt = $conn->prepare("UPDATE student SET name = ?, email = ?, address = ?, class_id = ?, image = ? WHERE id = ?");
    $stmt->bind_param('sssisi', $name, $email, $address, $class_id, $imagePath, $id);
    $stmt->execute();

    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header text-center">
            <h1>Edit Student</h1>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($student['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address:</label>
                    <textarea class="form-control" id="address" name="address" rows="3"><?= htmlspecialchars($student['address']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="class_id" class="form-label">Class:</label>
                    <select class="form-select" id="class_id" name="class_id">
                        <option value="">Select Class</option>
                        <?php while ($class = $classResult->fetch_assoc()): ?>
                            <option value="<?= $class['class_id'] ?>" <?= $class['class_id'] == $student['class_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image:</label>
                    <input type="file" class="form-control" id="image" name="image">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block">Update</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center">
            <a href="index.php" class="btn btn-secondary mt-3">Back to Home</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

