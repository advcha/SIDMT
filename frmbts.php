<?php
    /*session_start();
    $PageName = "regdaftarperdatapermohonan";
    $_SESSION['PageName'] = $PageName;
    require_once("include/mysql.class.php");
    include("checklogin.php");
    include("checkaccess.php");*/
	include("include/clsUtility.php");
	include("include/dbconfig.php");
    $myfunction = new MyFunction();
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");

    $idtower = $_REQUEST['id'];
    if(isset($_REQUEST['FormName']) && $_REQUEST['FormName']=="bts")
  	{
		$tinggi = 0;
		if($_POST['tinggi'] != "")
			$tinggi = $_POST['tinggi'];
		$elevasi = 0;
		if($_POST['elevasi'] != "")
			$elevasi = $_POST['elevasi'];
		$luas_tanah = 0;
		if($_POST['luas_tanah'] != "")
			$luas_tanah = $_POST['luas_tanah'];
		$rab_tower = 0;
		if($_POST['rab_tower'] != "")
			$rab_tower = str_replace(".","",$_POST['rab_tower']);
		/*$_lama_sewa_tahun = $_POST['lama_sewa_tahun'];
		$_lama_sewa_bulan = $_POST['lama_sewa_bulan'];
		if($_lama_sewa_tahun == "")
			$_lama_sewa_tahun = "0";
		if($_lama_sewa_bulan == "")
			$_lama_sewa_bulan = "0";
		$lama_sewa = $_lama_sewa_tahun.";".$_lama_sewa_bulan;*/

		$koord_b = 0;
		if($_POST['koord_b'] != "")
		{
			$koord_b = $_POST['koord_b'];
			if($_POST['selbujur']==0)	//DMS
				$koord_b = calculateDMSToDec($_POST['koord_b'],$_POST['koord_b_m'],$_POST['koord_b_s'],$_POST['bujur'],"bujur");
		}
		
		$koord_l = 0;
		if($_POST['koord_l'] != "")
		{
			$koord_l = $_POST['koord_l'];
			if($_POST['sellintang']==0)	//DMS
				$koord_l = calculateDMSToDec($_POST['koord_l'],$_POST['koord_l_m'],$_POST['koord_l_s'],$_POST['lintang'],"lintang");
		}

		$op_gabung = $_POST['hid_op_gabung'];
		if($op_gabung != "")
		{
			$op_gabung = ",".substr($op_gabung,0,-1);
			//$op_gabung = ",".$op_gabung;
		}		
		$operator_gabung = $_POST['seloperator'].$op_gabung;
		
		$njop_tanah = 0;
		if($_POST['njop_tanah'] != "")
			$njop_tanah = str_replace(".","",$_POST['njop_tanah']);

		$njop_menara = 0;
		if($_POST['njop_menara'] != "")
			$njop_menara = str_replace(".","",$_POST['njop_menara']);

		$njop_total = 0;
		if($_POST['njop_total'] != "")
			$njop_total = str_replace(".","",$_POST['njop_total']);

		$retribusi = 0;
		if($_POST['retribusi'] != "")
			$retribusi = str_replace(".","",$_POST['retribusi']);
			
		if($idtower!='')	//edit
  		{
			$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
			mysql_select_db($database) or die("Error conecting to db.");
			
			$idizin = $_POST['idizin'];
			if($idizin == "")
			{
				if($_POST['namafile'] != ""){
					if(copy('tmp/'.$_POST['namafile'], 'imb/'.$_POST['namafile']))
						unlink('tmp/'.$_POST['namafile']);
				}
				$sqlizin = "INSERT INTO izin (no_izin_prinsip,tgl_izin_prinsip,no_izin_ho,tgl_izin_ho,tgl_hbs_izin_ho,no_imb,tgl_imb,file_imb) VALUES ('".$_POST['no_izin_prinsip']."','".$myfunction->dateformatSlashDb($_POST['tgl_izin_prinsip'])."','".$_POST['no_izin_ho']."','".$myfunction->dateformatSlashDb($_POST['tgl_izin_ho'])."','".$myfunction->dateformatSlashDb($_POST['tgl_hbs_izin_ho'])."','".$_POST['no_imb']."','".$myfunction->dateformatSlashDb($_POST['tgl_imb'])."','".$_POST['namafile']."')";
				//echo "izin:".$sqlizin."<br>";
				if (!mysql_query($sqlizin, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				
				$idizin = mysql_insert_id();	//or use $sql = "SELECT LAST_INSERT_ID(idizin) FROM izin"
			}
			else
			{
				if($_POST['chkPerpanjang']!="")	//perpanjang izin
				{
					if($_POST['namafile'] != ""){
						if(copy('tmp/'.$_POST['namafile'], 'imb/'.$_POST['namafile']))
							unlink('tmp/'.$_POST['namafile']);
					}
					$sqlizin_new = "INSERT INTO izin (no_izin_prinsip,tgl_izin_prinsip,no_izin_ho,tgl_izin_ho,tgl_hbs_izin_ho,no_imb,tgl_imb,idizinlama,file_imb) VALUES ('".$_POST['no_izin_prinsip']."','".$myfunction->dateformatSlashDb($_POST['tgl_izin_prinsip'])."','".$_POST['no_izin_ho']."','".$myfunction->dateformatSlashDb($_POST['tgl_izin_ho'])."','".$myfunction->dateformatSlashDb($_POST['tgl_hbs_izin_ho'])."','".$_POST['no_imb']."','".$myfunction->dateformatSlashDb($_POST['tgl_imb'])."',".$idizin.",'".$_POST['namafile']."')";
					//echo "izin:".$sqlizin."<br>";
					if (!mysql_query($sqlizin_new, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
					
					$idizin_new = mysql_insert_id();
					$idizin = $idizin_new;
				}
				else
				{
					if($_POST['namafile'] != $_POST['namafileold'] && $_POST['namafileold'] != ""){
						unlink('imb/'.$_POST['namafileold']);
					}
					if($_POST['namafile'] != ""){
						if(copy('tmp/'.$_POST['namafile'], 'imb/'.$_POST['namafile']))
							unlink('tmp/'.$_POST['namafile']);
					}
					$sqlizin = "update izin set no_izin_prinsip='".$_POST['no_izin_prinsip']."',tgl_izin_prinsip='".$myfunction->dateformatSlashDb($_POST['tgl_izin_prinsip'])."',no_izin_ho='".$_POST['no_izin_ho']."',tgl_izin_ho='".$myfunction->dateformatSlashDb($_POST['tgl_izin_ho'])."',tgl_hbs_izin_ho='".$myfunction->dateformatSlashDb($_POST['tgl_hbs_izin_ho'])."',no_imb='".$_POST['no_imb']."',tgl_imb='".$myfunction->dateformatSlashDb($_POST['tgl_imb'])."',file_imb='".$_POST['namafile']."' where idizin=".$idizin;
					if (!mysql_query($sqlizin, $db)) {
						echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
					}
				}
			}
			
			$idnjop = $_POST['idnjop'];
			if($idnjop == "")
			{
				$sqlnjop = "INSERT INTO njop (njop_tanah,njop_menara,njop_total,retribusi) VALUES (".$njop_tanah.",".$njop_menara.",".$njop_total.",".$retribusi.")";
				
				if (!mysql_query($sqlnjop, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
				
				$idnjop = mysql_insert_id();	//or use $sql = "SELECT LAST_INSERT_ID(idizin) FROM izin"
			}
			else
			{
				$sqlnjop = "update njop set njop_tanah=".$njop_tanah.",njop_menara=".$njop_menara.",njop_total=".$njop_total.",retribusi=".$retribusi." where idnjop=".$idnjop;
				if (!mysql_query($sqlnjop, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			
  			$query = "update tower set idizin=".$idizin.",idnjop=".$idnjop.",lokasi='".$_POST['lokasi']."',lokasi_sppt_pbb='".$_POST['lokasi_sppt_pbb']."',kode_lokasi='".$_POST['kode_lokasi']."',idkec=".$_POST['selkecamatan'].",idnagari=".$_POST['selnagari'].",tinggi=".$tinggi.",elevasi=".$elevasi.",luas_tanah=".$luas_tanah.",rab_tower=".$rab_tower.",koord_b=".$koord_b.",koord_l=".$koord_l.",idpemilik=".$_POST['selpemilik'].",idoperator='".$operator_gabung."',pemilik_tanah='".$_POST['pemilik_tanah']."',status_tanah=".$_POST['status_tanah'].",akhir_kontrak='".$myfunction->dateformatSlashDb($_POST['tgl_akhir_kontrak'])."',type_lokasi=".$_POST['type_lokasi'].",jml_pengguna=".$_POST['jml_pengguna']." where idtower=".$idtower;
			//echo "query:".$query."<br>";
			$result = mysql_query($query) or die("Could not execute query.".mysql_error());
			
			$sqlcatudaya = "DELETE FROM catudaya WHERE idtower=".$idtower;
			if (!mysql_query($sqlcatudaya, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
			//echo $_POST['chkAki']."<br>".$_POST['chkGenset']."<br>".$_POST['chkSolar'];
			if($_POST['chkAki']=="on")
			{
				$sqlchk = "INSERT INTO catudaya (idtower,kodecatudaya,catudaya) VALUES (".$idtower.",0,'Aki')";
				if (!mysql_query($sqlchk, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if($_POST['chkGenset']=="on")
			{
				$sqlchk = "INSERT INTO catudaya (idtower,kodecatudaya,catudaya) VALUES (".$idtower.",1,'Genset')";
				if (!mysql_query($sqlchk, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if($_POST['chkSolar']=="on")
			{
				$sqlchk = "INSERT INTO catudaya (idtower,kodecatudaya,catudaya) VALUES (".$idtower.",2,'Panel Surya')";
				if (!mysql_query($sqlchk, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			
			$sqlopgabung = "DELETE FROM opgabung WHERE idtower=".$idtower;
			if (!mysql_query($sqlopgabung, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
  		}
  		else //add
		{
  			$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
			mysql_select_db($database) or die("Error conecting to db.");
	
			if($_POST['namafile'] != ""){
				if(copy('tmp/'.$_POST['namafile'], 'imb/'.$_POST['namafile']))
					unlink('tmp/'.$_POST['namafile']);
			}
			$sqlizin = "INSERT INTO izin (no_izin_prinsip,tgl_izin_prinsip,no_izin_ho,tgl_izin_ho,tgl_hbs_izin_ho,no_imb,tgl_imb,file_imb) VALUES ('".$_POST['no_izin_prinsip']."','".$myfunction->dateformatSlashDb($_POST['tgl_izin_prinsip'])."','".$_POST['no_izin_ho']."','".$myfunction->dateformatSlashDb($_POST['tgl_izin_ho'])."','".$myfunction->dateformatSlashDb($_POST['tgl_hbs_izin_ho'])."','".$_POST['no_imb']."','".$myfunction->dateformatSlashDb($_POST['tgl_imb'])."','".$_POST['namafile']."')";
			//echo "izin:".$sqlizin."<br>";
			if (!mysql_query($sqlizin, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
			
			$idizin = mysql_insert_id();	//or use $sql = "SELECT LAST_INSERT_ID(idizin) FROM izin"
			
			$sqlnjop = "INSERT INTO njop (njop_tanah,njop_menara,njop_total,retribusi) VALUES (".$njop_tanah.",".$njop_menara.",".$njop_total.",".$retribusi.")";
				
			if (!mysql_query($sqlnjop, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
			$idnjop = mysql_insert_id();
			
  			$query = "INSERT INTO tower(idizin,idnjop,lokasi,lokasi_sppt_pbb,kode_lokasi,idkec,idnagari,tinggi,elevasi,luas_tanah,rab_tower,koord_b,koord_l,idpemilik,idoperator,pemilik_tanah,status_tanah,akhir_kontrak,type_lokasi,jml_pengguna) VALUES(".$idizin.",".$idnjop.",'".$_POST['lokasi']."','".$_POST['lokasi_sppt_pbb']."','".$_POST['kode_lokasi']."',".$_POST['selkecamatan'].",".$_POST['selnagari'].",".$tinggi.",".$elevasi.",".$luas_tanah.",".$rab_tower.",".$koord_b.",".$koord_l.",".$_POST['selpemilik'].",'".$operator_gabung."','".$_POST['pemilik_tanah']."',".$_POST['status_tanah'].",'".$myfunction->dateformatSlashDb($_POST['tgl_akhir_kontrak'])."',".$_POST['type_lokasi'].",".$_POST['jml_pengguna'].")";
			//echo "query:".$query."<br>";
			$result = mysql_query($query) or die("Could not execute query.".mysql_error());
			$idtower = mysql_insert_id();
			
			if($_POST['chkAki']=="on")
			{
				$sqlchk = "INSERT INTO catudaya (idtower,kodecatudaya,catudaya) VALUES (".$idtower.",0,'Aki')";
				if (!mysql_query($sqlchk, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if($_POST['chkGenset']=="on")
			{
				$sqlchk = "INSERT INTO catudaya (idtower,kodecatudaya,catudaya) VALUES (".$idtower.",1,'Genset')";
				if (!mysql_query($sqlchk, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
			if($_POST['chkSolar']=="on")
			{
				$sqlchk = "INSERT INTO catudaya (idtower,kodecatudaya,catudaya) VALUES (".$idtower.",2,'Panel Surya')";
				if (!mysql_query($sqlchk, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
  		}
			
		$opgabung = explode(",",$operator_gabung);
		if(count($opgabung)>0)
		{
			for($j=0;$j<count($opgabung);$j++)
			{
				$sqlop = "INSERT INTO opgabung (idtower,idoperator) VALUES (".$idtower.",".$opgabung[$j].")";
				if (!mysql_query($sqlop, $db)) {
					echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
				}
			}
		}
		else
		{
			$sqlop = "INSERT INTO opgabung (idtower,idoperator) VALUES (".$idtower.",".$operator_gabung.")";
			if (!mysql_query($sqlop, $db)) {
				echo mysql_errno($db) . ": " . mysql_error($db) . "\n";
			}
		}
  	}
?>
<html>
    <head>
		<link rel="stylesheet" type="text/css" media="screen" href="themes/ui.jqgrid.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/datePicker.css">
		<script src="js/jquery-1.2.6.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="js/date.js" type="text/javascript"></script>
		<!--[if IE]><script type="text/javascript" src="scripts/jquery.bgiframe.js"></script><![endif]-->
		<script src="js/jquery.datePicker.js" type="text/javascript"></script>
		<script src="js/ajaxupload.3.5.js" type="text/javascript"></script>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery('.date-pick').datePicker({autoFocusNextInput: true,startDate:'01/01/2000'});
				jQuery("#no_izin_prinsip").focus();
				jQuery('#no_izin_prinsip').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_izin_prinsip").focus();
					}
				});
				jQuery('#tgl_izin_prinsip').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#no_izin_ho").focus();
					}
				});
				jQuery('#no_izin_ho').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_izin_ho").focus();
					}
				});
				jQuery('#tgl_izin_ho').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_hbs_izin_ho").focus();
					}
				});
				jQuery('#tgl_hbs_izin_ho').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#no_imb").focus();
					}
				});
				jQuery('#no_imb').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_imb").focus();
					}
				});
				jQuery('#tgl_imb').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#lokasi").focus();
					}
				});
				jQuery('#lokasi').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#selkecamatan").focus();
					}
				});
				jQuery('#selkecamatan').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#selnagari").focus();
					}
				});
				jQuery('#selnagari').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#lokasi_sppt_pbb").focus();
					}
				});
				jQuery('#lokasi_sppt_pbb').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#kode_lokasi").focus();
					}
				});
				jQuery('#kode_lokasi').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tinggi").focus();
					}
				});
				jQuery('#tinggi').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#elevasi").focus();
					}
				});
				jQuery('#elevasi').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#luas_tanah").focus();
					}
				});
				jQuery('#luas_tanah').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#rab_tower").focus();
					}
				});
				jQuery('#rab_tower').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#koord_b").focus();
					}
				});
				jQuery('#koord_b').bind('keypress', function(e) {
					if(e.keyCode==13){
						if(jQuery('#selbujur').val()==0){
							jQuery("#koord_b_m").focus();
						}
						else{
							jQuery("#koord_l").focus();
						}
					}
				});
				jQuery('#koord_b_m').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#koord_b_s").focus();
					}
				});
				jQuery('#koord_b_s').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#koord_l").focus();
					}
				});
				jQuery('#koord_l').bind('keypress', function(e) {
					if(e.keyCode==13){
						if(jQuery('#sellintang').val()==0){
							jQuery("#koord_l_m").focus();
						}
						else{
							jQuery("#seloperator").focus();
						}
					}
				});
				jQuery('#koord_l_m').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#koord_l_s").focus();
					}
				});
				jQuery('#koord_l_s').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#selpemilik").focus();
					}
				});
				jQuery('#selpemilik').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#seloperator").focus();
					}
				});
				jQuery('#seloperator').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#pemilik_tanah").focus();
					}
				});
				/*jQuery('#pemilik_tower').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#operator_gabung").focus();
					}
				});
				jQuery('#operator_gabung').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#pemilik_tanah").focus();
					}
				});*/
				jQuery('#pemilik_tanah').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#status_tanah").focus();
					}
				});
				jQuery('#status_tanah').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#tgl_akhir_kontrak").focus();
					}
				});
				jQuery('#tgl_akhir_kontrak').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#njop_tanah").focus();
					}
				});
				jQuery('#njop_tanah').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery("#njop_menara").focus();
					}
				});
				jQuery('#njop_menara').bind('keypress', function(e) {
					if(e.keyCode==13){
						jQuery(":button:last").focus();
					}
				});
				<?php
					if($idtower == ""){
				?>
						getNagari();
						getPemilik();
						getOperator();
						jQuery("#perpanjang").css("display","none");
				<?php
					}
					else
					{
						$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
						mysql_select_db($database) or die("Error conecting to db.");
						
						$SQL = "SELECT t.idtower,t.idizin,t.idnjop,i.no_izin_prinsip,i.tgl_izin_prinsip,
							i.no_izin_ho,i.tgl_izin_ho,i.tgl_hbs_izin_ho,i.no_imb,i.tgl_imb,
							t.lokasi,t.lokasi_sppt_pbb,t.kode_lokasi,t.idkec,t.idnagari,t.tinggi,t.elevasi,t.luas_tanah,t.rab_tower,
							t.koord_b,t.koord_l,t.idpemilik,p.nama,p.alamat,p.perwakilan,
							p.alamat_perwakilan,p.contact_person,p.telp,p.pemilik,p.telp_pemilik,
							GROUP_CONCAT(o.idoperator ORDER BY og.idopgabung SEPARATOR ',') AS noperator,t.pemilik_tanah,t.status_tanah,t.akhir_kontrak, 
							GROUP_CONCAT(DISTINCT c.kodecatudaya ORDER BY c.kodecatudaya SEPARATOR ',') AS catudaya,t.type_lokasi,
							t.jml_pengguna,n.njop_tanah,n.njop_menara,n.njop_total,n.retribusi,i.file_imb    
							FROM tower t INNER JOIN izin i ON t.idizin=i.idizin 
							LEFT JOIN njop n ON t.idnjop=n.idnjop 
							INNER JOIN pemilik p ON t.idpemilik=p.idpemilik 
							INNER JOIN opgabung og ON t.idtower=og.idtower
							INNER JOIN operator o ON og.idoperator=o.idoperator
							LEFT JOIN catudaya c ON t.idtower=c.idtower
							GROUP BY t.idtower
							HAVING t.idtower = ".$idtower;
						//echo "sql:".$SQL."<br>";
						$result = mysql_query( $SQL ) or die("Could not execute query.".mysql_error());
						$row = mysql_fetch_array($result,MYSQL_ASSOC);
						
						if($row[catudaya] != "")
						{
							$kodecatudaya = explode(",",$row[catudaya]);
							if(count($kodecatudaya)>0)
							{
								for($j=0;$j<count($kodecatudaya);$j++)
								{
									if($kodecatudaya[$j]=="0")
									{
									?>
										jQuery("#chkAki").attr("checked","checked");
									<?
									}
									else if($kodecatudaya[$j]=="1")
									{
									?>
										jQuery("#chkGenset").attr("checked","checked");
									<?
									}
									else if($kodecatudaya[$j]=="2")
									{
									?>
										jQuery("#chkSolar").attr("checked","checked");
									<?
									}
								}
							}
						}
						
						$operator_gabung = "";
						$operator_gabung2 = "";
						$hid_op_gabung = "";
						if($row[noperator] != "")
						{
							//$operator_gabung = $row[idoperator];
							$idoperator = explode(",",$row[noperator]);
							if(count($idoperator)>0)
							{
								for($j=0;$j<count($idoperator);$j++)
								{
									$idop = $idoperator[$j];
									if($j==0)
										$operator_gabung = $idop;
									else
									{
										if($idop != "")
										{
										$hid_op_gabung .= $idop.",";
										$operator_gabung2 .= "<div id='divop".$j."'><input type='checkbox' id='chkop".$j."'/><select id='op_".$j."' name='op_".$j."' onchange='setIdOpGabung();'></select></div>";
				?>
										getOperatorGabung("#op_"+<?php echo $j;?>+"",<?php echo $idop;?>);
				<?php
										}
									}
								}
							}
							else
							{
								$operator_gabung = $row[noperator];
							}
						}
						
						$namafile = isset($row['file_imb'])?$row['file_imb']:"";
						if($namafile != "")
						{
							$arrnamafile = explode(";",$namafile);
							if(count($arrnamafile) > 1)
							{
								for($j=0;$j<count($arrnamafile);$j++)
								{
									$listfile .= "<li id='li_".$j."'>".$arrnamafile[$j]." [<a href='#' onclick='openfile(".$j.");'>Open</a>] [<a href='#' onclick='hapusfile(".$j.");'>Hapus</a>]</li>";
								}
							}
							else
							{
								$listfile = "<li id='li_0'>".$namafile." [<a href='#' onclick='openfile(0);'>Open</a>] [<a href='#' onclick='hapusfile(0);'>Hapus</a>]</li>";
							}
						}
				?>
						jQuery("#tutupform").html("");
						jQuery("#perpanjang").css("display","block");
						jQuery("#chkPerpanjang").click(function(){
							if(jQuery("#chkPerpanjang").is(":checked")==true)
							{
								jQuery("#no_izin_prinsip").val("");
								jQuery("#tgl_izin_prinsip").val("");
								jQuery("#no_izin_ho").val("");
								jQuery("#tgl_izin_ho").val("");
								jQuery("#tgl_hbs_izin_ho").val("");
								jQuery("#no_imb").val("");
								jQuery("#tgl_imb").val("");
								jQuery("#chkAki").attr("value","");
								jQuery("#chkGenset").attr("value","");
								jQuery("#chkSolar").attr("value","");
								jQuery("#no_izin_prinsip").focus();
							}
							else
							{
								jQuery("#no_izin_prinsip").val(jQuery("#hid_no_izin_prinsip").val());
								jQuery("#tgl_izin_prinsip").val(jQuery("#hid_tgl_izin_prinsip").val());
								jQuery("#no_izin_ho").val(jQuery("#hid_no_izin_ho").val());
								jQuery("#tgl_izin_ho").val(jQuery("#hid_tgl_izin_ho").val());
								jQuery("#tgl_hbs_izin_ho").val(jQuery("#hid_tgl_hbs_izin_ho").val());
								jQuery("#no_imb").val(jQuery("#hid_no_imb").val());
								jQuery("#tgl_imb").val(jQuery("#hid_tgl_imb").val());
								//jQuery("#no_izin_prinsip").focus();
							}
						});
				<?php
					}
				?>
			});
			
			jQuery.fn.ForceNumericOnly =
			function()
			{
				return this.each(function()
				{
					$(this).keydown(function(e)
					{
						var key = e.charCode || e.keyCode || 0;
						// allow backspace, tab, enter, delete, arrows, numbers and keypad numbers ONLY
						return (
							key == 8 || 
							key == 9 ||
							key == 13 ||
							key == 46 ||
							(key >= 37 && key <= 40) ||
							(key >= 48 && key <= 57) ||
							(key >= 96 && key <= 105));
					});
				});
			};
			jQuery('#rab_tower').ForceNumericOnly();
			jQuery('#njop_tanah').ForceNumericOnly();
			jQuery('#njop_menara').ForceNumericOnly();

			jQuery.fn.addItems = function(data) {
				return this.each(function() {
					var list = this;
					$.each(data, function(index, itemData) {
						var option = new Option(itemData.Text, itemData.Value);
						list.add(option);
					});
				});
			};
			
			function getNagari()
			{
				idkec = jQuery('#selkecamatan').val();
				var nagari = jQuery.ajax({
					url: 'nagari.php?search=false&idkec='+idkec, 
					type:"POST",
					datatype: "json",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Failure to retrieve the Nagari.');
					}
				}).responseText;
				
				var json = nagari.split(';');
				var i=0;
				jQuery("#selnagari").empty();
				jQuery.each(json,function(){
					var keyval = json[i].split(':');
					jQuery("<option>").attr("value", keyval[0]).text(keyval[1]).appendTo("#selnagari");
					i++;
 				});
			}
			
			function getOperator()
			{
				idoperator = jQuery('#seloperator').val();
				var operator = jQuery.ajax({
					url: 'operator.php?search=false&idoperator='+idoperator, 
					type:"POST",
					datatype: "xml",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Failure to retrieve the Operator.');
					}
				}).responseText;
				/*jQuery(operator).find('operator').each(function(){
					jQuery("#alamat").text(jQuery(this).find('alamat').text());
					jQuery("#perwakilan").val(jQuery(this).find('perwakilan').text());
					jQuery("#alamat_perwakilan").text(jQuery(this).find('alamat_perwakilan').text());
					jQuery("#contact_person").val(jQuery(this).find('contact_person').text());
					jQuery("#telp").val(jQuery(this).find('telp').text());
					jQuery("#pemilik_op").val(jQuery(this).find('pemilik').text());
					jQuery("#telp_pemilik_op").val(jQuery(this).find('telp_pemilik').text());
				});*/
			}
			
			function getPemilik()
			{
				idpemilik = jQuery('#selpemilik').val();
				var pemilik = jQuery.ajax({
					url: 'pemilik.php?search=false&idpemilik='+idpemilik, 
					type:"POST",
					datatype: "xml",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Failure to retrieve the Pemilik Tower.');
					}
				}).responseText;
				jQuery(pemilik).find('pemilik').each(function(){
					jQuery("#alamat").text(jQuery(this).find('alamat').text());
					jQuery("#perwakilan").val(jQuery(this).find('perwakilan').text());
					jQuery("#alamat_perwakilan").text(jQuery(this).find('alamat_perwakilan').text());
					jQuery("#contact_person").val(jQuery(this).find('contact_person').text());
					jQuery("#telp").val(jQuery(this).find('telp').text());
					jQuery("#pemilik_tower").val(jQuery(this).find('pemilik_tower').text());
					jQuery("#telp_pemilik_tower").val(jQuery(this).find('telp_pemilik').text());
				});
			}
			
			function convertDMS(text)
			{
				var pilihan = "";
				if(text=="bujur")
				{
					pilihan = jQuery('#selbujur').val();
					if(pilihan==0)	//DMS
					{
						jQuery('#koord_b').attr("size","3");
						jQuery('#koord_b').attr("maxlength","3");
						jQuery('#koord_b_m').attr("style","");
						jQuery('#koord_b_s').attr("style","");
						jQuery('#bujur').attr("style","");
						
						calculateDecToDMS("bujur");
					}
					else
					{
						jQuery('#koord_b').attr("size","10");
						jQuery('#koord_b').attr("maxlength","10");
						jQuery('#koord_b_m').css("display","none");
						jQuery('#koord_b_s').css("display","none");
						jQuery('#bujur').css("display","none");
						var pos = jQuery('#bujur').val();
						calculateDMSToDec("bujur",pos);
					}
				}
				else
				{
					pilihan = jQuery('#sellintang').val();
					if(pilihan==0)	//DMS
					{
						jQuery('#koord_l').attr("size","3");
						jQuery('#koord_l').attr("maxlength","3");
						jQuery('#koord_l_m').attr("style","");
						jQuery('#koord_l_s').attr("style","");
						jQuery('#lintang').attr("style","");

						calculateDecToDMS("lintang");
					}
					else
					{
						jQuery('#koord_l').attr("size","10");
						jQuery('#koord_l').attr("maxlength","10");
						jQuery('#koord_l_m').css("display","none");
						jQuery('#koord_l_s').css("display","none");
						jQuery('#lintang').css("display","none");
						var pos = jQuery('#lintang').val();
						calculateDMSToDec("lintang",pos);
					}
				}
			}
			
			function calculateDecToDMS(text)
			{
				if(text=="bujur")
				{
					var bujur_val = jQuery('#koord_b').val();
					if(bujur_val=="")
						return;
					else
					{
						if(isNaN(bujur_val))
						{
							alert("Data koordinat diisi dengan angka. Ganti koma dengan titik");
							jQuery('#koord_b').focus();
							return;
						}
						else
						{
							// Change to absolute value
							lat = Math.abs(bujur_val);

							// Convert to Degree Minutes Seconds Representation
							LatDeg = Math.floor(lat);
							LatMin = Math.floor((lat-LatDeg)*60);
							LatSec =  (Math.round((((lat - LatDeg) - (LatMin/60)) * 60 * 60) * 100) / 100 ) ;
							
							jQuery('#koord_b').val(LatDeg);
							jQuery('#koord_b_m').val(LatMin);
							jQuery('#koord_b_s').val(roundNumber(LatSec,2));
						}
					}
				}
				else
				{
					var lintang_val = jQuery('#koord_l').val();
					if(lintang_val=="")
						return;
					else
					{
						if(isNaN(lintang_val))
						{
							alert("Data koordinat diisi dengan angka. Ganti koma dengan titik");
							jQuery('#koord_l').focus();
							return;
						}
						else
						{
							// Change to absolute value
							lon = Math.abs(lintang_val);

							// Convert to Degree Minutes Seconds Representation
							LonDeg = Math.floor(lon);
							LonMin = Math.floor((lon-LonDeg)*60);
							LonSec =  (Math.round((((lon - LonDeg) - (LonMin/60)) * 60 * 60) * 100) / 100 ) ;
							
							jQuery('#koord_l').val(LonDeg);
							jQuery('#koord_l_m').val(LonMin);
							jQuery('#koord_l_s').val(roundNumber(LonSec,2));
						}
					}
				}
			}
			
			function calculateDMSToDec(text,pos)
			{
				if(text=="bujur")
				{
					var bujur_val = jQuery('#koord_b').val();
					var bujur_m_val = jQuery('#koord_b_m').val();
					var bujur_s_val = jQuery('#koord_b_s').val();
					if(bujur_val=="" || bujur_m_val=="" || bujur_s_val=="")
						return;
					else
					{
						if(isNaN(bujur_val) || isNaN(bujur_m_val) || isNaN(bujur_s_val))
						{
							alert("Data koordinat diisi dengan angka. Ganti koma dengan titik");
							jQuery('#koord_b').focus();
							return;
						}
						else
						{
							// Change to absolute value
							LatDeg = Math.abs(bujur_val);
							LatMin = Math.abs(bujur_m_val);
							LatSec = Math.abs(bujur_s_val);

							// Convert to Decimal Degrees Representation
							var Lat = LatDeg + (LatMin/60) + (LatSec / 60 / 60);
							if(pos==1)
								Lat=-1.0000*Lat;
							jQuery('#koord_b').val(roundNumber(Lat,4));
						}
					}
				}
				else
				{
					var lintang_val = jQuery('#koord_l').val();
					var lintang_m_val = jQuery('#koord_l_m').val();
					var lintang_s_val = jQuery('#koord_l_s').val();
					if(lintang_val=="" || lintang_m_val=="" || lintang_s_val=="")
						return;
					else
					{
						if(isNaN(lintang_val) || isNaN(lintang_m_val) || isNaN(lintang_s_val))
						{
							alert("Data koordinat diisi dengan angka. Ganti koma dengan titik");
							jQuery('#koord_l').focus();
							return;
						}
						else
						{
							// Change to absolute value
							LonDeg = Math.abs(lintang_val);
							LonMin = Math.abs(lintang_m_val);
							LonSec = Math.abs(lintang_s_val);

							// Convert to Decimal Degrees Representation
							var Lon = LonDeg + (LonMin/60) + (LonSec / 60 / 60);
							if(pos==0)
								Lon=-1.0000*Lon;
							jQuery('#koord_l').val(roundNumber(Lon,4));
						}
					}
				}
			}
			
			function getOperatorGabung(idop,sel)
			{
				var op = jQuery.ajax({
					url: 'operator.php?search=true&sel=true', 
					type:"POST",
					datatype: "json",
					async: false, 
					success: function(data, result) {
						if (!result) 
							alert('Data Operator Belum Ada.');
					}
				}).responseText;
				
				var operator = op.split(";");
				jQuery.each(operator,function(i,val){
					var obj = val.split(":");
					var selop = "";
					if(sel != "")
					{
						if(obj[0]==sel)
							selop = "selected";
					}
					jQuery(idop).append("<option value='"+obj[0]+"' "+selop+">"+obj[1]+"</option>");
				});
			}
			
			function addOp(){
				//var i = jQuery("#op_gabung input").size()+1;
				var i = 0;
				jQuery("#op_gabung input").each(function(){
					var idchk = jQuery(this).attr("id");
					var j = idchk.substr(5);
					i = parseInt(j);
				});
				i++;
				jQuery("#op_gabung").append("<div id='divop"+i+"'><input type='checkbox' id='chkop"+i+"'/><select id='op_"+i+"' name='op_"+i+"' onchange='setIdOpGabung();'></select></div>");
				getOperatorGabung("#op_"+i+"","");
				setIdOpGabung();
			}
			
			function remOp(){
				jQuery("#op_gabung input").each(function(){
					var idchk = jQuery(this).attr("id");
					var j = idchk.substr(5);
					if(jQuery("#chkop"+j+"").is(":checked")==true)
					{
						jQuery("#divop"+j+"").remove();
					}
				});
				setIdOpGabung();
			}

			function setIdOpGabung()
			{
				jQuery("#hid_op_gabung").val("");
				var val = "";
				jQuery("#op_gabung input").each(function(){
					var idchk = jQuery(this).attr("id");
					var j = idchk.substr(5);
					val += jQuery("#op_"+j+"").val()+",";
				});
				jQuery("#hid_op_gabung").val(val);
			}
			
			function roundNumber(num, dec) {
				var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
				return result;
			}
				
			function addCommas(id,nStr)
			{
				nStr = nStr.replace(/\./g,'');
				nStr += '';
				x = nStr.split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '';
				var rgx = /(\d+)(\d{3})/;
				while (rgx.test(x1)) {
					x1 = x1.replace(rgx, '$1' + '.' + '$2');
				}
				//return x1 + x2;
				var njop_tanah = 0;
				var njop_menara = 0;
				var njop_total = 0;
				var retribusi = 0;
				jQuery("#"+id).val(x1 + x2);
				
				if(id=="njop_tanah"){
					njop_tanah = x1 + x2;
					njop_tanah = njop_tanah.replace(/\./g,'');
					njop_menara = jQuery("#njop_menara").val();
					if(njop_menara==""){
						njop_menara = 0;
					}else {
						njop_menara = njop_menara.replace(/\./g,''); 
					}
				} else {
					njop_menara = x1 + x2;
					njop_menara = njop_menara.replace(/\./g,'');
					njop_tanah = jQuery("#njop_tanah").val();
					if(njop_tanah==""){
						njop_tanah = 0;
					}else {
						njop_tanah = njop_tanah.replace(/\./g,''); 
					}
				}
				njop_total = parseFloat(njop_tanah) + parseFloat(njop_menara);
				retribusi = 0.02*njop_total;
				x = njop_total.toString().split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '';
				var rgxt = /(\d+)(\d{3})/;
				while (rgxt.test(x1)) {
					x1 = x1.replace(rgxt, '$1' + '.' + '$2');
				}
				jQuery("#njop_total").val(x1 + x2);
				
				x = retribusi.toString().split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '';
				var rgxr = /(\d+)(\d{3})/;
				while (rgxr.test(x1)) {
					x1 = x1.replace(rgxr, '$1' + '.' + '$2');
				}
				jQuery("#retribusi").val(x1 + x2);
			}
			
			function addCommas1(id,nStr)
			{
				nStr = nStr.replace(/\./g,'');
				nStr += '';
				x = nStr.split('.');
				x1 = x[0];
				x2 = x.length > 1 ? '.' + x[1] : '';
				var rgx = /(\d+)(\d{3})/;
				while (rgx.test(x1)) {
					x1 = x1.replace(rgx, '$1' + '.' + '$2');
				}
				//return x1 + x2;
				jQuery("#"+id).val(x1 + x2);
			}
			
			function openfile(idx)
			{
				var namafile = jQuery("#namafile").val();
				var url = "imb/"+namafile;
				//window.location.href = url;
				window.open(url,'_blank');
			}
			function hapusfile(idx)
			{
				jQuery("#li_"+idx).remove();
				var i = 0;
				if(confirm("Apakah file ini ingin dihapus?")){
				if(jQuery("li[id*=li_]").length > 0)
				{
					jQuery("li[id*=li_]").each(function(index){
						var filename = $(this).text().replace("[Hapus]","");
						if(i>0)
						{
							var namafile = jQuery("#namafile").val();
							jQuery("#namafile").val(namafile+";"+filename);
						}
						else
						{
							jQuery("#namafile").val(filename);
						}
						i++;
					});
				}
				else
				{
					jQuery("#namafile").val("");
				}
				}
			}
			
			$(function(){
				var btnUpload=$('#upload');
				var status=$('#status');
				var sessid = jQuery('#sessid').val();
				new AjaxUpload(btnUpload, {
					action: 'upload-file.php?sessid='+sessid,
					name: 'uploadfile',
					onSubmit: function(file, ext){
						/*if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
							// extension is not allowed 
							status.text('Only JPG, PNG or GIF files are allowed');
							return false;
						}*/
						status.text('Uploading...');
					},
					onComplete: function(file, response){
						//On completion clear the status
						status.text('');
						//Add uploaded file to list
						if(response === "exist"){
							alert("File '"+file+"' sudah ada di server. Ubah nama file yang akan di upload.");
						} else {
							if(response==="success"){
								var filecnt = jQuery("#filecount").val();
								filecnt++;
								jQuery("#filecount").val(filecnt);
								$('<li id="li_'+filecnt+'"></li>').appendTo('#files').html(file+' [<a href="#" onclick="openfile('+filecnt+');">Open</a>] [<a href="#" onclick="hapusfile('+filecnt+');">Hapus</a>]').addClass('success');
								var filename = jQuery("#namafile").val();
								if(filename != "")
								{
									jQuery("#namafile").val(filename+';'+file);
								}
								else
								{
									jQuery("#namafile").val(file);
								}
							} else{
								$('<li></li>').appendTo('#files').text(file).addClass('error');
							}
						}
					}
				});
			});
		</script>
		<style type="text/css">
			.ui-widget {
				font-size:0.7em;
			}
			
		</style>
	</head>
