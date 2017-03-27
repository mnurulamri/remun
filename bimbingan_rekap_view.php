<?php
include("conn.php");
#include("menu.php");

if(!session_id()) session_start();
$username = $_SESSION["username"];
$kd_organisasi = $_SESSION["kd_organisasi"];

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


$sql = "SELECT distinct departemen, program, sum(honor), kodeorganisasi
		FROM bimbingan a, organisasi b
		WHERE kd_organisasi = kodeorganisasi and a.tahun = $tahun and a.bulan = '$bulan'
		GROUP BY departemen, program
		ORDER BY departemen, program, kodeorganisasi";

$result = mysql_query($sql) or die (mysql_error());

$rows = mysql_num_rows($result);

if($rows > 0){
	
	echo "
	<body style='background:#336666'>
	<div style='height:10%; background:yellow'>&nbsp;</div>
	<div style='height:3%; background:#555555'>&nbsp;</div>
	<div style='text-align:center'>
	<table class='gridtable' align='center'>
		
		<tr>
			<th>Kode Program</th>
			
			<th>Program</th>
			
			<th>Jenjang</th>
			
			<th>Total Honor</th>
			
			
			
		<tr>
	";

	$total_honor = 0;
	
	while ($row = mysql_fetch_array($result)){
		
		echo "
			
		<tr onMouseOver=\"this.style.backgroundColor='#FFCCFF'\" onMouseOut=\"this.style.backgroundColor='#ffffff'\">
			
			<td>{$row[3]}</td>
			
			<td align='left' style='font:bold 11px verdana;'>
				<div style='cursor:pointer' class='msg_head' id=\"bimbingan2.php?kd_organisasi={$row[3]}&tahun=$tahun&bulan=$bulan\">{$row[0]}</div>
				<div class='msg_body'></div>
			</td>
			
			<td>{$row[1]}</td>
			
			<td align='right'>".number_format($row[2],0,'',',')."</td>
			
			
			
		</tr>
			
		";	
		
		$total_honor += $row[2];

	}

	echo "	
	</table>	
	<div style='height:10%; background:#555555'>&nbsp;</div>
	<div style='height:4%; padding-top:0.5%; background:yellow; font-family: verdana,arial,sans-serif; font-size:11px; color:#555555'><strong> Grand Total Honor Bimbingan : ".number_format($total_honor)."</strong></div>";
	
} else {
	echo "<div style='font-family: verdana,arial,sans-serif; font-size:11px; color:white;'><strong>Tidak Ada Data!</strong></div>";
}
?>
</div>
<html>
<!--<script type="text/javascript" src="js/jquery-1.2.3.pack.js"></script>
<script src="js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>-->
<script type="text/javascript">
	$(function run() {
		$(".msg_body").hide();
		
		//toggle the componenet with class msg_body
		$(".msg_head").click(function(){
			var z = $(this).attr('id');
			//alert(z);
			$(this).next(".msg_body").slideToggle(false, function(){$(".msg_body").load(z);});
			$(".msg_body").hide();
		});
	});
</script>

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