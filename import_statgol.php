<?
include("menu.php");
include("bulan.php");
?>
<script>
	var cur = 0;
	function displayDiv(idx) {
		if (cur==idx) return true;
		document.getElementById("div"+cur).style.display = "none";
		document.getElementById("div"+idx).style.display = "block";
		cur = idx;
		return true;
	}
		
	window.onload = function() {
		return displayDiv(document.f.ShowLog.selectedIndex);
	}
</script>

<link href="style.css" rel="stylesheet" type="text/css" />
<body style="background:#eee;">
	<br><br><br><br>
	<div class="raised">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent">
				<h1>Update NPWP, Status Nikah dan Golongan</h1>
			</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
	<br>
	<div class='inset'>
		<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
			<div class='boxcontent'>
				<p>
					<form name="f2" method="post" enctype="multipart/form-data">
					<!--<form name="f1" method="post" enctype="multipart/form-data" action="tes.php">-->
						<div style="font-family:arial; font-size:13px; font-weight:bold; text-align:center; color:#555545;">
							<p style="padding-top:10px;">
								Tahun:
								<select name="tahun">
									<option value="<?echo date('Y')?>"><?echo date('Y')?></option>
									<option value="<?echo date('Y')-1?>"><?echo date('Y')-1?></option>
								</select>
								Bulan:
								<select name="bulan">
									<option style="color:blue;" value="<?php echo $month ?>"><?php echo $month ?></option>
									<option style="color:magenta;" value="Januari">Januari</option>
									<option style="color:blue;" value="Februari">Februari</option>
									<option style="color:green;" value="Maret">Maret</option>
									<option style="color:purple;" value="April">April</option>
									<option style="color:red;" value="Mei">Mei</option>
									<option style="color:blue;" value="Juni">Juni</option>
									<option style="color:green;" value="Juli">Juli</option>
									<option style="color:purple;" value="Agustus">Agustus</option>				
									<option style="color:red;" value="September">September</option>
									<option style="color:blue;" value="Oktober">Oktober</option>
									<option style="color:green;" value="November">November</option>
									<option style="color:purple;" value="Desember">Desember</option>
								</select>
							</p>
							<p style="margin-top:10px;">
								Silahkan Pilih File Excel: <input name="userfile" type="file" style="border:2px solid orange; "/>
								<input name="upload" type="submit" value="Update"/>
							</p>
						</div>
					</form>
				</p>
			</div>
		<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
	<br>
	<div class='inset'>
		<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
			<div class='boxcontent'>
				<div style="font-family:arial; font-size:10px; font-weight:bold; text-align:left; color:#555545;">
					<p>
						<u>Cara penggunaan:</u><br>
						- File yang akan di upload dalam format XLSX<br>
						- File yang akan di upload diambil dari kalgaji yang di export dari Sipeg Gaji pada menu Data GAJI Semua
					</p>
				</div>
			</div>
		<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
