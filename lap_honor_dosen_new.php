<?php
//koneksi ke database
if(!session_id()) session_start();
include("conn.php");
$username = $_SESSION["username"];
#setting session, hadir aktual, tahun dan bulan

$kodeorganisasi = $_SESSION["kodeorganisasi"];
$_SESSION["tahun"] = $_POST["tahun"];
$_SESSION["bulan_gasal"] = $_POST["bulan_gasal"];
$tahun = $_SESSION["tahun"];
$bulan_gasal = $_SESSION["bulan_gasal"];
$nip = $_POST["nip"];
$namapengajar = $_POST["namapengajar"];

if(isset($bulan_gasal))$bulan = $bulan_gasal;

switch($bulan)
{
	case "Januari":
		$periode_bulan = "01";
		$hadiraktual = "hadirjanuari";
		break;
	case "Februari":
		$periode_bulan = "02";
		$hadiraktual = "hadirfebruari";
		break;
	case "Maret":
		$periode_bulan = "03";
		$hadiraktual = "hadirmaret";
		break;
	case "April":
		$periode_bulan = "04";
		$hadiraktual = "hadirapril";
		break;
	case "Mei":
		$periode_bulan = "05";
		$hadiraktual = "hadirmei";
		break;
	case "Juni":
		$periode_bulan = "06";
		$hadiraktual = "hadirjuni";
		break;
	case "Juli":
		$periode_bulan = "07";
		$hadiraktual = "hadirjuli";
		break;
	case "Agustus":
		$periode_bulan = "08";
		$hadiraktual = "hadiragustus";
		break;
	case "September":
		$periode_bulan = "09";
		$hadiraktual = "hadirseptember";
		break;
	case "Oktober":
		$periode_bulan = "10";
		$hadiraktual = "hadiroktober";
		break;
	case "November":
		$periode_bulan = "11";
		$hadiraktual = "hadirnovember";
		break;
	case "Desember":
		$periode_bulan = "12";
		$hadiraktual = "hadirdesember";
		break;
}

//if($username == "admin" or $username == "remunerasifisipui" or $_SESSION['user_nip'] == '090613091' or $_SESSION['user_nip'] == '090613045'){ report untuk admin

if($_SESSION['hak_akses'] == 1 or $_SESSION['hak_akses'] == 3){
	require('draw.php');

	#ambil data di tabel dan masukkan ke array
	$judullaporan = "Slip Gaji Dosen Periode $bulan $tahun";
	$_SESSION["judullaporan"] = $judullaporan;

	$bulantahun = $periode_bulan.$tahun;

	#ambil data dosen dari kalban
	$sql = "SELECT DISTINCT jabatanfungsional, skema FROM kalban WHERE tahun = '$tahun' and bulan = '$periode_bulan' and nip = '$nip'";
	$result = mysql_query ($sql) or die (mysql_error());
	$kalban = array();
	while ($row = mysql_fetch_assoc($result)){
		$kalban[] = $row;
	}

	#ambil data ijd dosen
	$query = "SELECT * FROM gaji_detail_2
				WHERE nip = '$nip' and tahun = '$tahun' and bulan = '$periode_bulan'";

	$sql = mysql_query ($query) or die ("error data pengajar: ".mysql_error());
	$ijd = array();
	while ($row = mysql_fetch_assoc($sql)) {
		$ijd[] = $row;
	}

	switch ($kalban[0]['skema']){
		case "Skema Lain": 
			$skema = "Skema Lain";
			$tunjangan_skema = 0;
			$honor_Xf = $ijd[0]['imbalan_pengajaran_skema_lain'] + $ijd[0]['imbalan_pengajaran_lintas_fak'];
			break;
		case "Skema Inti": 
			$skema = "Skema Inti";
			$tunjangan_skema = 0;
			$honor_Xf = $ijd[0]['imbalan_pengajaran_skema_inti_xf'];
			break;
		/*
		case 2: 
			$skema = "Inti Penelitian";
			$tunjangan_skema = $ts_inti_penelitian;
			$honor_Xf = $xf_skema_inti;
			break;
		*/
		case "Struktural": 
			$skema = "Struktural";
			$tunjangan_skema = $ijd[0]['tunjangan_skema_struktural'];
			$honor_Xf = $ijd[0]['imbalan_pengajaran_skema_inti_xf'];
			break;
	}

	#sertakan library FPDF dan bentuk objek
	require_once ("../remun/fpdf/fpdf.php");

	#tampilkan judul laporan
	class PDF extends FPDF
	{
	//Page header
		function Header()
		{
			$this->Image('images/makara_gold.jpg',10,5,13);
			$this->SetY(6);
			$this->Cell(15);
			$this->SetFont('Arial','B','9');
			$this->Cell(0,4, 'Universitas Indonesia', '0', 1, 'L');
			$this->Cell(15);
			$this->Cell(0,4, 'Fakultas Ilmu Sosial dan Ilmu Politik', '0', 1, 'L');
			$this->Cell(15);
			$this->Cell(0,4, $_SESSION["programstudi"], '0', 1, 'L');
			$this->Ln(4);
			$this->SetFont('Arial','B','9');
			$this->Cell(0,3, $_SESSION["judullaporan"], '0', 1, 'C');
			$this->Ln(4);
		}
	//Page footer
		function Footer()
		{
			//Position at 1.5 cm from bottom
			$this->SetY(-15);
			//Arial italic 8
			$this->SetFont('Arial','I',8);
			//Page number
			$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'C');
		}

	}

	$pdf = new PDF('L','mm','A4');
	$pdf->AddPage();
	$pdf->AliasNbPages();

	/**************************************\
	 proses pencetakan data dosen
	\**************************************/

