<?php
session_start();
?>
<html>
<body>
<?php
//Set up credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "users";

// Create connection
$link = new mysqli($servername,$username,$password,$database) or die($mysqli->error);

//set up player 1 and 2 *Legacy code*

//Set up player 2 fetch variables
$a = $_POST['player'];
$b = 0;
//fetch the 2nd player
$player1 = $_SESSION["u"];
$sql = "SELECT * FROM userdata WHERE NOT username = '$player1'"; 
$result = $link->query($sql);
while ($b != $a+1)
{
	$row = $result->fetch_assoc();
	$b += 1;
	$plyr2=$row['username'];
	
}

//Initial display
echo "<h1>Let's see who wins!</h1>";
$player2 = $plyr2;
$p1total =0;
$p2total =0;
$p1e = "";

//Display player 1 stats
$p1 = "SELECT * FROM userdata WHERE username = '$player1'";
$result = $link->query($p1);
while ($row = $result->fetch_assoc()) {
echo "<table>";
echo "<h2>" . $player1 . "</h2>";
echo "<tr>";
echo "<td><h3>" . $row['stat1'] . "</h3></td>" . "<br>";
$pstat = array(0,0,0,0,0,0);
$pstat[0] = $row['stat1']; 
echo "<td><h3>" . $row['stat2'] . "</h3></td>" . "<br>";
$pstat[1] = $row['stat2']; 
echo "<td><h3>" . $row['stat3'] . "</h3></td>" . "<br>";
$pstat[2] = $row['stat3']; 
echo "</tr>";
echo "</table>";
}

//Display player 2 stats
$p2 = "SELECT * FROM userdata WHERE username = '$player2'";
$result = $link->query($p2);
while ($row = $result->fetch_assoc()) {
echo "<table>";
echo "<h2>" . $player2 . "</h2>";
echo "<tr>";
echo "<td><h3>" . $row['stat1'] . "</h3></td>" . "<br>";
$pstat[3] = $row['stat1']; 
echo "<td><h3>" . $row['stat2'] . "</h3></td>" . "<br>";
$pstat[4] = $row['stat2']; 
echo "<td><h3>" . $row['stat3'] . "</h3></td>" . "<br>";
$pstat[5] = $row['stat3']; 
echo "</tr>";
echo "</table>";



//Points not used
$higherStat = -1;
$hS=array(0,0);
for ($i=0;$i<3;$i++)
{	
	//check for player with higher stat
	//player 1
	if ($pstat[$i] > $pstat[$i+3])
	{
		$hS[0]++;
	}
	//player 2
	if ($pstat[$i] < $pstat[$i+3])
	{
		$hS[1]++;
	}
	//draw
	if ($pstat[$i] == $pstat[$i+3])
	{
		continue;
	}
}	
	//handle different types of draws
	if ($hS[0] != $hS[1])
	{
		$higherStat = 1;
	}
	elseif ($hS[0] == 0 && $hS[1] == 0)
	{
		$higherStat = 2;
	}
	elseif ($hS[0] == 1 && $hS[1] == 1)
	{
		$higherStat = 3;
	}
	
//run scoring for winner and loser
switch ($higherStat) 
{
	case 1:
	//add bonus for stat wins

		//outer: see who gets the bonus, inner: determine if large or small bonus is awarded
		if ($hS[0] > $hS[1])
		{
			if ($hS[0] == 3)
			{
				$p1total+=525;
				echo "Player 1 won all 3 stats. +525 points!";
			}
		}
		elseif ($hS[0] < $hS[1]) 
		{
			if ($hS[1] == 3)
			{
				$p2total+=525;
				echo "Player 2 won all 3 stats. +525 points!";
			}
		}
		else {echo "An error occured: line 115";}
		
	echo "<hr>" . "<br>";
	
	//test for alignment bonus
	$alignMult = 0;
	
	//pull the alignment values for each player
	$p1align = "SELECT * FROM userdata WHERE username = '$player1'";
	$result = $link->query($p1align);
	while ($row = $result->fetch_assoc()) 
	{
		$p1al = $row['alignment'];
	}
	
	$p2align = "SELECT * FROM userdata WHERE username = '$player2'";
	$result = $link->query($p2align);
	while ($row = $result->fetch_assoc()) 
	{
		$p2al = $row['alignment'];
	}
	echo $player1 . " Alignment: " . $p1al . "<br>";
	echo $player2 . " Alignment: " . $p2al . "<br>";
	//modify the multiplier based on the alignment values
	if($p1al != $p2al)
	{
		$alignMult = 2;
		echo "Both players have opposite alignments. Unused points are doubled." . "<hr>" . "<br>";
	}
	else
	{
		$alignMult = 1;
	}
	
	//p1 unused points
	for ($i=0;$i<3;$i++)
	{
		$p1total += (150 - $pstat[$i]) * $alignMult;
	}
	//p2 unused points
	for($i=0;$i<3;$i++)
	{	
		$p2total += (150 - $pstat[$i+3]) * $alignMult;
	}
	break;
	
	case 2: //total draw
	break;
	
	case 3: //1 to 1 draw
	for($i=0;$i<3;$i++){
		$p1total += (150 - $pstat[$i+3]);
		$p2total += (150 - $pstat[$i+3]);
	}
	break;
	
	default:
	echo "Something went wrong";
	
}


//Points added for victory

for($i=0; $i<3;$i++)
{
	if ($pstat[$i] == $pstat[$i+3])
	{		
		break;
	}
	else
	{
		//p1 win
		if($pstat[$i]>$pstat[$i+3])
		{
			$p1total += 125;
		}
		//p2 win
		else
		{
			$p2total += 125;	
		}
	}
}


}

