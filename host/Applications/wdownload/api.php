<?php
include 'data.conf.php';

try {
	$reTask = $_GET['tasks'];
	$token = $_GET['key'];
	if ($reTask == 'get' && $token == $data['token']) {
		// code...
		$login = 1;
	}
} catch (Exception $e ) {
	$login = 0;
}
if ($login == 1) {
	// code...
	$zj = json_decode($tasks,true);
	$putTask = json_encode($zj,320);
	echo $putTask;
}else {
	// code...
	$putTask = '{"msg":"You are not eligible to visit","error":"0"}';
	echo $putTask;
}

?>