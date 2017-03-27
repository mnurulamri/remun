<?php

if(!session_id()) session_start();

$tahun_akad = $_SESSION["tahun_akad"];

$semester = $_SESSION["semester"];

$kodeorganisasi = $_SESSION["kodeorganisasi"];

if($semester == "gasal"){

	$smt = 1;

} else {

	$smt = 2;

}

include("conn.php");

include("bulan.php");

include("flag.php");

header('Content-type:text/javascript;charset=UTF-8');

$json=json_decode(stripslashes($_POST["_gt_json"]));//$pageNo = $json->{'pageInfo'}->{'pageNum'};

if($json->{'action'} == 'load'){

#$sql = $_SESSION["sql"];

if ($semester == "gasal"){
  if($_SESSION["username"] == "ppaa" or $_SESSION["username"] == "zaenal"){
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=2014 and bulan='12',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=2014 and bulan='09',hadiraktual,0)) as HadirSeptember,
				sum(if(a.tahun_akad=2014 and bulan='10',hadiraktual,0)) as HadirOktober,
				sum(if(a.tahun_akad=2014 and bulan='11',hadiraktual,0)) as HadirNovember,
				sum(if(a.tahun_akad=2014 and bulan='12',hadiraktual,0)) as HadirDesember,
				sum(if(a.tahun_akad=2014 and bulan='01',hadiraktual,0)) as HadirJanuari,
				sum(if(a.tahun_akad=2014 and bulan='12',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'					
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = 2014 and semester = 1 and flagtampil = 1 and
				  b.tahun_akad = 2014 and smt = 1 and kodekelas = kd_kelas and a.kode <> 0 and kodepdpt=0
			group by kode
		    order by b.flaghari, substr(b.jam,1,2), b.ruang, namapengajar";
  
  } else if($_SESSION["username"] == "orek" or $_SESSION["username"] == "kessos"){ //or $_SESSION["username"] == "politikreg" or $_SESSION["username"] == "politikpar"
    $sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=2014 and bulan='12',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=2014 and bulan='09',hadiraktual,0)) as HadirSeptember,
				sum(if(a.tahun_akad=2014 and bulan='10',hadiraktual,0)) as HadirOktober,
				sum(if(a.tahun_akad=2014 and bulan='11',hadiraktual,0)) as HadirNovember,
				sum(if(a.tahun_akad=2014 and bulan='12',hadiraktual,0)) as HadirDesember,
				sum(if(a.tahun_akad=2014 and bulan='01',hadiraktual,0)) as HadirJanuari,
				sum(if(a.tahun_akad=2014 and bulan='12',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = $tahun_akad and semester = $smt and flagtampil = 1 and
				  b.tahun_akad = $tahun_akad and smt = $smt and kodekelas = kd_kelas and a.kode <> 0 and kodepdpt=0
			group by kode
			order by b.flaghari, substr(b.jam,1,3), namamatakuliah, kodekelas, kode";

  } else if($_SESSION["username"] == "politikreg" or $_SESSION["username"] == "politikpar"){ //or $_SESSION["username"] == "politikreg" or $_SESSION["username"] == "politikpar"
    $sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=2014 and bulan='12',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=2014 and bulan='09',hadiraktual,0)) as HadirSeptember,
				sum(if(a.tahun_akad=2014 and bulan='10',hadiraktual,0)) as HadirOktober,
				sum(if(a.tahun_akad=2014 and bulan='11',hadiraktual,0)) as HadirNovember,
				sum(if(a.tahun_akad=2014 and bulan='12',hadiraktual,0)) as HadirDesember,
				sum(if(a.tahun_akad=2014 and bulan='01',hadiraktual,0)) as HadirJanuari,
				sum(if(a.tahun_akad=2014 and bulan='12',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = 2014 and semester = 1 and flagtampil = 1 and
				  b.tahun_akad = 2014 and smt = 1 and kodekelas = kd_kelas and a.kode <> 0 and kodepdpt=0 and (kodepasca=0 or kodepasca='' or kodepasca=1)
			group by kode
			order by b.flaghari, substr(b.jam,1,3), namamatakuliah, kodekelas, kode";

  }  else {  
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=2014 and bulan='12',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=2014 and bulan='09',hadiraktual,0)) as HadirSeptember,
				sum(if(a.tahun_akad=2014 and bulan='10',hadiraktual,0)) as HadirOktober,
				sum(if(a.tahun_akad=2014 and bulan='11',hadiraktual,0)) as HadirNovember,
				sum(if(a.tahun_akad=2014 and bulan='12',hadiraktual,0)) as HadirDesember,
				sum(if(a.tahun_akad=2014 and bulan='01',hadiraktual,0)) as HadirJanuari,
				sum(if(a.tahun_akad=2014 and bulan='12',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = 2014 and semester = 1 and flagtampil = 1 and
				  b.tahun_akad = 2014 and smt = 1 and kodekelas = kd_kelas and a.kode <> 0 and (kodepasca='0' or kodepasca='')
			group by kode
			order by b.flaghari, substr(b.jam,1,3), namamatakuliah, kodekelas, kode";
  }			
} else {
  if($_SESSION["username"] == "ppaa"){
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = $tahun_akad and semester = $smt and flagtampil = 1 and
				  b.tahun_akad = $tahun_akad and smt = $smt and kodekelas = kd_kelas and a.kode <> 0 and kodepdpt=0 and (kodepasca = 1 or kodepasca = 2)
			group by kode
		    order by b.flaghari, substr(b.jam,1,3), b.ruang";
  } else if($_SESSION["username"] == "mku"){
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = $tahun_akad and semester = $smt and flagtampil = 1 and
				  b.tahun_akad = $tahun_akad and smt = $smt and kodekelas = kd_kelas and a.kode <> 0 and (namamatakuliah = 'Ekonomika dan Pembangunan Sosial' or namamatakuliah = 'Hukum dan Pembangunan' or namamatakuliah = 'Sistem Ekonomi Indonesia' or namamatakuliah = 'Dasar-dasar Logika' or namamatakuliah = 'Filsafat Ilmu Sosial')
			group by kode
		    order by b.flaghari, substr(b.jam,1,3), namamatakuliah, kodekelas, kode"; 

  } else if($_SESSION["username"] == "orek" or $_SESSION["username"] == "politikpar"){
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = $tahun_akad and semester = $smt and flagtampil = 1 and
				  b.tahun_akad = $tahun_akad and smt = $smt and kodekelas = kd_kelas and a.kode <> 0 and kodepdpt=0 and kodepasca=1
			group by kode
		    order by b.flaghari, substr(b.jam,1,3), namamatakuliah, kodekelas, kode"; 

  } else {
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = $tahun_akad and semester = $smt and flagtampil = 1 and
				  b.tahun_akad = $tahun_akad and smt = $smt and kodekelas = kd_kelas and a.kode <> 0 and kodepdpt=0 and (kodepasca='0' or kodepasca='')
			group by kode
		    order by b.flaghari, substr(b.jam,1,3), namamatakuliah, kodekelas, kode"; 
  }
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

/*deal with those deleted

$deletedRecords = $json->{'deletedRecords'};

foreach ($deletedRecords as $value){

$sql = "delete from data where ...  ='".$value->... ."'";

$mysqli->query($sql)  or die($mysqli->error) ;

}*/

//deal with those updated


$sql = "";

$updatedRecords = $json->{'updatedRecords'};

foreach ($updatedRecords as $value){

//$sql ="UPDATE kalban SET HadirAktual = ".$value->$bulan_aktual." WHERE Id = ".$value->Id." and tahun_akad = ".$tahun_akad." and semester = ".$smt." and bulan = '".$vbulan."'";
$sql ="UPDATE kalban SET HadirAktual = ".$value->HadirDesember." WHERE Id = ".$value->Id." and tahun = 2014 and bulan = '12' and tahun_akad=2014 and semester=1";
$mysqli->query($sql)  or die($mysqli->error) ;

}



//deal with those inserted

$sql = "";

$insertedRecords = $json->{'insertedRecords'};

foreach ($insertedRecords as $value){

$sql = "insert into data (`...`, `...`) VALUES ('".$value->NamaPengajar."','".$value->KodeKelas."')";

$mysqli->query($sql) or die($mysqli->error) ;

}

  $ret = "{success : true,exception:''}";
  echo $ret;
  
} 

?>