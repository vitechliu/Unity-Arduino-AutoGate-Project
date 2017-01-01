<?php
header('Content-type:text/json');
$json = array('status' => true, 'errorMsg' => '');
$nA = array();
$nA['lzr'] = "刘子锐";
$nA['pyc'] = "潘宇超";
$nA['lmx'] = "刘明绪";
$nA['ymx'] = "岳孟雄";
$nA['yt'] = "杨泰";
$nA['wtz'] = "王庭舟";
if (!isset($_POST['token']) || $_POST['token'] != "123456") {
	$json['status'] = false;
	$json['errorMsg'] = 'token错误!';
	echo json_encode($json);
	die();
}
//变量
$door = (int)$_POST['door'];
$operation = (int)$_POST['operation'];
$name = $_POST['name'];
//curl
$url = "doorStatus.php";
$post_data = array("token" => "123456");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$output = curl_exec($ch);
curl_close($ch);
$outarr = json_decode($output, true);
//判断开关
if ($operation > 0) {
	if ($outarr[$door]['status']) {
		$json['status'] = false;
		$json['errorMsg'] = $door . '号门已经处于打开状态!';
		echo json_encode($json);
		die();
	}
} else {
	if (!$outarr[$door]['status']) {
		$json['status'] = false;
		$json['errorMsg'] = $door . '号门已经处于关闭状态!';
		echo json_encode($json);
		die();
	}
}
include "assets/API/dcon.php";
if (array_key_exists($name, $nA)) {
	$pdo = new PDO("mysql:host=" . $db_host . ";dbname=" . $db_database, $db_user, $db_password);
	if (!$pdo) {
		$json['status'] = false;
		$json['errorMsg'] = '数据库连接失败';
		echo json_encode($json);
		die();
	}
	$sql = "UPDATE arduino SET lastOperator = '" . $name . "', lastOperation = " . $operation . ", time = NOW() WHERE door = '" . $door . "'";
	$pdo->query($sql);
	$json['status'] = true;
	if ($operation > 0) $json['errorMsg'] = $nA[$operator] . "打开了" . $door . "号门!";
	else $json['errorMsg'] = $nA[$operator] . "关闭了" . $door . "号门!";
} else {
	$json['status'] = false;
	$json['errorMsg'] = '权限错误';
}
echo json_encode($json);dd
?>