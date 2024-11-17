<?php
include 'config.php';
$query = "SELECT student.id, student.name, student.email, student.created_at, student.image, classes.name AS class_name 
          FROM student 
          LEFT JOIN classes ON student.class_id = classes.class_id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-4">Student List</h1>
    <a href="create.php" class="btn btn-primary mb-3">Add Student</a>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Class</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['class_name']) ?: 'No Class' ?></td>
                <td><img src="<?= $row['image'] ?>" alt="Image" style="width: 50px;"></td>
                <td>
                    <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm">View</a>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
