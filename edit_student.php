<?php
$conn = new mysqli("localhost", "root", "", "student_marks_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student data for editing
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT * FROM students WHERE id = $id");
    $student = $result->fetch_assoc();
}

// Update student data
if ($_POST) {
    $id = $_POST['id'];
    $student_name = $_POST['student_name'];
    $father_name = $_POST['father_name'];
    $roll_number = $_POST['roll_number'];
    $marks = $_POST['marks'];
    $phoneNo = $_POST['phoneNo'];

    $sql = "UPDATE students SET 
            student_name='$student_name', 
            father_name='$father_name', 
            roll_number='$roll_number', 
            marks='$marks', 
            phoneNo='$phoneNo' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_students.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Marks System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="glass-card" data-aos="zoom-in">
            <h2 class="text-center mb-4">Edit Student Record</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $student['id'] ?>">
                
                <div class="mb-3">
                    <label class="form-label">Student Name</label>
                    <input type="text" name="student_name" class="form-control" 
                           value="<?= $student['student_name'] ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Father's Name</label>
                    <input type="text" name="father_name" class="form-control" 
                           value="<?= $student['father_name'] ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Roll Number</label>
                    <input type="text" name="roll_number" class="form-control" 
                           value="<?= $student['roll_number'] ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Marks</label>
                    <input type="number" name="marks" class="form-control" 
                           value="<?= $student['marks'] ?>" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Phone No.</label>
                    <input type="number" name="phoneNo" class="form-control" 
                           value="<?= $student['phoneNo'] ?>" required>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success flex-fill">
                        <i class="bi bi-check-circle"></i> Update
                    </button>
                    <a href="view_students.php" class="btn btn-secondary flex-fill">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
</body>
</html>