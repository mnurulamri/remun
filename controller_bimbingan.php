<?php

if(!session_id()) session_start();

$nip = $_SESSION["nip"];

$kd_organisasi = $_SESSION["kd_organisasi"];

$mysqli= new mysqli('localhost','remun','usbw','remun');

$max = $mysqli->query("select max(right(id,1)) as x from bimbingan where nip = '$nip'") ; 

while ($row = $max->fetch_assoc()) {

    $count = $row["x"];
	
}

if (isset($count)) {

	$no = $count + 1;
	
} else {

	$no = 1;
	
}

header('Content-type:text/javascript;charset=UTF-8');

$json=json_decode(stripslashes($_POST["_gt_json"]));//$pageNo = $json->{'pageInfo'}->{'pageNum'};

if($json->{'action'} == 'load'){

	$sql = "select id, nm_bimbingan, jml_mhs, a.harga_satuan as harga_satuan, honor 
			from bimbingan a, ket_bimbingan b, hs_bimbingan c 
			where a.kd_bimbingan = b.kd_bimbingan and a.kd_organisasi = c.kd_organisasi and nip = '$nip'";

	$handle = $mysqli->query($sql)  or die($mysqli->error) ;

	$retArray = array();

	$totalhonor = 0;
	
	while ($row = $handle->fetch_object()) {

		$retArray[] = $row;
		
		$totalhonor = $totalhonor + $row->honor;

	}

	$_SESSION["totalhonor"] = $totalhonor;
	
	$data = json_encode($retArray);

	$ret = "{data:" . $data .",\n";

	$ret .= "recordType : 'object'}";

	echo $ret;

}

else if($json->{'action'} == 'save'){

	$sql = "";

	$params = array();

	$errors = "geblek";

	/*deal with those deleted*/

	$deletedRecords = $json->{'deletedRecords'};

	foreach ($deletedRecords as $value){

		$sql = "delete from bimbingan where id ='".$value->id."'";

		$mysqli->query($sql) or die($mysqli->error) ;

	}

	//deal with those updated

	$sql = "";

	$updatedRecords = $json->{'updatedRecords'};

	foreach ($updatedRecords as $value){

		$sql = "UPDATE  `bimbingan` 
				SET kd_bimbingan =  '".$value->nm_bimbingan."', 
					jml_mhs = ".$value->jml_mhs.", 
					harga_satuan = ".$value->harga_satuan.", 
					honor = ".$value->honor." 
				WHERE `id` = '".$value->id."'";

		$mysqli->query($sql)  or die($mysqli->error) ;

	}



	//deal with those inserted
	
	
	$sql = "";

	$insertedRecords = $json->{'insertedRecords'};

	foreach ($insertedRecords as $value){

		$id = $kd_organisasi.$nip.$no;

		$sql = "INSERT into bimbingan (`id`, `nip`, `kd_organisasi`, `kd_bimbingan`, jml_mhs, harga_satuan, honor) 
				VALUES ('".$id."', '".$nip."', '".$kd_organisasi."', '".$value->nm_bimbingan."', ".$value->jml_mhs.", ".$value->harga_satuan.", ".$value->honor.")";

		$mysqli->query($sql) or die($mysqli->error) ;
		
		$no = $no + 1;

	}

		$ret = "{success : true,exception:''}";
		echo $ret;
  
} 

?>

