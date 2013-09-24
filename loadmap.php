<?php
	include("include/dbconfig.php");
	
	$idkec = $_REQUEST["idkec"];
	if(substr_count($idkec,",")>0)
		$idkec = substr($idkec,0,-1);
	$idop = $_REQUEST["idop"];
	if(substr_count($idop,",")>0)
		$idop = substr($idop,0,-1);
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");

?>
	<tr>
		<td>
			<img id='map_td' src='peta.php?idkec=<?php echo $idkec;?>&idop=<?php echo $idop;?>' usemap ='#td'>
			<map id='td' name='td'>
				<?php
					$w = 870;
					$b_b = 100.3167;
					$b_a = 100.85;

					$h = 594;
					$l_b = -0.2833;
					$l_a = -0.65;
					//membuat pointer cursor pada titik2 yg ada
					$querytitik = "";
					if(strlen($idkec)>0)
					{
						if($idkec != "0")	//certain kecamatan
						{
							$sql = "SELECT koord_b,koord_l,idtower FROM tower where idkec in (".$idkec.")";
							//check operator
							if(strlen($idop)>0)
							{
								if($idop != "0")
								{
									//$sql .= " and idoperator in (".$idop.")";
									$sql = "SELECT t.koord_b,t.koord_l,t.idtower FROM tower t 
									INNER JOIN pemilik p ON t.idpemilik=p.idpemilik 
									INNER JOIN opgabung og ON t.idtower=og.idtower 
									INNER JOIN operator o ON og.idoperator=o.idoperator  
									WHERE t.idkec IN (".$idkec.") AND o.idoperator IN (".$idop.")";
								}
							}
							$querytitik = mysql_query($sql);
						}
						else	//all kecamatan
						{
							$sql = "SELECT koord_b,koord_l,idtower FROM tower";
							if(strlen($idop)>0)
							{
								if($idop != "0")
								{
									//$sql .= " where idoperator in (".$idop.")";
									$sql = "SELECT t.koord_b,t.koord_l,t.idtower FROM tower t 
									INNER JOIN pemilik p ON t.idpemilik=p.idpemilik 
									INNER JOIN opgabung og ON t.idtower=og.idtower 
									INNER JOIN operator o ON og.idoperator=o.idoperator  
									WHERE o.idoperator IN (".$idop.")";
								}
							}
							$querytitik = mysql_query($sql);
						}
					}
					else	//no kecamatan so no operator
					{
						$querytitik = mysql_query("SELECT koord_b,koord_l,idtower FROM tower where idkec=''"); 
					}
					
					while($titik = mysql_fetch_row($querytitik))
					{
						$x = (($titik[0]-$b_b)/($b_a - $b_b))*$w;
						$y = (($titik[1]-$l_b)/($l_a - $l_b))*$h; 
						$minx = $x-3;
						$miny = $y-3;
						$maxx = $x+3;
						$maxy = $y+3;							
				?>
					<area shape='rect' coords='<?=$minx.",".$miny.",".$maxx.",".$maxy?>' href="javascript:getInfo('<?=$titik[2]?>');"> <!-- daftar link lokasi -->
				<?php
					}
				?>
			</map>
		</td>
	</tr>
<?php 
?>