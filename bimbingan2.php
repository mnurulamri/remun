<?php
if(!session_id()) session_start();
$kd_organisasi = $_GET["kd_organisasi"];
$tahun = $_GET["tahun"];
$bulan = $_GET["bulan"];

include("conn.php");

$sql="	SELECT a.nip, nama_pengajar, honor 
		FROM bimbingan a, pengajar b 
		WHERE kd_organisasi = '$kd_organisasi' and a.nip = b.nip and a.tahun=$tahun and a.bulan = '$bulan' and b.tahun = $tahun and b.bulan = '$bulan'";

$result = mysql_query($sql) or die(mysql_error());

$rows = mysql_num_rows($result);

if ($rows > 0){
	echo "<table style='border-collapse:collapse'>
	<tr style='padding-left:10px; vertical-align:middle; font: 11px verdana; border:1px solid orange;'>
		<th>Nip</th>
		<th>Nama Pengajar</th>
		<th>Total Honor</th>
	</tr>";

	while($row = mysql_fetch_array($result)) {
		$nip = $row["nip"];
		$namapengajar = $row["nama_pengajar"];
		$honor = $row["honor"];
		echo "
		<tr bgcolor='#FFFFCC' style='padding-left:10px; vertical-align:middle; font: 11px verdana; border:1px solid orange;' width='250px' height='20px' onmouseout=\"this.style.backgroundColor='#FFFFCC'\" onmouseover=\"this.style.backgroundColor='#FFCCFF'\"> 
			<td>".$nip."</td>
			<td>
				<div style='cursor:pointer' class='judul' id='bimbingan3.php?nip=$nip&tahun=$tahun&bulan=$bulan&kd_organisasi=$kd_organisasi'>".$namapengajar."</div>
				<div class='isi'></div>
			</td>
			<td align='right'>".number_format($honor)."</td>
		</tr>";
	}
	echo "</table>";
} else {
	echo "Tidak ada data";
}
mysql_close($con);
?> 

<script type="text/javascript">
/*
	$(document).ready(function() {
		$(".isi").hide();
		
		//toggle the componenet with class body
		$(".judul").click(function(){
			var url = $(this).attr('id');
			$(this).next(".isi").slideToggle(false, function(){$(".isi").load(url);});
			$(".isi").hide();
		});		
	});
*/
</script>



