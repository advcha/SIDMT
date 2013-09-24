<?php
     session_start();  
     $userid = $_SESSION["UserID"];
     if($userid == "" || $userid == null)
     {
        header("Location: index.php");
    }
?>