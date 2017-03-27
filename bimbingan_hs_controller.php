<?php

session_start();

include("conn.php");

$username = $_SESSION["username"];

$kd_organisasi = $_SESSION["kode"];

header('Content-type:text/javascript;charset=UTF-8');

$json = json_decode(stripslashes($_POST["_gt_json"]));//$pageNo = $json->{'pageInfo'}->{'pageNum'};

if($json->{'action'} == 'load'){

	if ($username == "admin" or $username == "remunerasifisipui") {
		$sql = "select kd_organisasi, programstudi, ketua_proposal, penguji_proposal, pembimbing_proposal, sekretaris_proposal,
					   ketua, penguji, pembimbing, penguji, sekretaris	
				from hs_bimbingan a, organisasi b 
				where a.kd_organisasi = b.kodeorganisasi";
	} else {	
		$sql = "select id, jenis_bimbingan, harga_satuan from bimbingan_hs where kd_organisasi = '$kd_organisasi'";
	}
	
	$handle = $mysqli->query($sql) or die($mysqli->error) ;

	$retArray = array();

	while ($row = $handle->fetch_object()) {

		$retArray[] = $row;

	}

	$data = json_encode($retArray);

	$ret = "{data:" . $data .",\n";

	$ret .= "recordType : 'object'}";

	echo $ret;

}

else if($json->{'action'} == 'save'){

	$sql = "";

	$params = array();

	$errors = "error...";

	/*deal with those deleted*/

	$deletedRecords = $json->{'deletedRecords'};

	foreach ($deletedRecords as $value){

		$sql = "DELETE FROM bimbingan_hs 
		        WHERE id = ".$value->id;

		$mysqli->query($sql) or die($mysqli->error) ;

	}

	//deal with those updated

	$sql = "";

	$updatedRecords = $json->{'updatedRecords'};

	foreach ($updatedRecords as $value){

		$sql = "UPDATE bimbingan_hs 
				SET jenis_bimbingan =  '".$value->jenis_bimbingan."', 
					harga_satuan = ".$value->harga_satuan."
				WHERE id = '".$value->id."'";

		$mysqli->query($sql)  or die($mysqli->error) ;

	}

	//deal with those inserted
	
	$sql = "";

	$insertedRecords = $json->{'insertedRecords'};

	foreach ($insertedRecords as $value){

		$sql = "INSERT into bimbingan_hs (kd_organisasi, jenis_bimbingan, harga_satuan) 
				VALUES ('".$kd_organisasi."', '".$value->jenis_bimbingan."', ".$value->harga_satuan.")";

		$mysqli->query($sql) or die($mysqli->error) ;
		
	}

		$ret = "{success : true,exception:''}";
		echo $ret;
  
} 

?>

