<?php
require '../config/db.php';
$pageTitle = 'Enrollments';

// Fetch enrollments with joins
$stmt = $pdo->query("SELECT e.id, s.firstname, s.lastname, c.title, e.enrolled_at FROM enrollments e JOIN students s ON e.student_id = s.id JOIN courses c ON e.course_id = c.id ORDER BY e.enrolled_at DESC");
$enrollments = $stmt->fetchAll();
?>

<?php include '../includes/sidebar.php'; ?>
<div class="container">
    <h1 class="mb-4">Enrollments</h1>
    <a href="../actions/add_enrollment.php" class="btn btn-primary mb-3">Add Enrollment</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Course</th>
                <th>Enrolled At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($enrollments as $enrollment): ?>
                <tr>
                    <td><?php echo $enrollment['id']; ?></td>
                    <td><?php echo htmlspecialchars($enrollment['firstname'] . ' ' . $enrollment['lastname']); ?></td>
                    <td><?php echo htmlspecialchars($enrollment['title']); ?></td>
                    <td><?php echo $enrollment['enrolled_at']; ?></td>
                    <td>
                        <a href="../actions/edit_enrollment.php?id=<?php echo $enrollment['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="../actions/delete_enrollment.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $enrollment['id']; ?>">
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