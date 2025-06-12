<?php
$conn = new mysqli("localhost", "root", "", "student_marks_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM students WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        // Auto-reset AUTO_INCREMENT after deletion
        $result = $conn->query("SELECT MAX(id) as max_id FROM students");
        $row = $result->fetch_assoc();
        $max_id = $row['max_id'];
        
        if ($max_id === null) {
            // If table is empty, reset to 1
            $next_id = 1;
        } else {
            // Set next ID to max_id + 1
            $next_id = $max_id + 1;
        }
        
        // Reset AUTO_INCREMENT
        $conn->query("ALTER TABLE students AUTO_INCREMENT = $next_id");
        
        header("Location: view_students.php?message=deleted");
    } else {
        header("Location: view_students.php?error=delete_failed");
    }
} else {
    header("Location: view_students.php");
}

$conn->close();
?>