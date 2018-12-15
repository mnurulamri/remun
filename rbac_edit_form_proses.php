<?php
/*
	Algoritma 1:
	buat array data yg lama dan baru
	bandingkan jumlah array yg lama demgan yg baru
	jika jumlah array yg lama dan baru hanya satu (1) maka gunakan update record
	jika jumlah array yg lama dan baru lebih dari satu (1) maka :
		bandingkan data array role lama dengan yang baru
		tandain setiap item array apakah masih ada/tidak ada, atau berubah 
		jika item array didata yg baru tidak ada sedangkan data array yg lama ada maka delete record yg lama
		jika item array didata yg baru ada sedangkan data array yg lama tidak ada maka insert record yg baru
	
	Algoritma 2:
	??jika jumlah array yg lama dan baru hanya satu (1) maka gunakan update record
	??jika jumlah array yg lama dan baru lebih dari satu (1) maka :
		hapus semua record ybs
		insert record baru
*/

//Include database configuration file
include('dbConfig.php');

//proses update user role
$role = array(
	'Admin' => 1,
	'Prodi' => 2,
	'Dosen' => 3,
	'PPAA' => 4
);

$nip = $_POST['nip'];
$nama = $_POST['nama'];
$sql = "DELETE FROM user_role WHERE user_nip = '$nip'";
$result = $db->query($sql) or die($db->error);

//menghilangkan tanda koma ',' pada akhir query pada looping array terakhir
$count = count($_POST['role']);

$sql = "INSERT INTO user_role VALUES";
$i=1;
foreach($_POST['role'] as $k => $v){
	//$sql.= '(nip="'.$nip.'", role_id="'.$role[$v].'"),';
	if($i == $count){
		$sql.= '("'.$nip.'", "'.$role[$v].'")';
	} else {
		$sql.= '("'.$nip.'", "'.$role[$v].'"),';
	}
	$i++;
}
//print_r($sql);
$result = $db->query($sql) or die($db->error);

// proses update role program studi / permission
$sql = "DELETE FROM user_permission WHERE user_nip = '$nip'";
$result = $db->query($sql) or die($db->error);
$count = count($_POST['prodi']);

if ($count > 0) {
	$sql = "INSERT INTO user_permission VALUES";
	$i=1;
	foreach($_POST['prodi'] as $k => $v){
		//$sql.= '(nip="'.$nip.'", role_id="'.$role[$v].'"),';
		if($i == $count){
			$sql.= '("'.$nip.'", "'.$v.'")';
		} else {
			$sql.= '("'.$nip.'", "'.$v.'"),';
		}
		$i++;
	}
} 
$result = $db->query($sql) or die($db->error);

//$sql.= ')';

//set tampilan role sesuai updetan
foreach ($_POST['role'] as $k => $v){
	$post_role[$v] = 1; //yang ada data rolenya tandain
}
echo '
	<td width="150px">'.$nip.'</td>
	<td width="150px"> '.$nama.' </td>';
foreach ($role as $k => $v){
	$cell = (isset( $post_role[$k] )) ? 1 : 0 ;
	$checked = ($cell==1) ? 'checked' : '' ;
	
	if ($cell==1 AND $i=='Prodi') {
		$cursor = 'style="cursor:pointer"';
		$view = '<input type="button" '.$cursor.' value="detail">';
		$class = 'check';
	} else {
		$view = '';
		$class = '';
		$cursor = '';
	}
	
	echo '
	<td id="'.$nip.'" class="'.$class.'" width="50px">
		<input type="checkbox" '.$checked.' size="10" id="'.$nip.'_'.$k.'"/>
			'.$view.'
	</td>';
	//normalin lagi
	$class = '';
	$checked ='';
	$check ='';
	$view = '';
	$cell = '';
}

echo '<td width="50px"><input type="button" value="edit" class="edit" data-nip="'.$nip.'"/></td>';

//echo '<pre>';
//print_r($_POST['prodi']);
//print_r($post_role);
//print_r($_POST['role']);
//echo '</pre>';
//echo '$("#'.$nip.'_Admin").prop("checked", true)';
?>