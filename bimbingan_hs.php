
<html>
<!-----------------------------------------------------------------
					Tampilan bimbingan detail
------------------------------------------------------------------>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<title>Honor Pembimbingan <? echo $_SESSION["programstudi"];?></title>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="/grid/gt_grid_height.css" />
<link rel="stylesheet" type="text/css" href="/grid/skin/vista/skinstyle.css" />
<script type="text/javascript" src="/grid/gt_msg_en.js"></script>
<script type="text/javascript" src="/grid/gt_grid_all.js"></script>
<script type="text/javascript" src="/grid/flashchart/fusioncharts/FusionCharts.js"></script>
<script type="text/javascript" src="/grid/calendar/calendar.js"></script>
<script type="text/javascript" src="/grid/calendar/calendar-setup.js"></script>
<script src="/highlight/jssc3.js" type="text/javascript"></script>
<link href="./highlight/style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" >
var grid_demo_id = "myGrid1";
var dsOption= {
	fields :[ //name pada fields bersifat case sensitive, jadi sesuaikan dengan nama atribut dari table
		{name: 'id'},
		{name: 'kd_organisasi'},
		{name: 'jenis_bimbingan'},
		{name: 'harga_satuan'}
	],
	recordType : 'object'
}

function formatNumber(num,dec,thou,pnt,curr1,curr2,n1,n2) {
	var x = Math.round(num * Math.pow(10,dec));
	if (x >= 0) n1=n2='';
	var y = (''+Math.abs(x)).split('');
	var z = y.length - dec;
	if (z<0) z--;
	for(var i = z; i < 0; i++) y.unshift('0');
	if (z<0) z = 1;
	y.splice(z, 0, pnt);
	if(y[0] == pnt) y.unshift('0');
	while (z > 3) {
		z-=3; y.splice(z,0,thou);
	}
	var r = curr1+n1+y.join('')+n2+curr2;
	return r;
}

var colsOption = [ //id pada colsOption bersifat case sensitive, jadi sesuaikan dengan nama atribut dari table
	//{id: 'chk' ,isCheckColumn : true, editable:false },
	{id: 'id', header: "ID", width :50, hidden:true},
	{id: 'jenis_bimbingan', header: "Jenis Bimbingan", width :330,  editor:{type:"text"}},
	{id: 'harga_satuan', header: "Harga Satuan", align:"right", width :90, editor:{type:"text"}, renderer: function(value, record, columnObj, grid, colNo, rowNo) { return formatNumber(value,0,",","","","","","")}}];

var gridOption={
	id : grid_demo_id,
	loadURL : 'bimbingan_hs_controller.php',
	saveURL : 'bimbingan_hs_controller.php',
	exportURL : '',	
	width: 460,  //"100%", // 700,
	height: "50%",  //"100%", // 330,
	container : 'gridbox',
	replaceContainer : true,
	encoding : 'UTF-8', // Sigma.$encoding(),
	dataset : dsOption ,
	columns : colsOption ,
	clickStartEdit : true ,
	showIndexColumn : true,
	pageSize:10,
	reloadAfterSave: true,
	submitUpdatedFields: true,
	toolbarContent : 'reload | add | del | save'
};

var mygrid=new Sigma.Grid( gridOption );
Sigma.Util.onLoad(function(){mygrid.render()});

//////////////////////////////////////////////////////////

</script>
<style>
#teks {font:bold 11px verdana;text-align:left; color:yellow;}
</style>
<?
include("menu.php");
?>
<br><br>
<body style="background:#336666;">
<div id="page-container" style="margin-left:30%;">
	<div id="header"></div>
	<div style="margin:15px;display:!none;"></div>				
	<div id="content">
		<div id="bigbox" style="margin:15px;display:!none;">
			<div id="gridbox"></div>
		</div>
	</div>
</div>
</body>
</html>