<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);


$distance = $_GET['distance'];

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


$insert = "INSERT INTO Trash_Tech (time, distance) VALUES('$current_date', '$distance');";

if (!empty($distance))
{
  $mysqli->query($insert);
}

$query = "SELECT distance, time FROM Trash_Tech ORDER BY id DESC LIMIT 1;";

$sql = mysqli_query($mysqli, $query);
$result = $sql->fetch_assoc();
$takenDistance = $result['distance'];
$takenDate = $result['time'];


echo "Distance: $takenDistance <br>";
echo "Date: $takenDate";


$mysqli->close();
 ?>
