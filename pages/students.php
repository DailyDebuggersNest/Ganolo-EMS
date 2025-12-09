<?php
require '../config/db.php';
$pageTitle = 'Students';

// Fetch students
$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container">
    <h1 class="mb-4">Students</h1>
    <a href="../actions/add_student.php" class="btn btn-primary mb-3">Add Student</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Date of Birth</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['id']; ?></td>
                    <td><?php echo htmlspecialchars($student['firstname']); ?></td>
                    <td><?php echo htmlspecialchars($student['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                    <td><?php echo htmlspecialchars($student['phone']); ?></td>
                    <td><?php echo $student['date_of_birth']; ?></td>
                    <td>
                        <a href="../actions/edit_student.php?id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="../actions/delete_student.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>