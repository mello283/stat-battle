<?php
session_start();

//pull input
$s1 = test_input($_POST["stat1"]);
$s2 = test_input($_POST["stat2"]);
$s3 = test_input($_POST["stat3"]);
$ele = test_input($_POST["element"]);
$align = test_input($_POST["alignment"]);


//make sure the stats are within range 
$range = keep_range($s1,$s2,$s3);
if ($range < 0){
	header("location:userprofile.php");
}
else{
	//Set up credentials
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "users";

	// Create connection
	$link = new mysqli($servername,$username,$password,$database) or die($mysqli->error);
	
	//update database
	$user = $_SESSION["u"];
	$sql = "UPDATE userdata SET stat1 = '$s1',stat2 = '$s2',stat3 = '$s3',element = '$ele',alignment='$align' WHERE username='$user'";
			if($link->query($sql) === TRUE){
				header("location:userprofile.php");
			}
			else{
				echo "Failed to update stats";
			}
		
			$link->close();
	}

//prevent sql injection
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

//validate the ranges
function keep_range($st1,$st2,$st3) {
	if ($st1 < 50 || $st1 > 150) {
		echo "Stat 1 is out of range<br>";
		$st1 = -1;
	}
	if ($st2 < 50 || $st2 > 150) {
		echo "Stat 2 is out of range<br>";
		$st2 = -1;
	}
	if ($st3 < 50 || $st3 > 150) {
		echo "Stat 3 is out of range<br>";
		$st3 = -1;
	}
	if ($st1 < 0 || $st2 < 0 || $st3 < 0) {
		$st1 = $st2 = $st3 = -1;
		return $st1 + $st2 + $st3;
	}
	else {
		return $st1 + $st2 + $st3;
	}
}
?>