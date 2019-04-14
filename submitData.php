<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

//$date = $_POST['date'];
//$distance = $_POST['distance'];

$date = "5/13/1999";
$distance = 5;

$mysqli = new mysqli("mysql.eecs.ku.edu", "m326s072", "xah7Aedi", "m326s072");

if ($mysqli->connect_errno)
{
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}


$insert = "INSERT INTO Trash_Tech (time, distance) VALUES('$date', '$distance');";

if ($date)
{
  $mysqli->query($insert);
}



$query = "SELECT distance FROM Trash_Tech ORDER BY id DESC LIMIT 1;"

$sql = mysqli_query($mysqli, $query);
$result = $sql->fetch_assoc();
$takenDistance = $result['distance'];


echo "hello: $takenDistance";





$mysqli->close();
 ?>