$honor_xu = 100 / 66 * $ijd[0]['imbalan_pengajaran_skema_inti_xu_beban_fak'];

	$jumlah_ditransfer = $ijd[0]['jumlah_ditransfer_ke_dosen_beban_fak'] + ((100 / 66 * $ijd[0]['imbalan_pengajaran_skema_inti_xu_beban_fak']) - $ijd[0]['imbalan_pengajaran_skema_inti_xu_beban_fak']);


	#tampilkan data tabelnya
	$pdf->SetFillColor(215,225,245);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(215,225,245);
	$pdf->SetFont('Arial','B','9');
	$pdf->Cell(277,8,'Data Dosen',0,1,'L',true);

	#Data Dosen
	$pdf->Cell(3);
	$pdf->SetFont('Arial','','9');
	$pdf->Cell(40,8,'NIP/NUP',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $ijd[0]['nip'], 0, 0, 'L');
	$pdf->Cell(50);
	$pdf->Cell(50,8,'Jumlah Ditransfer',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, number_format($jumlah_ditransfer), 0, 1, 'L');
	$pdf->Cell(3);
	$pdf->Cell(40,8,'Nama',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $ijd[0]['nama_pengajar'], 0, 0, 'L');
	$pdf->Cell(50);
	$pdf->Cell(50, 8, 'Nama di Rekening', 0, 0, 'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $ijd[0]['nama_di_rekening'], 0, 1, 'L');
	$pdf->Cell(3);
	$pdf->Cell(40,8,'Skema Pengajaran',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $skema, 0, 0, 'L');
	$pdf->Cell(50);
	$pdf->Cell(50, 8, 'Nama Bank', 0, 0, 'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $ijd[0]['bank'], 0, 1, 'L');
	$pdf->Cell(3);
	$pdf->Cell(40,8,'Jabatan Fungsional',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $kalban[0]['jabatanfungsional'], 0, 0, 'L');
	$pdf->Cell(50);
	$pdf->Cell(50, 8, 'Nomor Rekening', 0, 0, 'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $ijd[0]['no_rekening'], 0, 1, 'L');
	$pdf->Cell(3);
	$pdf->Cell(40, 8, 'Status Perkawinan', 0, 0, 'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $ijd[0]['status_nikah'], 0, 0, 'L');
	$pdf->Cell(50);
	$pdf->Cell(50, 8, 'NPWP', 0, 0, 'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(50, 8, $ijd[0]['npwp'], 0, 1, 'L');
	$pdf->Ln(4);

	#Honor dan Tunjangan
	$pdf->SetFont('Arial','B','9');
	$pdf->Cell(277,8,'Honor dan Tunjangan (sesuai ketetapan universitas):',0,1,'L',true);

	#$pdf->SetY(58);
	$pdf->SetFont('Arial','','9');
	$pdf->Cell(3);
	$pdf->Cell(52,8,'Dasar Kesejahteraan Setara BHMN',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['tambahan_dasar_kesjahteraan']), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(52,8,'Total Honor Xu',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($honor_xu), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(57,8,'Insentif Kehadiran',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20,8, number_format($ijd[0]['insentif_hadir_kerja']), 0, 1, 'R');
	$pdf->Cell(3);
	$pdf->Cell(52,8,'Tunjangan Keluarga',0,0,'L');  //fix
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['tunjangan_keluarga_bhmn_beban_ui']), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(52,8,'Total Honor Xf '.$skema,0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($honor_Xf), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(57,8,'Insentif GBPK',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['insentif_gbpk']), 0, 1, 'R');
	$pdf->Cell(3);
	$pdf->Cell(52,8,'Tunjangan Fungsional',0,0,'L');  //fix
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['tunjangan_fungsional_bhmn_beban_fak']), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(52,8,'Total Honor Menguji',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['honor_menguji']), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(57,8,'Tunjangan Kesehatan',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['tamb_kesjah_kesehatan']), 0, 1, 'R');
	$pdf->Cell(3);
	$pdf->Cell(52,8,'Tunjangan '.$skema,0,0,'L');  //fix
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($tunjangan_skema), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(52,8,'Total Honor Bimbingan',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['honor_pembimbingan']), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(57,8,'Kurang/Lebih Bayar',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['kurang_lebih_bayar_bulan_sebelumnya']), 0, 1, 'R');
	$pdf->Cell(3);
	$pdf->Cell(52,8,'Tunjangan SAF/DGBF',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['tunjangan_saf_dgbf']), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(52,8,' ',0,0,'L');
	$pdf->Cell(3,8,' ',0,0,'L');
	$pdf->Cell(20, 8, ' ', 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(57,8,'Jumlah Ditransfer',0,0,'L');
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($jumlah_ditransfer), 0, 1, 'R');
	$pdf->Cell(3);
	$pdf->Cell(52,8,'Honor Tugas Rutin Non Struktural',0,0,'L');  //fix
	$pdf->Cell(3,8,':',0,0,'L');
	$pdf->Cell(20, 8, number_format($ijd[0]['honor_tugas_rutin_non_struktural']), 0, 0, 'R');
	$pdf->Cell(20);
	$pdf->Cell(52,8,' ',0,0,'L');
	$pdf->Cell(3,8,' ',0,0,'L');
	$pdf->Cell(20, 8, ' ', 0, 0, 'R');

	$pdf->Ln(20);
	$pdf->SetFont('Arial','BUI','8');
	$pdf->Cell(80,5,'Keterangan:',0,1,'L');
	$pdf->SetFont('Arial','','8');
	$pdf->Cell(80,5,'- Nomor Rekening yang dipakai adalah nomor Rekening Fakultas Induk',0,1,'L');
	$pdf->Cell(80,5,'- Maksimal 4 SKS untuk Dosen dengan Skema Struktural',0,1,'L');
	$pdf->Cell(80,5,'- Maksimal 6 SKS untuk Dosen Inti Penelitian',0,1,'L');
	$pdf->Cell(80,5,'- Maksimal 8 SKS untuk Dosen Inti Pengajaran',0,1,'L');
	$pdf->Cell(80,5,'- Maksimal 4 SKS untuk Dosen Skema Lain (Dosen Tidak Tetap/Dosen Luar Biasa/Pensiun)',0,1,'L');
	$pdf->Cell(80,5,'- Pemilihan berdasarkan SKS dengan koefisien tertinggi sampai terendah',0,1,'L');
	$pdf->Cell(80,5,'- Rincian Data Honor Pengajaran Terlampir',0,1,'L');

	#cetak garis
	$style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(200,200,200));
	$pdf->Line(10, 29.1, 10, 77, $style); //garis vertikal sebelah kiri box data dosen
	$pdf->Line(287, 29.1, 287, 77, $style);  //garis vertikal sebelah kanan box data dosen
	$pdf->Line(10, 77, 287, 77, $style);  //garis horizontal box data dosen
	$pdf->Line(10, 81, 10, 137, $style); //garis vertikal sebelah kiri box honor tunjangan
	$pdf->Line(287, 81, 287, 137, $style);  //garis vertikal kanan box honor tunjangan
	$pdf->Line(10, 137, 287, 137, $style);  //garis horizontal box honor tunjangan

	$pdf->Ln(8);

	/**************************************\
	 proses pencetakan rincian matakuliah
	\**************************************/

	#ambil data ikut hitung = 1
	$query1 = "SELECT programstudi, program, bobotkontribusi, sksekivalen, sksdisetujui, namamatakuliah, sks, namakelas, hadiraktual, kehadiranseharusnya, honorxuskemainti, honorxfskemainti + honorxfskemalain + honorxflintasfak as honorxf, totalhonor
				FROM kalban 
				WHERE nip = '$nip' and ikuthitung = 1 and tahun = $tahun and bulan = '$periode_bulan' and kodepdpt=0
				ORDER BY program, programstudi, namamatakuliah, kodekelas";

	$sql1 = mysql_query ($query1) or die ("Gagal ngambil data Mata Kuliah ikut hitung 1: ".mysql_error());
	$data1 = array();
	while ($row1 = mysql_fetch_assoc($sql1)) {
		array_push($data1, $row1);
	}

	#hitung sub total ikut hitung = 1
	$subtotalhonorxu1 = 0;
	$subtotalhonorxf1 = 0;
	$subtotalhonor1 = 0;
	$sksEkivalen1 = 0;
	$i = 0;
	$rows1 = mysql_num_rows($sql1);

	for ($i; $i<$rows1; $i++) {	
		$subtotalhonorxu1 = $subtotalhonorxu1 + $data1["$i"]["honorxuskemainti"];
		$subtotalhonorxf1 = $subtotalhonorxf1 + $data1["$i"]["honorxf"];
		$subtotalhonor1 = $subtotalhonor1 + $data1["$i"]["totalhonor"];
		$sksEkivalen1 = $sksEkivalen1 + $data1["$i"]["sksekivalen"];
	}

	#ambil data ikut hitung = 0
	$query2 = "SELECT programstudi, program, bobotkontribusi, sksekivalen, sksdisetujui, namamatakuliah, sks, namakelas, hadiraktual, kehadiranseharusnya, honorxuskemainti, honorxfskemainti + honorxfskemalain + honorxflintasfak as honorxf, totalhonor
				FROM kalban 
				WHERE nip = '$nip' and ikuthitung = 0 and tahun = $tahun and bulan = '$periode_bulan' and kodepdpt=0
				ORDER BY program, programstudi, namamatakuliah, kodekelas";

	$sql2 = mysql_query ($query2) or die ("Gagal ngambil data Mata Kuliah ikut hitung 0: ".mysql_error());
	$data2 = array();
	while ($row2 = mysql_fetch_assoc($sql2)) {
		array_push($data2, $row2);
	}

	#hitung sub total ikut hitung = 0
	$subtotalhonorxu2 = 0;
	$subtotalhonorxf2 = 0;
	$subtotalhonor2 = 0;
	$sksEkivalen2 = 0;
	$i = 0;
	$rows2 = mysql_num_rows($sql2);

	for ($i; $i<$rows2; $i++) {	
		$subtotalhonorxu2 = $subtotalhonorxu2 + $data2["$i"]["honorxuskemainti"];
		$subtotalhonorxf2 = $subtotalhonorxf2 + $data2["$i"]["honorxf"];
		$subtotalhonor2 = $subtotalhonor2 + $data2["$i"]["totalhonor"];
		$sksEkivalen2 = $sksEkivalen2 + $data2["$i"]["sksekivalen"];
	}

	#setting judul laporan dan header tabel
	$header = array(
		array("label"=>"Program Studi", "length"=>40, "align"=>"C", "align2"=>"L"),
		array("label"=>"Program", "length"=>17, "align"=>"L", "align2"=>"L"),
		array("label"=>"Bobot Kontribusi", "length"=>15, "align"=>"L", "align2"=>"C"),
		array("label"=>"SKS Ekivalen", "length"=>13, "align"=>"C", "align2"=>"C"),
		array("label"=>"SKS Disetujui", "length"=>15, "align"=>"C", "align2"=>"C"),
		array("label"=>"Nama Mata Kuliah", "length"=>62, "align"=>"C", "align2"=>"L"),
		array("label"=>"SKS", "length"=>8, "align"=>"C", "align2"=>"C"),
		array("label"=>"Nama Kelas", "length"=>32, "align"=>"C", "align2"=>"L"),
		array("label"=>"Hadir Aktual;", "length"=>9, "align"=>"C", "align2"=>"C"),
		array("label"=>"Wajib Hadir", "length"=>9, "align"=>"C", "align2"=>"C"),
		array("label"=>"Honor Xu", "length"=>20, "align"=>"C", "align2"=>"R"),
		array("label"=>"Honor Xf", "length"=>20, "align"=>"C", "align2"=>"R"),
		array("label"=>"Total Honor", "length"=>20, "align"=>"C", "align2"=>"R")
	);

	#buat header tabel ikut hitung = 1
	$pdf->SetFont('Arial','I','8');
	$pdf->Cell(0,4, 'Rincian Data Honor Pengajaran Sesuai Ketetapan Universitas', '0', 1, 'L');	
	$pdf->SetFont('Arial','B','8');
	$pdf->SetFillColor(215,225,245);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(215,225,245);

	$pdf->Cell(40,4, '', 1, 0, 'C', true);		
	$pdf->Cell(17,4, '', 1, 0, 'C', true);		
	$pdf->Cell(15,4, 'Bobot', 1, 0, 'C', true);		
	$pdf->Cell(13,4, 'SKS', 1, 0, 'C', true);		
	$pdf->Cell(15,4, '', 1, 0, 'C', true);		
	$pdf->Cell(62,4, '', 1, 0, 'C', true);		
	$pdf->Cell(8,4, '', 1, 0, 'C', true);		
	$pdf->Cell(32,4, '', 1, 0, 'C', true);		
	$pdf->Cell(9,4, 'Jml', 1, 0, 'C', true);		
	$pdf->Cell(9,4, 'Wajib', 1, 0, 'C', true);		
	$pdf->Cell(20,4, 'Honor', 1, 0, 'C', true);		
	$pdf->Cell(20,4, 'Honor', 1, 0, 'C', true);		
	$pdf->Cell(20,4, 'Total', 1, 1, 'C', true);	

	$pdf->Cell(40,4, 'Program Studi', 1, 0, 'C', true);
	$pdf->Cell(17,4, 'Jenjang', 1, 0, 'C', true);
	$pdf->Cell(15,4, 'Kontribusi', 1, 0, 'C', true);
	$pdf->Cell(13,4, 'Ekivalen', 1, 0, 'C', true);
	$pdf->Cell(15,4, 'Disetujui', 1, 0, 'C', true);
	$pdf->Cell(62,4, 'Nama Mata Kuliah', 1, 0, 'C', true);
	$pdf->Cell(8,4, 'SKS', 1, 0, 'C', true);
	$pdf->Cell(32,4, 'Kelas', 1, 0, 'C', true);
	$pdf->Cell(9,4, 'Hadir', 1, 0, 'C', true);
	$pdf->Cell(9,4, 'Hadir', 1, 0, 'C', true);
	$pdf->Cell(20,4, 'Xu', 1, 0, 'C', true);
	$pdf->Cell(20,4, 'Xf', 1, 0, 'C', true);
	$pdf->Cell(20,4, 'Honor', 1, 1, 'C', true);

	#tampilkan data rincian mata kuliah
	$pdf->SetFillColor(238,249,269);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(221,232,252);
	$pdf->SetFont('Arial','','8');
	$fill = false;

	#tampilkan data ikut hitung = 1
	foreach ($data1 as $baris1) {
		$i = 0;
		$t = 15;
		foreach ($baris1 as $cell1) {
			if ($i == 10 or $i == 11 or $i == 12){ //format honor
					$cell1 = number_format($cell1);
				} else if ($i == 3){
					$cell1 = number_format($cell1,"2"); //format sks ekivalen	
				} else {
					$cell1 = $cell1;
				}

			$pdf->Cell($header[$i]['length'], 4, $cell1, 1, '0', $header[$i]['align2'], $fill);
			$i++;
		}	
		$fill = !$fill;
		$pdf->Ln();
		$t=+8;
	}

	//tampilkan total ikut hitung = 1
	$pdf->Ln(1);
	$pdf->SetFillColor(238,249,269);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(221,232,252);
	$pdf->SetFont('Arial','B','8');
	$pdf->cell(72); //220
	$pdf->cell(13,4,number_format($sksEkivalen1,"2"),1,0,'C',true);
	$pdf->cell(135);
	$pdf->cell(20,4,number_format($subtotalhonorxu1),1,0,'R',true);
	$pdf->cell(20,4,number_format($subtotalhonorxf1),1,0,'R',true);
	$pdf->cell(20,4,number_format($subtotalhonor1),1,0,'R',true);
	$pdf->Ln(8);

	#buat header tabel ikut hitung = 0
	if ($rows2 > 0){
		$pdf->SetFont('Arial','I','8');
		$pdf->Cell(0,4, 'Rincian Data Honor Pengajaran dengan Ikut Hitung = 0 (Sesuai Kebijakan Universitas Tidak Terbayarkan)', '0', 1, 'L');	

		$pdf->SetFont('Arial','B','8');
		$pdf->SetFillColor(215,225,245);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(215,225,245);

		$pdf->Cell(40,4, '', 1, 0, 'C', true);		
		$pdf->Cell(17,4, '', 1, 0, 'C', true);		
		$pdf->Cell(15,4, 'Bobot', 1, 0, 'C', true);		
		$pdf->Cell(13,4, 'SKS', 1, 0, 'C', true);		
		$pdf->Cell(15,4, '', 1, 0, 'C', true);		
		$pdf->Cell(62,4, '', 1, 0, 'C', true);		
		$pdf->Cell(8,4, '', 1, 0, 'C', true);		
		$pdf->Cell(32,4, '', 1, 0, 'C', true);		
		$pdf->Cell(9,4, 'Jml', 1, 0, 'C', true);		
		$pdf->Cell(9,4, 'Wajib', 1, 0, 'C', true);		
		$pdf->Cell(20,4, 'Honor', 1, 0, 'C', true);		
		$pdf->Cell(20,4, 'Honor', 1, 0, 'C', true);		
		$pdf->Cell(20,4, 'Total', 1, 1, 'C', true);	

		$pdf->Cell(40,4, 'Program Studi', 1, 0, 'C', true);
		$pdf->Cell(17,4, 'Jenjang', 1, 0, 'C', true);
		$pdf->Cell(15,4, 'Kontribusi', 1, 0, 'C', true);
		$pdf->Cell(13,4, 'Ekivalen', 1, 0, 'C', true);
		$pdf->Cell(15,4, 'Disetujui', 1, 0, 'C', true);
		$pdf->Cell(62,4, 'Nama Mata Kuliah', 1, 0, 'C', true);
		$pdf->Cell(8,4, 'SKS', 1, 0, 'C', true);
		$pdf->Cell(32,4, 'Kelas', 1, 0, 'C', true);
		$pdf->Cell(9,4, 'Hadir', 1, 0, 'C', true);
		$pdf->Cell(9,4, 'Hadir', 1, 0, 'C', true);
		$pdf->Cell(20,4, 'Xu', 1, 0, 'C', true);
		$pdf->Cell(20,4, 'Xf', 1, 0, 'C', true);
		$pdf->Cell(20,4, 'Honor', 1, 1, 'C', true);


		#tampilkan data tabelnya
		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','','8');
		$fill = false;

		#tampilkan data ikut hitung = 0
		foreach ($data2 as $baris2) {
			$i = 0;
			foreach ($baris2 as $cell2) {
				if ($i == 10 or $i == 11 or $i == 12){ //format honor
					$cell2 = number_format($cell2);
				} else if ($i == 3){
					$cell2 = number_format($cell2,"2"); //format sks ekivalen
				} else {
					$cell2 = $cell2;
				}

				$pdf->Cell($header[$i]['length'], 4, $cell2, 1, '0', $header[$i]['align2'], $fill);
				$i++;
			}	
			$fill = !$fill;
			$t=+8;
			$pdf->Ln();
		}

		$pdf->Ln(1);
		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','B','8');
		$pdf->cell(72); //220
		$pdf->cell(13,4,number_format($sksEkivalen2,"2"),1,0,'C',true);
		$pdf->cell(135);
		$pdf->cell(20,4,number_format($subtotalhonorxu2),1,0,'R',true);
		$pdf->cell(20,4,number_format($subtotalhonorxf2),1,0,'R',true);
		$pdf->cell(20,4,number_format($subtotalhonor2),1,0,'R',true);
		$pdf->Ln($t);
	/*
		#Keterangan Rincian Pembayaran
		$subtotalhonorxu = $subtotalhonorxu1 + $subtotalhonorxu2;
		$subtotalhonorxf = $subtotalhonorxf1 + $subtotalhonorxf2;
		$subtotalhonor = $subtotalhonor1 + $subtotalhonor2;

		$pdf->Ln(4);
		$pdf->cell(173);
		$pdf->cell(47,4, 'Rincian Pembayaran : ', '0', 0, 'L');
		$pdf->SetFont('Arial','B','8');
		$pdf->SetFillColor(215,225,245);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(215,225,245);
		$pdf->Cell(20,4, 'Honor', 1, 0, 'C', true);		
		$pdf->Cell(20,4, 'Honor', 1, 0, 'C', true);		
		$pdf->Cell(20,4, 'Total', 1, 1, 'C', true);	
		$pdf->cell(220);
		$pdf->Cell(20,4, 'Xu', 1, 0, 'C', true);
		$pdf->Cell(20,4, 'Xf', 1, 0, 'C', true);
		$pdf->Cell(20,4, 'Honor', 1, 1, 'C', true);
		$pdf->Ln(1);

		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','B','8');
		$pdf->cell(173);
		$pdf->cell(47,4, 'Sesuai Ketetapan Universitas  = ', '0', 0, 'L');
		$pdf->cell(20,4,number_format($subtotalhonorxu1),1,0,'R',true);
		$pdf->cell(20,4,number_format($subtotalhonorxf1),1,0,'R',true);
		$pdf->cell(20,4,number_format($subtotalhonor1),1,1,'R',true);	
		$pdf->Ln(1);
		$pdf->cell(173);
		$pdf->cell(47,4, 'Sesuai Kebijakan Fakultas       = ', '0', 0, 'L');
		$pdf->cell(20,4,number_format($subtotalhonorxu2),1,0,'R',true);
		$pdf->cell(20,4,number_format($subtotalhonorxf2),1,0,'R',true);
		$pdf->cell(20,4,number_format($subtotalhonor2),1,1,'R',true);
		$pdf->Ln(1);
		$pdf->cell(173);
		$pdf->cell(47,4, 'Total Pembayaran                     = ', '0', 0, 'L');
		$pdf->cell(20,4,number_format($subtotalhonorxu),1,0,'R',true);
		$pdf->cell(20,4,number_format($subtotalhonorxf),1,0,'R',true);
		$pdf->cell(20,4,number_format($subtotalhonor),1,1,'R',true);
		*/
	}


	/**************************************\
	 proses pencetakan rincian bimbingan
	\**************************************/

	$data = "";
	$data = array();
	$query = "	SELECT departemen, program, jenis_bimbingan, honor
				FROM bimbingan a, organisasi b
				WHERE $kodeorganisasi and tahun = $tahun and bulan = $periode_bulan and a.nip = '$nip' and kodeorganisasi = kd_organisasi";
	$sql = mysql_query ($query) or die ("error data bimbingan: ".mysql_error());
	$rows = mysql_num_rows($sql);

	if ($rows > 0){

		$pdf->Ln(4);

		#setting judul laporan dan header tabel
		$header = array(
			array("label"=>"Departemen", "length"=>40, "align"=>"C", "align2"=>"L"),
			array("label"=>"Jenjang", "length"=>30, "align"=>"C", "align2"=>"L"),
			array("label"=>"Nama Bimbingan", "length"=>90, "align"=>"C", "align2"=>"L"),
			array("label"=>"Total Honor", "length"=>20, "align"=>"C", "align2"=>"R")
		);

		#buat header tabel
		$pdf->SetFont('Arial','I','8');
		$pdf->Cell(0,4, 'Rincian Honor Lainnya', '0', 1, 'L');	
		$pdf->SetFont('Arial','B','8');
		$pdf->SetFillColor(215,225,245);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(215,225,245);

		$pdf->Cell(40,4, 'Program Studi', 1, 0, 'C', true);
		$pdf->Cell(30,4, 'Jenjang', 1, 0, 'C', true);
		$pdf->Cell(90,4, 'Keterangan', 1, 0, 'C', true);
		$pdf->Cell(20,4, 'Honor', 1, 1, 'C', true);

		#tampilkan data bimbingan
		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','','8');
		$fill = false;

		while ($row = mysql_fetch_assoc($sql)) {
			array_push($data, $row);
		}
		#tampilkan data tabelnya
		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','','8');
		$fill = false;

		#tampilkan data bimbingan
		$honor = 0;
		foreach ($data as $baris) {
			$i = 0;
			foreach ($baris as $cell) {
				if ($i == 3){ //format honor
					$cellx = number_format($cell);
					$honor = $honor + $cell;
				} else {
					$cellx = $cell;
				}
				$pdf->Cell($header[$i]['length'], 4, $cellx, 1, '0', $header[$i]['align2'], $fill);
				$i++;
			}	
			$fill = !$fill;
			$t=+8;
			$pdf->Ln();
		}

		#tampilkan total honor bimbingan
		$pdf->Ln(1);
		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','B','8');

		$pdf->cell(160);
		$pdf->cell(20,4,number_format($honor),1,0,'R',true);
		$pdf->Ln();
	}

	/**************************************\
	 proses pencetakan rincian insentif (di disable)
	\**************************************/

	$data = "";
	$data = array();
	$query = "	SELECT departemen, program, jenis_bimbingan, honor
					FROM bimbingan a, organisasi b
					WHERE $kodeorganisasi and tahun = $tahun and bulan = $periode_bulan and a.nip = '$nip' and kodeorganisasi = kd_organisasi and flag = 1";
	$sql = mysql_query ($query) or die ("error data bimbingan: ".mysql_error());
	$rows = mysql_num_rows($sql);

	if ($rows > 0){
		$pdf->Ln(4);
		#setting judul laporan dan header tabel
		$header = array(
			array("label"=>"Departemen", "length"=>40, "align"=>"C", "align2"=>"L"),
			array("label"=>"Jenjang", "length"=>30, "align"=>"C", "align2"=>"L"),
			array("label"=>"Nama Bimbingan", "length"=>70, "align"=>"C", "align2"=>"L"),
			array("label"=>"Total Honor", "length"=>20, "align"=>"C", "align2"=>"R")
		);

		#buat header tabel
		$pdf->SetFont('Arial','I','8');
		$pdf->Cell(0,4, 'Rincian Honor Insentif', '0', 1, 'L');	
		$pdf->SetFont('Arial','B','8');
		$pdf->SetFillColor(215,225,245);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(215,225,245);

		$pdf->Cell(40,4, 'Program Studi', 1, 0, 'C', true);
		$pdf->Cell(30,4, 'Jenjang', 1, 0, 'C', true);
		$pdf->Cell(70,4, 'Keterangan', 1, 0, 'C', true);
		$pdf->Cell(20,4, 'Honor', 1, 1, 'C', true);

		#tampilkan data insentif
		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','','8');
		$fill = false;

		while ($row = mysql_fetch_assoc($sql)) {
			array_push($data, $row);
		}
		#tampilkan data tabelnya
		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','','8');
		$fill = false;

		#tampilkan data insentif
		$honor = 0;
		foreach ($data as $baris) {
			$i = 0;
			foreach ($baris as $cell) {
				if ($i == 3){ //format honor
					$cellx = number_format($cell);
					$honor = $honor + $cell;
				} else {
					$cellx = $cell;
				}
				$pdf->Cell($header[$i]['length'], 4, $cellx, 1, '0', $header[$i]['align2'], $fill);
				$i++;
			}	
			$fill = !$fill;
			$t=+8;
			$pdf->Ln();
		}

		#tampilkan total honor insentif
		$pdf->Ln(1);
		$pdf->SetFillColor(238,249,269);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(221,232,252);
		$pdf->SetFont('Arial','B','8');

		$pdf->cell(140);
		$pdf->cell(20,4,number_format($honor),1,0,'R',true);
		$pdf->Ln();
	}

	#output file PDF
	$pdf->Output();

		 //==================================================================================================================================================================================\\
} else { //user non admin... user non admin... user non admin... user non admin... user non admin... user non admin... user non admin... user non admin... user non admin... user non admin...
         //==================================================================================================================================================================================\\

#ambil data di tabel dan masukkan ke array

$judullaporan = "Rincian Data Honor Pengajaran Periode $bulan $tahun";
$_SESSION["judullaporan"] = $judullaporan;

#ambil data ikut hitung = 1
$query1 = "SELECT programstudi, program, bobotkontribusi, sksekivalen, sksdisetujui, namamatakuliah, sks, namakelas, hadiraktual, kehadiranseharusnya, honorxuskemainti, honorxfskemainti + honorxfskemalain + honorxflintasfak as honorxf, totalhonor
			FROM kalban 
			WHERE $kodeorganisasi and nip = '$nip' and ikuthitung = '1' and tahun = $tahun and bulan='$periode_bulan' and kodepdpt=0
			ORDER BY program, programstudi, namamatakuliah, kodekelas";
			
$sql1 = mysql_query ($query1) or die ("Data Tidak Ditemukan!..");
$data1 = array();
while ($row1 = mysql_fetch_assoc($sql1)) {
	array_push($data1, $row1);
}

#hitung sub total ikut hitung = 1
$subtotalhonorxu1 = 0;
$subtotalhonorxf1 = 0;
$subtotalhonor1 = 0;
$i = 0;
$rows1 = mysql_num_rows($sql1);

for ($i; $i<$rows1; $i++) {	
	$subtotalhonorxu1 = $subtotalhonorxu1 + $data1["$i"]["honorxuskemainti"];
	$subtotalhonorxf1 = $subtotalhonorxf1 + $data1["$i"]["honorxf"];
	$subtotalhonor1 = $subtotalhonor1 + $data1["$i"]["totalhonor"];	
}

#ambil data ikut hitung = 0
$query2 = "SELECT programstudi, program, bobotkontribusi, sksekivalen, sksdisetujui, namamatakuliah, sks, namakelas, hadiraktual, kehadiranseharusnya, honorxuskemainti, honorxfskemainti + honorxfskemalain + honorxflintasfak as honorxf, totalhonor
			FROM kalban 
			WHERE $kodeorganisasi and nip = '$nip' and ikuthitung in ('0','') and tahun = $tahun and bulan = '$periode_bulan' and kodepdpt=0
			ORDER BY program, programstudi, namamatakuliah, kodekelas";
			
$sql2 = mysql_query ($query2) or die ("Data Tidak Ditemukan!..");
$data2 = array();
while ($row2 = mysql_fetch_assoc($sql2)) {
	array_push($data2, $row2);
}
 
#hitung sub total ikut hitung = 0
$subtotalhonorxu2 = 0;
$subtotalhonorxf2 = 0;
$subtotalhonor2 = 0;
$i = 0;
$rows2 = mysql_num_rows($sql2);

for ($i; $i<$rows2; $i++) {	
	$subtotalhonorxu2 = $subtotalhonorxu2 + $data2["$i"]["honorxuskemainti"];
	$subtotalhonorxf2 = $subtotalhonorxf2 + $data2["$i"]["honorxf"];
	$subtotalhonor2 = $subtotalhonor2 + $data2["$i"]["totalhonor"];	
}
 
#setting judul laporan dan header tabel
$header = array(
	array("label"=>"Program Studi", "length"=>40, "align"=>"C", "align2"=>"L"),
	array("label"=>"Program", "length"=>17, "align"=>"L", "align2"=>"L"),
	array("label"=>"Bobot Kontribusi", "length"=>15, "align"=>"L", "align2"=>"C"),
	array("label"=>"SKS Ekivalen", "length"=>15, "align"=>"C", "align2"=>"C"),
	array("label"=>"SKS Disetujui", "length"=>15, "align"=>"C", "align2"=>"C"),
	array("label"=>"Nama Mata Kuliah", "length"=>62, "align"=>"C", "align2"=>"L"),
	array("label"=>"SKS", "length"=>8, "align"=>"C", "align2"=>"C"),
	array("label"=>"Nama Kelas", "length"=>32, "align"=>"C", "align2"=>"L"),
	array("label"=>"Hadir Aktual;", "length"=>9, "align"=>"C", "align2"=>"C"),
	array("label"=>"Wajib Hadir", "length"=>9, "align"=>"C", "align2"=>"C"),
	array("label"=>"Honor Xu", "length"=>20, "align"=>"C", "align2"=>"R"),
	array("label"=>"Honor Xf", "length"=>20, "align"=>"C", "align2"=>"R"),
	array("label"=>"Total Honor", "length"=>20, "align"=>"C", "align2"=>"R")
);
 
#sertakan library FPDF dan bentuk objek
require_once ("../remun/fpdf/fpdf.php");

#tampilkan judul laporan
class PDF extends FPDF
{
//Page header
	function Header()
	{
		$this->Image('images/makara_gold.jpg',10,5,13);
		$this->SetY(6);
		$this->Cell(15);
		$this->SetFont('Arial','B','8');
		$this->Cell(0,4, 'Universitas Indonesia', '0', 1, 'L');
		$this->Cell(15);
		$this->Cell(0,4, 'Fakultas Ilmu Sosial dan Ilmu Politik', '0', 1, 'L');
		$this->Cell(15);
		$this->Cell(0,4, $_SESSION["programstudi"], '0', 1, 'L');
		$this->Ln(4);
		$this->SetFont('Arial','B','9');
		$this->Cell(0,3, $_SESSION["judullaporan"], '0', 1, 'C');
		$this->Ln(4);
		$this->Cell(26,3, "Nama Pengajar : ", '0', 0, 'L');
		$this->Cell(0,3, $_POST["namapengajar"], '0', 1, 'L');
		$this->Ln(1);
		
		#buat header tabel
		$this->SetFont('Arial','B','8');
		$this->SetFillColor(215,225,245);
		$this->SetTextColor(0);
		$this->SetDrawColor(215,225,245);
		
		$this->Cell(40,5, '', 1, 0, 'C', true);		
		$this->Cell(17,5, '', 1, 0, 'C', true);		
		$this->Cell(15,5, 'Bobot', 1, 0, 'C', true);		
		$this->Cell(13,5, 'SKS', 1, 0, 'C', true);		
		$this->Cell(15,5, '', 1, 0, 'C', true);		
		$this->Cell(62,5, '', 1, 0, 'C', true);		
		$this->Cell(8,5, '', 1, 0, 'C', true);		
		$this->Cell(32,5, '', 1, 0, 'C', true);		
		$this->Cell(9,5, 'Jml', 1, 0, 'C', true);		
		$this->Cell(9,5, 'Wajib', 1, 0, 'C', true);		
		$this->Cell(20,5, 'Honor', 1, 0, 'C', true);		
		$this->Cell(20,5, 'Honor', 1, 0, 'C', true);		
		$this->Cell(20,5, 'Total', 1, 1, 'C', true);	
		
		$this->Cell(40,5, 'Program Studi', 1, 0, 'C', true);
		$this->Cell(17,5, 'Jenjang', 1, 0, 'C', true);
		$this->Cell(15,5, 'Kontribusi', 1, 0, 'C', true);
		$this->Cell(13,5, 'Ekivalen', 1, 0, 'C', true);
		$this->Cell(15,5, 'Disetujui', 1, 0, 'C', true);
		$this->Cell(62,5, 'Nama Mata Kuliah', 1, 0, 'C', true);
		$this->Cell(8,5, 'SKS', 1, 0, 'C', true);
		$this->Cell(32,5, 'Kelas', 1, 0, 'C', true);
		$this->Cell(9,5, 'Hadir', 1, 0, 'C', true);
		$this->Cell(9,5, 'Hadir', 1, 0, 'C', true);
		$this->Cell(20,5, 'Xu', 1, 0, 'C', true);
		$this->Cell(20,5, 'Xf', 1, 0, 'C', true);
		$this->Cell(20,5, 'Honor', 1, 1, 'C', true);
	}
//Page footer
	function Footer()
	{
		//Position at 1.5 cm from bottom
		$this->SetY(-15);
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//Page number
		$this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'C');
	}

}

