<?php
switch (date("n")){
	case 1:
		$vbulan = "01";
		$month = "Januari";
		break;
	case 2:
		$vbulan = "02";
		$month = "Februari";
		break;
	case 3:
		$vbulan = "03";
		$month = "Maret";
		break;
	case 4:
		$vbulan = "04";
		$month = "April";
		break;
	case 5:
		$vbulan = "05";
		$month = "Mei";
		break;
	case 6:
		$vbulan = "06";
		$month = "Juni";
		break;
	case 7:
		$vbulan = "07";
		$month = "Juli";
		break;
	case 8:
		$vbulan = "08";
		$month = "Agustus";
		break;
	case 9:
		$vbulan = "09";
		$month = "September";
		break;
	case 10:
		$vbulan = "10";
		$month = "Oktober";
		break;
	case 11:
		$vbulan = "11";
		$month = "November";
		break;
	case 12:
		$vbulan = "12";
		$month = "Desember";
		break;
}

$bulan_aktual = "Hadir".$month;
?>