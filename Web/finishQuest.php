<?php
header('Content-type:text/json');
$json = array('status' => true, 'num' => -1,'errorMsg' => '', 'historyStatus' => array());
$nA = array();
if (!isset($_POST['token']) || $_POST['token'] != "123456") {
	$json['status'] = false;
	$json['errorMsg'] = 'token错误!';
	echo json_encode($json);
	die();
}
if (!isset($_POST['id'])) {
	$json['status'] = false;
	$json['errorMsg'] = '参数错误!';
	echo json_encode($json);
	die();
} 
$id = (int)$_POST['id'];

include "assets/API/dcon.php";
$pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_database, $db_user, $db_password);
if (!$pdo) {
	$json['status'] = false;
	$json['errorMsg'] = '数据库连接失败';
	echo json_encode($json);
	die();
}
$sql = "UPDATE arduino_history SET check1 = 1 WHERE id = ".$id;
$res = $pdo->exec($sql);
if ($res==0) {
    $json['status'] = false;
	$json['errorMsg'] = '未查找到数据!';
	echo json_encode($json);
	die();
}
$json['status'] = true;
echo json_encode($json);
?>