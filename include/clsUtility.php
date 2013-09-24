<?php
class MyFunction
{
    function getCurrFormattedDate(){
       $currdate = date("w;d;m;Y");
       $arrdate = explode(";",$currdate);
       $dayname = getDayName($arrdate[0]);
       $day = $arrdate[1];
       $monthname = getMonthName((int)$arrdate[2]);
       $year = $arrdate[3];
       return($dayname.', '.$day.' '.$monthname.' '.$year);
    }
	
	function dateformatSlashView($tgldb)
	{
		$display = "";
		if($tgldb != "")
		{
			$arrtgl = explode("-",$tgldb);
			$display = $arrtgl[2].'-'.$arrtgl[1].'-'.$arrtgl[0];
		}
		return ($display);
	}
	
	function dateformatSlashDb($tgl)
	{
		$display = "0000-00-00";
		if($tgl != "")
		{
			$arrtgl = explode("/",$tgl);
			$display = $arrtgl[2].'-'.$arrtgl[1].'-'.$arrtgl[0];
		}
		return ($display);
	}
	
	function dateformatSlash($tgl)
	{
		$arrtgl = explode("-",$tgl);
		$display = $arrtgl[2].'/'.$arrtgl[1].'/'.$arrtgl[0];
		if($display == "00/00/0000" || $display == "0000/00/00")
			$display = "";
		return ($display);
	}
	
	function dateformatLong($tgl)
	{
		$arrtgl = explode("-",$tgl);
		$display = $arrtgl[2].' '.getMonthName((int)$arrtgl[1]).' '.$arrtgl[0];
		if($arrtgl[2] == "0000" || $arrtgl[2] == "00")
			$display = "";
		return ($display);
	}
}
    
function getDayName($day){
    $dayname = "";
    switch($day){
        case 0:
           $dayname = "Minggu";
           break;
        case 1:
           $dayname = "Senin";
           break;
        case 2:
           $dayname = "Selasa";
           break;
        case 3:
           $dayname = "Rabu";
           break;
        case 4:
           $dayname = "Kamis";
           break;
        case 5:
           $dayname = "Jumat";
           break;
        default:
           $dayname = "Sabtu";
           break;
    }
    return($dayname);
}

function getMonthName($month){
    $monthname = "";
    switch($month){
        case 1:
           $monthname = "Januari";
           break;
        case 2:
           $monthname = "Februari";
           break;
        case 3:
           $monthname = "Maret";
           break;
        case 4:
           $monthname = "April";
           break;
        case 5:
           $monthname = "Mei";
           break;
        case 6:
           $monthname = "Juni";
           break;
        case 7:
           $monthname = "Juli";
           break;
        case 8:
           $monthname = "Agustus";
           break;
        case 9:
           $monthname = "September";
           break;
        case 10:
           $monthname = "Oktober";
           break;
        case 11:
           $monthname = "November";
           break;
        default:
           $monthname = "Desember";
           break;
    }
    return($monthname);
}

function calculateDecToDMS($val,$text)
{
	if($val=="")
		return;
	else
	{
		// Change to absolute value
		$pos = abs($val);

		// Convert to Degree Minutes Seconds Representation
		$PosDeg = floor($pos);
		$PosMin = floor(($pos-$PosDeg)*60);
		$PosSec = round(((($pos - $PosDeg) - ($PosMin/60)) * 60 * 60),2);
		$LB = "";
		if($text=="bujur")
		{
			if($val > 0)
				$LB = "BT";
			else
				$LB = "BB";
		}
		else
		{
			if($val < 0)
				$LB = "LS";
			else
				$LB = "LU";
		}
		return $PosDeg.";".$PosMin.";".$PosSec.";".$LB;
	}
}

function calculateDMSToDec($deg_val,$min_val,$sec_val,$LB,$text)
{
	if($deg_val=="" || $min_val=="" || $sec_val=="")
		return;
	else
	{
		// Change to absolute value
		$PosDeg = abs($deg_val);
		$PosMin = abs($min_val);
		$PosSec = abs($min_val);

		// Convert to Decimal Degrees Representation
		$Pos = $PosDeg + ($PosMin/60) + ($PosSec / 60 / 60);
		if($text=="lintang" && $LB==0)	//Lintang Selatan
			$Pos=-1.0000*$Pos;  
		return $Pos;
	}
}
/*
function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
} 
*/   
?>
