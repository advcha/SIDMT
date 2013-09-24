<?php
	header("Content-type: text/xml");
	include("include/dbconfig.php");
	$searchOn = strip_tags($_REQUEST['search']);
	$sel = strip_tags($_REQUEST['sel']);
	$idoperator = strip_tags($_REQUEST['idoperator']);
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	if($searchOn == 'true')
	{
		if($sel == "true")
		{
			$SQL = "SELECT idoperator,nama FROM operator";
		}
		else
		{
			$SQL = "SELECT '0' AS idoperator,'--Semua Operator--' AS nama
				UNION
				SELECT idoperator,nama FROM operator";
		}
	}
	else
	{
		if(!isset($_REQUEST['idoperator']))
		{
			$SQL = "select idoperator,nama,alamat,perwakilan,alamat_perwakilan,contact_person,telp,pemilik,telp_pemilik FROM operator WHERE idoperator=".$idoperator." order by nama";
		}
		else
		{
			$SQL = "SELECT '0' AS idoperator,'--Semua Operator--' AS nama
				UNION
				SELECT idoperator,nama FROM operator";
		}
	}
	//echo $SQL;
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());

	$operator = "";
	if($searchOn == 'true')
	{
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
		{
			$operator .= $row[idoperator].":".$row[nama].";";
		}
		if($operator != "")
			$operator = substr($operator,0,-1);
	}
	else
	{
		$operator = "<?xml version=\"1.0\"?>\n"; 
		$operator .= "<operators>\n"; 
		while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
		{
			$operator .= "\t<operator>\n"; 
			$operator .= "\t\t<idoperator>" . $row['idoperator'] . "</idoperator>\n";
			$operator .= "\t\t<nama>" . $row['nama'] . "</nama>\n";
			$operator .= "\t\t<alamat>" . $row['alamat'] . "</alamat>\n";
			$operator .= "\t\t<perwakilan>" . $row['perwakilan'] . "</perwakilan>\n";
			$operator .= "\t\t<alamat_perwakilan>" . $row['alamat_perwakilan'] . "</alamat_perwakilan>\n";
			$operator .= "\t\t<contact_person>" . $row['contact_person'] . "</contact_person>\n";
			$operator .= "\t\t<telp>" . $row['telp'] . "</telp>\n";
			$operator .= "\t\t<pemilik>" . $row['pemilik'] . "</pemilik>\n";
			$operator .= "\t\t<telp_pemilik>" . $row['telp_pemilik'] . "</telp_pemilik>\n";
			$operator .= "\t</operator>\n";
		}
		$operator .= "</operators>";
	}
	echo $operator;
?>