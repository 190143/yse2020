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

// データーベースに追加
function new_prodict($con, $id, $title, $author, $salesDate, $price, $in_stock)
{
    // SQL文
    $sql = "INSERT INTO books (id, title, author, salesDate, isbn, price, stock) VALUES (" . $id . " , " . $title . " , " . $author . " ," . $salesDate . " , 0 , " . $price . " , " . $in_stock . ")";
    var_dump($sql);
    // $con->query($sql);
}

// //SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
// if ($_SESSION['login'] == false) {
// 	//③SESSIONの「error2」に「ログインしてください」と設定する。
// 	$_SESSION['error2'] = 'ログインしてください';
// 	//④ログイン画面へ遷移する。
// 	header("Location: login.php");
// }
var_dump($_POST['id']);
// var_dump($_POST['id'], $_POST['title'], $_POST['author'], $_POST['salesDate'], $_POST['price'], $_POST['in_stock']);

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

//POSTでこの画面のボタンの「add」に値が入ってるか確認する。
//値が入っている場合は中身に「ok」が設定されていることを確認する。
if (!empty($_POST['add'])) {
    if ($_POST['add'] == 'ok') {
        // SQLに追加
        new_prodict($mysqli, $_POST['id'], $_POST['title'], $_POST['author'], $_POST['salesDate'], $_POST['price'], $_POST['in_stock']);
    }
    //SESSIONの「success」に「登録が完了しました」と設定する。
    $_SESSION['success'] = '登録が完了しました。';
    //「header」関数を使用して在庫一覧画面へ遷移する。
    header("Location: zaiko_ichiran.php");
}
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