<?php
//Include database configuration file
include('dbConfig.php');

$nip = $_POST['nip'];
$nama = stripslashes($_POST['nama']);

//cek apakah datanya sudah ada
$sql = "SELECT nama FROM view_nama_pengajar WHERE user_nip = '$nip' ";
$result = $db->query($sql) or die($db->error);
while ($row = $result->fetch_object()) {
	$nama_pengajar = $row->nama;
}
if ($result->num_rows > 0){
	echo 'Nama Pengajar '.$nama_pengajar.' sudah terdaftar';
} else {
	$sql = "INSERT INTO master_pengajar (nip, nama_pengajar) VALUES ('$nip', '$nama')";
	$result = $db->query($sql) or die($db->error);
	echo 'Nama Pengajar Sudah Didaftarkan';
}
?>