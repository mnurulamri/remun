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

/*
menentukan bulan current atau bulan berjalan 
diambil dari lock data hadir untuk bulan yg bernilai 1
*/
$sql = "SELECT bulan, lock_data FROM data_hadir_lock WHERE tahun_akad = $tahun_akad AND semester = $smt AND lock_data = '0'";
$result = mysql_query($sql) or die();

while($rows = mysql_fetch_object($result)){
	$_bulan = $vbulan = $rows->bulan;
}
$numrows = mysql_num_rows($result);

$bulan_arr = array('01'=>'Januari', '02'=>'Februari', '03'=>'Maret',
                   '04'=>'April', '05'=>'Mei', '06'=>'Juni',
                   '07'=>'Juli', '08'=>'Agustus', '09'=>'September',
                   '10'=>'Oktober', '11'=>'November', '12'=>'Desember');

//jika ada recordnya
if ($numrows > 0) {
	//jika ada bulan bernilai 0 atau bulan terset open
	$month = $bulan_arr[$_bulan];
} else {
	//jika dalam satu semester semua bulan terset closing maka set bulan saat ini dari semester yang berjalan
	//$_bulan = (strlen(date("n")) == 1) ? '0'.date("n") : date("n") ;
	$_bulan = $_SESSION["_bulan"];
}

$month = $bulan_arr[$_bulan];
$bulan_aktual = "Hadir".$month;

header('Content-type:text/javascript;charset=UTF-8');

$json=json_decode(stripslashes($_POST["_gt_json"]));//$pageNo = $json->{'pageInfo'}->{'pageNum'};

