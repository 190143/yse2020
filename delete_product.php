<?php
if ((function_exists('session_status')
&& session_status() !== PHP_SESSION_ACTIVE) || !session_id()) {
session_start();
}

function getByid($id, $con)
{
    $sql = "SELECT * FROM books WHERE id = " . $id;
    $bookdate = null;
    if ($bookdate = $con->query($sql)) {
        return $bookdate;
    }
    $bookdate->close();
}

function delete_record()
{
    $sql = "UPDATE books SET display = 0 WHERE id = ". $_POST['id']."";
}

function updateByid($id, $con, $total)
{
    $sql = "UPDATE books SET stock = " . $total . " WHERE id = " . $id;
    $con->query($sql);
}

if ($_SESSION['login'] == false) {
    $_SESSION['error2'] = 'ログインしてください';
    header("Location: login.php");
}

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
$_SESSION['error'] = '数値以外が入力されています';
$_SESSION['success'] = '入荷が完了しました。';
//header("Location: zaiko_ichiran.php");

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

    <form action="syukka_kakunin.php" method="post">
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
                    ?>
                        <input type="hidden" value="<?php echo $getId_id['id']; ?>" name="books[]">
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
                <button type="submit" id="kakutei" formmethod="POST" name="decision" value="1" formaction="delete_record">はい</button>
                <button type="submit" id="kakutei" formmethod="POST" name="decision" value="2" formaction="zaiko_ichiran">いいえ</button>
            </div>
        </div>
    </form>
    <!-- フッター -->
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>