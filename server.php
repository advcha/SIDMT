<?php
	include("include/clsUtility.php");
	//include("include/mysql.class.php");
	include("include/dbconfig.php");
	include("include/JSON.php");
	$json = new Services_JSON();
	$myfunction = new MyFunction();
	
	$mode = $_REQUEST["mode"];
	$type = $_REQUEST["type"];
	$oper = $_REQUEST["oper"];

	$page = $_REQUEST['page']; // get the requested page
	$limit = $_REQUEST['rows']; // get how many rows we want to have into the grid
	$sidx = $_REQUEST['sidx']; // get index row - i.e. user click to sort
	$sord = $_REQUEST['sord']; // get the direction
	if(!$sidx) $sidx =1;
	/* BEGIN SEARCH OPTION */
	$where = "";
	$wherepemilik = "";
	$whereoperator = "";
	$whereizin = "";
	$wherenagari = "";
	$wherecatudaya = "";
	$having = "";
	
	$searchOn = strip_tags($_REQUEST['_search']);
	if($searchOn == 'true') 
	{
		if($type == 'kecamatan') 
		{
			$fld = strip_tags($_REQUEST['searchField']);
			$fldata = mysql_real_escape_string(strip_tags($_REQUEST['searchString']));
			$foper = strip_tags($_REQUEST['searchOper']);
			
			// costruct where
			switch ($foper) {
				case "cn":
					$where .= " where ".$fld." LIKE '%".$fldata."%'";
					break;
				default:
					$where = "";
			}
			
		}
		/*else if($type == 'nagari') 
		{
			$filters = "";
			if(get_magic_quotes_gpc())
			{
				$filters = stripslashes($_REQUEST['filters']);
			}
			else
			{
				$filters = $_REQUEST['filters'];
			}
			$filters = json_decode($filters,true);
			$andor = $filters[groupOp];
			//$rules = $filters[rules];

			for($i=0;$i<count($filters[rules]);$i++)
			{
				$idkec = 0; 
				$nagari = ""; 
				
				if($filters[rules][$i][field] == "kecamatan")
				{
					$idkec = $filters[rules][$i][data]; 
				}
				
				if($filters[rules][$i][field] == "nagari")
				{
					$nagari = mysql_real_escape_string($filters[rules][$i][data]); 
				}
				
				switch ($andor) {
					case "AND":
						if($idkec != 0)
						{
							$where .= " AND a.idkec = ".$idkec;
						}
						if($nagari != "")
						{
							$where .= " AND b.nagari LIKE '%".$nagari."%'";
						}
						break;
					case "OR":
						if($idkec != 0)
						{
							$where .= " AND a.idkec = ".$idkec;
						}
						if($nagari != "")
						{
							if($idkec != 0)
							{
								$where .= " OR b.nagari LIKE '%".$nagari."%'";
							}
							else
							{
								$where .= " AND b.nagari LIKE '%".$nagari."%'";
							}
						}
						break;
					default:
						//$where = "";
				}
			}
			
			// costruct where
			
		}*/
		else if($type == 'nagari' || $type == 'operator' || $type == 'pemilik_tower' || $type == 'bts_all' || $type == 'detail_operator' || $type == 'detail_kecamatan')
		{
			$filters = "";
			if(get_magic_quotes_gpc())
			{
				$filters = stripslashes($_REQUEST['filters']);
			}
			else
			{
				$filters = $_REQUEST['filters'];
			}
			$filters = json_decode($filters,true);
			$andor = $filters[groupOp];

			for($i=0;$i<count($filters[rules]);$i++)
			{
				$fld = $filters[rules][$i][field];
				$data = mysql_real_escape_string($filters[rules][$i][data]);
				$foper = $filters[rules][$i][op];
				
				$sqloper_bef = "";
				$sqloper_aft = "";
				
				$tgl = array('i.tgl_izin_prinsip','i.tgl_izin_ho','i.tgl_hbs_izin_ho','i.tgl_imb','t.akhir_kontrak');
				switch($foper)
				{
					case "cn":
						$sqloper_bef = "LIKE '%";
						$sqloper_aft = "%'";
						break;
					case "eq":
						if(in_array($fld,$tgl))
						{
							$data = $myfunction->dateformatSlashDb($data);
						}
						$sqloper_bef = "= '";
						$sqloper_aft = "'";
						break;
					case "le":
						$data = $myfunction->dateformatSlashDb($data);
						$sqloper_bef = "<= '";
						$sqloper_aft = "'";
						break;
					case "ge":
						$data = $myfunction->dateformatSlashDb($data);
						$sqloper_bef = ">= '";
						$sqloper_aft = "'";
						break;
				}
				if($data != "")
				{
					if($where == "")
					{
						$where = " WHERE ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						if($type == 'bts_all' || $type == 'detail_operator' || $type == 'detail_kecamatan')
						{
							$_field = explode(".",$fld);
							if($_field[0]=="t" || $_field[0]=="i")
								$whereizin = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="p")
								$wherepemilik = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="n")
								$wherenagari = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="o")
								$whereoperator = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="c")
								$wherecatudaya = " WHERE ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						}
						if($type == 'nagari')
						{
							$where = " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						}
					}
					else
						$where .= " ".$andor." ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						if($type == 'bts_all' || $type == 'detail_operator' || $type == 'detail_kecamatan')
						{
							$_field = explode(".",$fld);
							if($_field[0]=="t" || $_field[0]=="i")
								$whereizin .= " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="p")
								$wherepemilik .= " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="n")
								$wherenagari .= " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="o")
								$whereoperator .= " AND ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
							else if($_field[0]=="c")
								$wherecatudaya = " WHERE ".$fld." ".$sqloper_bef.$data.$sqloper_aft;
						}
						
				}
				
			}
			
			// costruct where
			
		}
	}
	/* END SEARCH OPTION */
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());

	mysql_select_db($database) or die("Error conecting to db.");

	if($oper == "")
	{
		if($type == "kecamatan")
		{
			$sql = "SELECT COUNT(idkec) AS count FROM kecamatan ".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			/*$page = 1;
			$total_pages = 1;
			$limit = 0;*/
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "select idkec,kecamatan FROM kecamatan ".$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[idkec];
				$responce->rows[$i]['cell']=array($row[idkec],$j+1,$row[kecamatan]);
				$i++;
				$j++;
			}
			echo json_encode($responce);
		}
		else if($type == "nagari")
		{
			$sql = "SELECT COUNT(b.idnagari) AS count FROM kecamatan a inner join nagari b where a.idkec=b.idkec".$where;
			//echo $sql;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "select b.idnagari,a.kecamatan,b.nagari FROM kecamatan a inner join nagari b where a.idkec=b.idkec".$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			//echo $SQL;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[idnagari];
				$responce->rows[$i]['cell']=array($row[idnagari],$j+1,$row[kecamatan],$row[nagari]);
				$i++;
				$j++;
			}
			//print_r($responce);
			//exit();
			echo json_encode($responce);
		}
		else if($type == "operator")
		{
			$sql = "SELECT COUNT(*) AS count FROM operator".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "select idoperator,nama,alamat,perwakilan,alamat_perwakilan,contact_person,telp,pemilik,telp_pemilik FROM operator".$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start; 
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[idoperator];
				$responce->rows[$i]['cell']=array($row[idoperator],$j+1,$row[nama],$row[alamat],$row[perwakilan],$row[alamat_perwakilan],$row[contact_person],$row[telp],$row[pemilik],$row[telp_pemilik]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "pemilik_tower")
		{
			$sql = "SELECT COUNT(*) AS count FROM pemilik".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "select idpemilik,nama,alamat,perwakilan,alamat_perwakilan,contact_person,telp,pemilik,telp_pemilik FROM pemilik".$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start; 
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[idpemilik];
				$responce->rows[$i]['cell']=array($row[idpemilikr],$j+1,$row[nama],$row[alamat],$row[perwakilan],$row[alamat_perwakilan],$row[contact_person],$row[telp],$row[pemilik],$row[telp_pemilik]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "bts_all")
		{
			$sql = "SELECT COUNT(z.count) AS count FROM (SELECT COUNT(t.idtower) AS count  
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin".$whereizin." 
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$wherepemilik." 
				INNER JOIN nagari n ON t.idnagari=n.idnagari".$wherenagari."
				INNER JOIN kecamatan k ON n.idkec=k.idkec
				INNER JOIN opgabung og ON t.idtower=og.idtower
				INNER JOIN operator o ON og.idoperator=o.idoperator".$whereoperator."
				LEFT JOIN catudaya c ON t.idtower=c.idtower".$wherecatudaya."
				GROUP BY t.idtower) z";//.$where;
			//echo $sql;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "SELECT t.idtower,t.idizin,i.no_izin_prinsip,i.tgl_izin_prinsip,i.no_izin_ho,i.tgl_izin_ho,
				i.tgl_hbs_izin_ho,i.no_imb,i.tgl_imb,i.file_imb,t.lokasi,k.kecamatan,n.nagari,t.tinggi,t.elevasi,
				t.luas_tanah,t.rab_tower,t.koord_b,t.koord_l,p.nama,p.alamat,p.perwakilan,p.alamat_perwakilan,
				p.contact_person,p.telp,p.pemilik,p.telp_pemilik,
				GROUP_CONCAT(o.nama ORDER BY og.idopgabung SEPARATOR ', ') AS noperator,
				t.pemilik_tanah,t.status_tanah,t.akhir_kontrak,
				GROUP_CONCAT(DISTINCT c.catudaya ORDER BY c.kodecatudaya SEPARATOR ', ') AS catudaya 
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin".$whereizin."
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$wherepemilik." 
				INNER JOIN nagari n ON t.idnagari=n.idnagari".$wherenagari."
				INNER JOIN kecamatan k ON n.idkec=k.idkec
				INNER JOIN opgabung og ON t.idtower=og.idtower
				INNER JOIN operator o ON og.idoperator=o.idoperator".$whereoperator." 
				LEFT JOIN catudaya c ON t.idtower=c.idtower".$wherecatudaya." 
				GROUP BY t.idtower order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			//echo $SQL; 
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start; 
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[idtower];
				$elevasi = $row[elevasi];
				if($elevasi==0)
					$elevasi="";
				$luas_tanah = $row[luas_tanah];
				if($luas_tanah==0)
					$luas_tanah="";
				$rab_tower = $row[rab_tower];
				if($rab_tower==0)
					$rab_tower="";
				$status_tanah = $row[status_tanah];
				if($status_tanah==0)
					$status_tanah="Milik Sendiri";
				else
					$status_tanah="Sewa";
				/*$lama_sewa = explode(";",$row[lama_sewa]);
				$_lama_sewa = $lama_sewa[0]." Tahun";
				if(!($lama_sewa[1]=="" || $lama_sewa[1]==0))
					$_lama_sewa .= " ".$lama_sewa[1]." Bulan";*/
				$koord_b = explode(";",calculateDecToDMS($row[koord_b],"bujur"));
				$_koord_b = $koord_b[0]." ".$koord_b[1]." ".$koord_b[2]." ".$koord_b[3];
				$koord_l = explode(";",calculateDecToDMS($row[koord_l],"lintang"));
				$_koord_l = $koord_l[0]." ".$koord_l[1]." ".$koord_l[2]." ".$koord_l[3];

				/*$operator_gabung = "";
				if($row[idoperator] != "")
				{
					$idoperator = explode(",",$row[idoperator]);
					if(count($idoperator)>0)
					{
						for($j=0;$j<count($idoperator);$j++)
						{
							$idop = $idoperator[$j];
							if($idop != "")
							{
							$sqlop = "SELECT nama FROM operator WHERE idoperator = ".$idop;
							$resultop = mysql_query($sqlop) or die("Could not execute query.".mysql_error());
							$rowop = mysql_fetch_array($resultop,MYSQL_ASSOC);
							$operator_gabung .= " ".$rowop['nama'].",";
							}
						}
						$operator_gabung = substr(trim($operator_gabung),0,-1);
					}
					else
					{
						$sqlop = "SELECT nama FROM operator WHERE idoperator = ".$row[idoperator];
						//echo "sqlop:".$sqlop;
						$resultop = mysql_query($sqlop) or die("Could not execute query.".mysql_error());
						$rowop = mysql_fetch_array($resultop,MYSQL_ASSOC);
						$operator_gabung = $rowop['nama'];
					}
				}*/
				$responce->rows[$i]['cell']=array($row[idtower],$row[idizin],$j+1,$row[no_izin_prinsip],$row[tgl_izin_prinsip],$row[no_izin_ho],$row[tgl_izin_ho],$row[tgl_hbs_izin_ho],$row[no_imb],$row[tgl_imb],$row[file_imb],$row[lokasi],$row[kecamatan],$row[nagari],$row[tinggi],$elevasi,$luas_tanah,$rab_tower,$_koord_b,$_koord_l,$row[catudaya],$row[nama],$row[alamat],$row[perwakilan],$row[alamat_perwakilan],$row[contact_person],$row[telp],$row[pemilik],$row[telp_pemilik],$row[noperator],$row[pemilik_tanah],$status_tanah,$row[akhir_kontrak]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "detail_operator")
		{
			$idoperator = $_REQUEST["idoperator"];
			//$where = "";
			if($idoperator != 0)
				$whereoperator .= " AND o.idoperator = ".$idoperator;
			$sql = "SELECT COUNT(z.count) AS count FROM (SELECT COUNT(t.idtower) AS count  
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin".$whereizin."
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$wherepemilik." 
				INNER JOIN nagari n ON t.idnagari=n.idnagari".$wherenagari."
				INNER JOIN kecamatan k ON n.idkec=k.idkec
				INNER JOIN opgabung og ON t.idtower=og.idtower
				INNER JOIN operator o ON og.idoperator=o.idoperator".$whereoperator."
				GROUP BY t.idtower) z";//.$where;
			//echo $sql;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "SELECT t.idtower,i.no_izin_prinsip,i.tgl_izin_prinsip,i.tgl_hbs_izin_prinsip,
				i.no_izin_ho,i.tgl_izin_ho,i.tgl_hbs_izin_ho,i.no_imb,i.tgl_imb,i.tgl_hbs_imb,
				t.lokasi,k.kecamatan,n.nagari,t.tinggi,t.elevasi,t.koord_b,t.koord_l,
				p.nama,t.pemilik_tower  
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin".$whereizin."
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$wherepemilik." 
				INNER JOIN nagari n ON t.idnagari=n.idnagari".$wherenagari."
				INNER JOIN kecamatan k ON n.idkec=k.idkec
				INNER JOIN opgabung og ON t.idtower=og.idtower
				INNER JOIN operator o ON og.idoperator=o.idoperator".$whereoperator."
				GROUP BY t.idtower order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			//echo $SQL;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start; 
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$izin = "";
				$izinprinsip = $row[no_izin_prinsip];
				if($izinprinsip != "")
				{
					$tglizinprinsip = $myfunction->dateformatLong($row[tgl_izin_prinsip]);
					$izin = $izinprinsip."<br>".$tglizinprinsip;
				}
				else
				{
					$izinho = $row[no_izin_ho];
					if($izinho != "")
					{
						$tglizinho = $myfunction->dateformatLong($row[tgl_izin_ho]);
						$izin = $izinho."<br>".$tglizinho;
					}
					else
					{
						$imb = $row[no_imb];
						if($imb != "")
						{
							$tglimb = $myfunction->dateformatLong($row[tgl_imb]);
							$izin = $imb."<br>".$tglimb;
						}
					}
				}
				
				$lok_nag_kec = "";
				$lok = $row[lokasi];
				if($lok != "")
					$lok_nag_kec .= $lok."<br>";
				$nag = $row[nagari];
				if($nag != "")
					$lok_nag_kec .= "Nag.".$nag;
				$kec = $row[kecamatan];
				if($kec != "")
					$lok_nag_kec .= ", Kec.".$kec;

				$tg_ele = "";
				$tg = $row[tinggi];
				if($tg != "")
					$tg_ele .= $tg." M<br>";
				$ele = $row[elevasi];
				if($ele != "")
					if($ele != 0)
						$tg_ele .= $ele." M DPL";
					
				$b_l = "";
				$b = $row[koord_b];
				if($b != "")
				{
					$koord_b = explode(";",calculateDecToDMS($b,"bujur"));
					$b_l .= $koord_b[0]." ".$koord_b[1]." ".$koord_b[2]." ".$koord_b[3]."<br>";
				}
				$l = $row[koord_l];
				if($l != "")
				{
					$koord_l = explode(";",calculateDecToDMS($l,"lintang"));
					$b_l .= $koord_l[0]." ".$koord_l[1]." ".$koord_l[2]." ".$koord_l[3];
				}
				$responce->rows[$i]['id']=$row[idtower];
				$responce->rows[$i]['cell']=array($row[idtower],$j+1,$izin,$lok_nag_kec,$tg_ele,$b_l,$row[nama]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "detail_pemilik")
		{
			$idpemilik = $_REQUEST["idpemilik"];
			$where = "";
			if($idpemilik != 0)
				$where = " WHERE t.idpemilik = ".$idpemilik;
			$sql = "SELECT COUNT(t.idtower) AS count  
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin 
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$where;
			//echo $sql;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "SELECT t.idtower,i.no_izin_prinsip,i.tgl_izin_prinsip,i.tgl_hbs_izin_prinsip,
				i.no_izin_ho,i.tgl_izin_ho,i.tgl_hbs_izin_ho,i.no_imb,i.tgl_imb,i.tgl_hbs_imb,
				t.lokasi,k.kecamatan,n.nagari,t.tinggi,t.elevasi,t.koord_b,t.koord_l,
				p.nama,t.pemilik_tower  
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin 
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik  
				INNER JOIN nagari n ON t.idnagari=n.idnagari 
				INNER JOIN kecamatan k ON n.idkec=k.idkec".
				$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			//echo $SQL;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start; 
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$izin = "";
				$izinprinsip = $row[no_izin_prinsip];
				if($izinprinsip != "")
				{
					$tglizinprinsip = $myfunction->dateformatLong($row[tgl_izin_prinsip]);
					$izin = $izinprinsip."<br>".$tglizinprinsip;
				}
				else
				{
					$izinho = $row[no_izin_ho];
					if($izinho != "")
					{
						$tglizinho = $myfunction->dateformatLong($row[tgl_izin_ho]);
						$izin = $izinho."<br>".$tglizinho;
					}
					else
					{
						$imb = $row[no_imb];
						if($imb != "")
						{
							$tglimb = $myfunction->dateformatLong($row[tgl_imb]);
							$izin = $imb."<br>".$tglimb;
						}
					}
				}
				
				$lok_nag_kec = "";
				$lok = $row[lokasi];
				if($lok != "")
					$lok_nag_kec .= $lok."<br>";
				$nag = $row[nagari];
				if($nag != "")
					$lok_nag_kec .= "Nag.".$nag;
				$kec = $row[kecamatan];
				if($kec != "")
					$lok_nag_kec .= ", Kec.".$kec;

				$tg_ele = "";
				$tg = $row[tinggi];
				if($tg != "")
					$tg_ele .= $tg." M<br>";
				$ele = $row[elevasi];
				if($ele != "")
					if($ele != 0)
						$tg_ele .= $ele." M DPL";
					
				$b_l = "";
				$b = $row[koord_b];
				if($b != "")
				{
					$koord_b = explode(";",calculateDecToDMS($b,"bujur"));
					$b_l .= $koord_b[0]." ".$koord_b[1]." ".$koord_b[2]." ".$koord_b[3]."<br>";
				}
				$l = $row[koord_l];
				if($l != "")
				{
					$koord_l = explode(";",calculateDecToDMS($l,"lintang"));
					$b_l .= $koord_l[0]." ".$koord_l[1]." ".$koord_l[2]." ".$koord_l[3];
				}
				$responce->rows[$i]['id']=$row[idtower];
				$responce->rows[$i]['cell']=array($row[idtower],$j+1,$izin,$lok_nag_kec,$tg_ele,$b_l,$row[nama]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "detail_kecamatan")
		{
			$idkec = $_REQUEST["idkec"];
			$where = "";
			if($idkec != 0)
				$where = " WHERE t.idkec=".$idkec;
			$sql = "SELECT COUNT(t.idkec) AS count  
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin".$whereizin."
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$wherepemilik." 
				INNER JOIN nagari n ON t.idnagari=n.idnagari".$wherenagari."
				INNER JOIN kecamatan k ON n.idkec=k.idkec".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "SELECT t.idtower,i.no_izin_prinsip,i.tgl_izin_prinsip,i.tgl_hbs_izin_prinsip,
				i.no_izin_ho,i.tgl_izin_ho,i.tgl_hbs_izin_ho,i.no_imb,i.tgl_imb,i.tgl_hbs_imb,
				t.lokasi,k.kecamatan,n.nagari,t.tinggi,t.elevasi,t.koord_b,t.koord_l,
				p.nama,t.pemilik_tower  
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin".$whereizin."
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$wherepemilik." 
				INNER JOIN nagari n ON t.idnagari=n.idnagari".$wherenagari."
				INNER JOIN kecamatan k ON n.idkec=k.idkec".$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			//echo $SQL;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0;
			$j=$start;			
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$izin = "";
				$izinprinsip = $row[no_izin_prinsip];
				if($izinprinsip != "")
				{
					$tglizinprinsip = $myfunction->dateformatLong($row[tgl_izin_prinsip]);
					$izin = $izinprinsip."<br>".$tglizinprinsip;
				}
				else
				{
					$izinho = $row[no_izin_ho];
					if($izinho != "")
					{
						$tglizinho = $myfunction->dateformatLong($row[tgl_izin_ho]);
						$izin = $izinho."<br>".$tglizinho;
					}
					else
					{
						$imb = $row[no_imb];
						if($imb != "")
						{
							$tglimb = $myfunction->dateformatLong($row[tgl_imb]);
							$izin = $imb."<br>".$tglimb;
						}
					}
				}
				
				$lok_nag_kec = "";
				$lok = $row[lokasi];
				if($lok != "")
					$lok_nag_kec .= $lok."<br>";
				$nag = $row[nagari];
				if($nag != "")
					$lok_nag_kec .= "Nag.".$nag;
				$kec = $row[kecamatan];
				if($kec != "")
					$lok_nag_kec .= ", Kec.".$kec;

				$tg_ele = "";
				$tg = $row[tinggi];
				if($tg != "")
					$tg_ele .= $tg." M<br>";
				$ele = $row[elevasi];
				if($ele != "")
					if($ele != 0)
						$tg_ele .= $ele." M DPL";
					
				$b_l = "";
				$b = $row[koord_b];
				if($b != "")
				{
					$koord_b = explode(";",calculateDecToDMS($b,"bujur"));
					$b_l .= $koord_b[0]." ".$koord_b[1]." ".$koord_b[2]." ".$koord_b[3]."<br>";
				}
				$l = $row[koord_l];
				if($l != "")
				{
					$koord_l = explode(";",calculateDecToDMS($l,"lintang"));
					$b_l .= $koord_l[0]." ".$koord_l[1]." ".$koord_l[2]." ".$koord_l[3];
				}
				$responce->rows[$i]['id']=$row[idtower];
				$responce->rows[$i]['cell']=array($row[idtower],$j+1,$izin,$lok_nag_kec,$tg_ele,$b_l,$row[nama]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "rekap_operator")
		{
			//$idoperator = $_REQUEST["idoperator"];
			$where = "";
			/*if($idoperator != 0)
				$where = " WHERE t.idoperator=".$idoperator;*/
			$sql = "SELECT COUNT(a.idoperator) as count FROM (SELECT o.idoperator,o.nama,IFNULL(t.cnt,0) AS cnt 
				FROM operator o
				LEFT JOIN
				(SELECT idoperator,COUNT(idoperator) AS cnt FROM opgabung 
				GROUP BY idoperator) t ON o.idoperator=t.idoperator) a";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "SELECT o.idoperator,o.nama,IFNULL(t.cnt,0) AS cnt FROM operator o
				LEFT JOIN
				(SELECT idoperator,COUNT(idoperator) AS cnt FROM opgabung 
				GROUP BY idoperator) t ON o.idoperator=t.idoperator LIMIT ".$start." , ".$limit;
			//echo $SQL;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			//$rows = mysql_fetch_row($result,MYSQL_ASSOC);
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			//$responce->records = count($rows);
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[idoperator];
				$responce->rows[$i]['cell']=array($row[idoperator],$j+1,$row[nama],$row[cnt]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "rekap_pemilik")
		{
			$sql = "SELECT COUNT(a.idpemilik) as count FROM (SELECT p.idpemilik,p.nama,IFNULL(t.cnt,0) AS cnt 
				FROM pemilik p
				LEFT JOIN
				(SELECT idpemilik,COUNT(idpemilik) AS cnt FROM tower 
				GROUP BY idpemilik) t ON p.idpemilik=t.idpemilik) a";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "SELECT p.idpemilik,p.nama,IFNULL(t.cnt,0) AS cnt FROM pemilik p
				LEFT JOIN
				(SELECT idpemilik,COUNT(idpemilik) AS cnt FROM tower 
				GROUP BY idpemilik) t ON p.idpemilik=t.idpemilik LIMIT ".$start." , ".$limit;
			//echo $SQL;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			//$rows = mysql_fetch_row($result,MYSQL_ASSOC);
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			//$responce->records = count($rows);
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[idpemilik];
				$responce->rows[$i]['cell']=array($row[idpemilik],$j+1,$row[nama],$row[cnt]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "rekap_kecamatan")
		{
			$sql = "SELECT COUNT(a.idkec) as count FROM (SELECT k.idkec,k.kecamatan,IFNULL(t.cnt,0) AS cnt 
				FROM kecamatan k
				LEFT JOIN
				(SELECT idkec,COUNT(idkec) AS cnt FROM tower 
				GROUP BY idkec) t ON k.idkec=t.idkec) a";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "SELECT k.idkec,k.kecamatan,IFNULL(t.cnt,0) AS cnt FROM kecamatan k
				LEFT JOIN
				(SELECT idkec,COUNT(idkec) AS cnt FROM tower 
				GROUP BY idkec) t ON k.idkec=t.idkec LIMIT ".$start." , ".$limit;
			//echo $SQL;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			//$rows = mysql_fetch_row($result,MYSQL_ASSOC);
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			//$responce->records = count($rows);
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[idkec];
				$responce->rows[$i]['cell']=array($row[idkec],$j+1,$row[kecamatan],$row[cnt]);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "expired")
		{
			$idex = $_REQUEST["idex"];
			$where = "";
			$now = date("Y-m-d");
			$thismonth = date("Y-m");
			$thisyear = date("Y");
			
			switch($idex)
			{
				case 0:	//izin ho yg sdh habis berlaku
					$where = " WHERE i.tgl_hbs_izin_ho <= '".$now."'";
					break;
				case 1:	//izin ho habis bulan ini
					$where = " WHERE substr(i.tgl_hbs_izin_ho,1,7) = '".$thismonth."'";
					break;
				case 2:	//izin ho habis tahun ini
					$where = " WHERE substr(i.tgl_hbs_izin_ho,1,4) = '".$thisyear."'";
					break;
				case 3:	//izin ho masih berlaku
					$where = " WHERE i.tgl_hbs_izin_ho >= '".$now."'";
					break;
				case 4:	//kontrak tanah sdh habis berlaku
					$where = " WHERE t.akhir_kontrak <= '".$now."'";
					break;
			}
			if($idkec != 0)
				$where = " WHERE t.idkec=".$idkec;
			$sql = "SELECT COUNT(t.idkec) AS count  
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin".$whereizin."
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$wherepemilik." 
				INNER JOIN nagari n ON t.idnagari=n.idnagari".$wherenagari."
				INNER JOIN kecamatan k ON n.idkec=k.idkec".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) $start = 0;
			$SQL = "SELECT t.idtower,i.no_izin_prinsip,i.tgl_izin_prinsip,i.tgl_hbs_izin_prinsip,
				i.no_izin_ho,i.tgl_izin_ho,i.tgl_hbs_izin_ho,i.no_imb,i.tgl_imb,i.tgl_hbs_imb,
				t.lokasi,k.kecamatan,n.nagari,t.tinggi,t.elevasi,t.koord_b,t.koord_l,
				p.nama,t.pemilik_tower,t.akhir_kontrak   
				FROM tower t INNER JOIN izin i ON t.idizin=i.idizin".$whereizin."
				INNER JOIN pemilik p ON t.idpemilik=p.idpemilik".$wherepemilik." 
				INNER JOIN nagari n ON t.idnagari=n.idnagari".$wherenagari."
				INNER JOIN opgabung og ON t.idtower=og.idtower
				INNER JOIN operator o ON og.idoperator=o.idoperator".$whereoperator."
				INNER JOIN kecamatan k ON n.idkec=k.idkec".$where." order by ".$sidx." ".$sord." LIMIT ".$start." , ".$limit;
			//echo $SQL;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$izin = "";
				$izinprinsip = $row[no_izin_prinsip];
				if($izinprinsip != "")
				{
					$tglizinprinsip = $myfunction->dateformatLong($row[tgl_izin_prinsip]);
					$izin = $izinprinsip."<br>".$tglizinprinsip;
				}
				else
				{
					$izinho = $row[no_izin_ho];
					if($izinho != "")
					{
						$tglizinho = $myfunction->dateformatLong($row[tgl_izin_ho]);
						$izin = $izinho."<br>".$tglizinho;
					}
					else
					{
						$imb = $row[no_imb];
						if($imb != "")
						{
							$tglimb = $myfunction->dateformatLong($row[tgl_imb]);
							$izin = $imb."<br>".$tglimb;
						}
					}
				}
				
				$lok_nag_kec = "";
				$lok = $row[lokasi];
				if($lok != "")
					$lok_nag_kec .= $lok."<br>";
				$nag = $row[nagari];
				if($nag != "")
					$lok_nag_kec .= "Nag.".$nag;
				$kec = $row[kecamatan];
				if($kec != "")
					$lok_nag_kec .= ", Kec.".$kec;

				$tg_ele = "";
				$tg = $row[tinggi];
				if($tg != "")
					$tg_ele .= $tg." M<br>";
				$ele = $row[elevasi];
				if($ele != "")
					if($ele != 0)
						$tg_ele .= $ele." M DPL";
					
				$b_l = "";
				$b = $row[koord_b];
				if($b != "")
				{
					$koord_b = explode(";",calculateDecToDMS($b,"bujur"));
					$b_l .= $koord_b[0]." ".$koord_b[1]." ".$koord_b[2]." ".$koord_b[3]."<br>";
				}
				$l = $row[koord_l];
				if($l != "")
				{
					$koord_l = explode(";",calculateDecToDMS($l,"lintang"));
					$b_l .= $koord_l[0]." ".$koord_l[1]." ".$koord_l[2]." ".$koord_l[3];
				}
				
				/*$tgl_akhir_kontrak = $myfunction->dateformatLong($row[akhir_kontrak]);
				if($row[akhir_kontrak] == "" || $row[akhir_kontrak] == "0000-0-00")
					$tgl_akhir_kontrak = "";*/
				$tgl_habis_izin_ho = $myfunction->dateformatLong($row[tgl_hbs_izin_ho]);
				if($tgl_habis_izin_ho == "0000-0-00")
					$tgl_habis_izin_ho = "";
				$responce->rows[$i]['id']=$row[idtower];
				$responce->rows[$i]['cell']=array($row[idtower],$j+1,$izin,$lok_nag_kec,$tg_ele,$b_l,$row[nama],$tgl_habis_izin_ho);
				$i++;
				$j++;
			}

			echo json_encode($responce);
		}
		else if($type == "user")
		{
			$sql = "SELECT COUNT(iduser) AS count FROM user".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "select a.iduser,a.nama_lengkap,a.user,b.userlevel,IF(a.status>0,'Aktif','Tidak Aktif') AS status FROM user a inner join userlevel b on a.iduserlevel=b.iduserlevel order by a.iduser LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0;
			$j=$start;			
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[iduser];
				$responce->rows[$i]['cell']=array($row[iduser],$j+1,'',$row[nama_lengkap],$row[user],'','',$row[userlevel],$row[status]);
				$i++;
				$j++;
			}
			//print_r($responce);
			//exit();
			echo json_encode($responce);
		}
		else if($type == "userlevel")
		{
			$sql = "SELECT COUNT(iduserlevel) AS count FROM userlevel";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "select iduserlevel,userlevel FROM userlevel LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[iduserlevel];
				$responce->rows[$i]['cell']=array($row[iduserlevel],$j+1,$row[userlevel]);
				$i++;
				$j++;
			}
			//print_r($responce);
			//exit();
			echo json_encode($responce);
		}
		else if($type == "useraccess")
		{
			$sql = "SELECT COUNT(iduseraccess) AS count FROM useraccess".$where;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			
			if($count==0)	//useraccess is not exist, create a new one
			{
				$sqluseraccess = "INSERT INTO useraccess (iduseraccess,iduserlevel,adddata,editdata,deldata) VALUES (1,1,1,1,1)";
				if (!mysql_query($sqluseraccess, $db)) {
					echo mysql_errno($db) . "1: " . mysql_error($db) . "\n";
				}
			}
			
			if( $count >0 ) 
			{
				$total_pages = ceil($count/$limit);
			} 
			else 
			{
				$total_pages = 0;
			}
			
			if ($page > $total_pages) 
				$page=$total_pages;
			$start = $limit*$page - $limit; // do not put $limit*($page - 1)
			if ($start<0) 
				$start = 0;
			$SQL = "select a.iduseraccess,a.iduserlevel,b.userlevel,IF(a.adddata>0,'Bisa','Tidak Bisa') AS adddata,IF(a.editdata>0,'Bisa','Tidak Bisa') AS editdata,IF(a.deldata>0,'Bisa','Tidak Bisa') AS deldata FROM useraccess a inner join userlevel b on a.iduserlevel=b.iduserlevel order by a.iduseraccess LIMIT ".$start." , ".$limit;
			$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
			$responce->page = $page;
			$responce->total = $total_pages;
			$responce->records = $count;
			$i=0; 
			$j=$start;
			while($row = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				$responce->rows[$i]['id']=$row[iduseraccess];
				$responce->rows[$i]['cell']=array($row[iduseraccess],$row[iduserlevel],$j+1,$row[userlevel],$row[adddata],$row[editdata],$row[deldata]);
				$i++;
				$j++;
			}
			//print_r($responce);
			//exit();
			echo json_encode($responce);
		}
	}
	else if($oper == "edit")
	{
		if($type == "kecamatan")
		{
			$kec = $_REQUEST['kecamatan'];
			$idkec = $_REQUEST['id'];
			$sql = "SELECT COUNT(idkec) AS count FROM kecamatan WHERE idkec != ".$idkec." AND kecamatan = '".$kec."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "UPDATE kecamatan SET kecamatan = '".mysql_real_escape_string($kec)."' where idkec = ".$idkec;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Kecamatan '".$kec."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "nagari")
		{
			$nagari = $_REQUEST['nagari'];
			$idnagari = $_REQUEST['id'];
			$sql = "SELECT COUNT(idnagari) AS count FROM nagari WHERE idnagari != ".$idnagari." AND nagari = '".$nagari."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "UPDATE nagari SET nagari = '".mysql_real_escape_string($nagari)."' where idnagari = ".$idnagari;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Nagari '".$nagari."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "operator")
		{
			$nama = mysql_real_escape_string($_REQUEST['nama']);
			$alamat = mysql_real_escape_string($_REQUEST['alamat']);
			$perwakilan = mysql_real_escape_string($_REQUEST['perwakilan']);
			$alamat_perwakilan = mysql_real_escape_string($_REQUEST['alamat_perwakilan']);
			$contact_person = mysql_real_escape_string($_REQUEST['contact_person']);
			$telp = mysql_real_escape_string($_REQUEST['telp']);
			$pemilik = mysql_real_escape_string($_REQUEST['pemilik']);
			$telp_pemilik = mysql_real_escape_string($_REQUEST['telp_pemilik']);
			$idoperator = $_REQUEST['id'];
			
			$sql = "SELECT COUNT(idoperator) AS count FROM operator WHERE idoperator != ".$idoperator." AND nama = '".$nama."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "UPDATE operator SET nama = '".$nama."',alamat = '".$alamat."',perwakilan = '".$perwakilan."',alamat_perwakilan = '".$alamat_perwakilan."',contact_person = '".$contact_person."',telp = '".$telp."',pemilik='".$pemilik."',telp_pemilik='".$telp_pemilik."' where idoperator = ".$idoperator;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Operator '".$nama."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "pemilik_tower")
		{
			$nama = mysql_real_escape_string($_REQUEST['nama']);
			$alamat = mysql_real_escape_string($_REQUEST['alamat']);
			$perwakilan = mysql_real_escape_string($_REQUEST['perwakilan']);
			$alamat_perwakilan = mysql_real_escape_string($_REQUEST['alamat_perwakilan']);
			$contact_person = mysql_real_escape_string($_REQUEST['contact_person']);
			$telp = mysql_real_escape_string($_REQUEST['telp']);
			$pemilik = mysql_real_escape_string($_REQUEST['pemilik']);
			$telp_pemilik = mysql_real_escape_string($_REQUEST['telp_pemilik']);
			$idpemilik = $_REQUEST['id'];
			
			$sql = "SELECT COUNT(idpemilik) AS count FROM pemilik WHERE idpemilik != ".$idpemilik." AND nama = '".$nama."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "UPDATE pemilik SET nama = '".$nama."',alamat = '".$alamat."',perwakilan = '".$perwakilan."',alamat_perwakilan = '".$alamat_perwakilan."',contact_person = '".$contact_person."',telp = '".$telp."',pemilik='".$pemilik."',telp_pemilik='".$telp_pemilik."' where idpemilik = ".$idpemilik;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Pemilik Tower '".$nama."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "user")
		{
			$iduser = mysql_real_escape_string($_REQUEST['id']);
			$nama_lengkap = mysql_real_escape_string($_REQUEST['nama_lengkap']);
			$user = mysql_real_escape_string($_REQUEST['user']);
			$password = mysql_real_escape_string($_REQUEST['password']);
			$password2 = mysql_real_escape_string($_REQUEST['password2']);
			$userlevel = mysql_real_escape_string($_REQUEST['userlevel']);
			$status = mysql_real_escape_string($_REQUEST['status']);
			$changepass = mysql_real_escape_string($_REQUEST['changepass']);

			$err_admin = "";
			if($iduser==1)	//Administrator
			{
				$sql = "SELECT nama_lengkap,user,iduserlevel,status FROM user WHERE iduser = ".$iduser;
				$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				$n_lengkap = $row[nama_lengkap];
				$n_user = $row[user];
				$n_level = $row[iduserlevel];
				$n_status = $row[status];
				if($n_user != $user)
					$err_admin .= "Pengguna 'admin' Tidak Bisa di Ubah.";
				if($n_level != $userlevel)
					$err_admin .= " Tingkatan Administrator Tidak Bisa di Ubah.";
				if($n_status != $status)
					$err_admin .= " Status Administrator Tidak Bisa di Non Aktifkan.";
			}

			if($err_admin != "")
				echo $err_admin;
			else
			{
				if($changepass=="on")
				{
					if($password=="" || $password2=="")
						echo "Password Lama dan Password Baru Belum Di Isi";
					else
					{
						$sql = "SELECT COUNT(iduser) AS count FROM user WHERE iduser = ".$iduser." and password='".md5($password)."'";
						$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
						$count = $row['count'];
						if($count == 0)
							echo "Password Lama Tidak Benar";
						else
						{
							$SQL = "UPDATE user SET nama_lengkap='".$nama_lengkap."',user = '".$user."',password='".md5($password2)."',iduserlevel=".$userlevel.",status=".$status." WHERE iduser = ".$iduser;
							//echo $SQL;
							if (!mysql_query($SQL, $db)) {
								echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
							}
						}
					}
				}
				else
				{
					$SQL = "UPDATE user SET nama_lengkap='".$nama_lengkap."',user = '".$user."',iduserlevel=".$userlevel.",status=".$status." WHERE iduser = ".$iduser;
					if (!mysql_query($SQL, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
				}
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "userlevel")
		{
			$userlevel = mysql_real_escape_string($_REQUEST['userlevel']);
			$iduserlevel = mysql_real_escape_string($_REQUEST['id']);
			if($iduserlevel==1)	//Administrator
			{
				echo "Tingkatan Ini Tidak Bisa di Edit";
			}
			else
			{
				$sql = "SELECT COUNT(iduserlevel) AS count FROM userlevel WHERE iduserlevel != ".$iduserlevel." AND userlevel = '".$userlevel."'";
				$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				$count = $row['count'];
				if($count == 0)
				{
					$SQL = "UPDATE userlevel SET userlevel = '".$userlevel."' WHERE iduserlevel = ".$iduserlevel;
					if (!mysql_query($SQL, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
				}
				else
				{
					echo "Nama Tingkatan '".$userlevel."' Sudah Ada di Database";
				}
			}
			//mysql_query($SQL) or echo "Could not execute query.".mysql_error());
		}
		else if($type == "useraccess")
		{
			$iduseraccess = mysql_real_escape_string($_REQUEST['id']);
			$iduserlevel = mysql_real_escape_string($_REQUEST['iduserlevel']);
			$adddata = mysql_real_escape_string($_REQUEST['adddata']);
			$editdata = mysql_real_escape_string($_REQUEST['editdata']);
			$deldata = mysql_real_escape_string($_REQUEST['deldata']);

			$SQL = "UPDATE useraccess SET adddata = ".$adddata.",editdata = ".$editdata.",deldata = ".$deldata." WHERE iduseraccess = ".$iduseraccess;
			if (!mysql_query($SQL, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
		}
	}
	else if($oper == "add")
	{
		if($type == "kecamatan")
		{
			$kec = $_REQUEST['kecamatan'];
			$sql = "SELECT COUNT(idkec) AS count FROM kecamatan WHERE kecamatan = '".$kec."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO kecamatan (kecamatan) VALUES ('".mysql_real_escape_string($kec)."')";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Kecamatan '".$kec."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
		else if($type == "nagari")
		{
			$idkec = $_REQUEST['kecamatan'];
			$nagari = $_REQUEST['nagari'];
			$sql = "SELECT COUNT(idnagari) AS count FROM nagari WHERE nagari = '".$nagari."' AND idkec = ".$kec;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO nagari (nagari,idkec) VALUES ('".mysql_real_escape_string($nagari)."',".$idkec.")";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Nagari '".$nagari."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
		else if($type == "operator")
		{
			$nama = mysql_real_escape_string($_REQUEST['nama']);
			$alamat = mysql_real_escape_string($_REQUEST['alamat']);
			$perwakilan = mysql_real_escape_string($_REQUEST['perwakilan']);
			$alamat_perwakilan = mysql_real_escape_string($_REQUEST['alamat_perwakilan']);
			$contact_person = mysql_real_escape_string($_REQUEST['contact_person']);
			$telp = mysql_real_escape_string($_REQUEST['telp']);
			$pemilik = mysql_real_escape_string($_REQUEST['pemilik']);
			$telp_pemilik = mysql_real_escape_string($_REQUEST['telp_pemilik']);
			
			$sql = "SELECT COUNT(idoperator) AS count FROM operator WHERE nama = '".$nama."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO operator (nama,alamat,perwakilan,alamat_perwakilan,contact_person,telp,pemilik,telp_pemilik) VALUES ('".$nama."','".$alamat."','".$perwakilan."','".$alamat_perwakilan."','".$contact_person."','".$telp."','".$pemilik."','".$telp_pemilik."')";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Operator '".$nama."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
		else if($type == "pemilik_tower")
		{
			$nama = mysql_real_escape_string($_REQUEST['nama']);
			$alamat = mysql_real_escape_string($_REQUEST['alamat']);
			$perwakilan = mysql_real_escape_string($_REQUEST['perwakilan']);
			$alamat_perwakilan = mysql_real_escape_string($_REQUEST['alamat_perwakilan']);
			$contact_person = mysql_real_escape_string($_REQUEST['contact_person']);
			$telp = mysql_real_escape_string($_REQUEST['telp']);
			$pemilik = mysql_real_escape_string($_REQUEST['pemilik']);
			$telp_pemilik = mysql_real_escape_string($_REQUEST['telp_pemilik']);
			
			$sql = "SELECT COUNT(idpemilik) AS count FROM pemilik WHERE nama = '".$nama."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO pemilik (nama,alamat,perwakilan,alamat_perwakilan,contact_person,telp,pemilik,telp_pemilik) VALUES ('".$nama."','".$alamat."','".$perwakilan."','".$alamat_perwakilan."','".$contact_person."','".$telp."','".$pemilik."','".$telp_pemilik."')";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Pemilik Tower '".$nama."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
		else if($type == "user")
		{
			$nama_lengkap = mysql_real_escape_string($_REQUEST['nama_lengkap']);
			$user = mysql_real_escape_string($_REQUEST['user']);
			$password = mysql_real_escape_string($_REQUEST['password']);
			$password2 = mysql_real_escape_string($_REQUEST['password2']);
			$userlevel = mysql_real_escape_string($_REQUEST['userlevel']);
			$status = mysql_real_escape_string($_REQUEST['status']);

			$err_user = "";
			if($nama_lengkap == "")
				$err_user = "Nama Pengguna Belum di Isi";
			if($user == "")
				$err_user = "Pengguna Belum di Isi";
			if($password == "")
				$err_user = "Password Belum di Isi";
			if($password2 == "")
				$err_user = "Password (Lagi) Belum di Isi";
				
			if($err_user != "")
			{
				echo $err_user;
			}
			else
			{
				if($password != $password2)
				{
					echo "Password Tidak Sama";
				}
				else
				{
					$sql = "SELECT COUNT(iduser) AS count FROM user WHERE user = '".$user."'";
					$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
					$row = mysql_fetch_array($result,MYSQL_ASSOC);
					$count = $row['count'];
					if($count > 0)
					{
						echo "Pengguna '".$user."' Sudah Ada di Database";
					}
					else
					{
						$SQL = "INSERT INTO user (nama_lengkap,user,password,iduserlevel,status) VALUES ('".$nama_lengkap."','".$user."','".md5($password2)."',".$userlevel.",".$status.")";
						if (!mysql_query($SQL, $db)) {
							echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
						}
					}
				}
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
		else if($type == "userlevel")
		{
			$userlevel = mysql_real_escape_string($_REQUEST['userlevel']);
			$sql = "SELECT COUNT(iduserlevel) AS count FROM userlevel WHERE userlevel = '".$userlevel."'";
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "INSERT INTO userlevel (userlevel) VALUES ('".$userlevel."')";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				$iduserlevel = mysql_insert_id();
				//add user access
				$SQL = "INSERT INTO useraccess (iduserlevel,adddata,editdata,deldata) VALUES (".$iduserlevel.",0,0,0)";
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			else
			{
				echo "Nama Tingkatan '".$userlevel."' Sudah Ada di Database";
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
	}
	else if($oper == "del")
	{
		if($type == "kecamatan")
		{
			$idkec = $_REQUEST['id'];
			//check if there is a link with another table
			$sql = "SELECT COUNT(b.idkec) AS count FROM kecamatan a inner join nagari b on a.idkec=b.idkec where a.idkec=".$idkec;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "DELETE FROM kecamatan WHERE idkec = ".$idkec;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				//mysql_query($SQL) or die("Could not execute query.".mysql_error());
			}
			else
			{
				echo "Kecamatan Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Nagari";
			}
		}
		else if($type == "nagari")
		{
			$idnagari = $_REQUEST['id'];
			//check if there is a link with another table
			$sql = "SELECT COUNT(t.idnagari) AS count FROM tower t inner join nagari n on t.idnagari=n.idnagari where t.idnagari=".$idnagari;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "DELETE FROM nagari WHERE idnagari = ".$idnagari;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				//mysql_query($SQL) or die("Could not execute query.".mysql_error());
			}
			else
			{
				echo "Nagari Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Menara Telekomunikasi";
			}
		}
		else if($type == "operator")
		{
			$idoperator = $_REQUEST['id'];
			//check if there is a link with another table
			$sql = "SELECT COUNT(t.idoperator) AS count FROM tower t inner join operator o on t.idoperator=o.idoperator where t.idoperator=".$idoperator;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "DELETE FROM operator WHERE idoperator = ".$idoperator;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				//mysql_query($SQL) or die("Could not execute query.".mysql_error());
			}
			else
			{
				echo "Operator Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Menara Telekomunikasi";
			}
		}
		else if($type == "pemilik_tower")
		{
			$idpemilik = $_REQUEST['id'];
			//check if there is a link with another table
			$sql = "SELECT COUNT(t.idpemilik) AS count FROM tower t inner join pemilik p on t.idpemilik=p.idpemilik where t.idpemilik=".$idpemilik;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$count = $row['count'];
			if($count == 0)
			{
				$SQL = "DELETE FROM pemilik WHERE idpemilik = ".$idpemilik;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				//mysql_query($SQL) or die("Could not execute query.".mysql_error());
			}
			else
			{
				echo "Pemilik Tower Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Menara Telekomunikasi";
			}
		}
		else if($type == "bts_all")
		{
			$idtower = $_REQUEST['id'];
			//check if there is a link with another table
			//cari data izin
			$sql = "SELECT idizin FROM tower WHERE idtower=".$idtower;
			$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
			$row = mysql_fetch_array($result,MYSQL_ASSOC);
			$idizin = $row['idizin'];
			
			//hapus data izin
			$SQL = "DELETE FROM izin WHERE idizin = ".$idizin;
			if (!mysql_query($SQL, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
			//hapus data tower
			$SQL = "DELETE FROM tower WHERE idtower = ".$idtower;
			if (!mysql_query($SQL, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
			//mysql_query($SQL) or die("Could not execute query.".mysql_error());
		}
		else if($type == "user")
		{
			$iduser = $_REQUEST['id'];
			
			if($iduser==1)
			{
				echo "Pengguna Ini Tidak Bisa di Hapus";
			}
			else
			{
				$SQL = "DELETE FROM user WHERE iduser = ".$iduser;
				if (!mysql_query($SQL, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
		}
		else if($type == "userlevel")
		{
			$iduserlevel = $_REQUEST['id'];
			
			if($iduserlevel==1)
			{
				echo "Tingkatan Ini Tidak Bisa di Hapus";
			}
			else
			{
				$sql = "SELECT COUNT(a.iduser) AS count FROM user a INNER JOIN userlevel b on a.iduserlevel=b.iduserlevel WHERE b.iduserlevel=".$iduserlevel;
				$result = mysql_query($sql) or die("Could not execute query.".mysql_error());
				$row = mysql_fetch_array($result,MYSQL_ASSOC);
				$count = $row['count'];
				if($count == 0)
				{
					$SQL = "DELETE FROM userlevel WHERE iduserlevel = ".$iduserlevel;
					if (!mysql_query($SQL, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
					//mysql_query($SQL) or die("Could not execute query.".mysql_error());
				}
				else
				{
					echo "Tingkatan Ini Tidak Bisa di Hapus Karena Sudah Digunakan Di Data Pengguna";
				}
			}
		}
	}
?>