<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM course WHERE courseID = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: ../pages/courses.php?status=deleted');
    } catch (PDOException $e) {
        // If deletion fails (likely because students are enrolled in it), show error
        die("Error: Cannot delete this course because it is being used by curriculum subjects. Please delete those subjects first.");
    }
} else {
    header('Location: ../pages/courses.php');
}
exit;