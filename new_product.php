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
if ($_SESSION['login'] == false) {
	//③SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2'] = 'ログインしてください';
	//④ログイン画面へ遷移する。
	header("Location: login.php");
}

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
$new_id = $getId_id['id'] + 1;
// var_dump($new_id);
// 閉じる
$bookdate->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>新商品追加</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>

<body>
	<!-- ヘッダ -->
	<div id="header">
		<h1>新商品追加</h1>
	</div>
	<!-- メニュー -->
	<div id="menu">
		<nav>
			<ul>
				<li><a href="zaiko_ichiran.php?page=1">追加書籍</a></li>
			</ul>
		</nav>
	</div>
	<form action="new_prodict_kakunin.php" method="post">
		<div id="pagebody">
			<!-- エラーメッセージ -->
			<div id="error">
			</div>
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額(円)</th>
							<th id="stock">在庫数</th>
							<th id="in">入荷数</th>
						</tr>
					</thead>
					<!-- hiddenとは...「Webブラウザに表示されない文字をPOST/GETで送ることができる便利な関数である」 -->
					<input type="hidden" value="<?php echo $new_id; ?>" name="id">
					<tr>
						<td><?php echo $new_id ?></td>
						<td><input type="text" name='title' size='5' maxlength='11' required></td>
						<td><input type="text" name='author' size='5' maxlength='11' required></td>
						<td><input type="text" name='salesDate' size='5' maxlength='11' required></td>
						<td><input type="text" name='price' size='5' maxlength='11' required></td>
						<td><input type="text" name='stock' size='5' maxlength='11' required></td>
						<td><input type='text' name='in_stock' size='5' maxlength='11' required></td>
					</tr>
				</table>
				<button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
			</div>
		</div>
		<!-- フッター -->
		<div id="footer">
			<footer>株式会社アクロイト</footer>
		</div>
</body>

</html>