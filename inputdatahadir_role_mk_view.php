<?
if(!session_id()) session_start();
include 'conn.php';
$bulan = $_POST['bulan'];
$tahun = $_POST['tahun'];

//ambil data mata kuliah
$data = array();

/* query lama
$sql = "SELECT DISTINCT kodemk, namamatakuliah, kodepasca
        FROM kalban
        WHERE program = 'S1' AND kodemk like 'SPSP%' AND tahun = $tahun AND bulan = $bulan";
*/

$sql = "SELECT DISTINCT kodemk, namamatakuliah, kodepasca
        FROM kalban
        WHERE program like 'S1%' AND tahun = $tahun AND bulan = $bulan";
$result = mysql_query($sql);

while ($row = mysql_fetch_object($result)){
    $data[] = $row;
}

//tampilkan data matakuliah
$no=1;
$html = '
<table id="customers">
	<tr>
		<th rowspan="2">No</th>
		<th rowspan="2">Kode MK</th>
		<th rowspan="2">Nama Mata Kuliah</th>
		<th colspan="2"><input type="checkbox" onClick="toggle(this)" />Role Mata Kuliah</th>
	</tr>
	<tr>
		<th> PPAA </th>
		<th> Prodi </th>
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
?>

<script language="JavaScript">
function toggle(source) {
	checkboxes = document.getElementsByName('check_list[]');
	for(var i=0, n=checkboxes.length;i<n;i++) {
		checkboxes[i].checked = source.checked;
	}
}
</script>