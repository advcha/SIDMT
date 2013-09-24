<?php
	//include("include/clsUtility.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
	$idizin=mysql_real_escape_string($_REQUEST['id']);
	$izin_prinsip=mysql_real_escape_string($_REQUEST['izin_prinsip']);
	$izin_ho=mysql_real_escape_string($_REQUEST['izin_ho']);
	$imb=mysql_real_escape_string($_REQUEST['imb']);
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
	
	$res = "";
	
	if($idizin == "")	//check add
	{
		if($izin_prinsip != "")
		{
			$sql = "SELECT COUNT(idizin) AS count FROM izin WHERE no_izin_prinsip = '".$izin_prinsip."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count != 0)
				$res .= "No Izin Prinsip '".$izin_prinsip."' Sudah Ada di Database. ";
		}
		
		if($izin_ho != "")
		{
			$sql = "SELECT COUNT(idizin) AS count FROM izin WHERE no_izin_ho = '".$izin_ho."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count != 0)
				$res .= "No Izin HO '".$izin_ho."' Sudah Ada di Database. ";
		}
		
		if($imb != "")
		{
			$sql = "SELECT COUNT(idizin) AS count FROM izin WHERE no_imb = '".$imb."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count != 0)
				$res .= "No IMB '".$imb."' Sudah Ada di Database.";
		}
	}
	else	//check edit
	{
		if($izin_prinsip != "")
		{
			$sql = "SELECT COUNT(idizin) AS count FROM izin WHERE no_izin_prinsip = '".$izin_prinsip."' AND idizin !=".$idizin;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count != 0)
				$res .= "No Izin Prinsip '".$izin_prinsip."' Sudah Ada di Database. ";
		}
		
		if($izin_ho != "")
		{
			$sql = "SELECT COUNT(idizin) AS count FROM izin WHERE no_izin_ho = '".$izin_ho."' AND idizin !=".$idizin;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count != 0)
				$res .= "No Izin HO '".$izin_ho."' Sudah Ada di Database. ";
		}
		
		if($imb != "")
		{
			$sql = "SELECT COUNT(idizin) AS count FROM izin WHERE no_imb = '".$imb."' AND idizin !=".$idizin;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			//no imb bisa sama untuk izin yg diperpanjang --2001-08-25
			if($count != 0)
			{
				$sqli = "SELECT COUNT(idizinlama) AS count FROM izin WHERE no_imb = '".$imb."' AND idizinlama != 0";
				$resulti = mysql_query($sqli) or die("Could not execute query.".mysql_error());
				$rowi = mysql_fetch_array($resulti,MYSQL_ASSOC);
				$counti = $rowi['count'];
				if($counti == 0)
				{
					$res .= "No IMB '".$imb."' Sudah Ada di Database.";
				}
			}
		}
	}
	echo $res;
?>