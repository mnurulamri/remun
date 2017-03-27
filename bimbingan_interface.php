<?php 
include("menu.php"); 
include("bulan.php");
?>
<script type="text/javascript" src="js/jquery-1.2.3.min.js"></script>
<script type="text/javascript">
	var cur = 0;
	function displayDiv(idx) {
		if (cur==idx) return true;
		document.getElementById("div"+cur).style.display = "none";
		document.getElementById("div"+idx).style.display = "block";
		cur = idx;
		return true;
	}
		
	window.onload = function() {
		return displayDiv(document.form.semester.selectedIndex);
	}
	
	$(document).ready(function() {

		$().ajaxStart(function() {
			$('#loading').show();
			$('#result').hide();
		}).ajaxStop(function() {
			$('#loading').hide();
			$('#result').fadeIn('slow');
			clock.start();
		});

		$('#myForm').submit(function() {
			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				data: $(this).serialize(),
				success: function(data) {
					$('#result').html(data);
				}
			})
			return false;
		});
	})
	
	function hideContent() {
		$("#Hasil").fadeOut("slow")
		return false;
	}
</script>

<link href="style.css" media="screen, projection" rel="stylesheet" type="text/css">

<body bgcolor="#336666" style="background-color:#336666">
<div style="border:solid #336666; height:30px"><font style="color:#336666">&nbsp;</font></div>
	<form action="bimbingan_view.php" method="post" name="form" id="myForm">
		<div style="border:solid #336666">	
		
			<!----------------------------kotak atas-------------------------------------->
			<div class="inset">
				<b class="b1"></b><b class="b2" style="background-color:#fa0"></b><b class="b3" style="background-color:#fa0"></b><b class="b4" style="background-color:#fa0"></b>
				<div class="boxcontent" style="text-align:center;">
			<!---------------------------kotak atas--------------------------------------->		

					<div style="font:bold 15px verdana; color:#336666; background-color:#fa0">
						<div valign="center" align="center" colspan="3" height="22%">
						<h1>Lihat Data Bimbingan dan Insentif</h1>
						</div>
					</div>
					<table align="center" style="text:align:center">
						<tr>
							<td colspan="3" height="10px"></td>
						</tr>						
					</table>	
					
					<div>
						<div colspan="3" height="15px"></div>
					</div>
					<div style="font:bold 12px verdana; color:#555">
						<span align="right"><label>Tahun</label></span>
						<span align="center">:</span>
						<span>
							<select style="font:bold 11px verdana; color:#555" name="tahun">
								<option value="<?echo date('Y')?>"><?echo date('Y')?></option>
								<option value="<?echo date('Y')-1?>"><?echo date('Y')-1?></option>
							</select>
						</span>
						<span>&nbsp;&nbsp;&nbsp;</span>							
						<span align="right"><label>Bulan</label></span>
						<span align="center"><font color="#555">:</font></span>
						<span>
							<span>							
								<select style="font:bold 11px verdana; color:#555; width: 10em;" name="bulan" >
									<option style="color:blue;" value="<?php echo $month ?>"><?php echo $month ?></option>
									<option style="color:magenta;" value="Januari">Januari</option>									
									<option style="color:blue;" value="Februari">Februari</option>
									<option style="color:green;" value="Maret">Maret</option>
									<option style="color:purple;" value="April">April</option>
									<option style="color:red;" value="Mei">Mei</option>
									<option style="color:blue;" value="Juni">Juni</option>
									<option style="color:green;" value="Juli">Juli</option>
									<option style="color:purple;" value="Agustus">Agustus</option>
									<option style="color:red;" value="September">September</option>
									<option style="color:blue;" value="Oktober">Oktober</option>
									<option style="color:green;" value="November">November</option>
									<option style="color:purple;" value="Desember">Desember</option>									
								</select>							
							</span>
						</span>
					</div>
					<div>
						<span></span>
					</div>					
					<div>
						<hr style="color:#fa0"></hr>
					</div>
					<div>
						<div align="center">							
							<input type="submit" value="generate" name="generate" style="font:bold 11px verdana; height: 2.5em; :hover" onclick="document.getElementById('Hasil').style.display = 'block';"/>
							<input type="reset" value="Reset" onclick="return hideContent();"/>
						</div>
					</div>

			<!--------------------------kotak bawah----------------------------------->
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<!--------------------------kotak bawah----------------------------------->
			
		</div>
	</form>
	<div id='Hasil' style="display:none;">
		<?KotakAtasResult()?>		
		<div id="loading"style="display:none;"><img src="../remun/images/indicator.gif" alt="loading..." /></div>
		<div id="result"></div>
		<?KotakBawahResult()?>
	</div>
<?
function KotakAtasResult(){
	echo "
	<div class='inset' style='width:40%'>
		<b class='b1'></b><b class='b2' style='background-color:yellow'></b><b class='b3' style='background-color:yellow'></b><b class='b4' style='background-color:yellow'></b>
		<div class='boxcontent' style='text-align:center; background-color:#555555'>
	";
}

function KotakBawahResult(){
	echo "
		</div>
		<b class='b4b' style='background-color:yellow'></b><b class='b3b' style='background-color:yellow'></b><b class='b2b' style='background-color:yellow'></b><b class='b1b' style='background-color:yellow'></b>
	</div>
	";
}
?>
</body>