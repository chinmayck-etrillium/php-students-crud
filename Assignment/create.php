<?php
include 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $class_id = $_POST['class_id'];
    $image = $_FILES['image'];

    // Validate inputs
    if (empty($name)) {
        $errors[] = 'Name is required.';
    }
    if (!in_array($image['type'], ['image/jpeg', 'image/png'])) {
        $errors[] = 'Invalid image format (only JPG and PNG allowed).';
    }

    if (empty($errors)) {
        // Upload image
        $imagePath = 'uploads/' . uniqid() . '-' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);

        // Insert student
        $stmt = $conn->prepare("INSERT INTO student (name, email, address, class_id, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssds', $name, $email, $address, $class_id, $imagePath);
        $stmt->execute();

        header('Location: index.php');
        exit;
    }
}

// Fetch classes for dropdown
$classResult = $conn->query("SELECT * FROM classes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Student</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Add Student</h1>
    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Address:</label>
        <textarea name="address"></textarea><br>
        <label>Class:</label>
        <select name="class_id">
            <option value="">Select Class</option>
            <?php while ($class = $classResult->fetch_assoc()): ?>
                <option value="<?= $class['class_id'] ?>"><?= $class['name'] ?></option>
            <?php endwhile; ?>
        </select><br>
        <label>Image:</label>
        <input type="file" name="image" required><br>
        <button type="submit">Submit</button>
    </form>
</div>
</body>
</html>
