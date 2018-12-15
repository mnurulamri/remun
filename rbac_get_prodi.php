<?
include('conn.php');
$nip = $_POST['nip'];

$sql = "SELECT a.user_nip as user_nip, a.role_id as role_id, permission, programstudi
		FROM user_role a 
			LEFT JOIN user_permission b ON a.user_nip = b.user_nip 
			LEFT JOIN organisasi c  ON permission = kodeorganisasi
		WHERE a.user_nip = '$nip'";
		
$result = mysql_query($sql) or die(mysql_error());
echo '<table id="customers" style="margin:auto" cellspacing="0">';
while($row = mysql_fetch_object($result)){
	echo '<tr><td style="background:#fff">'.$row->programstudi.'</td></tr>';
}
echo '</table>';
?>