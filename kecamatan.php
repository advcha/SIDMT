<?php
	//include("include/clsUtility.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
	/*
		$post="test<br>";
		foreach ($_POST as $key=>$value){
			$post .= "key : ".$key." and value : ".$value.", ";
		}
		echo $post;
	*/
	$searchOn = strip_tags($_REQUEST['search']);
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	if($searchOn == 'true')
	{
		$SQL = "SELECT '0' AS idkec,'--Semua Kecamatan--' AS kecamatan
			UNION
			SELECT idkec,kecamatan FROM kecamatan";
	}
	else
	{
		$SQL = "select idkec,kecamatan FROM kecamatan order by idkec";
	}
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());

	$kec = "";
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
	{
		$kec .= $row[idkec].":".$row[kecamatan].";";
	}
	if($kec != "")
		$kec = substr($kec,0,-1);
	echo $kec;
?>