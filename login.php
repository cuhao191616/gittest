<?php
	$id=$_GET['id'];//帳戶編號
	
	//連結mysql
	include_once("..\link.php");
	$mysqli=pcf();
	
	
	
	echo json_encode($data);
?>