//Compare elements
$result = $link->query($p1);
while ($row = $result->fetch_assoc()) {
$p1e = $row['element'];
echo "p1 element: " . $p1e . "<br>";
}
$result = $link->query($p2);
while ($row = $result->fetch_assoc()) {
$p2e = $row['element'];
echo "p2 element: " . $p2e . "<br>";
}

//if elements are a tie
if ($p1e == $p2e)
{
	echo "Elements are the same . <br>";
	echo "No points added";
}

//check for wins and losses
//wins
if($p1e == "Fire" && $p2e == "Ice")
{
	$p1total += 200;
	echo $player1 . " has the winning element +200 points";
}

if($p1e == "Ice" && $p2e == "Earth")
{
	$p1total += 200;
	echo $player1 . " has the winning element +200 points";
}

if($p1e == "Earth" && $p2e == "Fire")
{
	$p1total += 200;
	echo $player1 ." has the winning element +200 points";
}

//loses
if($p2e == "Fire" && $p1e == "Ice")
{
	$p2total += 200;
	echo $player2 . " has the winning element +200 points";
}

if($p2e == "Ice" && $p1e == "Earth")
{
	$p2total += 200;
	echo $player2 . " has the winning element +200 points";
}

if($p2e == "Earth" && $p1e == "Fire")
{
	$p2total += 200;
	echo $player2 . " has the winning element +200 points";
}

//Determine a winner
echo "<br>" . $player1 . ": " . $p1total . "<br>" . $player2 . ": " . $p2total . "<hr>" . "<br>";


//Add exp
$exp = abs($p1total - $p2total);
if($p1total > $p2total)
{
	echo $player1 . " is the winner!";
	$result = $link->query($p1);
	while ($row = $result->fetch_assoc()) 
	{
	$exp += $row['exp'];
	}
	$sql = "UPDATE userdata SET exp = '$exp' WHERE username='$player1'";
	$link->query($sql);

}
if($p1total < $p2total)
{
	echo $player2 . " is the winner!";
	$result = $link->query($p2);
	while ($row = $result->fetch_assoc()) 
	{
	$exp += $row['exp'];
	}
	$sql = "UPDATE userdata SET exp = '$exp' WHERE username='$player2'";
	$link->query($sql);

}
echo "<br>Exp gained: " . abs($p1total - $p2total);
$link->close();

?>
<form action="dashboard.php">
<input type="submit" value="back to dashboard"><br><br>
</form>
</body>
</html>
