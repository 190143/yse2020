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
    $sql = "INSERT INTO books (id, title, author, salesDate, isbn, price, stock) VALUES (" . $id . " , '" . $title . "' , '" . $author . "' , '" . $salesDate . "' , 0 , " . $price . " , " . $in_stock . ")";
    // ...↓
    // $sql = "INSERT INTO books (title, author, salesDate, isbn, price, stock) VALUES ('" . $title . "' , '" . $author . "' , '" . $salesDate . "' , 0 , " . $price . " , " . $in_stock . ")";
    // var_dump($sql);
    $con->query($sql);
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

//POSTでこの画面のボタンの「add」に値が入ってるか確認する。
//値が入っている場合は中身に「ok」が設定されていることを確認する。
if (!empty($_POST['add'])) {
    if ($_POST['add'] == 'ok') {
        // 文字列の結合
        $date = $_POST['salesDate_year']."年".$_POST['salesDate_month']."月".$_POST['salesDate_day']."日";
        // SQLに追加
        new_prodict($mysqli, $_POST['id'], $_POST['title'], $_POST['author'], $date, $_POST['price'], $_POST['in_stock']);
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
    <title>新商品追加確認</title>
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>

<body>
    <!-- ヘッダ -->
    <div id="header">
        <h1>新商品追加確認</h1>
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
                            <!-- <th id="salesDate">発売日</th> -->
                            <th id="salesDate_year">発売日の年</th>
							<th id="salesDate_month">発売日の月</th>
							<th id="salesDate_day">発売日の日</th>
                            <th id="itemPrice">金額(円)</th>
                            <th id="stock">在庫数</th>
                            <th id="in">入荷数</th>
                        </tr>
                    </thead>
                    <!-- hiddenとは...「Webブラウザに表示されない文字をPOST/GETで送ることができる便利な関数である」 -->
                    <input type="hidden" value="<?php echo $_POST['id']; ?>" name="id">
                    <input type="hidden" value="<?php echo $_POST['title']; ?>" name="title">
                    <input type="hidden" value="<?php echo $_POST['author']; ?>" name="author">
                    <!-- <input type="hidden" value="<?php // echo $_POST['salesDate']; ?>" name="salesDate"> -->
                    <input type="hidden" value="<?php echo $_POST['salesDate_year']; ?>" name="salesDate_year">
                    <input type="hidden" value="<?php echo $_POST['salesDate_month']; ?>" name="salesDate_month">
                    <input type="hidden" value="<?php echo $_POST['salesDate_day']; ?>" name="salesDate_day">
                    <input type="hidden" value="<?php echo $_POST['price']; ?>" name="price">
                    <input type="hidden" value="<?php echo $_POST['stock']; ?>" name="stock">
                    <input type="hidden" value="<?php echo $_POST['in_stock']; ?>" name="in_stock">
                    <tr>
                        <td><?php echo $_POST['id']; ?></td>
                        <td><?php echo $_POST['title']; ?></td>
                        <td><?php echo $_POST['author']; ?></td>
                        <!-- <td><?php //echo $_POST['salesDate']; ?></td> -->
                        <td><?php echo $_POST['salesDate_year']; ?><a>年</a></td>
						<td><?php echo $_POST['salesDate_month']; ?><a>月</a></td>
						<td><?php echo $_POST['salesDate_day']; ?><a>日</a></td>
                        <td><?php echo $_POST['price']; ?></td>
                        <td><?php echo $_POST['stock']; ?></td>
                        <td><?php echo $_POST['in_stock']; ?></td>
                    </tr>
                </table>
                <div id="kakunin">
                    <p>
                        上記の書籍を登録します。<br>
                        よろしいですか？
                    </p>
                    <button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
                    <button type="submit" id="message" formaction="new_product.php">いいえ</button>
                </div>
            </div>
        </div>
        <!-- フッター -->
        <div id="footer">
            <footer>株式会社アクロイト</footer>
        </div>
</body>

</html>