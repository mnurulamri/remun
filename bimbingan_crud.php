<?php

if(!session_id()) session_start();

include("conn.php");

include("bulan.php");

$oper = $_POST["oper"];

$tahun = $_POST["tahun"];

$bulan = $_POST["bulan"];

$periode = "";

$nip = $_POST["nip"];

$kd_organisasi = $_SESSION["kode"];

$kd_bimbingan = $_POST["kd_bimbingan"];

$jenis_bimbingan = $_POST["jenis_bimbingan"];

$jml_mhs = $_POST["jml_mhs"];

if(isset($_POST["harga_satuan"])) $harga_satuan = $_POST["harga_satuan"];

$honor = $_POST["honor"];

if ($oper == "edit" or $oper == "del") $id = $_POST['id'];

switch ($oper) {
	
	case 'add':
		
		$q = mysql_query("INSERT INTO bimbingan (tahun, bulan, kd_organisasi, nip, kd_bimbingan, jenis_bimbingan, jml_mhs, harga_satuan, honor)
		
					      VALUES($tahun, '$bulan','$kd_organisasi', '$nip', '$kd_bimbingan', '$jenis_bimbingan', $jml_mhs, $harga_satuan, $honor)");
		
		break;
	
	case 'edit':
		
		$q = mysql_query("UPDATE bimbingan 
		
						  SET kd_bimbingan  = '$kd_bimbingan',

						  	  jenis_bimbingan = '$jenis_bimbingan',
						  
						      jml_mhs = $jml_mhs,
							  
							  harga_satuan = $harga_satuan,
							  
							  honor = $honor 
							  
						  WHERE id = $id");
		/*
		if($q){
		
			echo "Update Sukses";
		
		}
		*/
		break;
	
	case 'del':
		
		$q = mysql_query("DELETE FROM bimbingan WHERE id = $id");
	
	break;

}		
?>
