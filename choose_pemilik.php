<?php
	//include("include/clsUtility.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
    //$myfunction = new MyFunction();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	//check if any user is exist
	$SQL = "SELECT 0 AS idpemilik,'--Semua Pemilik Tower--' AS nama UNION SELECT idpemilik,nama FROM pemilik";
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
?>
<html>
    <head>
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<!--script src="js/jquery-1.2.6.min.js" type="text/javascript" charset="utf-8"></script-->
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#c_pemilik").focus();
				jQuery('#c_pemilik').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery(":button:last").focus();
					}
				});
			});
		</script>
		<style type="text/css">
			.ui-widget {
				font-size:0.8em;
			}
		</style>
	</head>
<body>
<form name="frmlogin" id="frmlogin" method="post">
	<table>
		<tr>
			<td>
				<fieldset style="width:240px">
					<legend></legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>Pemilik Tower :</b></td>
							<td><select name="c_pemilik" id="c_pemilik">
							<?php 
								while($row = mysql_fetch_array($result,MYSQL_ASSOC)){
									echo "<option value='".$row['idpemilik']."'>".$row['nama']."</option>";
								}
							?>
							</select>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
