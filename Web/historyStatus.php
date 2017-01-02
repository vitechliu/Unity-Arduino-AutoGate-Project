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
include "assets/API/dcon.php";
$pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_database, $db_user, $db_password);
if (!$pdo) {
	$json['status'] = false;
	$json['errorMsg'] = '数据库连接失败';
	echo json_encode($json);
	die();
}
if (isset($_POST['checked']) && $_POST['checked']==1) $sql = "SELECT * FROM arduino_history ORDER BY time";
else $sql = "SELECT * FROM arduino_history WHERE check1 = 0 ORDER BY time";
$res = $pdo->query($sql);
$num = -1;
while ($row = $res->fetch(PDO::FETCH_ASSOC)) {	
    $num++;
	$json['historyStatus'][$num+1] = array('id' => $row['id'], 'operator' => $row['operator'],'door' => $row['door'], 'operation' => $row['operation'], 'time' => $row['time'], 'check1' => $row['check1']);
}
$json['status'] = true;
$json['num'] = $num;
echo json_encode($json);
?>