<?php include_once('menu.php'); ?>
<script>
function searchFilter(page_num) {
    page_num = page_num?page_num:0;
    var keywords = $('#keywords').val();
    var sortBy = $('#sortBy').val();
    $.ajax({
        type: 'POST',
        url: 'rbac_get_data.php',
        data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy,
        beforeSend: function () {
            $('.loading-overlay').show();
        },
        success: function (html) {
            $('#posts_content').html(html);
            $('.loading-overlay').fadeOut("slow");
        }
    });
}
</script>

<br><br><br><br>
	
	<div class="blink_me">U N D E R C O N S T R U C T I O N</div>
<div class="post-search-panel">
	<input type="text" value="Filter" style="text-align:center; color:#fff; background-color:#444; border:2px solid #444;" size="1"/>
    <input type="text" id="keywords" placeholder="keywords" onkeyup="searchFilter()"/>
    <select id="sortBy" onchange="searchFilter()">
        <option value="asc">Sort By</option>
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
    </select>
</div>
<div class="post-wrapper">
    <div class="loading-overlay"><div class="overlay-content">Loading.....</div></div>
    <div id="posts_content"></div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	function fetch_data()  
      {  
    	page_num = 0;
    	var keywords = "";
    	var sortBy = "asc";
    	$.ajax({
        	type: 'POST',
        	url: 'rbac_get_data.php',
        	data:'page='+page_num+'&keywords='+keywords+'&sortBy='+sortBy,
        	beforeSend: function () {
            	$('.loading-overlay').show();
        	},
        	success: function (html) {
            	$('#posts_content').html(html);
            	$('.loading-overlay').fadeOut("slow");
        	}
    	});
      }
      fetch_data();
      
	
    $(document).on('click', '.check', function(){  
    //$(".check").click(function(){
    	var nip = $(this).attr("id")
    	//alert(nip)
    	$.ajax({
    		type: "POST",
    		url: "rbac_get_prodi.php",
    		data:"nip=" + nip,	// atau bisa juga pake -> $(this).serialize(),
    		success:function(data){
    			$("#prodi-"+nip).html(data)			
    			$("#detail-prodi-"+nip).toggle(function(){
    				//$("#tr-"+nip).css("background", "#f2f2f2")
    			})
    		}
    	})
    })

    $(document).on('click', '.edit', function(){  
    //$(".edit").click(function(){
    	var nip = $(this).data("nip")
    	$.ajax({
    		type: "POST",
    		url: "rbac_edit_form.php",
    		data:"nip=" + nip,	// atau bisa juga pake -> $(this).serialize(),
    		success:function(data){
    			$("#prodi-"+nip).html(data)			
    			$("#detail-prodi-"+nip).toggle(function(){
    				
    			})
    		}
    	})
    })

    $(document).on('click', '#simpan', function(){

        /* contoh
        var check = $('input[type=checkbox]:checked').map(function(_, el) {
            return $(el).val()
        }).get()
        */
        var nip = $(this).data("nip")
        var nama = $("#tr-"+nip).find("td:nth-child(2)").text()
        var role = $('.role:checked').map(function(_, el) {
            return $(el).val()
        }).get()

        var prodi = $('.prodi:checked').map(function(_, el) {
            return $(el).val()
        }).get()
		
        $.ajax({
            type: "POST",
            url: "rbac_edit_form_proses.php",
            data:{nip:nip, role:role, prodi:prodi, nama:nama},  // atau bisa juga pake -> $(this).serialize(),
            success:function(data){
            	$("#tr-"+nip).html(data)
            	//var pagedata = jQuery.data( document.body, data);
				//var break_data = JSON.parge(pagedata)
            	//document.myform.extra.value = data,
            //$.get(data)
            	//alert(data)
                alert('data sudah disimpan')
                //$("#"+nip+"_Admin").prop("checked", true)
                console.log(data)
            }
        })
        
    })

    $(document).on('click', '.check', function(){
        $('.prodi:checkbox').not(this).prop('checked', this.checked);
    })
    
    $(document).on('click', '.prodi', function(){
        $('.check:checkbox').not(this).prop('checked', this.checked);
    })
    /*
    $(document).on('click', '.role', function(){
        var id = $(this).data("id")
        alert(id)
    })
	*/
})


	
</script>

<style>
table tr td, th {border:1px solid gray; padding:2px;}
.detail-prodi {display:none;}
.post-search-panel{text-align:center; padding-bottom:5px}
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 70%;
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
/* blink */
.blink_me {
	text-align:center;
    -webkit-animation-name: blinker;
    -webkit-animation-duration: 1.5s;
    -webkit-animation-timing-function: linear;
    -webkit-animation-iteration-count: infinite;

    -moz-animation-name: blinker;
    -moz-animation-duration: 1.5s;
    -moz-animation-timing-function: linear;
    -moz-animation-iteration-count: infinite;

    animation-name: blinker;
    animation-duration: 1.5s;
    animation-timing-function: linear;
    animation-iteration-count: infinite;
}

@-moz-keyframes blinker {  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}

@-webkit-keyframes blinker {  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}

@keyframes blinker {  
    0% { opacity: 1.0; }
    50% { opacity: 0.0; }
    100% { opacity: 1.0; }
}
</style>