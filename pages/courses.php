<?php
require '../config/db.php';
$pageTitle = 'Courses';

// Fetch courses
$stmt = $pdo->query("SELECT * FROM courses ORDER BY created_at DESC");
$courses = $stmt->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container">
    <h1 class="mb-4">Courses</h1>
    <a href="../actions/add_course.php" class="btn btn-primary mb-3">Add Course</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Credits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo $course['id']; ?></td>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td><?php echo htmlspecialchars($course['description']); ?></td>
                    <td><?php echo $course['credits']; ?></td>
                    <td>
                        <a href="../actions/edit_course.php?id=<?php echo $course['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="../actions/delete_course.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
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