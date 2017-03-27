<?
if(!session_id()) session_start();
if(!isset($_SESSION['username']) || empty($_SESSION['username']))	header("Location:index.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php include("menu.php"); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Bimbingan dan Insentif</title>
        <!-- Mengincludekan JQueryUI CSS -->
		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/sunny/jquery-ui-1.8.16.custom.css" />
        <!-- Mengincludekan CSS Jqgrid -->
		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/ui.jqgrid.css" />
		
		<!-- Sedikit CSS agar lebih bagus -->
		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/pemanis.css" />
		
        <!-- Mengincludekan Library Jquery -->
		<script src="jqgrid/js/jquery-1.5.2.min.js" type="text/javascript"></script>
		
		<!-- Library Jquery UI untuk mempercantik Button2 nya -->
		<!-- Mengincludekan Library Jquery UI-->
		<script src="jqgrid/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>

		<link rel="stylesheet" type="text/css" media="screen" href="jqgrid/css/center.css" />
		
		<!-- Mengincludekan Locale untuk JQGrid -->
		<script src="jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
        <!-- Mengincludekan Library untuk JQGrid -->
		<script src="jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
	
		<script language="javascript" type="text/javascript">
		$(document).ready(function(){
			//Mempercantik Button dengan Jquery UI
			$("#AddButton").button();
			
			var grid = $("#list2");
			grid.jqGrid({
				url: 'bimbingan_json.php', //URL Tujuan Yg Mengenerate data Json nya
				datatype: "json", //Datatype yg di gunakan
				height: "auto", //Mengset Tinggi table jadi Auto menyesuaikan dengan isi table
				mtype: "GET",
				colNames: ['edit','hapus','id','Nama Pengajar','Keterangan Honor','Jml Mhs/Item','Harga Satuan','Honor'],
				colModel: [						
					{name:'act', sortable:false, width: 30, align: 'center'},
					{name:'delete', sortable:false, width: 30, align: 'center'},
					{name:'id', key:true, index:'id', hidden:true,editable:false,editrules:{required:true}},
					{name:'nama_pengajar', index:'nama_pengajar'},
					{name:'jenis_bimbingan', index:'jenis_bimbingan', width: 250},
					{name:'jml_mhs',index:'jml_mhs', align:'center', width: 75},
					{name:'harga_satuan',index:'harga_satuan',align:'right', width: 100},
					{name:'honor',index:'honor',align:'right', width: 110}
				],
				rownumbers:true,
				rowNum: 10,
				rowList: [10,20,30,40,100],
				pager: '#pager2',
				sortname: 'nama_pengajar',
				viewrecords: true,
				sortorder: "desc",
				editurl: "bimbingan_crud.php",
				multiselect: false,
				footerrow : true, 
				userDataOnFooter : true, 
				caption: "Data Bimbingan",
				altRows : true
			});
			grid.jqGrid('navGrid','#pager2',{view:true,edit:false,add:false,del:false});
			
			$("#submit").live("click", function(){
				if($("#nama_pengajar").val()=="") {
					alert('Field Nama tidak boleh kosong!');
					$("#nama_pengajar").focus();
					return false;
				}
				if($("#jenis_bimbingan").val()=="") {
					alert('Field Bimbingan tidak boleh kosong!');
					$("#nm_bimbingan").focus();
					return false;
				}
				if($("#jml_mhs").val()=="") {
					alert('Field Jumlah Mahasiswa tidak boleh kosong!');
					$("#jml_mhs").focus();
					return false;
				}
				if($("#harga_satuan").val()=="") {
					alert('Field Harga Satuan tidak boleh kosong!');
					$("#harga_satuan").focus();
					return false;
				}
				var jwb = confirm('Anda Yakin ?');
				if (jwb == 1)
				{						
					return $.ajax({
						type: $("#form1").attr("method"),
						url: $("#form1").attr("action"),
						data : $("#form1").serialize(),
						async: false,
						success: function(data) {
							if(data.error) {
								alert(data);
							}else {
								//alert("Update Sukses");
								$('#list2').trigger('reloadGrid'); //Triger Untuk Reload List JQGrid
								clock.start();
							}
						}								
					}).responseText
				}
				else {
					return false;
				}
			});
			
			$('a.add').live('click',function(){		
				page = $(this).attr("href");
				$('#formInput').html('<img src="jqgrid/loading.gif" style="margin-left: auto; margin-right: auto; display:block;">').load(page, function(){
					var tahun = jQuery("#tahun").val();
					var bulan = jQuery("#bulan").val();				
					$.ajax({
						url: "bimbingan_lock.php", // Url to which the request is send
						type: "POST",             // Type of request to be send, called as method
						data: 'tahun='+tahun+'&bulan='+bulan, //  -> Data sent to server, a set of key/value pairs (i.e. form fields and values)
						success: function(data)   // A function to be called if request succeeds
						{
							$("#lock").html(data);
						}					
					});
				});
				return false;
			});

		});

		//Fungsi Javascript Numeric Only
		function isNumberKey(evt)
		{
			var charCode = (evt.which) ? evt.which : event.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;		
			return true;
		}			


		//////////////////////////...tambahan fungsi searching...//////////////////////////////////////////////////////////////////
		var timeoutHnd;
		var flAuto = false;

		function doSearch(ev){
			if(!flAuto)
				return;
		//	var elem = ev.target||ev.srcElement;
			if(timeoutHnd)
				clearTimeout(timeoutHnd)
			timeoutHnd = setTimeout(gridReload,500)
		}

		function gridReload(){
			var tahun = jQuery("#tahun").val();
			var bulan = jQuery("#bulan").val();
			
			jQuery("#list2").jqGrid('setGridParam',{url:"bimbingan_json.php?tahun="+tahun+"&bulan="+bulan,page:1}).trigger("reloadGrid");
			clock.start();
			/*kosongkan box input
			document.form1.nama_pengajar.value = "";
			document.form1.nip.value = "";
			document.form1.nama_pengajar.value = "";
			document.getElementById("jenis_bimbingan").options[0].text = "...Pilih jenis Bimbingan...";
			document.form1.vharga_satuan.value = 0;
			document.form1.jml_mhs.value = 0;
			document.form1.vhonor.value = 0;*/
			
			//kunci data bimbingan
			$.post('bimbingan_lock.php', {tahun:tahun, bulan:bulan}, 
				function(output){
					$('#lock').html(output).show("slow",0.8);
				}
			);
		}

		function enableAutosubmit(state){
			flAuto = state;
			jQuery("#submitButton").attr("disabled",state);
		}
		</script>		
	</head>
	<body  style="background:#336666">
		
		<br><br>
		<div style="margin-left: auto; margin-right: auto; text-align:center">
			<?
			//untuk ngunci tombol
			/*$cur_year = date('Y');
			$cur_month = date('m');
			$result = mysql_query("SELECT DISTINCT trans FROM bimbingan WHERE tahun=$cur_year and bulan='$cur_month'") or die();
			$row_num = mysql_num_rows($result);
			if($row_num == 0){ //jika tidak ada data
				echo '
				<a href="bimbingan_input.php" class="add" ><input type="button" id="AddButton" class="button" value="Tambah Data" ></a>';
			} else {
				while($row = mysql_fetch_object($result)){
					if($row->trans == 0){
						echo '
					<a href="bimbingan_input.php" class="add" ><input type="button" id="AddButton" class="button" value="Tambah Data" ></a>';	
					}
				}
			}*/
			?>
			<a href="bimbingan_input.php" class="add" ><input type="button" id="AddButton" class="button" value="Tambah Data" ></a> <!---->
		</div>	
		<div id="formInput"></div>		
		<br/>
		<table id="list2" class="scroll" cellpadding="0" cellspacing="0"></table>
		<div id="pager2" class="scroll" style="text-align:center;"></div>
		<br/>		
	</body>
</html>