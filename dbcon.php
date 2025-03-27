<?php
	$host = "sql212.hstn.me";
	$user = "mseet_37682836";
	$pass = "8YJbfsZEVCdh";	
	$db = "mseet_37682836_attendancemsystem";
	
	$conn = new mysqli($host, $user, $pass, $db);
	if($conn->connect_error){
		echo "Seems like you have not configured the database. Failed To Connect to database:" . $conn->connect_error;
	}
?>