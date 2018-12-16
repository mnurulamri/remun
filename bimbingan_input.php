<?
if(!session_id()) session_start();
include("conn.php");
include("bulan.php");

/*$kd_program_studi = $_SESSION["kode"];
$query = "SELECT * FROM bimbingan_hs WHERE kd_organisasi = '$kd_program_studi'";
$sql = mysql_query($query);
$data = array();
while($row = mysql_fetch_array($sql)){
	$data[] = $row;
}*/
?>

<script src="jqgrid/js/autocomplete.js" type="text/javascript"></script>
<script src="js/jquery.formatCurrency.js" type="text/javascript"></script>
<script type="text/javascript">

$(document).ready(function(){
	//Mempercantik Button dengan Jquery UI
	$("#submit").button();
	$("#reset").button();
	$("#AddBimbingan").button();
	$("#clear").button();
	
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

//fungsi clear nama dosen
function kosongkan(){
	document.getElementById('nama_pengajar').value = ''; 
	document.getElementById('nip').value = ''; 
	document.getElementById('nama_pengajar').focus();
	document.getElementById('jenis_bimbingan').value = '';
	//document.getElementById('jenis_bimbingan').selectedIndex = 0;
	//document.getElementById('vjenis_bimbingan').value = '...Pilih jenis Bimbingan...';
	document.getElementById('jml_mhs').value = 0;
	document.getElementById('harga_satuan').value = 0;
	document.getElementById('vharga_satuan').value = 0;
	document.getElementById('honor').value = 0;
	document.getElementById('vhonor').value = 0;/**/
}


/*********************** hide tombol *************************************
	var tahun = jQuery("#tahun").val();
	var bulan = jQuery("#bulan").val();	
	//document.writeln('kacang goreng');
	$.post('bimbingan_lock.php', {tahun:tahun, bulan:bulan}, 
		function(output){
			$('#lock').html(output).show("slow",0.8);
			//$('#lock').hide();
		}
	);
*************************************************************************/
	
/*function hargaSatuan(hs){
	//set nilai harga satuan bimbingan
	document.form1.vharga_satuan.value = hs;
	document.form1.harga_satuan.value = hs;
	//set jenis bimbingan
	var x = document.form1.jenis_bimbingan.selectedIndex;
	var selected_index = document.form1.jenis_bimbingan.options[x].text;
	document.form1.vjenis_bimbingan.value = selected_index;
}

function OpenHargaSatuan(){
	window.open("bimbingan_hs_add.php",'',"location=0, directories=0");
}
*/

</script>
<div>
<fieldset id="center">
	<legend>Add Data</legend>
	<form method="post" action="bimbingan_crud.php" name="form1" id="form1">
		<input type="hidden" name="oper" value="add">
		<table border="0" cellpadding="3" cellspacing="0" id="center">
			<tr>
				<td id="label">Tahun</td>
				<td>: </td>
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
						<option style="color:blue;" value="<?php echo $vbulan ?>"><?php echo $month ?></option>
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
				<td id="label">Jenis Honor</td>
				<td>: </td>
				<td>
					<select name="kd_bimbingan" id="kd_bimbingan" class="ui-widget ui-widget-content padding ui-corner-all" onchange="">
						<option value="bimbingan">Bimbingan</option>
						<option value="menguji">Menguji</option>
						<option value="insentif">Insentif Kehadiran (Khusus KKI)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td id="label">Nama Pengajar</td>
				<td>:</td>
				<td>
					<input type="text" name="nama_pengajar" id="nama_pengajar" size="27" class="ui-widget ui-widget-content padding ui-corner-all" onkeyup="lihat(this.value)"/>	
					<input type="button" value="clear" id="clear" class="button" onclick="kosongkan()"/>
					<div id="kotaksugest" ></div>					
				</td>
				<td id="label">NIP</td>
				<td>: </td>
				<td><input type="text" name="nip" id="nip" class="ui-widget ui-widget-content padding ui-corner-all"></td>
			</tr>
			<tr>
				<td id="label">Keterangan Honor</td>
				<td>:</td>
				<td>
					<input type="text" name="jenis_bimbingan" id="jenis_bimbingan" size="50" class="ui-widget ui-widget-content padding ui-corner-all" onchange="hargaSatuan(this.value)">
					<!--<select name="jenis_bimbingan" id="jenis_bimbingan" class="ui-widget ui-widget-content padding ui-corner-all" onchange="hargaSatuan(this.value)">
						<option style="color:blue;" value="">...Pilih jenis Bimbingan...</option>
						<?/*foreach($data as $datas){
							echo "<option value='$datas[3]'>$datas[2]</option>";	
						}*/?>
					</select>-->
					<!--<input type="button" id="AddBimbingan" class="button" value="add" onclick="OpenHargaSatuan()">-->
					  
				</td>
				<td id="label">Jumlah Mhs/Item</td>
				<td>:</td>
				<td><input type="text" name="jml_mhs" id="jml_mhs" size="4" value="1" onkeypress="return isNumberKey(event)" onClick="this.select();" maxlength="4" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align: center"></td>
			</tr>
			<tr>
				<td id="label">Harga Satuan</td>
				<td>:</td>
				<!--<td><input type="text" value="9999999" onkeypress="return isNumberKey(event)" name="harga_satuan" size="7" maxlength="7" id="harga_satuan" onblur="return formatNumber(this.value,0,',','','','','','')" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align: center"></td>-->
				<td><input type="text" name="vharga_satuan" id="vharga_satuan" onClick="this.select();" onkeypress="return isNumberKey(event)" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align:right" /></td>
				<td id="label">Honor</td>
				<td>:</td>
				<td><input type="text" onkeypress="return isNumberKey(event)" name="vhonor" size="10" maxlength="10" id="vhonor" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align: center"></td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<div id="lock"></div>
				</td>
			</tr>
			
			<input type="hidden" name="harga_satuan" id="harga_satuan" onkeypress="return isNumberKey(event)" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align:right" />
			<input type="hidden" onkeypress="return isNumberKey(event)" name="honor" size="10" maxlength="10" id="honor" class="ui-widget ui-widget-content padding ui-corner-all" style="text-align: center"/>
			<input type="hidden" name="vjenis_bimbingan" id="vjenis_bimbingan"/>
		</table>								
	</form>
</fieldset>
</div>