</body>
<?
if((!empty($_FILES["userfile"])) && ($_FILES['userfile']['error'] == 0)) {

	//inisialisasi
	$tahun = $_POST["tahun"];
	$vbulan = $_POST["bulan"];

	switch($vbulan)
	{
		case "Januari":
			$bulan = "01";
			$tahun_akad = $tahun;
			break;
		case "Februari":
			$bulan = "02";
			$tahun_akad = $tahun - 1;
			break;
		case "Maret":
			$bulan = "03";
			$tahun_akad = $tahun - 1;
			break;
		case "April":
			$bulan = "04";
			$tahun_akad = $tahun - 1;
			break;
		case "Mei":
			$bulan = "05";
			$tahun_akad = $tahun - 1;
			break;
		case "Juni":
			$bulan = "06";
			$tahun_akad = $tahun - 1;
			break;
		case "Juli":
			$bulan = "07";
			$tahun_akad = $tahun - 1;
			break;
		case "Agustus":
			$bulan = "08";
			$tahun_akad = $tahun;
			break;
		case "September":
			$bulan = "09";
			$tahun_akad = $tahun;
			break;
		case "Oktober":
			$bulan = "10";
			$tahun_akad = $tahun;
			break;
		case "November":
			$bulan = "11";
			$tahun_akad = $tahun;
			break;
		case "Desember":
			$bulan = "12";
			$tahun_akad = $tahun;
			break;
	}

	//cek data yang akan diinput sudah ada atau belum, jika sudah ada maka data akan dihapus dan diganti dengan data yang baru diupload
	$sql = "DELETE FROM pengajar_temp WHERE tahun = $tahun AND bulan = '$bulan'";
	$result = mysql_query($sql);

	// membaca file excel yang diupload
	//echo 'Nama File = '.$fileName;
	require_once "simplexlsx.class.php";
	$xlsx = new SimpleXLSX( $_FILES['userfile']['tmp_name'] );
	$excel = array(array());
	$excel = $xlsx->rows(1); //mulai baris kedua karena baris pertamanya judul kolom\

	// nilai awal counter untuk jumlah data yang sukses dan yang gagal diimport
	$sukses = 0;
	$gagal = 0;
	$j = 0;
	echo "
	<br>
	<div class='inset' text-align:center;>
		<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
			<div class='boxcontent' text-align:center;>
				<div style='font-family:arial; font-size:10px; font-weight:bold; text-align:center; color:#555545;'>
					<p style='text-align:center;'>
						<label>Log Import</label><br>
						<table style=\"width=80%; margin:auto\">
	";

	for($i=1; $i<sizeof($excel); $i++)
	{
		if($excel[$i][3] <> ''){
			
			// membaca data
			$nip = $excel[$i][3];
			$status_nikah = $excel[$i][17];
			$status_nikah = ($excel[$i][17] <> '0') ? $excel[$i][17] : '--' ;

			$golongan = $excel[$i][19];
			$golongan = ($excel[$i][19]<>'') ? $excel[$i][19] : '--' ;
			$golongan = str_replace('Kosong', '--', $golongan);

			$npwp = $excel[$i][33];
			$npwp = ($excel[$i][33]<>'') ? $excel[$i][33] : '--' ;

			// setelah data dibaca, sisipkan ke dalam tabel pengajar_temp
			$query = "
			INSERT INTO pengajar_temp (
				tahun,
				bulan,
				nip,
				status_nikah,
				golongan,
				npwp
			)

			VALUES (
				'$tahun',
				'$bulan',
				'$nip',
				'$status_nikah',
				'$golongan',
				'$npwp'
			)";

			//ekseskusi query
			$hasil = mysql_query($query) or die(mysql_error());

			// jika proses insert data sukses, maka counter $sukses bertambah
			// jika gagal, maka counter $gagal yang bertambah
			if ($hasil){
				$sukses++;
				#echo "<tr style='color:green; font-weight:bold'; font-size:7px; font-family:arial'><td>".$j."</td><td>".$d5."</td><td>sukses</td></tr>";
			} else {
				$gagal++;	
				echo "
				<th bgcolor='lightorange'>No</th><th>NIP</th><th>Upload</th>
				<tr style='padding-left:10px; vertical-align:middle; font:bold 11px verdana; border:1px solid orange;'><td>".$j + 1 ."</td><td>".$nip."</td><td>gagal</td></tr>";
			}
		}		
	}

	echo "</table>";

	// tampilan status sukses dan gagal
	echo "
	<p><b>Proses import data selesai<b></br>
	Jumlah data yang sukses diimport : ".$sukses."<br>
	Jumlah data yang gagal diimport : ".$gagal."</p>
					</p>
				</div>
			</div>
		<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
	";

	//hilangkan data dobel
	$sql = "SELECT DISTINCT nip, status_nikah, golongan, npwp FROM pengajar_temp WHERE tahun = '$tahun' AND bulan = '$bulan'";
	$result = mysql_query($sql) or die(mysql_error());
	$temp = array();
	while ($row = mysql_fetch_object($result)) {
		$temp[] = $row;
	}

	//update record status_nikah, golongan dan npwp pada tabel gaji_detail_2
	$sql = "UPDATE gaji_detail_2 a, pengajar_temp b
			SET a.status_nikah = b.status_nikah, a.golongan = b.golongan, a.npwp = b.npwp
			WHERE a.tahun = b.tahun and a.bulan = b.bulan and a.nip = b.nip";
	$result = mysql_query($sql) or die(mysql_error());

	/*
	//test data
	echo '<table>';
	foreach ($temp as $key => $value) {
		echo "<tr>";
		foreach ($value as $k => $v) {
			echo "<td>$v</td>";
		}
		echo "</tr>";
	}
	echo '</table>';
	*/
}
?>

<style>
	table {border:1px solid #FCC; border-collapse:collapse; font-family:arial,sans-serif; font-size:100%; text-align:center;}
	td,th {border:1px solid #FCC; border-collapse:collapse; padding:5px;}
	table th {background:#999;}
</style>