<?php
session_start();
//establish variables
$name = $pass = $ele = $stat1 = $stat2 = $stat3 = "";
$range = 0;

//check for empty space
if($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = test_input($_POST["user_name"]);
		$pass = test_input($_POST["password"]);
		$stat1 = test_input($_POST["stat1"]);
		$stat2 = test_input($_POST["stat2"]);
		$stat3 = test_input($_POST["stat3"]);
		$ele = test_input($_POST["element"]);
		$align = test_input($_POST["alignment"]);
	//make sure the stats are within range 
	$range = keep_range($stat1,$stat2,$stat3);
	if ($range < 0){
		header("location:register.html");
	}
	else 
	{
		//start connection
		$servername = "localhost";
		$username = "root";
		$password = "";
		$database = "users";

		// Create connection
		$link = new mysqli($servername, $username, $password, $database);
		
			// Check connection
		if ($link->connect_error) 
		{
			echo "Database connection failed" . $link->connect_error;
		}
		
			//insert userdata into data table
			//encrypt password
			$pass = password_hash($pass,PASSWORD_DEFAULT);
			$sql = "INSERT INTO userdata (username,password,stat1,stat2,stat3,element,alignment,exp,level)
				VALUES ('$name','$pass','$stat1','$stat2','$stat3','$ele','$align',1,1)";
			//run userdata insert query
			if($link->query($sql) === TRUE)
			{
				$_SESSION["u"] = $name;
				$_SESSION["p"] = $pass;
				$_SESSION["s1"] = $stat1;
				$_SESSION["s2"] = $stat2;
				$_SESSION["s3"] = $stat3;
				$_SESSION["e"] = $ele;
				$_SESSION["a"] = $align;
				//$sql = "ALTER TABLE battled ADD COLUMN $name varchar(200)";
				//$link->query($sql);
				header("location:userprofile.php");
				
			}
		
			else 
			{
				echo "Username taken"; //or not submitting
			}
		}
	$link->close();

	}
else 
{
	echo "Method was not POST or was not sent over successfully";
}

//prevent sql injection
function test_input($data) 
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

//validate the ranges
function keep_range($st1,$st2,$st3) 
{
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