<?php
    session_start(); 
    require_once("include/mysql.class.php");
  
    $iduserlevel = $_SESSION["UserRights"];
    $iddepartment = $_SESSION["DeptID"];
    $pagename = $_SESSION["PageName"];
    if($iduserlevel == "" || $iduserlevel == null)
    {
        header("Location: index.php");
    }else
    {
        $db = new MySQL();
        if (! $db->Open()) {
            $db->Kill();
        }
        $query = "select iduseraccess FROM useraccess a INNER JOIN menu b ON a.idmenu=b.idmenu WHERE a.iduserlevel=".$iduserlevel." AND a.iddepartment=".$iddepartment." AND b.namepage='".$pagename."'";
        //echo($query);
        $db->Query($query);
        if($db->RowCount() != 1)
			  {
			      header("Location: noaccess.php");
        }
    }
?>
