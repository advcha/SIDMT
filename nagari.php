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
	$idkec = strip_tags($_REQUEST['idkec']);
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	if($searchOn == 'true')
	{
		$SQL = "SELECT '0' AS idnagari,'--Semua Nagari--' AS nagari
			UNION
			SELECT idnagari,nagari FROM nagari where idkec = ".$idkec;
	}
	else
	{
		$SQL = "select idnagari,nagari FROM nagari where idkec = ".$idkec." order by idnagari";
	}
	//echo $SQL;
	$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());

	$nagari = "";
	while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
	{
		$nagari .= $row[idnagari].":".$row[nagari].";";
	}
	if($nagari != "")
		$nagari = substr($nagari,0,-1);
	echo $nagari;
?>