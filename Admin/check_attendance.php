<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

header('Content-Type: application/json');

if(isset($_POST['className']) && isset($_POST['mode']) && isset($_POST['date'])) {
    $className = $_POST['className'];
    $mode = $_POST['mode'];
    $date = $_POST['date'];
    
//school
    $qm = "SELECT * FROM tbladmin WHERE Id = ".$_SESSION['userId']."";
    $rs = $conn->query($qm);
    $num = $rs->num_rows;
    $rows = $rs->fetch_assoc();
    $school = $rows['school'];

    // Check if attendance already exists for this class, mode and date
    $query = "SELECT COUNT(*) as count FROM atdetails 
              WHERE className = ? AND mode = ? AND school = ? AND date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss",$className, $mode,$school,$date);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo json_encode(['taken' => $row['count'] > 0]);
} else {
    echo json_encode(['taken' => false]);
}
?>