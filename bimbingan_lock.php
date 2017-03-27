<style type="text/css">a.tombol, .tombol {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #050505;padding: 2px 5px;text-decoration: none;background: -moz-linear-gradient(top,#ffffff 0%,#c7d95f 50%,#add136 50%,#6d8000);background: -webkit-gradient(linear, left top, left bottom,from(#ffffff),color-stop(0.50, #c7d95f),color-stop(0.50, #add136),to(#6d8000));-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;border: 1px solid #6d8000;-moz-box-shadow:0px 1px 3px rgba(000,000,000,0.5),inset 0px 0px 2px rgba(255,255,255,1);-webkit-box-shadow:0px 1px 3px rgba(000,000,000,0.5),inset 0px 0px 2px rgba(255,255,255,1);box-shadow:0px 1px 3px rgba(000,000,000,0.5),inset 0px 0px 2px rgba(255,255,255,1);text-shadow:0px -1px 0px rgba(000,000,000,0.2),0px 1px 0px rgba(255,255,255,0.4);}a.tombol:hover, .tombol:hover {font-family: Arial, Helvetica, sans-serif;font-size: 12px;color: #050505;padding: 2px 5px;text-decoration: none;background: -moz-linear-gradient(top,#ffffff 0%,yellow 50%,#fa0 50%,#fa0);background: -webkit-gradient(linear, left top, left bottom,from(#ffffff),color-stop(0.50, #fa0),color-stop(0.50, #fa0),to(#fa0));-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;border: 1px solid #6d8000;-moz-box-shadow:0px 1px 3px rgba(000,000,000,0.5),inset 0px 0px 2px rgba(255,255,255,1);-webkit-box-shadow:0px 1px 3px rgba(000,000,000,0.5),inset 0px 0px 2px rgba(255,255,255,1);box-shadow:0px 1px 3px rgba(000,000,000,0.5),inset 0px 0px 2px rgba(255,255,255,1);text-shadow:0px -1px 0px rgba(000,000,000,0.2),0px 1px 0px rgba(255,255,255,0.4);}</style>
<?
include('conn.php');

$tahun = mysql_real_escape_string(stripslashes($_POST['tahun']));
$bulan = mysql_real_escape_string(stripslashes($_POST['bulan']));

$sql = "SELECT DISTINCT trans FROM bimbingan WHERE tahun = $tahun AND bulan = '$bulan'";
$result = mysql_query($sql);
$num_rows = mysql_num_rows($result);
$data = 0;
	
if($num_rows > 0){
	while($row = mysql_fetch_object($result)){
		$data = $row->trans;
	}
	
}

if($data == 1){
	echo '
					<div id="lock"></div>';
} else {
	echo '
					<div id="lock">
						<br><hr>
						<input type="button" value="Save" name="submit" id="submit" class="tombol"/>
						<input type="reset" name="reset" id="reset" value="reset"  class="tombol"/>
					</div>';
}
?>