<?php
if(isset($_POST['page'])){
    //Include pagination class file
    include('Pagination.ajax.class.php');
    
    //Include database configuration file
    include('dbConfig.php');

$sql = "SELECT DISTINCT user_nip, role_id
		FROM user_role
		ORDER BY user_nip";

$result = $db->query($sql);
while ($row = $result->fetch_object()) {
	$role_user[$row->user_nip][$row->role_id] = 1;
}

    $start = !empty($_POST['page'])?$_POST['page']:0;
    $limit = 10;
    
    //set conditions for search
    $whereSQL = $orderSQL = '';
    $keywords = $_POST['keywords'];
    $sortBy = $_POST['sortBy'];
    if(!empty($keywords)){
    	$whereSQL = "WHERE YEAR(CURDATE()) AND nama LIKE '%".$keywords."%'";
    }
    if(!empty($sortBy)){
        $orderSQL = " ORDER BY nama ".$sortBy;
    }else{
        $orderSQL = " ORDER BY nama DESC ";
    }

    //get number of rows
    //$queryNum = $db->query("SELECT COUNT(*) as postNum FROM posts ".$whereSQL.$orderSQL);
    $sql = "SELECT COUNT(*) AS postNum FROM  view_nama_pengajar $whereSQL $orderSQL";
	$result = $db->query($sql);

	/*while ($row = $result->fetch_object()) {
		$array_nama[$row->user_nip] = $row->nama;
	}
	asort($array_nama);
	//$rowCount= count($array_nama);*/
    $resultNum = $result->fetch_assoc();
    $rowCount = $resultNum['postNum'];

    //initialize pagination class
    $pagConfig = array(
        'currentPage' => $start,
        'totalRows' => $rowCount,
        'perPage' => $limit,
        'link_func' => 'searchFilter'
    );
    $pagination =  new Pagination($pagConfig);
    
    //get rows
    $query = $db->query("SELECT * FROM view_nama_pengajar $whereSQL $orderSQL LIMIT $start, $limit");
	$result = $query;
	while ($row = $result->fetch_object()) {
		$array_nama[$row->user_nip] = $row->nama;
	}
	asort($array_nama);
	
$role_name = array(
	'1'=>'Admin',
	'2'=>'Prodi',
	'3'=>'Dosen',
	'4'=>'PPAA'
);

    if($query->num_rows > 0){
    	echo '
<table id="customers" cellspacing="0">
	<tr>
		<th rowspan="2" width="150px">NIP</th>
		<th rowspan="2" width="150px">NAMA</th>
		<th colspan="4">ROLE</th>
		<th  rowspan="2"width="50px">&nbsp;</th>
	</tr>
	<tr>
		<th width="50px">ADMIN</th>
		<th width="50px">PRODI</th>
		<th width="50px">DOSEN</th>
		<th width="50px">PPAA</th>
	</tr>';
	
        echo '<table id="customers" class="post-list">'; //echo '<div class="post-list">';
        foreach ($array_nama as $nip => $nama) {
        	echo '
				<tr id="tr-'.$nip.'" class="list-item">
					<td width="150px">'.$nip.'</td>
					<td width="150px"> '.$nama.'</td>';
					
					//cetak masing2 cell role
			for ($i=1; $i <= 4; $i++) {
				$cell = (isset($role_user[$nip][$i])) ? 1 : 0 ;
				$checked = ($cell==1) ? 'checked' : '' ;
				if ($cell==1 AND $i==2) {
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
						<input type="checkbox" '.$checked.' size="10" id="'.$nip.'_'.$role_name[$i].'"/>
						'.$view.'
					</td>';
				$cell = '';
				$checked = '';
				$view = '';
				$class = '';
				$cursor = '';
			}
			echo '
				<td width="50px"><input type="button" value="edit" class="edit" data-nip="'.$nip.'"/></td>
			</tr>	
			<tr class="detail-prodi" id="detail-prodi-'.$nip.'">
				<td id="prodi-'.$nip.'" colspan="7">testing</td>
			</tr>';
		}        
        echo '</table>';
        echo '<div style="text-align:center; padding-top:10px;   font-size:12px; font-family: \"Trebuchet MS", Arial, Helvetica, sans-serif;\">'; 
        echo $pagination->createLinks();
        echo '<div>';
	}
}