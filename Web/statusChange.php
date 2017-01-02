<?php
header('Content-type:text/json');
$json = array('status' => true, 'errorMsg' => '');
if (!isset($_POST['token']) || $_POST['token'] != "123456") {
	$json['status'] = false;
	$json['errorMsg'] = 'token错误!';
	echo json_encode($json);
	die();
}
//变量
$door = (int)$_POST['door'];
$status = (int)$_POST['status'];

include "assets/API/dcon.php";
	$pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_database, $db_user, $db_password);
	if (!$pdo) {
		$json['status'] = false;
		$json['errorMsg'] = '数据库连接失败';
		echo json_encode($json);
		die();
	}
	$sql = "UPDATE arduino SET status = " . $status . " WHERE door = " . $door;
    
	$res = $pdo->exec($sql);

	
$json['status'] = true;
echo json_encode($json);
?>