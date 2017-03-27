<?php
$server = "localhost";
$user = "remun";
$pass = "usbw";
$db = "remun";

$mysqli = mysql_connect($server,$user,$pass) or die('error connecting mysql');
mysql_select_db($db,$mysqli) or die('Database tidak ditemukan');

$mysqli = new mysqli($server,$user,$pass,$db) or die('error connecting mysql');
?>
