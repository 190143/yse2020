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
function new_prodict($con, $id, $title, $author, $salesDate, $isbn, $price,  $in_stock)
{

    // SQL文
    // ID / タイトル / 著作者人 / 発売日 / ISBNコード / 価格 / 在庫数 / 入荷数
    $sql = "INSERT INTO books (id, title, author, salesDate, isbn, price, stock, display) VALUES (" . $id . " , '" . $title . "' , '" . $author . "' , '" . $salesDate . "' , $isbn , " . $price . " , " . $in_stock . " , 1)";
    var_dump($sql);
    // ...↓
    // $sql = "INSERT INTO books (title, author, salesDate, isbn, price, stock) VALUES ('" . $title . "' , '" . $author . "' , '" . $salesDate . "' , 0 , " . $price . " , " . $in_stock . ")";
    // var_dump($sql);
    $con->query($sql);
}

// Is_Numericが$_POST[]のままだと正常に動作しないので箱に入れる
$isbn;
$price;
$stock;
if (!empty($_POST['isbn']) && !empty($_POST['price']) && !empty($_POST['stock'])) {
    $isbn = $_POST['isbn'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // 数値以外が入力されていた場合
    if (!is_numeric($isbn) || !is_numeric($price) || !is_numeric($stock)) {
        //⑬SESSIONの「error」に「数値以外が入力されています」と設定する。
        $_SESSION['error'] = '数値以外が入力されています';
        //⑭「include」を使用して「new_product.php」を呼び出す。
        include 'new_product.php';
        //⑮「exit」関数で処理を終了する。
        exit;
    }
}



// カレンダーの「-」を年・月・日に変換
// 一文字が入る変数
$mozi = null;
// 完成形の文が入る変数
$letter = null;
// 実行回数が入る変数
$int = 0;
// 年・月・日の処理状況
$date_letter = 0;

while (true) {
    // 左から一文字ずつ取り出し
    $mozi = substr($_POST['salesDate'], $int, 1);

    // if (strcmp($mozi, "-") == 0) {
    if ((strcmp($mozi, "-") == 0) || (strcmp($mozi, "") == 0)) {
        // 年・月・日を呼び出す
        switch ($date_letter) {
            case 0:
                $mozi = str_replace("-", "年", $mozi);
                break;
            case 1:
                $mozi = str_replace("-", "月", $mozi);
                break;
            case 2:
                $mozi = "日";
                break;
        }
        $date_letter++;
    }

    // 完成の文字を生成していく
    $letter = $letter . $mozi;

    // 最後の右側（空白）になったら
    if (strcmp($mozi, "") == 0) {
        // 処理を終える
        break;
    }

    // 繰り返した回数を１増やす
    $int++;
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
        // $date = $_POST['salesDate_year'] . "年" . $_POST['salesDate_month'] . "月" . $_POST['salesDate_day'] . "日";
        $date = $letter;
        // SQLに追加
        new_prodict($mysqli, $_POST['id'], $_POST['title'], $_POST['author'],  $date, $_POST['isbn'], $_POST['price'], $_POST['in_stock']);
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
                            <th id="sa">ISBN</th>
                            <th id="itemPrice">金額(円)</th>
                            <th id="stock">在庫数</th>
                            <th id="in">入荷数</th>
                        </tr>
                    </thead>
                    <!-- hiddenとは...「Webブラウザに表示されない文字をPOST/GETで送ることができる便利な関数である」 -->
                    <input type="hidden" value="<?php echo $_POST['id']; ?>" name="id">
                    <input type="hidden" value="<?php echo $_POST['title']; ?>" name="title">
                    <input type="hidden" value="<?php echo $_POST['author']; ?>" name="author">
                    <input type="hidden" value="<?php echo $_POST['isbn']; ?>" name="isbn">
                    <!-- 年・月・日に変換したもの -->
                    <input type="hidden" value="<?php echo $letter; ?>" name="salesDate">
                    <input type="hidden" value="<?php echo $_POST['price']; ?>" name="price">
                    <input type="hidden" value="<?php echo $_POST['stock']; ?>" name="stock">
                    <input type="hidden" value="<?php echo $_POST['in_stock']; ?>" name="in_stock">
                    <tr>
                        <td><?php echo $_POST['id']; ?></td>
                        <td><?php echo $_POST['title']; ?></td>
                        <td><?php echo $_POST['author']; ?></td>
                        <td><?php echo $letter; ?></td>
                        <td><?php echo $_POST['isbn']; ?></td>
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