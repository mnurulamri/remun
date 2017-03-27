<?php

session_start();

$username = $_SESSION["username"];

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

	if ($username == "admin" or $username == "remunerasifisipui") {
		$sql = "select kd_organisasi, programstudi, ketua_proposal, penguji_proposal, pembimbing_proposal, sekretaris_proposal,
					   ketua, penguji, pembimbing, penguji, sekretaris	
				from hs_bimbingan a, organisasi b 
				where a.kd_organisasi = b.kodeorganisasi";
	} else {	
		$sql = "select kd_organisasi, programstudi, ketua_proposal, penguji_proposal, pembimbing_proposal, sekretaris_proposal,
					   ketua, penguji, pembimbing, penguji, sekretaris	
				from hs_bimbingan a, organisasi b 
				where a.kd_organisasi = b.kodeorganisasi and kd_organisasi = '$kd_organisasi'";
	}
	
	$handle = $mysqli->query($sql)  or die($mysqli->error) ;

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

	$errors = "geblek";

	/*deal with those deleted*/

	$deletedRecords = $json->{'deletedRecords'};

	foreach ($deletedRecords as $value){

		$sql = "delete from hs_bimbingan where programstudi ='".$value->programstudi."'";

		$mysqli->query($sql) or die($mysqli->error) ;

	}

	//deal with those updated

	$sql = "";

	$updatedRecords = $json->{'updatedRecords'};

	foreach ($updatedRecords as $value){

		$sql = "UPDATE hs_bimbingan 
				SET ketua_proposal =  ".$value->ketua_proposal.", 
					penguji_proposal = ".$value->penguji_proposal.", 
				    pembimbing_proposal = ".$value->pembimbing_proposal.", 
					sekretaris_proposal = ".$value->sekretaris_proposal.",
					ketua = ".$value->ketua.", 
					penguji = ".$value->penguji.", 
				    pembimbing = ".$value->pembimbing.", 
					sekretaris = ".$value->sekretaris."
				WHERE kd_organisasi = '".$value->kd_organisasi."'";

		$mysqli->query($sql)  or die($mysqli->error) ;

	}



	//deal with those inserted
	
	
	$sql = "";

	$insertedRecords = $json->{'insertedRecords'};

	foreach ($insertedRecords as $value){

		$id = $kd_organisasi.$nip.$no;

		$sql = "INSERT into bimbingan (`id`, `nip`, `kd_organisasi`, `kd_bimbingan`, jml_mhs, honor) 
				VALUES ('".$id."', '".$nip."', '".$kd_organisasi."', '".$value->nm_bimbingan."', ".$value->jml_mhs.", ".$value->honor.")";

		$mysqli->query($sql) or die($mysqli->error) ;
		
		$no = $no + 1;

	}

		$ret = "{success : true,exception:''}";
		echo $ret;
  
} 

?>

