<?php
/*
 * session_status()の結果が「PHP_SESSION_NONE」と一致するか判定する。
 * 一致した場合はif文の中に入る。
 */
if ((function_exists('session_status')
    && session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
    session_start();
}

// 選択したIDから書籍情報を取得する
function getByid($id, $con)
{
    $sql = "SELECT * FROM books WHERE id = " . $id;
    $bookdate = null;
    if ($bookdate = $con->query($sql)) {
        return $bookdate;
    }
    $bookdate->close();
}

// 選択した書籍（レコード）を非表示に変更
function delete_record($con, $id)
{
    $sql = "UPDATE books SET display = 0 WHERE id = " . $id . ";";
    var_dump($sql);
    // SQLを実行 
    $con->query($sql);
}

// ログインしていない場合はログイン画面に移動
if ($_SESSION['login'] == false) {
    $_SESSION['error2'] = 'ログインしてください';
    header("Location: login.php");
}

// 何も選択されていないとき
if (empty($_POST['books'])) {
    //⑨SESSIONの「success」に「削除する商品が選択されていません」と設定する。
    $_SESSION['success'] = "削除する商品が選択されていません";
    echo $SESSION = $_POST['sussion'];
    //⑩在庫一覧画面へ遷移する。
    header("Location: zaiko_ichiran.php");
    exit;
}

// SQLに接続
$host = 'localhost';
$user_name = 'zaiko2020_yse';
$db_name = 'zaiko2020_yse';
$password = '2020zaiko';
$mysqli = new mysqli($host, $user_name, $password, $db_name);

if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} else {
    $mysqli->set_charset('utf8');
}

// 削除画面で「はい」が押されたら実行
if (!empty($_POST['add'])) {
    if ($_POST['add'] == 'ok') {
        // 繰り返す
        foreach ($_POST['id'] as $id) {
            var_dump($id);
            delete_record($mysqli, $id);
        }
        $_SESSION['success'] = '削除が完了しました。';
        header("Location: zaiko_ichiran.php");
    }
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
    <title>商品削除</title>
</head>

<body>
    <div id="header">
        <h1>商品削除</h1>
    </div>

    <div id="menu">
        <nav>
            <ul>
                <li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
            </ul>
        </nav>
    </div>

    <form action="delete_product.php" method="post">
        <div id="pagebody">

            <div id="error">
                <?php
                if (isset($_SESSION["error"])) {
                    echo $_SESSION["error"];
                }
                ?>
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
                        </tr>
                    </thead>
                    <?php

                    foreach ($_POST['books'] as $book) {
                        $getId_id = getById($book, $mysqli)->fetch_assoc();

                        // 選択した書籍の在庫が1以上な場合は「在庫があります」とエラーを表示する
                        if ($getId_id["stock"] >= 1) {

                            // 何回も同じ文を繰り返してしまう...。
                            // var_dump('在庫がある商品が選択されています');
                            $_SESSION['error'] = '在庫がある商品が選択されています';
                        }
                    ?>
                        <input type="hidden" value="<?php echo $getId_id['id']; ?>" name="id[]">
                        <input type="hidden" value="<?php echo $getId_id['books']; ?>" name="books[]">
                        <tr>
                            <td><?php echo $getId_id["id"]; ?></td>
                            <td><?php echo $getId_id["title"]; ?></td>
                            <td><?php echo $getId_id["author"]; ?></td>
                            <td><?php echo $getId_id["salesDate"]; ?></td>
                            <td><?php echo $getId_id["price"]; ?></td>
                            <td><?php echo $getId_id["stock"]; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <div id="kakunin">
                    <p>
                        上記の書籍を削除します。<br>
                        よろしいですか？
                    </p>
                    <button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
                    <button type="submit" id="message" formaction="new_product.php">いいえ</button>
                </div>
            </div>
        </div>
    </form>
    <!-- フッター -->
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>