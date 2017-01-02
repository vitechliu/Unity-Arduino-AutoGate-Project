<?php
header('Content-type:text/json');
$json = array('status' => true, 'errorMsg' => '','num'=>0);
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

//curl1
$url = "http://vitechliu.com/arduino/historyStatus.php";
$post_data = array("token" => "123456");
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_POST, 1);
curl_setopt($ch1, CURLOPT_POSTFIELDS, $post_data);
$output1 = curl_exec($ch1);
curl_close($ch1);
$outarr1 = json_decode($output1, true);
//判断栈空
if ((int)$outarr1['num']>0) {
    $json['status'] = false;
    $json['errorMsg'] = '上一项操作正在处理中!';
    echo json_encode($json);
    die();
}


//curl2
$url = "http://vitechliu.com/arduino/doorStatus.php";
$post_data = array("token" => "123456");
$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch2, CURLOPT_POST, 1);
curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_data);
$output2 = curl_exec($ch2);
curl_close($ch2);
$outarr2 = json_decode($output2, true);
//判断开关
if ($operation > 0) {
	if ($outarr2['doorStatus'][$door]['status']) {
		$json['status'] = false;
		$json['errorMsg'] = $door . '号门已经处于打开状态!';
		echo json_encode($json);
		die();
	}
} else {
	if (!$outarr2['doorStatus'][$door]['status']) {
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
    
    $sql = "INSERT INTO arduino_history VALUES (null,'".$name. "',".$door.",".$operation . ",NOW(),0)";
	$pdo->query($sql);
    
	$json['status'] = true;
	if ($operation > 0) $json['errorMsg'] = $nA[$name] . "打开了" . $door . "号门!";
	else $json['errorMsg'] = $nA[$name] . "关闭了" . $door . "号门!";
} else {
	$json['status'] = false;
	$json['errorMsg'] = '权限错误';
}
echo json_encode($json);
?>