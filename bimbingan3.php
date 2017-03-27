<?php
include("conn.php");
#include("menu.php");

if(!session_id()) session_start();

//$kd_organisasi = $_SESSION["kd_organisasi"];
$username = $_SESSION["username"];

if (isset($_POST["bulan"])){
	$tahun = $_POST["tahun"];
	$vbulan = $_POST["bulan"];
	bulan();	
} else {
	$tahun = $_GET["tahun"];
	$bulan = $_GET["bulan"];
	$nip = $_GET["nip"];
}


	$sql = "SELECT id, a.nip as nip, nama_pengajar, a.kd_bimbingan, nm_bimbingan, jml_mhs, harga_satuan, honor 
			FROM bimbingan a, ket_bimbingan b, pengajar d
			WHERE a.kd_bimbingan = b.kd_bimbingan and a.nip = d.nip and a.kd_organisasi = '$kd_organisasi' and d.tahun=$tahun and d.bulan='$bulan' and a.tahun=d.tahun and a.bulan=d.bulan and a.nip = $nip
			ORDER BY nama_pengajar, a.kd_bimbingan";


$result = mysql_query($sql) or die (mysql_error());

$rows = mysql_num_rows($result);

if($rows > 0){

	$data = "";

	$data = array();

	while ($row = mysql_fetch_object($result)){

		$data[$row->nama_pengajar][] = $row;

	}

	$grand_total_honor = 0;
	
	foreach ($data as $index => $baris){	
	
		echo "
		
		<table class='gridtable' align='center'>
		
		<tr>
		
			<th>Tugas Bimbingan</th>
			
			<th>Jumlah Mahasiswa</th>
			
			<th>Harga Satuan</th>
			
			<th>Total Honor</th>
			
		<tr>
		
		";
		
		$total_honor = 0;
		
		foreach ($baris as $kolom){	
			
			echo "
			
			<tr onMouseOver=\"this.style.backgroundColor='#FFCCFF'\" onMouseOut=\"this.style.backgroundColor='#ffffff'\">
			
				<td align='left'>{$kolom->nm_bimbingan}</td>
				
				<td>{$kolom->jml_mhs}</td>
				
				<td align='right'>".number_format($kolom->harga_satuan)."</td>
				
				<td align='right'>".number_format($kolom->honor,0,'',',')."</td>
				
			</tr>
			
			";	
			
			$total_honor += $kolom->honor;
			
			$grand_total_honor += $kolom->honor;

		}

		echo "
		</table>		
		";
		
	};
} else {
	echo "<div style='font-family: verdana,arial,sans-serif; font-size:11px; color:white;'><strong>Tidak Ada Data!</strong></div>";
}
?>
</div>
<html>

<!-- CSS goes in the document HEAD or added to your external stylesheet -->
<style type="text/css">
table.gridtable {
	font-family: verdana,arial,sans-serif;
	font-size:11px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
	margin-left:auto;
	margin-right:auto;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>

<!-- Table goes in the document BODY -->

</body>
</html>

<?
function bulan(){
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
}
?>