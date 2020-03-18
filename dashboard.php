<?php
session_start();
?>
<html>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.17.0/jquery.validate.min.js"></script>
<?php

echo "<h1>Dashboard</h1>";

//Set up credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "users";

// Create connection
$link = new mysqli($servername,$username,$password,$database) or die($mysqli->error);

//pull users from the database
//this appears in a list format
$user = $_SESSION["u"];
//code to randomly pair the user with the opponent
$sql = "SELECT ID FROM userdata WHERE username = '$user'";
$result = $link->query($sql);
$userID=-1;
while($row = $result->fetch_assoc())
{
	$userID = $row['ID'];
}
$sql = "SELECT ID FROM userdata ORDER BY ID DESC LIMIT 1";
$result = $link->query($sql);
$randMax=0;
while($row = $result->fetch_assoc())
{
	$randMax = $row['ID'];
}
$randOp = rand(1,$randMax);
//prevent the user from battling themselves
while($randOp == $userID)
{
	$randOp = rand(1,$randMax);
}
$_SESSION["user"] = $userID;
$_SESSION["opponent"] = $randOp;
//code to display the list of users *legacy code*

$sql = "SELECT * FROM userdata WHERE NOT username = '$user'"; 
$result = $link->query($sql);
while ($row = $result->fetch_assoc())
{
	echo "<hr>";
	echo $row['username'] . " " . 
	"<form action=\"battle.php\" method=\"post\">"  . 
	"<input class=\"player\" type=\"radio\" value=\"\" name=\"player\"></input>" . "<hr>";
}
echo "<input id=\"submit\" type=\"submit\" value=\"Battle!\" name=\"\"></input>" . "</form>";

//Change the randombot stats
$amt = array(0,0,0);
for($i=0;$i<3;$i++)
{
	$amt[$i] = rand(50,150);
}
$eamt = rand(1,3); //element amount
$aamt = rand(1,2); //alignment amount
//generate stats for the randbot
$sql = "UPDATE userdata SET stat1 = '$amt[0]' WHERE username='randomstatbot'";
$link->query($sql);
$sql = "UPDATE userdata SET stat2 = '$amt[1]' WHERE username='randomstatbot'";
$link->query($sql);
$sql = "UPDATE userdata SET stat3 = '$amt[2]' WHERE username='randomstatbot'";
$link->query($sql);
//generate the element for the randbot
switch ($eamt)
{
	case 1:$sql = "UPDATE userdata SET element = 'Fire' WHERE username='randomstatbot'";
		   $link->query($sql);
		   break;
	case 2:$sql = "UPDATE userdata SET element = 'Ice' WHERE username='randomstatbot'";
		   $link->query($sql);
		   break;
	case 3:$sql = "UPDATE userdata SET element = 'Earth' WHERE username='randomstatbot'";
		   $link->query($sql);
		   break;
}
//generate the alignment for the randbot
switch($aamt)
{
    case 1:$sql = "UPDATE userdata SET alignment = 'Light' WHERE username='randomstatbot'";
        break;
    case 2:$sql = "UPDATE userdata SET alignment = 'Dark' WHERE username='randomstatbot'";
        break;
}
?>
<script>

$('.player').each(function (i) {
    $(this).attr('value', i);
});

</script>

<form action="userprofile.php">
<input type="submit" value="Back to profile"><br>
</form>
</body>
</html>

