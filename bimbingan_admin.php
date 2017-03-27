<?
if(!session_id()) session_start();
include("bulan.php");
include("menu.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Bimbingan</title>
		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/sunny/jquery-ui-1.8.16.custom.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/pemanis.css" />
		<script src="jqgrid/js/jquery-1.5.2.min.js" type="text/javascript"></script>
		<script src="jqgrid/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
		<script src="jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
		<script src="jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
	
		<script type="text/javascript">
			$(document).ready(function(){
				var grid = $("#list2");
				grid.jqGrid({
					url: 'bimbingan_admin_json1.php', //URL Tujuan Yg Mengenerate data Json nya
					datatype: "json", //Datatype yg di gunakan
					height: "auto", //Mengset Tinggi table jadi Auto menyesuaikan dengan isi table
					mtype: "GET",
					colNames: ['id','Kode Organisasi','Program','Program Studi','Honor'],
					colModel: [
						{name:'id', key:true,index:'id', hidden:true,editable:false,editrules:{required:true}},
						{name:'kd_organisasi', index:'kd_organisasi', hidden:false,editable:false,editrules:{required:true}},
						{name:'program',width:70, index:'program',editable:true,editrules:{required:true}},
						{name:'prodi',width:250, index:'prodi',editable:true,editrules:{required:true}},
						{name:'honor', width:70, index:'honor',align:'right',editable:true,editrules:{required:true}}
					],
					rownumbers:true,
					rowNum: 10,
					rowList: [10,20,30],
					pager: '#pager2',
					sortname: 'program',
					viewrecords: true,
					sortorder: "desc",
					multiselect: false,
					caption: "Record Test",
					subGrid: true,
					caption: "Rekap Data Bimbingan",
					footerrow : true, 
					userDataOnFooter : true, 
					altRows : true,

					subGridRowExpanded: function(subgrid_id, row_id) {
						
						// we pass two parameters
						// subgrid_id is a id of the div tag created whitin a table data
						// the id of this elemenet is a combination of the "sg_" + id of the row
						// the row_id is the id of the row
						// If we wan to pass additinal parameters to the url we can use
						// a method getRowData(row_id) - which returns associative array in type name-value
						// here we can easy construct the flowing
						
						var subgrid_table_id, pager_id;
						subgrid_table_id = subgrid_id + "_t";
						pager_id = "p_" + subgrid_table_id;
						$("#"+subgrid_id).html("<table id='"+subgrid_table_id+"' class='scroll'></table><div id='"+pager_id+"' class='scroll'></div>");
						var tahun = jQuery("#tahun").val();
						var bulan = jQuery("#bulan").val();
						jQuery("#"+subgrid_table_id).jqGrid({
														
							url:"bimbingan_admin_json2.php?id="+row_id+"&tahun="+tahun+"&bulan="+bulan,
							datatype: "json",
							colNames: ['Nama Pengajar','Jenis Bimbingan','Jml Mhs','Harga Satuan','Honor'],
							colModel: [
								{name:'nama_pengajar', index:'nama_pengajar'},
								{name:'jenis_bimbingan', index:'jenis_bimbingan'},
								{name:'jml_mhs', width:45, index:'jml_mhs', align:'center'},
								{name:'harga_satuan', width:70, index:'harga_satuan', align:'right'},
								{name:'honor', width:70, index:'honor', align:'right'}							
							],
							rownumbers:true,
							rowNum:30,
							pager: pager_id,
							sortname: 'num',
							sortorder: "asc",
							height: '100%'
						});
						jQuery("#"+subgrid_table_id).jqGrid('navGrid',"#"+pager_id,{edit:false,add:false,del:false});
					},
				
					subGridRowColapsed: function(subgrid_id, row_id) {
					
					}
				});
				grid.jqGrid('navGrid','#pager2',{view:true,edit:false,add:false,del:false});				
			});
			
			function gridReload(){	
				var tahun = jQuery("#tahun").val();
				var bulan = jQuery("#bulan").val();
				jQuery("#list2").jqGrid('setGridParam',{url:"bimbingan_admin_json1.php?tahun="+tahun+"&bulan="+bulan,page:1}).trigger("reloadGrid");	
			}
		</script>
	</head>
	<body style="background:#336666">
		<br><br>
		<form method="post" action="bimbingan_admin_json1.php" name="form1" id="form1">
			<input type="hidden" name="oper" value="add">
			<table border="0" cellpadding="3" cellspacing="0" id="center" style="margin-left:auto; margin-right:auto;">
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
			</table>								
		</form>	
		<br>
		<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="pager2" class="scroll" style="text-align:center;"></div>
	</body>
</html>