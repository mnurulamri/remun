<?php
include("conn.php");
if(!session_id()) session_start();
$kd_organisasi = $_SESSION["kode"];
$kata = $_POST['q'];
#$query = mysql_query("select distinct namapengajar, nip from kalban where kodeorganisasi='$kd_organisasi' and namapengajar like '%$kata%' limit 10");
//$query = mysql_query("select distinct nama_pengajar, nip, ket from master_pengajar where nama_pengajar like '%$kata%' limit 10");
//$query = mysql_query("select distinct nama_pengajar, nip from gaji_detail_2 where nama_pengajar like '%$kata%' limit 10");

/*$sql = "SELECT DISTINCT nama_pengajar, nip FROM gaji_detail_2 WHERE nama_pengajar like '%$kata%' limit 15
UNION DISTINCT
SELECT namapengajar, nip FROM kalban WHERE tahun>= 2018 AND namapengajar like '%$kata%' limit 15";*/
$sql = "SELECT nama as nama_pengajar, user_nip as nip FROM view_nama_pengajar WHERE nama like '%$kata%' limit 15";
$query = mysql_query($sql);

echo "<div class='suggestionsBox'><div class='suggestionList'>";
while($k = mysql_fetch_array($query)){
	echo '
	<li onClick="isi(\''.$k[0].'\'); isiNip(\''.$k[1].'\');" style="cursor:pointer">
		<div style="color:gold"><b>'.$k[0].'</b></div>';
		if(empty($k[2])){
			echo '<div><i><span>'.$k[1].'</span></i></div>';
		} else {
			echo '<div><i><span>'.$k[1].'</span><span> - nip '.$k[2].'</span></i></div>';
		}
	echo'
		<!-- <div><i><span>'.$k[1].'</span><span> - '.$k[2].'</span></i></div> -->
	</li>';
}

echo "</div></div>"
?>

<style>
	.suggestionsBox {
		position: absolute;
		<!--left: 30px;-->
		margin: 10px 0px 0px 0px;
		width: 200px;
		background-color: gray;
		-moz-border-radius: 7px;
		-webkit-border-radius: 7px;
		border: 2px solid gray;	
		color: #fff;
		z-index:99;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		list-style-type: none;
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: orange;
	}
</style>