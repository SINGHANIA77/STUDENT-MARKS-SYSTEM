<?php
$conn = new mysqli("localhost", "root", "", "student_marks_system");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$student_name = $conn->real_escape_string($_POST['student_name']);
$father_name = $conn->real_escape_string($_POST['father_name']);
$roll_number = $conn->real_escape_string($_POST['roll_number']);
$marks = $conn->real_escape_string($_POST['marks']);
$phoneNo = $conn->real_escape_string($_POST['phoneNo']);

// Check if roll number already exists
$check_sql = "SELECT * FROM students WHERE roll_number = '$roll_number'";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows > 0) {
    header("Location: index.php?error=duplicate_roll");
    exit();
}

$sql = "INSERT INTO students (student_name, father_name, roll_number, marks, phoneNo)
        VALUES ('$student_name', '$father_name', '$roll_number', '$marks','$phoneNo')";

if ($conn->query($sql) === TRUE) {
  header("Location: index.php?success=1");
} else {
  header("Location: index.php?error=failed");
}

$conn->close();
?>