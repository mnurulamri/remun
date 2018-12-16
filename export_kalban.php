<?php
if(!session_id()) session_start();
// Connect database.
include("conn.php");
//set variabel
$tahun = $_POST["tahun"];
$vbulan = $_POST["bulan"];

switch($vbulan)
{
	case "Januari":
		$bulan = "01";
		break;
	case "Februari":
		$bulan = "02";
		break;
	case "Maret":
		$bulan = "03";
		break;
	case "April":
		$bulan = "04";
		break;
	case "Mei":
		$bulan = "05";
		break;
	case "Juni":
		$bulan = "06";
		break;
	case "Juli":
		$bulan = "07";
		break;
	case "Agustus":
		$bulan = "08";
		break;
	case "September":
		$bulan = "09";
		break;
	case "Oktober":
		$bulan = "10";
		break;
	case "November":
		$bulan = "11";
		break;
	case "Desember":
		$bulan = "12";
		break;
}

//set query
$sql = "SELECT tahun_akad, Semester, Id, Nip, NamaPengajar, KodeMK, KodeKelas, HadirAktual, KodeOrganisasi, KehadiranSeharusnya, KehadiranAktualAsing, KehadiranSeharusnyaAsing, IkutHitung
        FROM kalban 
        WHERE tahun = $tahun and bulan = '$bulan' AND kodepdpt NOT IN (4, 5)";


// Get data records from table.
$result = mysql_query($sql);

// Functions for export to excel.
function xlsBOF() {
	echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
	return;
}

function xlsEOF() {
	echo pack("ss", 0x0A, 0x00);
	return;
}

function xlsWriteNumber($Row, $Col, $Value) {
	echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
	echo pack("d", $Value);
	return;
}

function xlsWriteLabel($Row, $Col, $Value ) {
	$L = strlen($Value);
	echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
	echo $Value;
	return;
	}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=data_hadir_$vbulan_$tahun.xls ");
header("Content-Transfer-Encoding: binary ");
xlsBOF();

/*
Make a top line on your excel sheet at line 1 (starting at 0).
The first number is the row number and the second number is the column, both are start at '0'
*/
xlsWriteLabel(0,0,"id");
xlsWriteLabel(0,1,"siak_identifier_$bulan-$tahun");
xlsWriteLabel(0,2,"hadir_aktua_$bulan-$tahun");
xlsWriteLabel(0,3,"hadir_seharusnya_$bulan-$tahun");
xlsWriteLabel(0,4,"hadir_aktual_asing_$bulan-$tahun");
xlsWriteLabel(0,5,"hadir_asing_seharusnya_$vbulan-$tahun");

$xlsRow = 1;
// Put data records from mysql by while loop.
while($row=mysql_fetch_array($result)){

	if ($row['IkutHitung']) {
		$SiakIdentifier = $row['tahun_akad'].$row['Semester'].substr($row['KodeOrganisasi'], 6, 2).$row['KodeMK'].$row['KodeKelas'].$row['Nip'].trim(substr($row['NamaPengajar'], 0, 4));
	} else {
		$SiakIdentifier = '';
	}

	xlsWriteNumber($xlsRow,0,$row['Id']);
	xlsWriteLabel($xlsRow,1,$SiakIdentifier);
	xlsWriteLabel($xlsRow,2,$row['HadirAktual']);
	xlsWriteLabel($xlsRow,3,$row['KehadiranSeharusnya']);
	xlsWriteLabel($xlsRow,4,$row['KehadiranAktualAsing']);
	xlsWriteLabel($xlsRow,5,$row['KehadiranSeharusnyaAsing']);
	$xlsRow++;
}
xlsEOF();
exit();
?>
