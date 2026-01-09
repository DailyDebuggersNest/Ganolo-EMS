<?php
require '../config/db.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode([]);
    exit;
}

$student_id = $_GET['id'];

try {
    $stmt = $pdo->prepare("
        SELECT c.subject_code, c.description, e.enrolled_at
        FROM enrollments e
        JOIN curriculum c ON e.subject_id = c.CurriculumID
        WHERE e.student_id = ?
        ORDER BY e.enrolled_at DESC
    ");
    $stmt->execute([$student_id]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($history);

} catch (PDOException $e) {
    // In a real app, you might log this error instead of outputting it
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>