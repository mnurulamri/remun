<?php
	include("../../remun/remun/conn.php");

	$page  = $_GET['page'];
	$limit = $_GET['rows'];
	$sidx  = $_GET['sidx'];
	$sord  = $_GET['sord'];
	
	if(!$sidx) $sidx=1;
	
	function ToSql ($field, $oper, $val) {
		// we need here more advanced checking using the type of the field - i.e. integer, string, float
		switch ($field) {
			case 'program':
			case 'prodi':
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
	$where = ""; //if there is no search request sent by jqgrid, $where should be empty
	$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
	$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
	$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
	if ($_GET['_search'] == 'true') {
		$where = getWhereClause($searchField,$searchOper,$searchString);
	}
        
	
	$data = mysql_query("SELECT COUNT(*) as count FROM organisasi".$where);
	$row  = mysql_fetch_array($data);
	$count = $row["count"];
	//die($count);
	
	$count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit;
	if($start <0) $start = 0;
	
	$data1 = mysql_query("SELECT id, kodeorganisasi, program, prodi
						  FROM organisasi
						  ORDER BY $sidx $sord LIMIT $start, $limit");
			  	
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($line = mysql_fetch_array($data1)){
		//$responce->rows[$i]['id']   = $line["id"];
		$responce->rows[$i]['cell'] = array($line["id"], $line["kodeorganisasi"], $line["program"], $line["prodi"]);
		$i++;
	}
	echo json_encode($responce);
?>