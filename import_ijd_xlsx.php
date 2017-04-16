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
<body style="background:#336666;">
	<br><br><br><br>
	<div class="raised">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent">
				<h1>Upload IJD</h1>
			</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
	<br>
	<div class='inset'>
		<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
			<div class='boxcontent'>
				<p>
					<form name="f1" method="post" enctype="multipart/form-data">
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
								<input name="upload" type="submit" value="Upload"/>
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
				<div style="font-family:tahoma; font-size:11px; font-weight:bold; text-align:left; color:#555545;">
					<p>
						<u>Petunjuk:</u><br>
						- File yang akan di upload dalam format XLSX<br>
						- File yang akan di upload bersumber dari Lampiran IJD Fakultas (Lamp IJD Fakultas - Current) yang di export dari Sipeg Gaji<br>
						- Selanjutnya update data Status Nikah, Golongan dan NPWP pada menu Update Status 
					</p>
				</div>
			</div>
		<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
</body>
<?
if((!empty($_FILES["userfile"])) && ($_FILES['userfile']['error'] == 0)) {
	//get file
	$fileName	= basename($_FILES['userfile']['name']);
	$fileExt	= substr($fileName, strrpos($fileName, '.') + 1);

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
	$sql = "DELETE FROM gaji_detail_2 WHERE tahun = $tahun AND bulan = '$bulan'";
	$result = mysql_query($sql);

	// membaca file excel yang diupload
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
		if($excel[$i][1] == 'Fakultas Ilmu Sosial dan Ilmu Politik'){
			$j++;
			// membaca data
			$nip = $excel[$i][3];
			$nama_pengajar = $excel[$i][4];
			$gaji_pokok_bhmn_beban_ui = toNumber($excel[$i][5]);
			$tunjangan_keluarga_bhmn_beban_ui = toNumber($excel[$i][6]);
			$tunjangan_fungsional_bhmn_beban_ui = toNumber($excel[$i][7]);
			$tunjangan_tugas_belajar_bhmn_beban_ui = toNumber($excel[$i][8]);
			$gaji_pokok_bhmn_beban_fak = toNumber($excel[$i][9]);
			$tunjangan_keluarga_bhmn_beban_fak = toNumber($excel[$i][10]);
			$tunjangan_fungsional_bhmn_beban_fak = toNumber($excel[$i][11]);
			$tunjangan_tugas_belajar_bhmn_beban_fak = toNumber($excel[$i][12]);
			$gp_tunjangan_bhmn_beban_fak = toNumber($excel[$i][13]);
			$tambahan_dasar_kesjahteraan = toNumber($excel[$i][14]);
			$tunjangan_skema_struktural = toNumber($excel[$i][15]);
			$tunjangan_skema_inti_penelitian_beban_ui = toNumber($excel[$i][16]);
			$imbalan_pengajaran_skema_inti_xu_beban_fak = toNumber($excel[$i][17]);
			$imbalan_pengajaran_skema_inti_xf = toNumber($excel[$i][18]);
			$imbalan_pengajaran_skema_lain = toNumber($excel[$i][19]);
			$imbalan_pengajaran_lintas_fak = toNumber($excel[$i][20]);
			$imbalan_pengajaran_bahasa_asing = toNumber($excel[$i][21]);
			$imbalan_pengajaran_ppkpt = toNumber($excel[$i][22]);
			$tunjangan_koordinator_ppkpt = toNumber($excel[$i][23]);
			$tunjangan_koordinator_mata_ajar = toNumber($excel[$i][24]);
			$honor_pembimbingan = toNumber($excel[$i][25]);
			$honor_menguji = toNumber($excel[$i][26]);
			$tunjangan_saf_dgbf = toNumber($excel[$i][27]);
			$honor_tugas_rutin_non_struktural = toNumber($excel[$i][28]);
			$honor_tugas_tambahan = toNumber($excel[$i][29]);
			$insentif_hadir_kerja = toNumber($excel[$i][30]);
			$insentif_transport = toNumber($excel[$i][31]);
			$insentif_umak = toNumber($excel[$i][32]);
			$insentif_penelitian = toNumber($excel[$i][33]);
			$insentif_selisih = toNumber($excel[$i][34]);
			$insentif_gbpk = toNumber($excel[$i][35]);
			$insentif_tgs_belajar = toNumber($excel[$i][36]);
			$tamb_kesjah_kesehatan = toNumber($excel[$i][37]);
			$komponen_remunerasi_khusus = toNumber($excel[$i][38]);
			$jaminan_hari_tua = toNumber($excel[$i][39]);
			$jaminan_pensiun = toNumber($excel[$i][40]);
			$jaminan_pem_kesehatan = toNumber($excel[$i][41]);
			$jaminan_kematian = toNumber($excel[$i][42]);
			$jaminan_kec_kerja = toNumber($excel[$i][43]);
			$dplk_beban_fak = toNumber($excel[$i][44]);
			$bpjs_beban_fak = toNumber($excel[$i][45]);
			$total_beban_fak = toNumber($excel[$i][46]);
			$kurang_lebih_bayar_bulan_sebelumnya = toNumber($excel[$i][47]);
			$total_kurang_lebih_bayar = toNumber($excel[$i][48]);
			$pph_ditanggung_lembaga = toNumber($excel[$i][49]);
			$imbal_jasa_bruto_seluruhnya = toNumber($excel[$i][50]);
			$pph_seluruhnya = toNumber($excel[$i][51]);
			$imbal_jasa_neto_seluruhnya = toNumber($excel[$i][52]);
			$tunda_bayar = toNumber($excel[$i][53]);
			$potongan_jht = toNumber($excel[$i][54]);
			$potongan_jam_pensiun = toNumber($excel[$i][55]);
			$potongan_bpjs_kesehatan = toNumber($excel[$i][56]);
			$potongan_dplk = toNumber($excel[$i][57]);
			$jumlah_ditransfer_ke_dosen_beban_fak = toNumber($excel[$i][58]);
			$jamsostek_transfer_beban_fak = toNumber($excel[$i][59]);
			$dplk_transfer_beban_fak = toNumber($excel[$i][60]);
			$bpjs_kesehatan_transfer_beban_fak = toNumber($excel[$i][61]);
			$nama_di_rekening = $excel[$i][62];
			$bank = $excel[$i][63];
			$no_rekening = $excel[$i][64];

			$nama_pengajar = addslashes($nama_pengajar);
			$nama_di_rekening = addslashes($nama_di_rekening);
			$total_xf = $imbalan_pengajaran_skema_inti_xf + $imbalan_pengajaran_skema_lain + $imbalan_pengajaran_lintas_fak;

			// setelah data dibaca, sisipkan ke dalam tabel gaji_detail_2
			$query = "
			INSERT INTO gaji_detail_2 (
				tahun,
				bulan,
				nip,
				nama_pengajar,
				gaji_pokok_bhmn_beban_ui,
				tunjangan_keluarga_bhmn_beban_ui,
				tunjangan_fungsional_bhmn_beban_ui,
				tunjangan_tugas_belajar_bhmn_beban_ui,
				gaji_pokok_bhmn_beban_fak,
				tunjangan_keluarga_bhmn_beban_fak,
				tunjangan_fungsional_bhmn_beban_fak,
				tunjangan_tugas_belajar_bhmn_beban_fak,
				gp_tunjangan_bhmn_beban_fak,
				tambahan_dasar_kesjahteraan,
				tunjangan_skema_struktural,
				tunjangan_skema_inti_penelitian_beban_ui,
				imbalan_pengajaran_skema_inti_xu_beban_fak,
				imbalan_pengajaran_skema_inti_xf,
				imbalan_pengajaran_skema_lain,
				imbalan_pengajaran_lintas_fak,
				imbalan_pengajaran_bahasa_asing,
				imbalan_pengajaran_ppkpt,
				tunjangan_koordinator_ppkpt,
				tunjangan_koordinator_mata_ajar,
				honor_pembimbingan,
				honor_menguji,
				tunjangan_saf_dgbf,
				honor_tugas_rutin_non_struktural,
				honor_tugas_tambahan,
				insentif_hadir_kerja,
				insentif_transport,
				insentif_umak,
				insentif_penelitian,
				insentif_selisih,
				insentif_gbpk,
				insentif_tgs_belajar,
				tamb_kesjah_kesehatan,
				komponen_remunerasi_khusus,
				jaminan_hari_tua,
				jaminan_pensiun,
				jaminan_pem_kesehatan,
				jaminan_kematian,
				jaminan_kec_kerja,
				dplk_beban_fak,
				bpjs_beban_fak,
				total_beban_fak,
				kurang_lebih_bayar_bulan_sebelumnya,
				total_kurang_lebih_bayar,
				pph_ditanggung_lembaga,
				imbal_jasa_bruto_seluruhnya,
				pph_seluruhnya,
				imbal_jasa_neto_seluruhnya,
				tunda_bayar,
				potongan_jht,
				potongan_jam_pensiun,
				potongan_bpjs_kesehatan,
				potongan_dplk,
				jumlah_ditransfer_ke_dosen_beban_fak,
				jamsostek_transfer_beban_fak,
				dplk_transfer_beban_fak,
				bpjs_kesehatan_transfer_beban_fak,
				nama_di_rekening,
				bank,
				no_rekening
			)

			VALUES (
				$tahun,
				'$bulan',
				'$nip',
				'$nama_pengajar',
				'$gaji_pokok_bhmn_beban_ui',
				'$tunjangan_keluarga_bhmn_beban_ui',
				'$tunjangan_fungsional_bhmn_beban_ui',
				'$tunjangan_tugas_belajar_bhmn_beban_ui',
				'$gaji_pokok_bhmn_beban_fak',
				'$tunjangan_keluarga_bhmn_beban_fak',
				'$tunjangan_fungsional_bhmn_beban_fak',
				'$tunjangan_tugas_belajar_bhmn_beban_fak',
				'$gp_tunjangan_bhmn_beban_fak',
				'$tambahan_dasar_kesjahteraan',
				'$tunjangan_skema_struktural',
				'$tunjangan_skema_inti_penelitian_beban_ui',
				'$imbalan_pengajaran_skema_inti_xu_beban_fak',
				'$imbalan_pengajaran_skema_inti_xf',
				'$imbalan_pengajaran_skema_lain',
				'$imbalan_pengajaran_lintas_fak',
				'$imbalan_pengajaran_bahasa_asing',
				'$imbalan_pengajaran_ppkpt',
				'$tunjangan_koordinator_ppkpt',
				'$tunjangan_koordinator_mata_ajar',
				'$honor_pembimbingan',
				'$honor_menguji',
				'$tunjangan_saf_dgbf',
				'$honor_tugas_rutin_non_struktural',
				'$honor_tugas_tambahan',
				'$insentif_hadir_kerja',
				'$insentif_transport',
				'$insentif_umak',
				'$insentif_penelitian',
				'$insentif_selisih',
				'$insentif_gbpk',
				'$insentif_tgs_belajar',
				'$tamb_kesjah_kesehatan',
				'$komponen_remunerasi_khusus',
				'$jaminan_hari_tua',
				'$jaminan_pensiun',
				'$jaminan_pem_kesehatan',
				'$jaminan_kematian',
				'$jaminan_kec_kerja',
				'$dplk_beban_fak',
				'$bpjs_beban_fak',
				'$total_beban_fak',
				'$kurang_lebih_bayar_bulan_sebelumnya',
				'$total_kurang_lebih_bayar',
				'$pph_ditanggung_lembaga',
				'$imbal_jasa_bruto_seluruhnya',
				'$pph_seluruhnya',
				'$imbal_jasa_neto_seluruhnya',
				'$tunda_bayar',
				'$potongan_jht',
				'$potongan_jam_pensiun',
				'$potongan_bpjs_kesehatan',
				'$potongan_dplk',
				'$jumlah_ditransfer_ke_dosen_beban_fak',
				'$jamsostek_transfer_beban_fak',
				'$dplk_transfer_beban_fak',
				'$bpjs_kesehatan_transfer_beban_fak',
				'$nama_di_rekening',
				'$bank',
				'$no_rekening'
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
}

function toNumber($n){
	$number = str_replace('.', '', $n);
	$number = str_replace(',', '', $number);
	return $number;
}
?>

<style>
	table {border:1px solid #FCC; border-collapse:collapse; font-family:arial,sans-serif; font-size:100%; text-align:center;}
	td,th {border:1px solid #FCC; border-collapse:collapse; padding:5px;}
	table th {background:#999;}
</style>