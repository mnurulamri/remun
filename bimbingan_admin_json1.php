<?php
	include("conn.php");

	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];
	
	if(!$sidx) $sidx=1;
	
	if(isset($_GET["tahun"]))
		$tahun = $_GET['tahun'];
	else
		$tahun = date("Y");
	
	if(isset($_GET["bulan"]))
		$bulan = $_GET['bulan'];
	else
		$bulan = date("m");
	
	function ToSql ($field, $oper, $val) {
		// we need here more advanced checking using the type of the field - i.e. integer, string, float
		switch ($field) {
			case 'nik':
				//return intval($val);
				//break;
			case 'nama':
			case 'alamat':
			case 'kota':
				//return floatval($val);
				//break;
			default :
				//mysql_real_escape_string is better
				if($oper=='bw' || $oper=='bn') return "'" . addslashes($val) . "%'";
				else if ($oper=='ew' || $oper=='en') return "'%" . addcslashes($val) . "'";
				else if ($oper=='cn' || $oper=='nc') return "'%" . addslashes($val) . "%'";
				else return "'" . addslashes($val) . "'";
		}
	}
	
	###For Single Searching###
	
	//array to translate the search type
	$ops = array(
            'eq'=>'=', //equal
            'ne'=>'<>',//not equal
            'lt'=>'<', //less than
            'le'=>'<=',//less than or equal
            'gt'=>'>', //greater than
            'ge'=>'>=',//greater than or equal
            'bw'=>'LIKE', //begins with
            'bn'=>'NOT LIKE', //doesn't begin with
            'in'=>'LIKE', //is in
            'ni'=>'NOT LIKE', //is not in
            'ew'=>'LIKE', //ends with
            'en'=>'NOT LIKE', //doesn't end with
            'cn'=>'LIKE', // contains
            'nc'=>'NOT LIKE'  //doesn't contain
	);
	function getWhereClause($col, $oper, $val){
            global $ops;
            if($oper == 'bw' || $oper == 'bn') $val .= '%';
            if($oper == 'ew' || $oper == 'en' ) $val = '%'.$val;
            if($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni') $val = '%'.$val.'%';
            return " WHERE $col {$ops[$oper]} '$val' ";
	}
	$where = " WHERE tahun = $tahun and bulan = '$bulan'"; //if there is no search request sent by jqgrid, $where should be empty
	$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
	$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
	$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
	if ($_GET['_search'] == 'true') {
		$where = getWhereClause($searchField,$searchOper,$searchString);
	}
        
	
	$data = mysql_query("SELECT COUNT(*) as count FROM bimbingan".$where);
	$row  = mysql_fetch_array($data);
	$count = $row["count"];
	//die($count);
	
	$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;
	
	$data1 = mysql_query("SELECT distinct b.id as id, kd_organisasi, program, prodi, sum(honor) as honor
						  FROM bimbingan a, organisasi b, master_pengajar c
						  WHERE kd_organisasi = kodeorganisasi and a.tahun = $tahun and a.bulan = '$bulan' and 
								a.nip = c.nip
						  GROUP BY program, substr(kd_organisasi,4,2), kd_organisasi");
			  //ORDER BY $sidx $sord LIMIT $start, $limit");
	
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	$honor = 0;
	while($line = mysql_fetch_array($data1)){
		$honor = $honor + $line["honor"];
		$responce->rows[$i]['id']   = $line["id"];
		$responce->rows[$i]['cell'] = array($line["id"],$line["kd_organisasi"],$line["program"],$line["prodi"],number_format($line["honor"]));
		$i++;
	}
	$responce->userdata['honor'] = number_format($honor);
	echo json_encode($responce);
?>