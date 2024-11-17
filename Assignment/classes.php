<?php
include 'config.php';

// Handle adding a class
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        header('Location: classes.php');
        exit;
    }
}

// Fetch all classes
$classResult = $conn->query("SELECT * FROM classes");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Classes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1>Manage Classes</h1>
    <form method="POST">
        <label>Class Name:</label>
        <input type="text" name="name" required>
        <button type="submit">Add Class</button>
    </form>
    <h2>Existing Classes</h2>
    <ul>
        <?php while ($class = $classResult->fetch_assoc()): ?>
            <li><?= htmlspecialchars($class['name']) ?> <a href="edit_class.php?id=<?= $class['class_id'] ?>">Edit</a> | 
            <a href="delete_class.php?id=<?= $class['class_id'] ?>">Delete</a></li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
