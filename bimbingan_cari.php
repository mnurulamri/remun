<?php
include("conn.php");
if(!session_id()) session_start();
$kd_organisasi = $_SESSION["kode"];
$kata = $_POST['q'];
#$query = mysql_query("select distinct namapengajar, nip from kalban where kodeorganisasi='$kd_organisasi' and namapengajar like '%$kata%' limit 10");
$query = mysql_query("select distinct nama_pengajar, nip from master_pengajar where nama_pengajar like '%$kata%' limit 10");
echo "<div class='suggestionsBox'><div class='suggestionList'>";
while($k = mysql_fetch_array($query)){
	echo '<li onClick="isi(\''.$k[0].'\'); isiNip(\''.$k[1].'\');" style="cursor:pointer">'.$k[0].'</li>';
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
		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: orange;
	}
</style>