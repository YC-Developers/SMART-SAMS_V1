<?php
error_reporting(0);

include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

// Save Lesson
if (isset($_POST['save'])) {
  $teacherId = $_POST['teacher_id'];
  $lessonTitle = $_POST['lesson_title'];
  $lessonDescription = $_POST['lesson_description'];

  $query = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'] . "";
  $rs = $conn->query($query);
  $num = $rs->num_rows;
  $rows = $rs->fetch_assoc();
  $school = $rows['school'];


  $query = mysqli_query($conn, "INSERT INTO lessons (teacher_id, lesson_title, lesson_description, school) 
        VALUES ('$teacherId', '$lessonTitle', '$lessonDescription', '$school')");

  if ($query) {
    $statusMsg = "<div class='alert alert-success'>Lesson added successfully!</div>";
  } else {
    $statusMsg = "<div class='alert alert-danger'>An error occurred!</div>";
  }
}

// Edit Lesson
if (isset($_GET['Id']) && $_GET['action'] == "edit") {
  $lessonId = $_GET['Id'];
  $query = mysqli_query($conn, "SELECT * FROM lessons WHERE id = '$lessonId'");
  $lesson = mysqli_fetch_assoc($query);

  if (isset($_POST['update'])) {
    $lessonTitle = $_POST['lesson_title'];
    $lessonDescription = $_POST['lesson_description'];

    $query = mysqli_query($conn, "UPDATE lessons SET 
            lesson_title = '$lessonTitle', 
            lesson_description = '$lessonDescription'
            WHERE id = '$lessonId'");

    if ($query) {
      header("Location: lessons.php");
    } else {
      $statusMsg = "<div class='alert alert-danger'>An error occurred!</div>";
    }
  }
}

// Delete Lesson
if (isset($_GET['Id']) && $_GET['action'] == "delete") {
  $lessonId = $_GET['Id'];
  $query = mysqli_query($conn, "DELETE FROM lessons WHERE id = '$lessonId'");

  if ($query) {
    header("Location: lessons.php");
  } else {
    $statusMsg = "<div class='alert alert-danger'>An error occurred!</div>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="../img/logo/attnlg.jpg" rel="icon">
  <title>AMS - Createteachers</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
        <?php include "Includes/topbar.php"; ?>
        <!-- Topbar -->

        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Lessons</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Lessons</li>
            </ol>
          </div>
          <div class="container mt-4">
            <?php echo $statusMsg; ?>

            <form method="post">
              <div class="form-group">
                <label for="teacher_id">Select Teacher</label>
                <select name="teacher_id" class="form-control" required>
                  <option value="">-- Select Teacher --</option>
                  <?php
                  $teachers = mysqli_query($conn, "SELECT * FROM tblteachers WHERE school='$s' ORDER BY name ASC");
                  while ($teacher = mysqli_fetch_assoc($teachers)) {
                    echo "<option value='" . $teacher['id'] . "'>" . $teacher['name'] . "</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="form-group">
                <label for="lesson_title">Lesson Title</label>
                <input type="text" name="lesson_title" class="form-control" value="<?php echo $lesson['lesson_title']; ?>" required>
              </div>

              <div class="form-group">
                <label for="lesson_description">Lesson Description</label>
                <textarea name="lesson_description" class="form-control" rows="3" value="<?php echo $lesson['lesson_description']; ?>"></textarea>
              </div>

              <button type="submit" name="save" class="btn btn-primary">Add Lesson</button>
            </form>

            <hr>

            <div class="row">
              <div class="col-lg-12">
                <div class="card mb-4">
                  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">All Lessons</h6>
                  </div>
                  <div class="table-responsive p-3">
                    <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                      <thead class="thead-light">
                        <tr>
                          <th>#</th>
                          <th>Teacher</th>
                          <th>Lesson Title</th>
                          <th>Description</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $lessons = mysqli_query(
                          $conn,
                          "SELECT lessons.*, tblteachers.name AS teacher_name FROM lessons 
     INNER JOIN tblteachers ON lessons.teacher_id = tblteachers.id 
     WHERE lessons.school='$school'"
                        );
                        while ($lesson = mysqli_fetch_assoc($lessons)) {
                          echo "<tr>
                        <td>" . $sn++ . "</td>
                        <td>" . $lesson['teacher_name'] . "</td>
                        <td>" . $lesson['lesson_title'] . "</td>
                        <td>" . $lesson['lesson_description'] . "</td>
                        <td>
                            <a href='?action=edit&Id=" . $lesson['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='?action=delete&Id=" . $lesson['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                    </tr>";
                        }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      <?php include "Includes/footer.php"; ?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function() {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>