if($json->{'action'} == 'load'){

#$sql = $_SESSION["sql"];

if ($semester == "gasal"){
  /*if($_SESSION["username"] == "ppaa" or $_SESSION["username"] == "zaenal" or $_SESSION["kd_organisasi"] == '01.00.09.01'){*/
	if($_SESSION["username"] == "ppaa" or $_SESSION["kd_organisasi"] == '01.00.09.01')
	{
		
		$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
					sum(if(a.tahun_akad=$tahun_akad and bulan='09',hadiraktual,0)) as HadirSeptember,
					sum(if(a.tahun_akad=$tahun_akad and bulan='10',hadiraktual,0)) as HadirOktober,
					sum(if(a.tahun_akad=$tahun_akad and bulan='11',hadiraktual,0)) as HadirNovember,
					sum(if(a.tahun_akad=$tahun_akad and bulan='12',hadiraktual,0)) as HadirDesember,
					sum(if(a.tahun_akad=$tahun_akad and bulan='01',hadiraktual,0)) as HadirJanuari,
					sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
					sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
					sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui				
		FROM kalban a 
		LEFT JOIN jadwal b ON kd_kelas = kodekelas AND a.tahun_akad = b.tahun_akad AND a.semester = b.smt
		WHERE $kodeorganisasi AND a.tahun_akad = $tahun_akad AND semester = $smt AND flagtampil = 1 AND
			  b.tahun_akad = $tahun_akad AND smt = $smt and kodekelas = kd_kelas and a.kode <> 0 AND kodepdpt = 0 AND (kodepasca = 1 or kodepasca = 2)
		GROUP BY kode
		ORDER BY b.flaghari, SUBSTR(b.jam,1,3), b.ruang";

		/*
		//untuk bulan desember 
		$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='12',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
					sum(if(a.tahun_akad=$tahun_akad and bulan='09',hadiraktual,0)) as HadirSeptember,
					sum(if(a.tahun_akad=$tahun_akad and bulan='10',hadiraktual,0)) as HadirOktober,
					sum(if(a.tahun_akad=$tahun_akad and bulan='11',hadiraktual,0)) as HadirNovember,
					sum(if(a.tahun_akad=$tahun_akad and bulan='12',hadiraktual,0)) as HadirDesember,
					sum(if(a.tahun_akad=$tahun_akad and bulan='01',hadiraktual,0)) as HadirJanuari,
					sum(if(a.tahun_akad=$tahun_akad and bulan='12',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
					sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
					sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui					
		FROM kalban a 
			LEFT JOIN jadwal b ON kd_kelas = kodekelas AND a.tahun_akad = b.tahun_akad AND a.semester = b.smt
		WHERE $kodeorganisasi AND a.tahun_akad = $tahun_akad AND semester = $smt AND flagtampil = 1 AND
			  b.tahun_akad = $tahun_akad AND smt = $smt and kodekelas = kd_kelas and a.kode <> 0 AND kodepdpt = 0 AND (kodepasca = 1 or kodepasca = 2)
		GROUP BY kode
		ORDER BY b.flaghari, SUBSTR(b.jam,1,3), b.ruang";*/
	} 
	//untuk program studi yang penginputan data hadirnya sebagian di PPAA sehingsa baik PPAA maupun program studi sama2 bisa mengakses data yang sama
	/*else if($_SESSION["kd_organisasi"] == "01.04.09.01" or $_SESSION["kd_organisasi"] == "01.02.09.01" or $_SESSION["kd_organisasi"] == "05.02.09.01" or $_SESSION["kd_organisasi"] == "01.06.09.01" or $_SESSION["kd_organisasi"] == "02.07.09.01") 
	{		
		$sql = "SELECT kd_kelas, b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='09',hadiraktual,0)) as HadirSeptember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='10',hadiraktual,0)) as HadirOktober,
				sum(if(a.tahun_akad=$tahun_akad and bulan='11',hadiraktual,0)) as HadirNovember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='12',hadiraktual,0)) as HadirDesember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='01',hadiraktual,0)) as HadirJanuari,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui
				FROM kalban a 
			LEFT JOIN jadwal b ON kd_kelas = kodekelas AND a.tahun_akad = b.tahun_akad AND a.semester = b.smt
		WHERE $kodeorganisasi AND a.tahun_akad = $tahun_akad AND semester = $smt AND flagtampil = 1 AND
			  b.tahun_akad = $tahun_akad AND smt = $smt and kodekelas = kd_kelas and a.kode <> 0 AND kodepdpt = 0 AND (kodepasca = '' or kodepasca = 0 or kodepasca = 1)
		GROUP BY kode
		ORDER BY b.flaghari, SUBSTR(b.jam,1,3), b.ruang";*/
		
		/*
		//untuk bulan desember 
		$sql = "SELECT kd_kelas, b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='12',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='09',hadiraktual,0)) as HadirSeptember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='10',hadiraktual,0)) as HadirOktober,
				sum(if(a.tahun_akad=$tahun_akad and bulan='11',hadiraktual,0)) as HadirNovember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='12',hadiraktual,0)) as HadirDesember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='01',hadiraktual,0)) as HadirJanuari,
				sum(if(a.tahun_akad=$tahun_akad and bulan='12',kehadiranseharusnya,0)) as 'KehadiranSeharusnya'
				FROM kalban a 
			LEFT JOIN jadwal b ON kd_kelas = kodekelas AND a.tahun_akad = b.tahun_akad AND a.semester = b.smt
		WHERE $kodeorganisasi AND a.tahun_akad = $tahun_akad AND semester = $smt AND flagtampil = 1 AND
			  b.tahun_akad = $tahun_akad AND smt = $smt and kodekelas = kd_kelas and a.kode <> 0 AND kodepdpt = 0 AND (kodepasca = '' or kodepasca = 0 or kodepasca = 1)
		GROUP BY kode
		ORDER BY b.flaghari, SUBSTR(b.jam,1,3), b.ruang";	
	}  */
	else 
	{  
		$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan ',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='09',hadiraktual,0)) as HadirSeptember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='10',hadiraktual,0)) as HadirOktober,
				sum(if(a.tahun_akad=$tahun_akad and bulan='11',hadiraktual,0)) as HadirNovember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='12',hadiraktual,0)) as HadirDesember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='01',hadiraktual,0)) as HadirJanuari,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',kehadiranseharusnya,0)) as 'KehadiranSeharusnya', 
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui
				
			FROM kalban a 
			LEFT JOIN jadwal b ON kd_kelas = kodekelas AND a.tahun_akad = b.tahun_akad AND a.semester = b.smt
		WHERE $kodeorganisasi AND a.tahun_akad = $tahun_akad AND semester = $smt AND flagtampil = 1 AND
			  b.tahun_akad = $tahun_akad AND smt = $smt and kodekelas = kd_kelas and a.kode <> 0 AND kodepdpt = 0 AND (kodepasca = '' or kodepasca = 0 or kodepasca = 1) 
		GROUP BY kode
		ORDER BY b.flaghari, SUBSTR(b.jam,1,3), b.ruang";
		//AND kodemk NOT LIKE 'U%'
		
		/*
		//untuk bulan desember 
		$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='12',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='09',hadiraktual,0)) as HadirSeptember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='10',hadiraktual,0)) as HadirOktober,
				sum(if(a.tahun_akad=$tahun_akad and bulan='11',hadiraktual,0)) as HadirNovember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='12',hadiraktual,0)) as HadirDesember,
				sum(if(a.tahun_akad=$tahun_akad and bulan='01',hadiraktual,0)) as HadirJanuari,
				sum(if(a.tahun_akad=$tahun_akad and bulan='12',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui				
			FROM kalban a 
			LEFT JOIN jadwal b ON kd_kelas = kodekelas AND a.tahun_akad = b.tahun_akad AND a.semester = b.smt
		WHERE $kodeorganisasi AND a.tahun_akad = $tahun_akad AND semester = $smt AND flagtampil = 1 AND
			  b.tahun_akad = $tahun_akad AND smt = $smt and kodekelas = kd_kelas and a.kode <> 0 AND kodepdpt = 0 AND (kodepasca = '' or kodepasca = 0)
		GROUP BY kode
		ORDER BY b.flaghari, SUBSTR(b.jam,1,3), b.ruang";	*/
	}			
} else {  //jika semester genap!
  if($_SESSION["username"] == "ppaa" or $_SESSION["kd_organisasi"] == '01.00.09.01'){
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='08',hadiraktual,0)) as 'HadirAgustus',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui	
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = $tahun_akad and semester = $smt and flagtampil = 1 and
				  b.tahun_akad = $tahun_akad and smt = $smt and kodekelas = kd_kelas and a.kode <> 0 and kodepdpt=0 and (kodepasca = 1 or kodepasca = 2)
			group by kode
		    order by b.flaghari, substr(b.jam,1,3), b.ruang";
  } else if($_SESSION["username"] == "mku"){
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = $tahun_akad and semester = $smt and flagtampil = 1 and
				  b.tahun_akad = $tahun_akad and smt = $smt and kodekelas = kd_kelas and a.kode <> 0 and (namamatakuliah = 'Ekonomika dan Pembangunan Sosial' or namamatakuliah = 'Hukum dan Pembangunan' or namamatakuliah = 'Sistem Ekonomi Indonesia' or namamatakuliah = 'Dasar-dasar Logika' or namamatakuliah = 'Filsafat Ilmu Sosial')
			group by kode
		    order by b.flaghari, substr(b.jam,1,3), namamatakuliah, kodekelas, kode"; 

  /*} else if($_SESSION["username"] == "orek" or $_SESSION["username"] == "politikpar" or $_SESSION["kd_organisasi"] == "01.04.09.01" or $_SESSION["kd_organisasi"] == "01.02.09.01" or $_SESSION["kd_organisasi"] == "05.02.09.01" or $_SESSION["kd_organisasi"] == "01.06.09.01" or $_SESSION["kd_organisasi"] == "02.07.09.01"){
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui				
			from kalban	a, jadwal b
			where $kodeorganisasi and a.tahun_akad = $tahun_akad and semester = $smt and flagtampil = 1 and
				  b.tahun_akad = $tahun_akad and smt = $smt and kodekelas = kd_kelas and a.kode <> 0 and (kodepasca='1')
			group by kode
		    order by b.flaghari, substr(b.jam,1,3), namamatakuliah, kodekelas, kode"; */

  } else {

	/*$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='08',hadiraktual,0)) as 'HadirAgustus',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$bulan_genap',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui					
			FROM kalban a 
			LEFT JOIN jadwal b ON kd_kelas = kodekelas AND b.tahun_akad = $tahun_akad AND smt = $smt AND a.tahun_akad = $tahun_akad AND semester = $smt AND flagtampil = 1 AND
			  a.kode <> 0 AND kodepdpt = 0 AND (kodepasca = '' or kodepasca = 0 or kodepasca = 1)
		WHERE $kodeorganisasi 
		GROUP BY kode
		ORDER BY b.flaghari, SUBSTR(b.jam,1,3), b.ruang"; */
  	
	
	$sql = "select b.Hari as Hari, b.Jam as Jam, sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',id,0)) as 'Id', Program, ProgramStudi, NamaMataKuliah, NamaKelas, NamaPengajar,
				sum(if(a.tahun_akad=$tahun_akad and bulan='02',hadiraktual,0)) as 'HadirFebruari',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='03',hadiraktual,0)) as 'HadirMaret',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='04',hadiraktual,0)) as 'HadirApril',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='05',hadiraktual,0)) as 'HadirMei',		   
				sum(if(a.tahun_akad=$tahun_akad and bulan='06',hadiraktual,0)) as 'HadirJuni',
				sum(if(a.tahun_akad=$tahun_akad and bulan='07',hadiraktual,0)) as 'HadirJuli',
				sum(if(a.tahun_akad=$tahun_akad and bulan='08',hadiraktual,0)) as 'HadirAgustus',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',kehadiranseharusnya,0)) as 'KehadiranSeharusnya',
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',IkutHitung,0)) as IkutHitung,
				sum(if(a.tahun_akad=$tahun_akad and bulan='$_bulan',SKSDisetujui,0)) as SKSDisetujui			
			FROM kalban a 
			LEFT JOIN jadwal b ON kd_kelas = kodekelas AND b.tahun_akad = $tahun_akad AND smt = $smt
		WHERE $kodeorganisasi AND a.tahun_akad = $tahun_akad AND semester = $smt AND flagtampil = 1 AND
			  a.kode <> 0 AND kodepdpt = 0 AND (kodepasca = '' or kodepasca = 0 or kodepasca = 1) AND kodemk NOT LIKE 'U%'
		GROUP BY kode
		ORDER BY b.flaghari, SUBSTR(b.jam,1,3), b.ruang"; 
	
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

$sql ="UPDATE kalban 
     SET 
          HadirAktual = ".$value->$bulan_aktual." 
     WHERE 
          Id = ".$value->Id." AND 
          tahun_akad = ".$tahun_akad." AND 
          semester = ".$smt." AND 
          bulan = '".$_bulan."'";
          
//old
//$sql ="UPDATE kalban SET HadirAktual = ".$value->$bulan_aktual." WHERE Id = ".$value->Id." and tahun_akad = ".$tahun_akad." and semester = ".$smt." and bulan = '".$vbulan."'";
//$sql ="UPDATE kalban SET HadirAktual = ".$value->$bulan_aktual." WHERE Id = ".$value->Id." and tahun = 2017 and bulan = '02'";
//$sql ="UPDATE kalban SET HadirAktual = ".$value->HadirDesember." WHERE Id = ".$value->Id." and tahun = 2016 and bulan = '12'";

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