$pdf = new PDF('L','mm','A4');
$pdf->AddPage();
$pdf->AliasNbPages();
 
#tampilkan data tabelnya
$pdf->SetFillColor(238,249,269);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(221,232,252);
$pdf->SetFont('Arial','','8');
$fill = false;

#tampilkan data ikut hitung = 1
foreach ($data1 as $baris1) {
	$i = 0;
	$t = 15;
	foreach ($baris1 as $cell1) {
		if ($i == 10 or $i == 11 or $i == 12){ //format honor
				$cell1 = number_format($cell1);
			} else if ($i == 3){
				$cell1 = number_format($cell1,"2"); //format sks ekivalen	
			} else {
				$cell1 = $cell1;
			}
			
		$pdf->Cell($header[$i]['length'], 8, $cell1, 1, '0', $header[$i]['align2'], $fill);
		$i++;
	}	
	$fill = !$fill;
	$pdf->Ln();
	$t=+8;
}

$pdf->Ln(1);
$pdf->SetFillColor(238,249,269);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(221,232,252);
$pdf->SetFont('Arial','B','8');
$pdf->cell(220);
$pdf->cell(20,8,number_format($subtotalhonorxu1),1,0,'R',true);
$pdf->cell(20,8,number_format($subtotalhonorxf1),1,0,'R',true);
$pdf->cell(20,8,number_format($subtotalhonor1),1,0,'R',true);
$pdf->Ln(8);

