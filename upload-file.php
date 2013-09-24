<?php
	//require_once("checklogin.php");
	$uploaddir = 'imb/'; 
	$tmpdir = 'tmp/'; 
	$file = $uploaddir . basename($_FILES['uploadfile']['name']); 
	$file_tmp = $tmpdir . basename($_FILES['uploadfile']['name']); 
	if(!file_exists($file)){
		if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $file_tmp)) 
		{ 
			echo "success"; 
		} 
		else 
		{
			echo "error";
		}
	} else {
		echo "exist";
	}
?>