<?php
if(!session_id()) session_start();
include("menu.php"); 
include("bulan.php");

$data = array();

//ambil term terakhir untuk menampilkan data mata kuliah pada bulan yang berjalan saat ini
$sql = "SELECT MAX(tahun) as tahun, MAX(bulan) as bulan
        FROM kalban
        WHERE bulan < 13";
$result = mysql_query($sql);

while ($row = mysql_fetch_object($result)){
    $tahun = $row->tahun;
    $bulan = $row->bulan;
}

//ambil data mata kuliah
$data = array();
/* query sebelumnya
$sql = "SELECT DISTINCT kodemk, namamatakuliah, kodepasca
        FROM kalban
        WHERE program = 'S1' AND kodemk like 'SPSP%' AND tahun = $tahun AND bulan = $bulan";
*/
$sql = "SELECT DISTINCT kodemk, namamatakuliah, kodepasca
        FROM kalban
        WHERE program = 'S1' AND tahun = $tahun AND bulan = $bulan";
$result = mysql_query($sql);

while ($row = mysql_fetch_object($result)){
    $data[] = $row;
}

//set array bulan
$array_bulan = array(
		"01" => "Januari" , "02" => "Februari",  "03" =>"Maret",  "04"=>"April", "05" => "Mei", "06" => "Juni",
		"07" => "Juli", "08" => "Agustus", "09" => "September", "10" => "Oktober", "11" => "November", "12" => "Desember"
	);

//option bulan
$option = '';
foreach($array_bulan as $k => $v){
	if($k == date('m') ){
		$option .= '<option style="color:blue;" value="'.$k.'" selected>'.$v.'</option>';
	} else {
		$option .= '<option style="color:blue;" value="'.$k.'">'.$v.'</option>';
	}
	
}

?>
	
<link href="style.css" media="screen, projection" rel="stylesheet" type="text/css">
<body bgcolor="#336666" style="background-color:#336666">
<div style="border:solid #336666; height:70px"><font style="color:#336666">&nbsp;</font></div>
	<form action="" method="post" name="form">
		<div style="border:solid #336666">	
		
			<!----------------------------kotak atas-------------------------------------->
			<div class="inset">
				<b class="b1"></b><b class="b2" style="background-color:#fa0"></b><b class="b3" style="background-color:#fa0"></b><b class="b4" style="background-color:#fa0"></b>
				<div class="boxcontent" style="text-align:center;">
			<!---------------------------kotak atas------------------------------------- -->		

					<div style="font:bold 15px verdana; color:#336666; background-color:#fa0">
						<div valign="center" align="center" colspan="3" height="22%">
						<h1>Set Role Matakuliah</h1>
						</div>
					</div>
					<br><br>
					<div>
						<div colspan="3" height="15px"></div>
					</div>
					<div style="font:bold 12px verdana; color:#555">
						<span align="right"><label>Tahun</label></span>
						<span align="center">:</span>
						<span>
							<select style="font:bold 11px verdana; color:#555" name="tahun" id="tahun">
								 <option value="<?echo $tahun?>"><?echo $tahun?></option>	 								
							</select>
						</span>
						<span>&nbsp;&nbsp;&nbsp;</span>							
						<span align="right"><label>Bulan</label></span>
						<span align="center"><font color="#555">:</font></span>
						<span>
							<span id="div0">							
								<select style="font:bold 11px verdana; color:#555; width: 10em;" name="bulan" id="bulan" >
									 <?php echo $option?> 								
								</select>							
							</span>
						</span>
					</div>
					<div>
						<span>&nbsp;</span>
					</div>	

			<!--------------------------kotak bawah--------------------------------- -->
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<!--------------------------kotak bawah--------------------------------- -->
			
		</div>
	</form>

<br>

<div style="text-align:center">
	<button id="submit">submit</button>
</div>
<div style="text-align:center">
	 <img src="images/spinner.gif" id="spinner"/>
</div>

<div id="proses" style="text-align:center"></div>
<br>
<div id="data-mk">	
			
<?php

//tampilkan data matakuliah
$no=1;
$html = '
<table id="customers">
	<tr>
		<th rowspan="2">No</th>
		<th rowspan="2">Kode MK</th>
		<th rowspan="2">Nama Mata Kuliah</th>
		<th colspan="2">Role Mata Kuliah</th>
	</tr>
	<tr>
		<th>PPAA</th>
		<th>Prodi</th>
	</tr>';
	
foreach($data as $k => $v){
	$html.= '
	<tr>
		<td style="text-align:center">'.$no.'</td>
		<td>'.$v->kodemk.'</td>
		<td>'.$v->namamatakuliah.'</td>';
		if( $v->kodepasca == 2){
			$html.= '
			<td style="text-align:center"><input type="checkbox" id="ppaa-'.$v->kodemk.'" name="check_list[]" value="ppaa-'.$v->kodemk.'" checked></td>
			<td style="text-align:center"><input type="checkbox" id="prodi-'.$v->kodemk.'" name="check_list[]" value="prodi-'.$v->kodemk.'"></td>';
	
		} else if( $v->kodepasca == 1) {
			$html.= '
			<td style="text-align:center"><input type="checkbox" id="ppaa-'.$v->kodemk.'" name="check_list[]" value="ppaa-'.$v->kodemk.'" checked ></td>
			<td style="text-align:center"><input type="checkbox" id="prodi-'.$v->kodemk.'" name="check_list[]" value="prodi-'.$v->kodemk.'" checked ></td>';
	
		} else {
			$html.= '
			<td style="text-align:center"><input type="checkbox" id="ppaa-'.$v->kodemk.'" name="check_list[]" value="ppaa-'.$v->kodemk.'" ></td>
			<td style="text-align:center"><input type="checkbox" id="prodi-'.$v->kodemk.'" name="check_list[]" value="prodi-'.$v->kodemk.'" checked ></td>';
	
		}
		echo'
		</tr>';
	$no++;
}

$html.= '</table>';
echo $html;
//print_r($data);
?>
</div>

<script>
$(document).ready(function(){	

	$('#spinner').hide();
	//$("form").submit(function(){
	$("#submit").click(function(){
		var test = [];
		$('#spinner').show();
		var tahun = $("#tahun").val();
		var bulan = $("#bulan").val();
		//var test = $("input[type='checkbox']:checked").val();
        var val = [];
        $(':checkbox:checked').each(function(i){
          val[i] = $(this).val();
        });
		//alert(tahun+' '+bulan+' '+val);
		
		$.post('inputdatahadir_role_mk.php', {tahun:tahun, bulan:bulan, check_list:val}, function(data){
			$('#proses').fadeIn().html(data);
			$('#proses').fadeOut(3000);
			$('#spinner').hide();
		});
		
		
		return false;
		
	});
	
	$("#bulan").change(function(){

		//$('#spinner').show();
		var tahun = $("#tahun").val();
		var bulan = $("#bulan").val();
		
		$.post('inputdatahadir_role_mk_view.php', {tahun:tahun, bulan:bulan}, function(data){
			$('#data-mk').html(data);
		});
		
		
		return false;
		
	});
	
});
</script>

<style>
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 60%;
    margin:auto;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 5px;
    font-size:12px;
}

#customers th{text-align:center}
#customers tr:nth-child(even){background-color: #f2f2f2;}
#customers tr:nth-child(odd){background-color: #fff;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 2px;
    padding-bottom: 2px;
    text-align: center;
    vertical-align: middle;
    background-color: #4CAF50;
    color: white;
}
#submit{
	background:#fa0;
	padding:5px;
	font-weight:bold;
	font-size:12px;
}
</style>
</body>