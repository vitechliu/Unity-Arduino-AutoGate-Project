<?php
header('Content-type:text/json');
$json = array('status' => true, 'errorMsg' => '', 'doorStatus' => array());
$nA = array();
if (!isset($_POST['token']) || $_POST['token'] != "123456") {
	$json['status'] = false;
	$json['errorMsg'] = 'token错误!';
	echo json_encode($json);
	die();
}
include "assets/API/dcon.php";
$pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_database, $db_user, $db_password);
if (!$pdo) {
	$json['status'] = false;
	$json['errorMsg'] = '数据库连接失败';
	echo json_encode($json);
	die();
}
$sql = "SELECT * FROM arduino ";
$res = $pdo->query($sql);
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
	$json['status'] = true;
	$json['doorStatus'][(int)$row['door']] = array('status' => $row['status'], 'lastOperator' => $row['lastOperator'], 'lastOperation' => $row['lastOperation'], 'time' => $row['time']);
}
echo json_encode($json);
?>