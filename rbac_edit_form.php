(<?php
include_once('conn.php');
$nip = $_POST['nip'];

//set nama role
$role_name = array(
	'1'=>'Admin',
	'2'=>'Prodi',
	'3'=>'Dosen',
	'4'=>'PPAA'
);

//ambil data role user
$sql = "SELECT DISTINCT user_nip, role_id
		FROM user_role
		WHERE user_nip = '$nip' ";
$result = mysql_query($sql) or die(mysql_error());
while ($row = mysql_fetch_object($result)) {
	$role_user[$row->user_nip][$row->role_id] = 1;
}

//ambil data nama staf
$sql = "SELECT * FROM view_nama_pengajar WHERE user_nip = '$nip' "; 
$result = mysql_query($sql) or die(mysql_error());
while ($row = mysql_fetch_object($result)) {
	$array_nama[$row->user_nip] = $row->nama;
}

//ambil data user prodi
$sql = "SELECT a.user_nip as user_nip, a.role_id as role_id, permission, programstudi
		FROM user_role a 
			LEFT JOIN user_permission b ON a.user_nip = b.user_nip 
			LEFT JOIN organisasi c  ON permission = kodeorganisasi
		WHERE a.user_nip = '$nip' ";
$result = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_object($result)){
	$user_prodi[$row->permission] = $row->programstudi;
}

//ambil data prodi
$sql = "SELECT kodeorganisasi, programstudi
		FROM organisasi
		WHERE prodi not like '%FIA%' AND prodi is not null 
		ORDER BY SUBSTR(4,2), SUBSTR(1,2)";
$result = mysql_query($sql) or die(mysql_error());
while($row = mysql_fetch_object($result)){
	$prodi[$row->kodeorganisasi] = $row->programstudi;
}

if (count($role_user) > 0) {
	echo '
	<table id="" cellspacing="0">
		<tr>
			<th colspan="4">ROLE</th>
		</tr>';
	foreach ($role_user as $k_nip => $v_nip) {
		$nama = (isset($array_nama[$k_nip])) ? $array_nama[$k_nip] : '?' ;
		echo '
		<tr id="tr-'.$k_nip.'">
			<td colspan="4">';
			//cetak masing2 cell role
			for ($i=1; $i <= 4; $i++) {
				$cell = (isset($role_user[$k_nip][$i])) ? 1 : 0 ;
				$checked = ($cell==1) ? 'checked' : '' ;
				//$view = ($cell==1 AND $i==2) ? 'view detail' : '' ;
				if ($cell==1 AND $i==2) {
					$cursor = 'style="cursor:pointer"';
					//$view = '<input type="button" '.$cursor.' value="detail">';
					$class = 'check_prodi';
					$class_check = 'check';  //trigger berupa class untuk ngasih/hide contrengan tiap nama prodi
				} else {
					$view = '';
					$class = '';
					$cursor = '';
					$class_check = '';
				}
				$check = ($i=='2') ? 'check' : '' ;
				echo '
					<span id="'.$k_nip.'" class="'.$class.'">
						<input type="checkbox" '.$checked.' class="role '.$check.'" size="10" value="'.$role_name[$i].'"/>
						'. $role_name[$i] .'
					</span>
				';
				$cell = '';
				$checked = '';
				$view = '';
				$class = '';
				$cursor = '';
				$check ='';
			}
		echo '
			</td>
			<td><input type="button" id="simpan" data-nip="'.$k_nip.'" value="simpan" ></td>
		</tr>
		<tr><th colspan="4" style="text-align:center"><b>Program Studi</b></th></tr>';
		
		foreach($prodi as $kodeorganisasi => $programstudi){
			if(isset($user_prodi[$kodeorganisasi])){
				$checked = 'checked';
			} else {
				$checked = '';
			}
			echo '
				
				<tr>
					<td><input type="checkbox" class="prodi" '.$checked.' size="10" value="'.$kodeorganisasi.'"/></td>
					<td>'. $kodeorganisasi .'</td>
					<td colspan="2">'. $programstudi .'</td>
				</tr>
			';
			$checked = '';
		}
	}
	echo '</table>';
} else {
	echo '
	<table id="" cellspacing="0">
		<tr>
			<th colspan="4">ROLE</th>
		</tr>		
		<tr id="tr-'.$nip.'">
			<td colspan="4">';
			foreach ($role_name as $k => $v) {
				$check = ($k=='2') ? 'check' : '' ;
				echo '
				<span id="'.$nip.'">
					<input type="checkbox" class="role '.$check.'" size="10" value="'.$v.'" data-id="'.$nip.'_'.$v.'"/> '.$v.'
				</span>';
			}
		echo '
			</td>
			<td><input type="button" id="simpan" data-nip="'.$nip.'" value="simpan" ></td>
		</tr>

		<tr>
			<th colspan="4" style="text-align:center"><b>Program Studi</b></th>
		</tr>';
		
		foreach($prodi as $kodeorganisasi => $programstudi){
			if(isset($user_prodi[$kodeorganisasi])){
				$checked = 'checked';
			} else {
				$checked = '';
			}
			echo '
				
				<tr>
					<td><input type="checkbox" class="prodi" '.$checked.' size="10" value="'.$kodeorganisasi.'"/></td>
					<td>'. $kodeorganisasi .'</td>
					<td colspan="2">'. $programstudi .'</td>
				</tr>
			';
			$checked = '';
		}
	echo '</table>';
}




/*
echo '<pre>';
print_r($role_user);
print_r($array_nama);
print_r($prodi);
echo '</pre>';
*/
?>