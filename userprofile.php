<?php
session_start();
//Set up credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "users";

// Create connection
$link = new mysqli($servername,$username,$password,$database) or die($mysqli->error);

?>

<!DOCTYPE html>
<html>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<h1>Welcome to your profile!</h1>

<?php 
echo "Here are your stats " . $_SESSION["u"] . "." . "<br>";

//pull associated information from database
$username = $_SESSION["u"];
$sql = "SELECT * FROM userdata WHERE username='$username'";
$result = $link->query($sql);
while ($row = $result->fetch_assoc()) {
    $exp = $row['exp'];
	$remaining = 450-($row['stat1'] + $row['stat2'] + $row['stat3']);
	echo "Stat 1: " . $row['stat1'] . "<br>";
	echo "Stat 2: " . $row['stat2'] . "<br>";
	echo "Stat 3: " . $row['stat3'] . "<br>";
	echo "Element: " . $row['element'] . "<br>";
	echo "Alignment: ". $row['alignment'] . "<br>";
	echo "Remaining points: " . $remaining . "<br>";
	echo "Exp: " . $row['exp'] . "<br>";
	echo "Level: " . $row['level'] . "<br>";
	
}

//Leveling system
$levelUp = false;
$sql = "SELECT level FROM userdata WHERE  username = '$username'"; 
$result = $link->query($sql);
while ($row = $result->fetch_assoc())
{
	$levelUp = true;
	$level = $row['level'];
	if ($exp > ($level * 100))
	{
		echo "<h5><a href=".">You reached a new level click here!</a></h5>";
		$exp = 0;
		
		$level += 1;
	}
}

//Update database from levelling 
if ($levelUp = true){
$sql = "UPDATE userdata SET exp = '0' WHERE username = '$username'"; 
$link->query($sql);
$sql = "UPDATE userdata SET level = '$level' WHERE username = '$username'"; 
$link->query($sql);
}
?>

<form action="dashboard.php">
<input type="submit" value="Dashboard">
</form>

<form action="change stats.php" method="POST">
<h3>Change stats  (To change stats you must have something typed in all the boxes)</h3>
<input id="stat" type="text" name="stat1">
<input id="stat" type="text" name="stat2">
<input id="stat" type="text" name="stat3"><br>
<h3>Alignment: </h3>
<input id="align" type="radio" name="alignment" value="Light" checked="checked">Light</input><br>
<input id="align" type="radio" name="alignment" value="Dark">Dark</input><br>
<h3>Element: </h3>
<input id="ele" type="radio" name="element" value="Fire" checked="checked">Fire</input><br>
<input id="ele" type="radio" name="element" value="Ice">Ice</input><br>
<input id="ele" type="radio" name="element" value="Earth">Earth</input><br>
<input id="submit" type="submit" value="Change stats">
</form>
<form action="sign in.html"><br><br>
<input type="submit" value="Log out">
</form>
<script>
$(document).ready(function(){
    $("#flip").click(function(){
        $("#panel").slideToggle("slow");
    });
});

</script>
<div id="flip" style="color:blue">Click here to hide this box.</div>
<div id="panel" style="background-color:#aaaaaa">The 3 numbers at the the top are your stats. You can have a
range from 50 to 150. Each stat will be compared side by side. Winning 2 out of 3 will add a 200 point bonus and all 3 a 525 point bonus.
The elements are like a game of rock paper scissors. 
Fire beats Ice and Ice beats Earth and Earth beats Fire. 
For each point not put toward a stat you will be given that many points 
toward your victory total. 
If you have more points in your stat than the opponent then you get 125 points. 
An Elemental victory is 200 points. Experience gained is 
equal to your total minus the opposing player's total. Each level is 100 points times the level you are on.
Alignment handles your unused points. If you and your opponent have opposite alignments then the points from your unused points is doubled. If they are the same then the amount stays the same. 
</div>
</body>
</html>