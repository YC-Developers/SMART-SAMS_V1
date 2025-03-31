<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
$qm = "SELECT * FROM tbladmin WHERE Id = " . $_SESSION['userId'] . "";
$rs = $conn->query($qm);
$num = $rs->num_rows;
$rows = $rs->fetch_assoc();
$school = $rows['school'];


// Fetch lessons and classes for the dropdowns
$lessons = mysqli_query($conn, "SELECT * FROM lessons WHERE school='$school'  ORDER BY lesson_title ASC");
$classes = mysqli_query($conn, "SELECT * FROM tblclass WHERE school='$school' ORDER BY className ASC");
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['assign_lesson'])) {
  // Handle lesson assignment
  $lessonId = $_POST['lesson_id'];
  $classId = $_POST['classId'];

  // Check if the lesson is already assigned to the class
  $checkQuery = "SELECT * FROM class_lessons WHERE lesson_id = '$lessonId' AND class_id = '$classId'";
  $checkResult = mysqli_query($conn, $checkQuery);

  if (mysqli_num_rows($checkResult) > 0) {
    $statusMsg = "This lesson is already assigned to the selected class.";
  } else {
    // Insert the assignment into the database
    $assignQuery = "INSERT INTO class_lessons (lesson_id, class_id) VALUES ('$lessonId', '$classId')";
    if (mysqli_query($conn, $assignQuery)) {
      $statusMsg = "Lesson assigned successfully.";
    } else {
      $statusMsg = "Error assigning lesson.";
    }
  }
}

// Fetch the assignments for displaying
$assignmentsQuery = "
    SELECT cl.id, lessons.lesson_title, tblclass.className 
    FROM class_lessons cl
    INNER JOIN lessons ON cl.lesson_id = lessons.id
    INNER JOIN tblclass ON cl.class_id = tblclass.Id
    WHERE lessons.school='$school' AND tblclass.school='$school'
";
$assignmentsResult = mysqli_query($conn, $assignmentsQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>AMS - Assign Lessons</title>
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
            <h1 class="h3 mb-0 text-gray-800">Assign Lessons to Classes</h1>
          </div>

          <!-- Status Message -->
          <?php if ($statusMsg != "") { ?>
            <div class="alert alert-info">
              <?php echo $statusMsg; ?>
            </div>
          <?php } ?>

          <!-- Assign Lesson Form -->
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="m-0 font-weight-bold text-primary">Assign Lesson</h6>
            </div>
            <div class="card-body">
              <form method="post">
                <div class="form-group">
                  <label for="lesson_id">Select Lesson</label>
                  <select name="lesson_id" class="form-control" required>
                    <option value="">-- Select a Lesson --</option>
                    <?php
                    while ($lesson = mysqli_fetch_assoc($lessons)) {
                      echo "<option value='{$lesson['id']}'>{$lesson['lesson_title']}</option>";
                    }
                    ?>
                  </select>
                </div>

                <div class="form-group">
                  <label for="classId">Select Class</label>
                  <select name="classId" class="form-control" required>
                    <option value="">-- Select a Class --</option>
                    <?php
                    while ($class = mysqli_fetch_assoc($classes)) {
                      echo "<option value='{$class['Id']}'>{$class['className']}</option>";
                    }
                    ?>
                  </select>
                </div>

                <button type="submit" name="assign_lesson" class="btn btn-primary">Assign Lesson</button>
              </form>
            </div>
          </div>

          <!-- Assigned Lessons Table -->
          <div class="card mb-4">
            <div class="card-header">
              <h6 class="m-0 font-weight-bold text-primary">Assigned Lessons</h6>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Class Name</th>
                    <th>Lesson Title</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sn = 1;
                  while ($row = mysqli_fetch_assoc($assignmentsResult)) {
                    echo "<tr>
                          <td>{$sn}</td>
                          <td>{$row['className']}</td>
                          <td>{$row['lesson_title']}</td>
                      </tr>";
                    $sn++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>