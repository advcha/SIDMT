<?php
	header("Content-type: text/xml");
	include("include/dbconfig.php");
	$searchOn = strip_tags($_REQUEST['search']);
	$idpemilik = strip_tags($_REQUEST['idpemilik']);
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	if($searchOn == 'true')
	{
		$SQL = "SELECT '0' AS idpemilik,'--Semua Pemilik Tower--' AS nama
			UNION
			SELECT idpemilik,nama FROM pemilik";
	}
	else
	{
		$SQL = "select idpemilik,nama,alamat,perwakilan,alamat_perwakilan,contact_person,telp,pemilik,telp_pemilik FROM pemilik WHERE idpemilik=".$idpemilik." order by nama";
	}
	//echo $SQL;
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());

	$pemilik = "";
	if($searchOn == 'true')
	{
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
		{
			$pemilik .= $row[idpemilik].":".$row[nama].";";
		}
		if($pemilik != "")
			$pemilik = substr($pemilik,0,-1);
	}
	else
	{
		$pemilik = "<?xml version=\"1.0\"?>\n"; 
		$pemilik .= "<pemiliks>\n"; 
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
		{
			$pemilik .= "\t<pemilik>\n"; 
			$pemilik .= "\t\t<idpemilik>" . $row['idpemilik'] . "</idpemilik>\n";
			$pemilik .= "\t\t<nama>" . $row['nama'] . "</nama>\n";
			$pemilik .= "\t\t<alamat>" . $row['alamat'] . "</alamat>\n";
			$pemilik .= "\t\t<perwakilan>" . $row['perwakilan'] . "</perwakilan>\n";
			$pemilik .= "\t\t<alamat_perwakilan>" . $row['alamat_perwakilan'] . "</alamat_perwakilan>\n";
			$pemilik .= "\t\t<contact_person>" . $row['contact_person'] . "</contact_person>\n";
			$pemilik .= "\t\t<telp>" . $row['telp'] . "</telp>\n";
			$pemilik .= "\t\t<pemilik_tower>" . $row['pemilik'] . "</pemilik_tower>\n";
			$pemilik .= "\t\t<telp_pemilik>" . $row['telp_pemilik'] . "</telp_pemilik>\n";
			$pemilik .= "\t</pemilik>\n";
		}
		$pemilik .= "</pemiliks>";
	}
	echo $pemilik;
?>