<body>
<form name="frmsubmit" id="frmsubmit" method="post">
	<div id="status_simpan" style="color:green;"></div>
	<div id="perpanjang"><input name="chkPerpanjang" id="chkPerpanjang" type="checkbox"><b>Perpanjang Izin Prinsip/HO/IMB</b></input></div>
	<table>
		<tr>
			<td>
				<fieldset style="width:610px">
					<legend>
						<b style="color:darkblue;">Izin Prinsip</b>
					</legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>Nomor&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="no_izin_prinsip" id="no_izin_prinsip" size="20" maxlength="50" type="text" value="<?php echo $row['no_izin_prinsip'];?>"></td>
							<td>&nbsp;</td>
							<td><b>Tanggal Surat&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="tgl_izin_prinsip" id="tgl_izin_prinsip" size="12" maxlength="10" class="date-pick" type="text" value="<?php if($row['tgl_izin_prinsip']!="") {echo $myfunction->dateformatSlash($row['tgl_izin_prinsip']);}?>">
							</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td style="display:none;"><b>Tgl. Habis Berlaku</b></td>
							<td style="display:none;"><input name="tgl_hbs_izin_prinsip" id="tgl_hbs_izin_prinsip" size="12" maxlength="10" class="date-pick" type="text" value="<?php //if($row['tgl_hbs_izin_prinsip']!="") {echo $myfunction->dateformatSlash($row['tgl_hbs_izin_prinsip']);}?>">
							</td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset style="width:610px">
					<legend>
						<b style="color:darkblue;">Izin HO</b>
					</legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>Nomor&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="no_izin_ho" id="no_izin_ho" size="20" maxlength="50" type="text" value="<?php echo $row['no_izin_ho'];?>"></td>
							<td>&nbsp;</td>
							<td><b>Tgl. Mulai Berlaku</b></td>
							<td><input name="tgl_izin_ho" id="tgl_izin_ho" size="12" maxlength="10" type="text" class="date-pick" value="<?php if($row['tgl_izin_ho']!="") {echo $myfunction->dateformatSlash($row['tgl_izin_ho']);}?>">
							</td>
							<td>&nbsp;</td>
							<td><b>Tgl. Habis Berlaku</b></td>
							<td><input name="tgl_hbs_izin_ho" id="tgl_hbs_izin_ho" size="12" maxlength="10" type="text" class="date-pick" value="<?php if($row['tgl_hbs_izin_ho']!="") {echo $myfunction->dateformatSlash($row['tgl_hbs_izin_ho']);}?>">
							</td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset style="width:610px">
					<legend>
						<b style="color:darkblue;">IMB</b>
					</legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>Nomor&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="no_imb" id="no_imb" size="20" maxlength="50" type="text" value="<?php echo $row['no_imb'];?>"></td>
							<td>&nbsp;</td>
							<td><b>Tanggal Surat&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="tgl_imb" id="tgl_imb" size="10" maxlength="10" type="text" class="date-pick" value="<?php if($row['tgl_imb']!="") {echo $myfunction->dateformatSlash($row['tgl_imb']);}?>">
							<div type="text" id="datepicker"></div>
							</td>
							<td>&nbsp;</td>
							<td colspan="2" width="200px">
								<table width="100%">
									<tr>
										<td>&nbsp;</td>
										<td>
											<div id="upload">
												<span>Upload File IMB<span>
											</div>
											<span id="status"></span>
										</td>
									</tr>
									<tr>
										<td>&nbsp;</td>
										<td><ul id="files"><?php echo $listfile;?></ul></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset style="width:610px">
					<legend>
						<b style="color:darkblue;">Data Tower/Menara</b>
					</legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>Lokasi</b></td>
							<td><textarea name="lokasi" id="lokasi" cols="25" rows="3"><?php echo $row['lokasi'];?></textarea></td>
							<td>&nbsp;</td>
							<td><b>Kecamatan</b></td>
							<td>
								<select id="selkecamatan" name="selkecamatan" onchange="getNagari();">
								<?php
									$sqlkec = "SELECT idkec,kecamatan from kecamatan";
									$resultkec = mysql_query( $sqlkec ) or die("Could not execute query.".mysql_error());
									while($rowkec = mysql_fetch_array($resultkec,MYSQL_ASSOC)) 
									{
										if($idtower != "")
										{
								?>
										<option value="<?php echo $rowkec['idkec'];?>" <?php if($row['idkec']==$rowkec['idkec']){echo 'selected="true"';}?>><?php echo $rowkec['kecamatan'];?></option>
								<?php
										}
										else
										{
								?>
										<option value="<?php echo $rowkec['idkec'];?>"><?php echo $rowkec['kecamatan'];?></option>
								<?php
										}
									}
								?>
								</select>
							</td>
							<td>&nbsp;</td>
							<td><b>Nagari</b></td>
							<td><select id="selnagari" name="selnagari">
								<?php
									if($idtower != "")
									{
									$sqlnagari = "SELECT idnagari,nagari from nagari where idkec=".$row['idkec'];
									$resultnagari = mysql_query( $sqlnagari ) or die("Could not execute query.".mysql_error());
									while($rownagari = mysql_fetch_array($resultnagari,MYSQL_ASSOC)) 
									{
										
								?>
										<option value="<?php echo $rownagari['idnagari'];?>" <?php if($row['idnagari']==$rownagari['idnagari']){echo 'selected="true"';}?>><?php echo $rownagari['nagari'];?></option>
								<?php
										
									}
									}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<td><b>Lokasi SPPT PBB</b></td>
							<td><textarea name="lokasi_sppt_pbb" id="lokasi_sppt_pbb" cols="25" rows="3"><?php echo $row['lokasi_sppt_pbb'];?></textarea></td>
							<td>&nbsp;</td>
							<td><b>Kode Lokasi</b></td>
							<td><input name="kode_lokasi" id="kode_lokasi" size="10" maxlength="10" type="text" value="<?php echo $row['kode_lokasi']?>"></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td><b>Tinggi</b></td>
							<td><input name="tinggi" id="tinggi" size="10" maxlength="5" type="text" value="<?php if($row['tinggi']==0 || $row['tinggi']==""){echo "";}else{echo $row['tinggi'];}?>"> m</td>
							<td>&nbsp;</td>
							<td><b>Elevasi</b></td>
							<td><input name="elevasi" id="elevasi" size="10" maxlength="10" type="text" value="<?php if($row['elevasi']==0 || $row['elevasi']==""){echo "";}else{echo $row['elevasi'];}?>"> m dpl</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<td><b>Luas Tanah</b></td>
							<td><input name="luas_tanah" id="luas_tanah" size="10" maxlength="5" type="text" value="<?php if($row['luas_tanah']==0 || $row['luas_tanah']==""){echo "";}else{echo $row['luas_tanah'];}?>"> m2</td>
							<td>&nbsp;</td>
							<td><b>RAB Tower</b></td>
							<td><input name="rab_tower" id="rab_tower" size="15" maxlength="15" type="text" value="<?php if($row['rab_tower']==0 || $row['rab_tower']==""){echo "";}else{echo $row['rab_tower'];}?>" style="text-align:right;" onkeyup="addCommas1('rab_tower',this.value);"> Rp</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<?php
								$koord_b = calculateDecToDMS($row['koord_b'],"bujur");
								$_koord_b = explode(";",$koord_b);
							?>
							<td><b>Koordinat Bujur</b></td>
							<td><input id="koord_b" name="koord_b" size="3" maxlength="3" type="text" title="Isi Koordinat Bujur, Misal : 100 30 45.8 BT untuk pilihan DMS atau 100.49 untuk pilihan Desimal" value="<?php echo $_koord_b[0];?>"><input id="koord_b_m" name="koord_b_m" size="3" maxlength="3" type="text" value="<?php echo $_koord_b[1];?>"><input id="koord_b_s" name="koord_b_s" size="3" maxlength="5" type="text" value="<?php echo $_koord_b[2];?>"><select id="bujur" name="bujur"><option value="0">BT</option><option value="1">BB</option></select>
								<select id="selbujur" name="selbujur" onchange="convertDMS('bujur');">
									<option value="0">DMS</option>
									<option value="1">Desimal</option>
								</select></td>
							<td>&nbsp;</td>
							<?php
								$koord_l = calculateDecToDMS($row['koord_l'],"lintang");
								$_koord_l = explode(";",$koord_l);
							?>
							<td><b>Koordinat Lintang</b></td>
							<td colspan="4"><input id="koord_l" name="koord_l" size="3" maxlength="3" type="text" title="Isi Koordinat Lintang, Misal : 00 27 36.4 LS untuk pilihan DMS atau -0.49 untuk pilihan Desimal" value="<?php echo $_koord_l[0];?>"><input id="koord_l_m" name="koord_l_m" size="3" maxlength="3" type="text" value="<?php echo $_koord_l[1];?>"><input id="koord_l_s" name="koord_l_s" size="3" maxlength="5" type="text" value="<?php echo $_koord_l[2];?>"><select id="lintang" name="lintang"><option value="0">LS</option><option value="1">LU</option></select><select id="sellintang" name="sellintang" onchange="convertDMS('lintang');">
									<option value="0">DMS</option>
									<option value="1">Desimal</option>
								</select></td>
						</tr>
						<tr>
							<td><b>Catudaya</b></td>
							<td colspan="2"><input id='chkAki' name='chkAki' type='checkbox'>Aki/Baterai</input>&nbsp;<BR/><input id='chkGenset' name='chkGenset' type='checkbox'>Genset</input>&nbsp;<BR/><input id='chkSolar' name='chkSolar' type='checkbox'>Panel Surya</input></td>
							<td><b>Type/Lokasi Kawasan Menara</b></td>
							<td colspan="4"><select id="type_lokasi" name="type_lokasi">
									<option value="0">Kawasan pertanian/persawahan/perkebunan</option>
									<option value="1">Kawasan hutan</option>
									<option value="2">Kawasan hunian kepadatan rendah</option>
									<option value="3">Kawasan hunian kepadatan sedang</option>
									<option value="4">Kawasan hunian kepadatan tinggi</option>
									<option value="5">Kawasan Perkantoran</option>
									<option value="6">Kawasan perdagangan</option>
									<option value="7">Kawasan Bandara/Pelabuhan/Industri/Purbakala</option>
								</select></td>
						</tr>
						<tr>
							<td><b>Jumlah Pengguna</b></td>
							<td><select id="jml_pengguna" name="jml_pengguna">
									<option value="0">1 operator telekomunikasi</option>
									<option value="1">2 operator telekomunikasi</option>
									<option value="2">3 operator telekomunikasi</option>
									<option value="3">>3 operator telekomunikasi</option>
								</select></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset style="width:610px">
					<legend>
						<b style="color:darkblue;">Data Pemilik Tower</b>
					</legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>Pemilik Tower</b></td>
							<td><select id="selpemilik" name="selpemilik" onchange="getPemilik();">
								<?php
									$sqlpemilik = "SELECT idpemilik,nama from pemilik";
									$resultpemilik = mysql_query( $sqlpemilik ) or die("Could not execute query.".mysql_error());
									while($rowpemilik = mysql_fetch_array($resultpemilik,MYSQL_ASSOC)) 
									{
								?>
										<option value="<?php echo $rowpemilik['idpemilik'];?>" <?php if($row['idpemilik']==$rowpemilik['idpemilik']){echo 'selected="true"';}?>><?php echo $rowpemilik['nama'];?></option>
								<?php
									}
								?>
								</select>
							</td>
							<!--td><input name="nama" size="30" maxlength="100" type="text" value="<?php //echo $row['nama'];?>"></td-->
							<td>&nbsp;</td>
							<td><b>Alamat Kantor Pusat</b></td>
							<td><textarea id="alamat" name="alamat" disabled cols="30" rows="3"><?php echo $row['alamat'];?></textarea></td>
						</tr>
						<tr>
							<td><b>Perwakilan</b></td>
							<td><input id="perwakilan" name="perwakilan" disabled size="30" maxlength="100" type="text" value="<?php echo $row['perwakilan'];?>"></td>
							<td>&nbsp;</td>
							<td><b>Alamat Perwakilan</b></td>
							<td><textarea id="alamat_perwakilan" name="alamat_perwakilan" disabled cols="30" rows="3"><?php echo $row['alamat_perwakilan'];?></textarea></td>
						</tr>
						<tr>
							<td><b>Contact Person</b></td>
							<td><input id="contact_person" name="contact_person" disabled size="30" maxlength="100" type="text" value="<?php echo $row['contact_person'];?>"></td>
							<td>&nbsp;</td>
							<td><b>No. Telp</b></td>
							<td><input id="telp" name="telp" disabled size="25" maxlength="50" type="text" value="<?php echo $row['telp'];?>"></td>
						</tr>
						<tr>
							<td><b>Pemilik Perusahaan</b></td>
							<td><input id="pemilik_tower" name="pemilik_tower" disabled size="30" maxlength="100" type="text" value="<?php echo $row['pemilik'];?>"></td>
							<td>&nbsp;</td>
							<td><b>No. Telp Pemilik</b></td>
							<td><input id="telp_pemilik_tower" name="telp_pemilik_tower" disabled size="25" maxlength="50" type="text" value="<?php echo $row['telp_pemilik'];?>"></td>
						</tr>
						<tr>
							<td><b>Operator Yang Bergabung</b></td>
							<td><!--input name="pemilik_tower" id="pemilik_tower" size="30" maxlength="50" type="text" value="<?php //echo $row['pemilik_tower'];?>"-->
								<input type="button" id="but_add_op" value="Tambah Operator Yang Bergabung" onclick="addOp();">
								<input type="button" id="but_rem_op" value="Hapus Operator Yang Bergabung" onclick="remOp();">
							</td>
							<td>&nbsp;</td>
							<td><!--b>Operator Yang Bergabung</b--></td>
							<td><!--input name="operator_gabung" id="operator_gabung" size="30" maxlength="100" type="text" value="<?php //echo $row['operator_gabung'];?>"-->
								<select id="seloperator" name="seloperator">
								<?php
									$sqloperator = "SELECT idoperator,nama from operator";
									$resultoperator = mysql_query( $sqloperator ) or die("Could not execute query.".mysql_error());
									while($rowoperator = mysql_fetch_array($resultoperator,MYSQL_ASSOC)) 
									{
								?>
										<option value="<?php echo $rowoperator['idoperator'];?>" <?php if($operator_gabung==$rowoperator['idoperator']){echo 'selected="true"';}?>><?php echo $rowoperator['nama'];?></option>
								<?php
									}
								?>
								</select>
								<div id="op_gabung"><?php echo $operator_gabung2;?></div>
							</td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset style="width:610px">
					<legend>
						<b style="color:darkblue;">Data Lain</b>
					</legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>Pemilik Tanah</b></td>
							<td><input name="pemilik_tanah" id="pemilik_tanah" size="15" maxlength="150" type="text" value="<?php echo $row['pemilik_tanah'];?>"></td>
							<td>&nbsp;</td>
							<td><b>Status Tanah</b></td>
							<td>
								<select name="status_tanah" id="status_tanah">
									<option value="0" <?php if($row['status_tanah']==0){echo 'selected="true"';}?>>Milik Sendiri</option>
									<option value="1" <?php if($row['status_tanah']==1){echo 'selected="true"';}?>>Sewa</option>
								</select>
							</td>
							<td>&nbsp;</td>
							<!--td><b>Lama Sewa</b></td>
							<?php
								/*$_lama_sewa = $row['lama_sewa'];
								if($_lama_sewa == "")
									$_lama_sewa =  explode(";","0;0");
								else
									$_lama_sewa = explode(";",$_lama_sewa);*/
								
							?>
							<td><input name="lama_sewa_tahun" size="3" maxlength="3" type="text" value="<?php //echo $_lama_sewa[0];?>"> Tahun&nbsp;<input name="lama_sewa_bulan" size="3" maxlength="3" type="text" value="<?php //echo $_lama_sewa[1];?>"> Bulan 
							</td-->
							<td><b>Tgl. Akhir Kontrak&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="tgl_akhir_kontrak" id="tgl_akhir_kontrak" size="10" maxlength="10" type="text" class="date-pick" value="<?php if($row['akhir_kontrak']!="") {echo $myfunction->dateformatSlash($row['akhir_kontrak']);}?>">
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset style="width:610px">
					<legend>
						<b style="color:darkblue;">Data NJOP & Retribusi</b>
					</legend>
					<table style="background-color: lightblue;">
						<tr>
							<td><b>NJOP Tanah&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="njop_tanah" id="njop_tanah" size="15" maxlength="15" type="text" value="<?php echo $row['njop_tanah'];?>" style="text-align:right;" onkeyup="addCommas('njop_tanah',this.value);"></td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><b>NJOP Menara&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="njop_menara" id="njop_menara" size="15" maxlength="15" type="text" value="<?php echo $row['njop_menara'];?>" style="text-align:right;" onkeyup="addCommas('njop_menara',this.value);">
							</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><b>NJOP Total&nbsp;&nbsp;&nbsp;</b></td>
							<td><input name="njop_total" id="njop_total" size="20" maxlength="20" type="text" value="<?php echo $row['njop_total'];?>" readonly="readonly" style="text-align:right;">
							</td>
						</tr>
						<tr>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
							<td><b>Besar Retribusi (2% dari NJOP Total)</b></td>
							<td><input name="retribusi" id="retribusi" size="20" maxlength="20" type="text" value="<?php echo $row['retribusi'];?>" readonly="readonly" style="text-align:right;">
							</td>
						</tr>
					</table>
				</fieldset>
			</td>
		</tr>
	</table>
	<!--div id="topbar" align="center"-->
		<!--a href="" onClick="closebar(); return false"></a-->
		<!--input type="button" name="batal" id="batal" value="Batal"-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<!--input type="submit" name="simpan" id="simpan" value="Simpan"-->
		<!--input type="button" name="simpan" id="simpan" value="Simpan"-->
	<!--/div-->
    <input type="hidden" name="FormName" value="bts">
	<input type="hidden" name="id" value="<?php echo $idtower;?>">
	<input type="hidden" name="dontclose" id="dontclose" value="0">
	<input type="hidden" name="hid_op_gabung" id="hid_op_gabung" value="<?php echo $hid_op_gabung;?>">
	<input type="hidden" name="idizin" id="idizin" value="<?php echo $row['idizin'];?>">
	<input type="hidden" name="idnjop" id="idnjop" value="<?php echo $row['idnjop'];?>">
	<input type="hidden" id="hid_no_izin_prinsip" value="<?php echo $row['no_izin_prinsip'];?>">
	<input type="hidden" id="hid_tgl_izin_prinsip" value="<?php if($row['tgl_izin_prinsip']!="") {echo $myfunction->dateformatSlash($row['tgl_izin_prinsip']);}?>">
	<input type="hidden" id="hid_no_izin_ho" value="<?php echo $row['no_izin_ho'];?>">
	<input type="hidden" id="hid_tgl_izin_ho" value="<?php if($row['tgl_izin_ho']!="") {echo $myfunction->dateformatSlash($row['tgl_izin_ho']);}?>">
	<input type="hidden" id="hid_tgl_hbs_izin_ho" value="<?php if($row['tgl_hbs_izin_ho']!="") {echo $myfunction->dateformatSlash($row['tgl_hbs_izin_ho']);}?>">
	<input type="hidden" id="hid_no_imb" value="<?php echo $row['no_imb'];?>">
	<input type="hidden" id="hid_tgl_imb" value="<?php if($row['tgl_imb']!="") {echo $myfunction->dateformatSlash($row['tgl_imb']);}?>">
	<input type="hidden" name="filecount" id="filecount" value="0">
	<input type="hidden" name="namafile" id="namafile" value="<?php echo $namafile;?>">
	<input type="hidden" name="namafileold" id="namafileold" value="<?php echo $namafile;?>">
</form>
</body>
</html>