<?php
session_start();

function getByid($id, $con)
{
    $sql = "SELECT * FROM books WHERE id = " . $id;
    $bookdate = null;
    if ($bookdate = $con->query($sql)) {
        return $bookdate;
    }
    $bookdate->close();
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
$index = 0;
foreach ($_POST['books'] as $book) {
    $update_stock = $_POST['stock'][$index];
    if (!is_numeric($update_stock)) {
        $_SESSION['error'] = '数値以外が入力されています';
        include 'syukka.php';
        exit;
    }

    $book_data_1 = getByid($book, $mysqli)->fetch_assoc();
    $book_total = $book_data_1['stock'] - $_POST['stock'][$index];
    if ($book_total < 0) {
        $_SESSION['error'] = '出荷する個数が在庫数を超えています';
        include('syukka.php');
        exit;
    }
}

if (!empty($_POST['add'])) {
    if ($_POST['add'] == 'ok') {
        $number_of_books_1 = 0;
        foreach ($_POST['books'] as $book) {
            $book_data_3 = getByid($book, $mysqli)->fetch_assoc();
            $book_total_number = $book_data_3['stock'] - $_POST['stock'][$number_of_books_1];
            updateByid($book, $mysqli, $book_total_number);
        }
    }
    $_SESSION['success'] = '出荷が完了しました。';
    header("Location: zaiko_ichiran.php");
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>出荷</title>
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>

<body>
    <div id="header">
        <h1>出荷</h1>
    </div>

    <div id="menu">
        <nav>
            <ul>
                <li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
            </ul>
        </nav>
    </div>

    <form action="delete_prodct.php" method="post">
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
                            <th id="in">出荷数</th>
                        </tr>
                    </thead>
                    <?php
                    foreach ($_POST['books'] as $book) {
                        $getId_id = getId($book, $mysqli)->fetch_assoc();
                    ?>
                        <input type="hidden" value="<?php echo $getId_id['id']/* ⑰ ⑯の戻り値からidを取り出し、設定する */; ?>" name="books[]">
                        <tr>
                            <td><?php echo $getId_id["id"]; ?></td>
                            <td><?php echo $getId_id["title"]; ?></td>
                            <td><?php echo $getId_id["author"]; ?></td>
                            <td><?php echo $getId_id["salesDate"]; ?></td>
                            <td><?php echo $getId_id["price"]; ?></td>
                            <td><?php echo $getId_id["stock"]; ?></td>
                            <td><input type='text' name='stock[]' size='5' maxlength='11' required></td>
                        </tr>
                    <?php
                    }
                    ?>
                </table>
                <button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">確定</button>
            </div>
        </div>
    </form>
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>