#buat header tabel ikut hitung = 0
if ($rows2 > 0){
	$pdf->SetFont('Arial','I','8');
	$pdf->Cell(0,4, 'Rincian Data Honor Pengajaran dengan Ikut Hitung = 0 (Sesuai Kebijakan Universitas Tidak Terbayarkan)', '0', 1, 'L');	

	$pdf->SetFont('Arial','B','8');
	$pdf->SetFillColor(215,225,245);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(215,225,245);

	$pdf->Cell(40,5, '', 1, 0, 'C', true);		
	$pdf->Cell(17,5, '', 1, 0, 'C', true);		
	$pdf->Cell(15,5, 'Bobot', 1, 0, 'C', true);		
	$pdf->Cell(13,5, 'SKS', 1, 0, 'C', true);		
	$pdf->Cell(15,5, '', 1, 0, 'C', true);		
	$pdf->Cell(62,5, '', 1, 0, 'C', true);		
	$pdf->Cell(8,5, '', 1, 0, 'C', true);		
	$pdf->Cell(32,5, '', 1, 0, 'C', true);		
	$pdf->Cell(9,5, 'Jml', 1, 0, 'C', true);		
	$pdf->Cell(9,5, 'Wajib', 1, 0, 'C', true);		
	$pdf->Cell(20,5, 'Honor', 1, 0, 'C', true);		
	$pdf->Cell(20,5, 'Honor', 1, 0, 'C', true);		
	$pdf->Cell(20,5, 'Total', 1, 1, 'C', true);	

	$pdf->Cell(40,5, 'Program Studi', 1, 0, 'C', true);
	$pdf->Cell(17,5, 'Jenjang', 1, 0, 'C', true);
	$pdf->Cell(15,5, 'Kontribusi', 1, 0, 'C', true);
	$pdf->Cell(13,5, 'Ekivalen', 1, 0, 'C', true);
	$pdf->Cell(15,5, 'Disetujui', 1, 0, 'C', true);
	$pdf->Cell(62,5, 'Nama Mata Kuliah', 1, 0, 'C', true);
	$pdf->Cell(8,5, 'SKS', 1, 0, 'C', true);
	$pdf->Cell(32,5, 'Kelas', 1, 0, 'C', true);
	$pdf->Cell(9,5, 'Hadir', 1, 0, 'C', true);
	$pdf->Cell(9,5, 'Hadir', 1, 0, 'C', true);
	$pdf->Cell(20,5, 'Xu', 1, 0, 'C', true);
	$pdf->Cell(20,5, 'Xf', 1, 0, 'C', true);
	$pdf->Cell(20,5, 'Honor', 1, 1, 'C', true);


	#tampilkan data tabelnya
	$pdf->SetFillColor(238,249,269);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(221,232,252);
	$pdf->SetFont('Arial','','8');
	$fill = false;
			
	#tampilkan data ikut hitung = 0
	foreach ($data2 as $baris2) {
		$i = 0;
		foreach ($baris2 as $cell2) {
			if ($i == 10 or $i == 11 or $i == 12){ //format honor
				$cell2 = number_format($cell2);
			} else if ($i == 3){
				$cell2 = number_format($cell2,"2"); //format sks ekivalen
			} else {
				$cell2 = $cell2;
			}

			$pdf->Cell($header[$i]['length'], 8, $cell2, 1, '0', $header[$i]['align2'], $fill);
			$i++;
		}	
		$fill = !$fill;
		$t=+8;
		$pdf->Ln();
	}

	$pdf->Ln(1);
	$pdf->SetFillColor(238,249,269);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(221,232,252);
	$pdf->SetFont('Arial','B','8');
	$pdf->cell(220);
	$pdf->cell(20,8,number_format($subtotalhonorxu2),1,0,'R',true);
	$pdf->cell(20,8,number_format($subtotalhonorxf2),1,0,'R',true);
	$pdf->cell(20,8,number_format($subtotalhonor2),1,0,'R',true);
	$pdf->Ln(4);

	#Keterangan Rincian Pembayaran
	$subtotalhonorxu = $subtotalhonorxu1 + $subtotalhonorxu2;
	$subtotalhonorxf = $subtotalhonorxf1 + $subtotalhonorxf2;
	$subtotalhonor = $subtotalhonor1 + $subtotalhonor2;
	
	$pdf->Ln(7);
	$pdf->cell(173);
	$pdf->cell(47,8, 'Rincian Pembayaran : ', '0', 0, 'L');
	$pdf->SetFont('Arial','B','8');
	$pdf->SetFillColor(215,225,245);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(215,225,245);
	$pdf->Cell(20,4, 'Honor', 1, 0, 'C', true);		
	$pdf->Cell(20,4, 'Honor', 1, 0, 'C', true);		
	$pdf->Cell(20,4, 'Total', 1, 1, 'C', true);	
	$pdf->cell(220);
	$pdf->Cell(20,4, 'Xu', 1, 0, 'C', true);
	$pdf->Cell(20,4, 'Xf', 1, 0, 'C', true);
	$pdf->Cell(20,4, 'Honor', 1, 1, 'C', true);
	$pdf->Ln(1);
	
	$pdf->SetFillColor(238,249,269);
	$pdf->SetTextColor(0);
	$pdf->SetDrawColor(221,232,252);
	$pdf->SetFont('Arial','B','8');
	$pdf->cell(173);
	$pdf->cell(47,8, 'Sesuai Ketetapan Universitas  = ', '0', 0, 'L');
	$pdf->cell(20,8,number_format($subtotalhonorxu1),1,0,'R',true);
	$pdf->cell(20,8,number_format($subtotalhonorxf1),1,0,'R',true);
	$pdf->cell(20,8,number_format($subtotalhonor1),1,1,'R',true);	
	$pdf->Ln(1);
	$pdf->cell(173);
	$pdf->cell(47,8, 'Sesuai Kebijakan Fakultas       = ', '0', 0, 'L');
	$pdf->cell(20,8,number_format($subtotalhonorxu2),1,0,'R',true);
	$pdf->cell(20,8,number_format($subtotalhonorxf2),1,0,'R',true);
	$pdf->cell(20,8,number_format($subtotalhonor2),1,1,'R',true);
	$pdf->Ln(1);
	$pdf->cell(173);
	$pdf->cell(47,8, 'Total                                = ', '0', 0, 'L');
	$pdf->cell(20,8,number_format($subtotalhonorxu),1,0,'R',true);
	$pdf->cell(20,8,number_format($subtotalhonorxf),1,0,'R',true);
	$pdf->cell(20,8,number_format($subtotalhonor),1,1,'R',true);
}

#output file PDF
$pdf->Output();
}
?>
