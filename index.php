<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Marks System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.3.1/css/hover-min.css" rel="stylesheet">
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
      <a href="view_students.php" class="btn btn-info">
        <i class="bi bi-list"></i> View All Students
      </a>
    </div>

    <!-- Add Student Form -->
    <div class="glass-card" data-aos="zoom-in">
      <h2 class="text-center mb-4">
        <i class="bi bi-person-plus"></i> Add Student Record
      </h2>
      
      <!-- Success Message -->
      <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle"></i> Student record added successfully!
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <form method="POST" action="add_student.php">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">
              <i class="bi bi-person"></i> Student Name
            </label>
            <input type="text" name="student_name" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">
              <i class="bi bi-person-check"></i> Father's Name
            </label>
            <input type="text" name="father_name" class="form-control" required>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">
              <i class="bi bi-hash"></i> Roll Number
            </label>
            <input type="text" name="roll_number" class="form-control" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">
              <i class="bi bi-award"></i> Marks
            </label>
            <input type="number" name="marks" class="form-control" min="0" max="100" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">
              <i class="bi bi-telephone"></i> Phone No.
            </label>
            <input type="tel" name="phoneNo" class="form-control" required>
          </div>
        </div>
        
        <button type="submit" class="btn btn-primary w-100 btn-hover-glow">
          <i class="bi bi-plus-circle"></i> Add Student
        </button>
      </form>
    </div>

    <!-- Recent Records Preview -->
    <div class="mt-5" data-aos="fade-up">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-white">
          <i class="bi bi-clock-history"></i> Recent Student Records
        </h3>
        <a href="view_students.php" class="btn btn-outline-light">
          <i class="bi bi-eye"></i> View All
        </a>
      </div>
      
      <div class="glass-card">
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
              </tr>
            </thead>
            <tbody>
              <?php
              $conn = new mysqli("localhost", "root", "", "student_marks_system");
              if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
              }
              
              $result = $conn->query("SELECT * FROM students ORDER BY id DESC LIMIT 5");
              
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
              </tr>
              <?php 
                endwhile;
              else:
              ?>
              <tr>
                <td colspan="6" class="text-center">No student records found.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
</body>
</html>