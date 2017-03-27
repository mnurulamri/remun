<?php
//koneksi ke database
include("conn.php");

#setting session, hadir aktual, tahun dan bulan
if(!session_id()) session_start();
$username = $_SESSION["username"];
$tahun = $_POST["tahun"];
$vbulan = $_POST["bulan"];

switch($vbulan)
{
	case "Januari":
		$bulan = "01";
		$hadiraktual = "hadirjanuari";
		break;
	case "Februari":
		$bulan = "02";
		$hadiraktual = "hadirfebruari";
		break;
	case "Maret":
		$bulan = "03";
		$hadiraktual = "hadirmaret";
		break;
	case "April":
		$bulan = "04";
		$hadiraktual = "hadirapril";
		break;
	case "Mei":
		$bulan = "05";
		$hadiraktual = "hadirmei";
		break;
	case "Juni":
		$bulan = "06";
		$hadiraktual = "hadirjuni";
		break;
	case "Juli":
		$bulan = "07";
		$hadiraktual = "hadirjuli";
		break;
	case "Agustus":
		$bulan = "08";
		$hadiraktual = "hadiragustus";
		break;
	case "September":
		$bulan = "09";
		$hadiraktual = "hadirseptember";
		break;
	case "Oktober":
		$bulan = "10";
		$hadiraktual = "hadiroktober";
		break;
	case "November":
		$bulan = "11";
		$hadiraktual = "hadirnovember";
		break;
	case "Desember":
		$bulan = "12";
		$hadiraktual = "hadirdesember";
		break;
}

$query = "update bimbingan set trans = 1 where tahun=$tahun and bulan='$bulan'";
$sql = mysql_query ($query) or die ("error: ".mysql_error());
?>