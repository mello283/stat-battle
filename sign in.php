<?php
session_start();
//Set up credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "users";

// Create connection
$link = new mysqli($servername,$username,$password,$database) or die($mysqli->error);

//Start query
$username = $_POST['user_name'];
$password = $_POST['password'];

//prevent sql injection
$username = test_input($username);
$password = test_input($password);

$sql = "SELECT * FROM userdata WHERE username='$username'"; 
$result = $link->query($sql);

//verify username
$num_results =0;
while ($row = $result->fetch_assoc()) {
	if ($row['username'] == $username)
	{
		$num_results++;
	}
	//printf("<br />%s",$row['username']);
}
//verify password
$sql = "SELECT password FROM userdata WHERE username='$username'";
$result = $link->query($sql);
$pass_results=0;
while ($row = $result->fetch_assoc()) {
    $p = $row['password'];
    if(password_verify($password, $p))
	{
	header("location:userprofile.php");
	}
}



if ($num_results == 1) {
	 $_SESSION["u"] = $username;
	 
	 if($pass_results == 1)
	 {
	header("location:userprofile.php");
	 }
	 else
	 {
		 echo "<h1>Unrecognized password. Please try again or register</h1>";
	 }
}
else 
{
	echo "<h1>Unrecognized username. Please try again or register</h1><br>";
}

//prevent sql injection
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

$result->close();
$link->close();
?>