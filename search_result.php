<?php
//セッションを開始する
session_start();

//②SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if ($_SESSION['login'] == false) {
    //SESSIONの「error2」に「ログインしてください」と設定する。
    $_SESSION['error2'] = 'ログインしてください';
    //ログイン画面へ遷移する。
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
    //データベースで使用する文字コードを「UTF8」にする
    $mysqli->set_charset('utf8');
}

// 変数名
$salesData = null;
$price = null;
$stock = null;

// 未入力（1）ではなかった場合実行
if (strcmp($_POST['salesDate'], 1) !== 0) {
    // 範囲の上限を計算
    $salesData_2 = $_POST['salesDate'] + 9;
    // SQL文
    $salesData = "AND `salesDate` BETWEEN '" . $_POST['salesDate'] . "年' and '" . $salesData_2 . "年'";
}

// 未入力（1）ではなかった場合実行
if (strcmp($_POST['price'], 1) !== 0) {
    // 範囲の上限を計算
    $price_2 = $_POST['price'] + 99;
    // SQL文
    $price = "AND `price` BETWEEN '" . $_POST['price'] . "' AND '" . $price_2 . "'";
}

// 未入力（1）ではなかった場合実行
if (strcmp($_POST['stock'], 1) !== 0) {
    // 範囲の上限を計算
    $stock = $_POST['stock'] + 9;
    // SQL文
    $stock = "AND `stock` BETWEEN '" . $_POST['stock'] . "' AND '" . $stock . "'";
}

//タイトル検索 + 発売年代のSQL + 金額 + 在庫数
$sql = "SELECT * FROM `books` WHERE `title` LIKE '" . $_POST['keyword'] . "%'" . $salesData . $price . $stock;
// var_dump($sql);

// 実行
$bookdate = $mysqli->query($sql);

// var_dump($_POST['keyword']);
// var_dump($_POST['salesDate']);
// var_dump($_POST['price']);
// var_dump($_POST['stock']);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
    <title>検索結果</title>
</head>

<body>
    <!-- ヘッダ -->
    <div id="header">
        <h1>検索結果</h1>
    </div>

    <!-- メニュー -->
    <div id="menu">
        <nav>
            <ul>
                <li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
            </ul>
        </nav>
    </div>

    <form action="search_result.php" method="post" id="myform" name="myform">
        <div id="pagebody">
            <!-- エラーメッセージ -->
            <div id="error">
                <?php
                //⑬SESSIONの「error」にメッセージが設定されているかを判定する。
                //設定されていた場合はif文の中に入る。
                if (isset($_SESSION["error"])) {
                    //⑭SESSIONの「error」の中身を表示する。
                    echo $_SESSION["error"];
                }
                ?>
            </div>
            <div id="center">
                <!-- 書籍一覧の表示 -->
                <table>
                    <thead>
                        <tr>
                            <th id="check"></th>
                            <th id="id">ID</th>
                            <th id="book_name">書籍名</th>
                            <th id="author">著者名</th>
                            <th id="salesDate">発売日</th>
                            <th id="itemPrice">金額</th>
                            <th id="stock">在庫数</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //⑩SQLの実行結果の変数から1レコードのデータを取り出す。レコードがない場合はループを終了する。
                        while ($row = $bookdate->fetch_assoc()) {
                            // displayが0だった場合非表示
                            if ($row['display'] == 1) {
                                //⑪extract変数を使用し、1レコードのデータを渡す。
                                //$extract = $extract->get($id);
                                // extract($id);

                                echo "<tr id='book'>";
                                // name = books[]でPOSTで$_POST['books']で受信できる
                                echo "<td id='check'><input type='checkbox' name='books[]'value='" . $row['id'] . "'></td>";
                                echo "<td id='id'>" . $row['id'] . "</td>";
                                echo "<td id='title'>" . $row['title'] . "</td>";
                                echo "<td id='author'>" . $row['author'] . "</td>";
                                echo "<td id='date'>" . $row['salesDate'] . "</td>";
                                echo "<td id='price'>" . $row['price'] . "</td>";
                                echo "<td id='stock'>" . $row['stock'] . "</td>";
                                echo "</tr>";
                            }
                        }
                        $bookdate->close();
                        ?>
                    </tbody>
                </table>
                <button type="submit" id="kakutei" formmethod="POST" name="decision" value="3" formaction="nyuka.php">入荷</button>
                <button type="submit" id="kakutei" formmethod="POST" name="decision" value="4" formaction="syukka.php">出荷</button>
            </div>
        </div>
    </form>
    <!-- フッター -->
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>