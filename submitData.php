<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);


$distance = $_GET['distance'];
$setupDistance = $_GET['setup'];

// set default timezone
date_default_timezone_set('America/Chicago'); // CDT

$info = getdate();
$date = $info['mday'];
$month = $info['mon'];
$year = $info['year'];
$hour = $info['hours'];
$min = $info['minutes'];
$sec = $info['seconds'];

$current_date = "$date/$month/$year - $hour:$min:$sec";


$mysqli = new mysqli("mysql.eecs.ku.edu", "m326s072", "xah7Aedi", "m326s072");

if ($mysqli->connect_errno)
{
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}
echo "$initVal";

$insert = "INSERT INTO Trash_Tech (time, distance) VALUES('$current_date', '$distance');";
$setInitial = "INSERT INTO TRASH_TECH_INIT (initial) VALUES ($setupDistance);";
$drop = "DELETE FROM TRASH_TECH_INIT;";

if (!empty($setupDistance))
{
    $mysqli->query($drop);
    $mysqli->query($setInitial);
}

if (!empty($distance))
{
  $mysqli->query($insert);
}

$query = "SELECT distance, time FROM Trash_Tech ORDER BY id DESC LIMIT 1;";
$getSize = "SELECT initial FROM TRASH_TECH_INIT LIMIT 1;";

$sql = mysqli_query($mysqli, $query);
$result = $sql->fetch_assoc();
$takenDistance = $result['distance'];
$takenDate = $result['time'];

$sqlInitial = mysqli_query($mysqli, $getSize);
$resultInitial = $sqlInitial->fetch_assoc();
$initVal = $resultInitial['initial'];
$percentage = ($initVal - $takenDistance )/ $initVal * 100;
echo "<div style=\"margin: 0 auto; width: 50%;text-align: center\">";
echo "<head> <h1> Trash Tech - Home </h1> </head>";
echo "<body style=\"background-color: lightseagreen \">";

if($percentage>=90)
{
  echo "<script> alert(\"Time to empty the trash!\")</script> <br>";
}

echo "Percentage Full: $percentage% <br>";
echo "Date: $takenDate";

echo "</body>";


$mysqli->close();
 ?>
