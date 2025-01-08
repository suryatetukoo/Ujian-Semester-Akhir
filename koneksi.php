<?php
date_default_timezone_set('Asia/Jakarta');

$servername = "localhost";
$username = "root";
$password = "";
$db = "webdailyjournal";


$conn = new mysqli($servername,$username,$password,$db);


if($conn->connect_error){
	die("Connection failed : ".$conn->connect_error);
}

?>