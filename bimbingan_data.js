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
		rowList: [10,20,30,40],
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
		alert($("#bulan").val());
		//if($("#bulan").val()=="01" || $("#bulan").val()=="02" || $("#bulan").val()=="03" || $("#bulan").val()=="04") {
		if($("#bulan").val()=="03"){
			alert('Data sudah di kunci!');
			$("#tahun").focus();
			return false;
		}
		
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
		$('#formInput').html('<img src="jqgrid/loading.gif" style="margin-left: auto; margin-right: auto; display:block;">').load(page);
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
	alert(tahun+' '+bulan);
	jQuery("#list2").jqGrid('setGridParam',{url:"bimbingan_json.php?tahun="+tahun+"&bulan="+bulan,page:1}).trigger("reloadGrid");
	//kosongkan box input
	document.form1.nama_pengajar.value = "";
	document.form1.nip.value = "";
	document.form1.nama_pengajar.value = "";
	document.getElementById("jenis_bimbingan").options[0].text = "...Pilih jenis Bimbingan...";
	document.form1.vharga_satuan.value = 0;
	document.form1.jml_mhs.value = 0;
	document.form1.vhonor.value = 0;

}

function enableAutosubmit(state){
	flAuto = state;
	jQuery("#submitButton").attr("disabled",state);
}