<?
if(!session_id()) session_start();
include("../../remun/remun/menu.php");
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
					url: 'bimbingan_admin_hs_json1.php', //URL Tujuan Yg Mengenerate data Json nya
					datatype: "json", //Datatype yg di gunakan
					height: "auto", //Mengset Tinggi table jadi Auto menyesuaikan dengan isi table
					mtype: "GET",
					colNames: ['id','Kode Organisasi','Program','Program Studi'],
					colModel: [
						{name:'id', key:true,index:'id', hidden:true,editable:false,editrules:{required:true}},
						{name:'kodeorganisasi', index:'kd_organisasi', hidden:true,editable:false,editrules:{required:true}},
						{name:'program',width:70, index:'program',editable:true,editrules:{required:true}},
						{name:'prodi',width:250, index:'prodi',editable:true,editrules:{required:true}}
					],
					rownumbers:true,
					rowNum: 10,
					rowList: [10,20,30,40,50],
					pager: '#pager2',
					sortname: 'program',
					viewrecords: true,
					sortorder: "desc",
					multiselect: false,
					subGrid: true,
					caption: "Harga Satuan Bimbingan",

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
						jQuery("#"+subgrid_table_id).jqGrid({														
							url:"bimbingan_admin_hs_json2.php?id="+row_id,
							datatype: "json",
							colNames: ['Jenis Bimbingan', 'Harga Satuan'],
							colModel: [
								{name:'jenis_bimbingan',  width:214, index:'jenis_bimbingan'},
								{name:'harga_satuan', width:70, index:'harga_satuan', align:'right'}							
							],
							rownumbers:true,
							rowNum:20,
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
		</script>
	</head>
	<body style="background:#336666">
		<br><br>
		<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="pager2" class="scroll" style="text-align:center;"></div>
	</body>
</html>