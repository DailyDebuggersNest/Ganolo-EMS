<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM curriculum WHERE id = ?");
    $stmt->execute([$id]);
}

// Redirect back to curriculum page
header('Location: ../pages/curriculum.php');
exit;
?>