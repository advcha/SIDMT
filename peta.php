<?php
	include("include/dbconfig.php");
	
	$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
	mysql_select_db($database) or die("Error conecting to db.");
 
	/**
	 * merge two true colour images with variable opacity while maintaining alpha
	 * transparency of both images.
	 *
	 * @access public
	 *
	 * @param  resource $dst  Destination image link resource
	 * @param  resource $src  Source image link resource
	 * @param  int      $dstX x-coordinate of destination point
	 * @param  int      $dstY y-coordinate of destination point
	 * @param  int      $srcX x-coordinate of source point
	 * @param  int      $srcY y-coordinate of source point
	 * @param  int      $w    Source width
	 * @param  int      $h    Source height
	 * @param  int      $pct  Opacity of source image
	 ******************************************************************************/
	function imagecopymerge_alpha($dst, $src, $dstX, $dstY, $srcX, $srcY, $w, $h, $pct)
	{
		$pct /= 100;
	 
		/* make sure opacity level is within range before going any further */
		$pct  = max(min(1, $pct), 0);
	 
		if ($pct == 0)
		{
			/* 0% opacity? then we have nothing to do */
			return;
		}
	 
		/* work out if we need to bother correcting for opacity */
		if ($pct < 1)
		{
			/* we need a copy of the original to work from, only copy the cropped */
			/* area of src                                                        */
			$srccopy  = imagecreatetruecolor($w, $h);
	 
			/* attempt to maintain alpha levels, alpha blending must be *off* */
			imagealphablending($srccopy, false);
			imagesavealpha($srccopy, true);
	 
			imagecopy($srccopy, $src, 0, 0, $srcX, $srcY, $w, $h);
	 
			/* we need to know the max transaprency of the image */
			$max_t = 0;
	 
			for ($y = 0; $y < $h; $y++)
			{
				for ($x = 0; $x < $w; $x++)
				{
					$src_c = imagecolorat($srccopy, $x, $y);
					$src_a = ($src_c >> 24) & 0xFF;
	 
					$max_t = $src_a > $max_t ? $src_a : $max_t;
				}
			}
			/* src has no transparency? set it to use full alpha range */
			$max_t = $max_t == 0 ? 127 : $max_t;
	 
			/* $max_t is now being reused as the correction factor to apply based */
			/* on the original transparency range of  src                         */
			$max_t /= 127;
	 
			/* go back through the image adjusting alpha channel as required */
			for ($y = 0; $y < $h; $y++)
			{
				for ($x = 0; $x < $w; $x++)
				{
					$src_c  = imagecolorat($src, $srcX + $x, $srcY + $y);
					$src_a  = ($src_c >> 24) & 0xFF;
					$src_r  = ($src_c >> 16) & 0xFF;
					$src_g  = ($src_c >>  8) & 0xFF;
					$src_b  = ($src_c)       & 0xFF;
	 
					/* alpha channel compensation */
					$src_a = ($src_a + 127 - (127 * $pct)) * $max_t;
					$src_a = ($src_a > 127) ? 127 : (int)$src_a;
	 
					/* get and set this pixel's adjusted RGBA colour index */
					$rgba  = ImageColorAllocateAlpha($srccopy, $src_r, $src_g, $src_b, $src_a);
	 
					/* ImageColorAllocateAlpha returns -1 for PHP versions prior  */
					/* to 5.1.3 when allocation failed                               */
					if ($rgba === false || $rgba == -1)
					{
						$rgba = ImageColorClosestAlpha($srccopy, $src_r, $src_g, $src_b, $src_a);
					}
	 
					imagesetpixel($srccopy, $x, $y, $rgba);
				}
			}
	 
			/* call imagecopy passing our alpha adjusted image as src */
			imagecopy($dst, $srccopy, $dstX, $dstY, 0, 0, $w, $h);
	 
			/* cleanup, free memory */
			imagedestroy($srccopy);
			return;
		}
	 
		/* still here? no opacity adjustment required so pass straight through to */
		/* imagecopy rather than imagecopymerge to retain alpha channels          */
		imagecopy($dst, $src, $dstX, $dstY, $srcX, $srcY, $w, $h);
		return;
	}
	
	$peta_combine="";
	//$peta_warna="";
	$idkec=$_GET['idkec'];
	$idop=$_GET['idop'];
	$sql = "";
	$querytitik = "";
	
	//if(isset($_GET['idkec']))
	//{
		if(strlen($idkec)>0)
		{
			if($idkec != "0")
			{
				$sql = "SELECT path_warna FROM kecamatan where idkec in (".$idkec.")";
				$querytitik = "SELECT koord_b,koord_l,idtower FROM tower where idkec in (".$idkec.")";
				if(strlen($idop)>0)
				{
					if($idop != "0")
					{
						//$querytitik .= " and idoperator in (".$idop.")";
						$querytitik = "SELECT t.koord_b,t.koord_l,t.idtower FROM tower t 
						INNER JOIN pemilik p ON t.idpemilik=p.idpemilik 
						INNER JOIN opgabung og ON t.idtower=og.idtower 
						INNER JOIN operator o ON og.idoperator=o.idoperator  
						WHERE t.idkec IN (".$idkec.") AND o.idoperator IN (".$idop.")";
					}
				}
			}
			else
			{
				$sql = "SELECT path_warna FROM kecamatan";
				$querytitik = "SELECT koord_b,koord_l,idtower FROM tower";
				if(strlen($idop)>0)
				{
					if($idop != "0")
					{
						//$querytitik .= " where idoperator in (".$idop.")";
						$querytitik = "SELECT t.koord_b,t.koord_l,t.idtower FROM tower t 
						INNER JOIN pemilik p ON t.idpemilik=p.idpemilik 
						INNER JOIN opgabung og ON t.idtower=og.idtower 
						INNER JOIN operator o ON og.idoperator=o.idoperator  
						WHERE o.idoperator IN (".$idop.")";
					}
				}
			}
			
			$kec = mysql_query($sql);
			$i=0;
			while($datakec = mysql_fetch_row($kec))
			{
				$peta_warna = imagecreatefrompng($datakec[0]);
				if($i==0)
				{
					$peta_combine = $peta_warna;
					imagealphablending($peta_combine, true);
					imagesavealpha($peta_combine, true);
				}
				else
				{
					$peta_x = imagesx($peta_warna); 
					$peta_y = imagesy($peta_warna); 

					imagecopymerge_alpha($peta_combine,$peta_warna,0,0,0,0,$peta_x,$peta_y,100);
				}
				$i++;
			}
		}
		else
		{
			$peta_combine = imagecreatefrompng("bg_w.png");
			
			/* <- must be set to retain alpha blending/merging */
			imagealphablending($peta_combine, true);
			imagesavealpha($peta_combine, true);
		}
	//}
	/*else
	{
		$kec = mysql_query("SELECT path_warna FROM kecamatan");
		$i=0;
		while($datakec = mysql_fetch_row($kec))
		{
			$peta_warna = imagecreatefrompng($datakec[0]);
			if($i==0)
			{
				// <- must be set to retain alpha blending/merging 
				$peta_combine = $peta_warna;
				imagealphablending($peta_combine, true);
				imagesavealpha($peta_combine, true);
			}
			else
			{
				$peta_x = imagesx($peta_warna); 
				$peta_y = imagesy($peta_warna); 

				imagecopymerge_alpha($peta_combine,$peta_warna,0,0,0,0,$peta_x,$peta_y,100);
			}
			$i++;
		}
		mysql_free_result($kec);
	}*/
	$peta_wilayah = imagecreatefrompng("images/peta_td.png"); 
	$peta_wilayah_x = imagesx($peta_wilayah); 
	$peta_wilayah_y = imagesy($peta_wilayah); 

	imagecopymerge_alpha($peta_combine,$peta_wilayah,0,0,0,0,$peta_wilayah_x,$peta_wilayah_y,100);
	$warna = imagecolorallocate ($peta_combine, 0,0,255); //menentukan warna titik lokasi pada peta
	//Hitam = 0,0,0; putih = 255,255,255
	//merah = 255,0,0; lime = 0,255,0; biru = 0,0,255
	//kuning = 255,255,0; Fuchsia = 255,0,255
	//Cyan = 0,255,255; 
	$w = imagesx($peta_combine);
	$b_b = 100.3167;
	$b_a = 100.85;

	$h = imagesy($peta_combine);
	$l_b = -0.2833;
	$l_a = -0.65;
	
	/*$querytitik = "";
	if(isset($_GET['idkec']))
	{
		$querytitik = mysql_query("SELECT koord_b,koord_l FROM tower where idkec in (".$idkec.")"); //query informasi koordinat lokasi
	}
	else
	{
		$querytitik = mysql_query("SELECT koord_b,koord_l FROM tower"); //query informasi koordinat lokasi
	}*/

	$result = mysql_query($querytitik);
	while($titik = mysql_fetch_row($result))
	{
		$x = (($titik[0]-$b_b)/($b_a - $b_b))*$w;
		$y = (($titik[1]-$l_b)/($l_a - $l_b))*$h;
		imagefilledrectangle($peta_combine,$x-3,$y-3,$x+3,$y+3,$warna); //membuat titik kotak pada peta
	}
	
	header ("Content-type: image/png"); 

	imagepng($peta_combine);
	imagedestroy($peta_combine);

?>