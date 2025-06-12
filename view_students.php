<?php
// Handle Reset Auto Increment
if (isset($_GET['reset_id']) && $_GET['reset_id'] == 'true') {
    $conn = new mysqli("localhost", "root", "", "student_marks_system");
    
    if ($conn->connect_error) {
        header("Location: view_students.php?error=connection_failed");
        exit();
    }
    
    // Reset AUTO_INCREMENT to 1
    $reset_sql = "ALTER TABLE students AUTO_INCREMENT = 1";
    
    if ($conn->query($reset_sql) === TRUE) {
        $conn->close();
        header("Location: view_students.php?message=reset_success");
        exit();
    } else {
        $conn->close();
        header("Location: view_students.php?error=reset_failed");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students - Student Marks System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Navigation -->
        <div class="text-center mb-4" data-aos="fade-down">
            <a href="index.php" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Add New Student
            </a>
            <a href="view_students.php" class="btn btn-info me-2">
                <i class="bi bi-list"></i> View All Students
            </a>
            <button type="button" class="btn btn-warning" onclick="resetAutoIncrement()">
                <i class="bi bi-arrow-clockwise"></i> Reset ID Counter
            </button>
        </div>

        <!-- Search Box -->
        <div class="glass-card mb-4" data-aos="fade-right">
            <h4 class="text-center mb-3">Search Students</h4>
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Search by student name..." 
                       value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-search"></i> Search
                </button>
                <?php if (isset($_GET['search'])): ?>
                    <a href="view_students.php" class="btn btn-secondary ms-2">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php if ($_GET['message'] == 'deleted'): ?>
                    Student record deleted successfully!
                <?php elseif ($_GET['message'] == 'reset_success'): ?>
                    ID counter has been reset successfully! The next student will get ID 1 (or the next available number).
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php if ($_GET['error'] == 'delete_failed'): ?>
                    Failed to delete student record!
                <?php elseif ($_GET['error'] == 'reset_failed'): ?>
                    Failed to reset ID counter! Please try again.
                <?php elseif ($_GET['error'] == 'connection_failed'): ?>
                    Database connection failed! Please check your database settings.
                <?php endif; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Students Table -->
        <div class="glass-card" data-aos="fade-up">
            <h3 class="text-center mb-4">
                <?php if (isset($_GET['search'])): ?>
                    Search Results for "<?= htmlspecialchars($_GET['search']) ?>"
                <?php else: ?>
                    All Student Records
                <?php endif; ?>
            </h3>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-dark">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Father's Name</th>
                            <th>Roll Number</th>
                            <th>Marks</th>
                            <th>Phone No.</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli("localhost", "root", "", "student_marks_system");
                        
                        // Search functionality
                        $search_condition = "";
                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                            $search = $conn->real_escape_string($_GET['search']);
                            $search_condition = "WHERE student_name LIKE '%$search%' 
                                               OR father_name LIKE '%$search%' 
                                               OR roll_number LIKE '%$search%'";
                        }
                        
                        $sql = "SELECT * FROM students $search_condition ORDER BY id DESC";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0):
                            while($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['student_name']) ?></td>
                            <td><?= htmlspecialchars($row['father_name']) ?></td>
                            <td><?= htmlspecialchars($row['roll_number']) ?></td>
                            <td>
                                <span class="badge <?= $row['marks'] >= 80 ? 'bg-success' : ($row['marks'] >= 60 ? 'bg-warning' : 'bg-danger') ?>">
                                    <?= $row['marks'] ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($row['phoneNo']) ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="edit_student.php?id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit Student">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['student_name']) ?>')"
                                            title="Delete Student">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php 
                            endwhile;
                        else:
                        ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                <?php if (isset($_GET['search'])): ?>
                                    No students found matching your search.
                                <?php else: ?>
                                    No student records found.
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Statistics -->
            <?php if ($result->num_rows > 0): ?>
            <div class="mt-3 text-center">
                <small class="text-muted">
                    Total Records: <?= $result->num_rows ?>
                </small>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Confirm Delete</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-white">
                    Are you sure you want to delete the record for <strong id="studentName"></strong>?
                    <br><small class="text-muted">This action cannot be undone.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset ID Confirmation Modal -->
    <div class="modal fade" id="resetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title text-white">Confirm Reset ID Counter</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-white">
                    Are you sure you want to reset the ID counter?
                    <br><small class="text-muted">This will make the next student ID start from 1 (or the next available number).</small>
                    <br><small class="text-warning">Note: Existing student IDs will remain unchanged.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="view_students.php?reset_id=true" class="btn btn-warning">
                        <i class="bi bi-arrow-clockwise"></i> Reset Counter
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
        
        function confirmDelete(id, name) {
            document.getElementById('studentName').textContent = name;
            document.getElementById('confirmDeleteBtn').href = 'delete_student.php?id=' + id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
        
        function resetAutoIncrement() {
            new bootstrap.Modal(document.getElementById('resetModal')).show();
        }
    </script>
</body>
</html>