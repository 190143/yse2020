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
        include 'zaiko_kakunin.php';
        exit;
    }
    $book_data_1 = getByid($book, $mysqli)->fetch_assoc();
    $book_total = $book_data_1['stock'] - $_POST['stock'][$index];
    if ($book_total < 0) {
        $_SESSION['error'] = '在庫数が0ではない';
        include('zaiko_kakunin.php');
        exit;
    }
    $index++;
}
if (!empty($_POST['add'])) {
	if ($_POST['add'] == 'ok') {
		$number_of_books_1 = 0;
		foreach ($_POST['books'] as $book) {
			$book_data_3 = getByid($book, $mysqli)->fetch_assoc();
			$book_total_number = $book_data_3['stock'] - $_POST['stock'][$number_of_books_1];
			updateByid($book, $mysqli, $book_total_number);
			$index++;
		}
	}
	$_SESSION['success'] = '削除しました';
	header("Location: zaiko_ichiran.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
    <title>商品削除</title>
</head>

<body>
    <div id="header">
        <h1>商品削除</h1>
    </div>
    <form action="syukka_kakunin.php" method="post" id="test">
        <div id="pagebody">
            <div id="center">
                <table>
                    <thead>
                        <tr>
                            <th id="book_name">書籍名</th>
                            <th id="stock">在庫数</th>
                            <th id="stock">削除数</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $index = 0;
                        foreach ($_POST['books'] as $book_1) {
                            $book_2 = getByID($book_1, $mysqli)->fetch_assoc();
                        ?>
                            <tr>
                                <td><?php echo $book_2['title']; ?></td>
                                <td><?php echo $book_2['stock']; ?></td>
                                <td><?php echo $_POST['stock'][$index] ?></td>
                            </tr>
                            <input type="hidden" name="books[]" value="<?php echo $book_1; ?>">
                            <input type="hidden" name="stock[]" value='<?php echo $_POST['stock'][$index]; ?>'>
                        <?php
                            $index++;
                        }
                        ?>
                    </tbody>
                </table>
                <div id="kakunin">
                    <p>
                        上記の書籍を削除します。<br>
                        よろしいですか？
                    </p>
                    <button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
                    <button type="submit" id="message" formaction="syukka.php">いいえ</button>
                </div>
            </div>
        </div>
    </form>
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>
</html>