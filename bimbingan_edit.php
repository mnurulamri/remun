<script src="js/jquery.formatCurrency.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
	//Mempercantik Button dengan Jquery UI
	$("#submit").button();
	$("#reset").button();
	
	$("#vharga_satuan, #jml_mhs").keyup(function(){
		$("#vharga_satuan").toNumber();
		var hs = $("#vharga_satuan").val();
		var jml_mhs = $("#jml_mhs").val();
		var honor = hs * jml_mhs;
		$("#harga_satuan").val(hs);		
		$("#honor").val(honor);
		$("#vhonor").val(honor);
		$("#vhonor").formatCurrency();
		$("#vharga_satuan").formatCurrency();
	})
});
	
</script>
<?php
	include("conn.php");
	
	#siapkan data untuk form edit
	if(!session_id()) session_start();
	$kd_organisasi = $_SESSION["kode"];	
	$id = $_GET['id'];	
	$sql = "SELECT a.tahun as tahun, a.bulan as bulan, a.nip as nip, a.id as id, nama_pengajar, kd_bimbingan, jenis_bimbingan, jml_mhs, harga_satuan, honor
			  FROM bimbingan a, master_pengajar c
			  WHERE 
				a.nip = c.nip and 				
				a.id = $id";
	$query = mysql_query($sql);
	$row = mysql_fetch_array($query);
	
	/*create jenis bimbingan
	$query = "SELECT * FROM bimbingan_hs WHERE kd_organisasi = '$kd_organisasi'";
	$sql = mysql_query($query);
	$data = array();
	while($baris_data = mysql_fetch_array($sql)){
		$data[] = $baris_data;
	};*/
	
	
	switch ($row["bulan"]){
	case "01":
		$month = "Januari";
		break;
	case "02":
		$month = "Februari";
		break;
	case "03":
		$month = "Maret";
		break;
	case "04":
		$month = "April";
		break;
	case "05":
		$month = "Mei";
		break;
	case "06":
		$month = "Juni";
		break;
	case "07":
		$month = "Juli";
		break;
	case "08":
		$month = "Agustus";
		break;
	case "09":
		$month = "September";
		break;
	case "10":
		$month = "Oktober";
		break;
	case "11":
		$month = "November";
		break;
	case "12":
		$month = "Desember";
		break;
	}
