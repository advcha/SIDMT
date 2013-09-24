<?php
	include("include/clsUtility.php");
	include("include/dbconfig.php");
    $myfunction = new MyFunction();
	
	$idtower = $_REQUEST["idtower"];
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	/*$sql = "SELECT o.nama,t.pemilik_tower,t.lokasi,k.kecamatan,n.nagari,t.tinggi,t.elevasi,t.koord_b,t.koord_l FROM tower t 
	inner join operator o on t.idoperator=o.idoperator 
	inner join kecamatan k on t.idkec=k.idkec 
	inner join nagari n on t.idnagari=n.idnagari 
	WHERE idtower = ".$idtower;*/
	$sql = "SELECT p.nama,GROUP_CONCAT(o.nama ORDER BY og.idopgabung SEPARATOR ', ') AS noperator,
		t.lokasi,k.kecamatan,n.nagari,t.tinggi,t.elevasi,t.koord_b,t.koord_l 
		FROM tower t INNER JOIN pemilik p ON t.idpemilik=p.idpemilik  
		INNER JOIN nagari n ON t.idnagari=n.idnagari 
		INNER JOIN kecamatan k ON n.idkec=k.idkec
		INNER JOIN opgabung og ON t.idtower=og.idtower
		INNER JOIN operator o ON og.idoperator=o.idoperator AND t.idtower = ".$idtower."
		GROUP BY t.idtower";
	//echo $SQL;
	$result = mysql_query( $sql ) or die("Could not execute query.".mysql_error());
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	
?>
<html>
    <head>
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<script src="js/jquery-1.2.6.min.js" type="text/javascript" charset="utf-8"></script>
		<style type="text/css">
			.ui-widget {
				font-size:0.7em;
			}
		</style>
	</head>
<body>
<form name="frmmap" id="frmmap">
	<table>
		<tr>
			<td>
				<fieldset style="width:240px">
					<legend></legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>Pemilik Tower :</b></td>
							<td><?php echo $row[nama];?></td>
						</tr>
						<tr>
							<td><b>Operator :</b></td>
							<td><?php echo $row[noperator];?></td>
						</tr>
						<tr>
							<td><b>Lokasi :</b></td>
							<td><?php echo $row[lokasi];?></td>
						</tr>
						<tr>
							<td><b>Kecamatan :</b></td>
							<td><?php echo $row[kecamatan];?></td>
						</tr>
						<tr>
							<td><b>Nagari :</b></td>
							<td><?php echo $row[nagari];?></td>
						</tr>
						<tr>
							<td><b>Tinggi :</b></td>
							<td><?php echo $row[tinggi];?> m</td>
						</tr>
						<tr>
							<td><b>Elevasi :</b></td>
							<td><?php echo $row[elevasi];?> m dpl</td>
						</tr>
						<tr>
							<td><b>Koordinat :</b></td>
							<td><?php 
							$bujur = str_replace(";"," ",calculateDecToDMS($row[koord_b],"bujur"));
							$lintang = str_replace(";"," ",calculateDecToDMS($row[koord_l],"lintang"));
							echo $bujur."<br>".$lintang;
							?></td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
</form>
</body>
</html>
