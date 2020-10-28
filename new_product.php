<?php
/*
 * session_status()の結果が「PHP_SESSION_NONE」と一致するか判定する。
 * 一致した場合はif文の中に入る。
 */
if ((function_exists('session_status')
	&& session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
	// 	//②セッションを開始する
	session_start();
}

// //SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
// if ($_SESSION['login'] == false) {
// 	//③SESSIONの「error2」に「ログインしてください」と設定する。
// 	$_SESSION['error2'] = 'ログインしてください';
// 	//④ログイン画面へ遷移する。
// 	header("Location: login.php");
// }

//データベースへ接続し、接続情報を変数に保存する
$host = 'localhost';
$user_name = 'zaiko2020_yse';
$db_name = 'zaiko2020_yse';
$password = '2020zaiko';
$mysqli = new mysqli($host, $user_name, $password, $db_name);

if ($mysqli->connect_error) {
	echo $mysqli->connect_error;
	exit();
} else {
	//⑦データベースで使用する文字コードを「UTF8」にする
	$mysqli->set_charset('utf8');
}

// データーベースから最後のidを取得
// SQL文
$sql = " SELECT * FROM books ORDER BY id DESC LIMIT 1 ";
// SQL実行
$bookdate = $mysqli->query($sql);
// 取得
$getId_id = $bookdate->fetch_assoc();
// id取得
// var_dump($getId_id['id']);
// 閉じる
$bookdate->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>