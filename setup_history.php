<?php
require 'config/db.php';

try {
    // 1. Add Columns if they don't exist
    $pdo->exec("ALTER TABLE enrollments ADD COLUMN grade VARCHAR(5) DEFAULT NULL");
    $pdo->exec("ALTER TABLE enrollments ADD COLUMN schedule_id VARCHAR(50) DEFAULT 'SCH-DEFAULT'");
    echo "Database columns added successfully.<br>";

    // 2. Create a "Perfect Student" for demonstration
    // Check if he exists first
    $stmt = $pdo->prepare("SELECT id FROM students WHERE lastname = 'Perfect' AND firstname = 'Student'");
    $stmt->execute();
    $student = $stmt->fetch();

    if (!$student) {
        // Create the student
        $pdo->exec("INSERT INTO students (lastname, firstname, middlename, age, email, phone) 
                    VALUES ('Perfect', 'Student', 'A.', 20, 'perfect@student.com', '09123456789')");
        $student_id = $pdo->lastInsertId();
    } else {
        $student_id = $student['id'];
    }

    // 3. Clear his existing enrollments to avoid duplicates during testing
    $stmt = $pdo->prepare("DELETE FROM enrollments WHERE student_id = ?");
    $stmt->execute([$student_id]);

    // 4. Enroll him in a full 1st Year, 1st Sem load (WITH GRADES = COMPLETED)
    // We assume some curriculum items exist. We will fetch 3 items to be his "Completed" sem.
    $curriculum_1 = $pdo->query("SELECT CurriculumID FROM curriculum LIMIT 3")->fetchAll();
    
    foreach ($curriculum_1 as $c) {
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id, enrolled_at, grade, schedule_id) VALUES (?, ?, NOW(), '1.25', 'MWF 8-9AM')");
        $stmt->execute([$student_id, $c['CurriculumID']]);
    }

    // 5. Enroll him in 1st Year, 2nd Sem (NO GRADES = IN PROGRESS)
    // We fetch 2 other items
    $curriculum_2 = $pdo->query("SELECT CurriculumID FROM curriculum LIMIT 2 OFFSET 3")->fetchAll();
    foreach ($curriculum_2 as $c) {
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, subject_id, enrolled_at, grade, schedule_id) VALUES (?, ?, NOW(), NULL, 'TTH 10-12PM')");
        $stmt->execute([$student_id, $c['CurriculumID']]);
    }

    echo "Perfect Student (ID: $student_id) created with History data!";

} catch (PDOException $e) {
    // Ignore error if column already exists
    echo "Note: " . $e->getMessage();
}
?>