?>
<fieldset id="center">
	<legend>Edit Data</legend>
	<form method="post" action="bimbingan_crud.php" name="form1" id="form1">
		<input type="hidden" name="id" value="<?php echo $row['id'] ?>">
		<input type="hidden" name="oper" value="edit">
		<table border="1" cellpadding="3" cellspacing="0" id="center">
			<tr>
				<td id="label">Tahun</td>
				<td>:</td>
				<td>
					<select name="tahun" id="tahun" class="ui-widget ui-widget-content padding ui-corner-all" onchange="gridReload()">
						<option value="<?echo date('Y')?>"><?echo date('Y')?></option>
						<option value="<?echo date('Y')-1?>"><?echo date('Y')-1?></option>
					</select>
				</td>			
				<td id="label">Bulan</td>
				<td>:</td>
				<td>
					<select name="bulan" id="bulan" class="ui-widget ui-widget-content padding ui-corner-all" onchange="gridReload()">
						<option style="color:blue;" value="<?php echo $month ?>"><?php echo $month ?></option>
						<option style="color:magenta;" value="01">Januari</option>									
						<option style="color:blue;" value="02">Februari</option>
						<option style="color:green;" value="03">Maret</option>
						<option style="color:purple;" value="04">April</option>
						<option style="color:red;" value="05">Mei</option>
						<option style="color:blue;" value="06">Juni</option>
						<option style="color:green;" value="07">Juli</option>
						<option style="color:purple;" value="08">Agustus</option>
						<option style="color:red;" value="09">September</option>
						<option style="color:blue;" value="10">Oktober</option>
						<option style="color:green;" value="11">November</option>
						<option style="color:purple;" value="12">Desember</option>									
					</select>							
				</td>
			</tr>
			<tr>
				<td id="label">Nama Pengajar</td>
				<td>:</td>
				<td>
					<input type="text" name="nama_pengajar" value="<?echo $row['nama_pengajar']?>" id="nama_pengajar" size="27" class="ui-widget ui-widget-content padding ui-corner-all" onkeyup="lihat(this.value)">										
					<div id="kotaksugest" ></div>					
				</td>
				<td id="label">NIP</td>
				<td>:</td>
				<td><input type="text" name="nip" readonly="readonly" value="<?echo $row['nip']?>" id="nip" class="ui-widget ui-widget-content padding ui-corner-all"></td>
			</tr>
			<tr>
				<td id="label">Jenis Honor</td>
				<td>:</td>
				<td>
					<?
					switch ($row['kd_bimbingan']) {
						case 'bimbingan':
							$ket = 'Bimbingan';
							break;
						case 'menguji':
							$ket = 'Menguji';
							break;
						case 'insentif':
							$ket = 'Insentif Kehadiran (Khusus KKI)';
							break;
						default:
							$ket = '?';
							break;
					}
					?>
					<select name="kd_bimbingan" id="kd_bimbingan" class="ui-widget ui-widget-content padding ui-corner-all">
						<option value="<?echo $row['kd_bimbingan']?>"><?echo $ket?></option>
						<option value="bimbingan">Bimbingan</option>
						<option value="menguji">Menguji</option>
						<option value="insentif">Insentif Kehadiran (Khusus KKI)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td id="label">Keterangan Honor</td>
				<td>:</td>
				<td>
					<input type="text" name="jenis_bimbingan" id="jenis_bimbingan" value="<?echo $row['jenis_bimbingan']?>" class="ui-widget ui-widget-content padding ui-corner-all">
					<!--<select name="jenis_bimbingan" id="jenis_bimbingan" class="ui-widget ui-widget-content padding ui-corner-all" onchange="hargaSatuan(this.value)">
						<option style="color:blue;" value="<?/*echo $row['harga_satuan']?>"><?echo $row['jenis_bimbingan']*/?></option>
						<?/*foreach($data as $datas){
							echo "<option value='$datas[3]'>$datas[2]</option>";	
						}*/?>
					</select>-->						
				</td>
				<td id="label">Jumlah Mhs/Item</td>
				<td>:</td>
				<td><input type="text" onkeypress="return isNumberKey(event)" name="jml_mhs" value="<?echo $row['jml_mhs']?>"size="4" maxlength="4" id="jml_mhs" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align: center"></td>
			</tr>
			<tr>
				<td id="label">Harga Satuan</td>
				<td>:</td>
				<!--<td><input type="text" value="9999999" onkeypress="return isNumberKey(event)" name="harga_satuan" size="7" maxlength="7" id="harga_satuan" onblur="return formatNumber(this.value,0,',','','','','','')" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align: center"></td>-->
				<td><input type="text" name="vharga_satuan" value="<?echo number_format($row['harga_satuan'])?>"id="vharga_satuan" onkeypress="return isNumberKey(event)" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align:right" /></td>
				<td id="label">Honor</td>
				<td>:</td>
				<td><input type="text" onkeypress="return isNumberKey(event)" name="vhonor" value="<?echo number_format($row['honor'])?>" size="10" maxlength="10" id="vhonor" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align: center"></td>
			</tr>
			<tr>
				<td colspan="3" align="center"><input type="button" value="Save" name="submit" id="submit" class="button"/>
			</tr>
			<input type="hidden" name="harga_satuan" id="harga_satuan" value="<?echo $row['harga_satuan']?>" onkeypress="return isNumberKey(event)" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align:right" />
			<input type="hidden" onkeypress="return isNumberKey(event)" name="honor" value="<?echo $row['honor']?>" size="10" maxlength="10" id="honor" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align: center"/>
			<input type="hidden" name="vjenis_bimbingan" id="vjenis_bimbingan" value="<?echo $row['jenis_bimbingan']?>"/>
		</table>																
	</form>
</fieldset>