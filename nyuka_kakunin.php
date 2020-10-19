<?php
/* 
【機能】
入荷で入力された個数を表示する。入荷を実行した場合は対象の書籍の在庫数に入荷数を加
えた数でデータベースの書籍の在庫数を更新する。

【エラー一覧（エラー表示：発生条件）】
なし
*/

//①セッションを開始する
session_start();
function getByid($id, $con)
{
	/* 
	 * ②書籍を取得するSQLを作成する実行する。
	 * その際にWHERE句でメソッドの引数の$idに一致する書籍のみ取得する。
	 * SQLの実行結果を変数に保存する。
	 */
	$sql = "SELECT * FROM books WHERE id = " . $id;
	$bookdate = null;
	if ($bookdate = $con->query($sql)) {
		//③実行した結果から1レコード取得し、returnで値を返す。
		return $bookdate;
	}
	$bookdate->close();
}

function updateByid($id, $con, $total)
{
	/*
	 * ④書籍情報の在庫数を更新するSQLを実行する。
	 * 引数で受け取った$totalの値で在庫数を上書く。
	 * その際にWHERE句でメソッドの引数に$idに一致する書籍のみ取得する。
	 */
	$sql = "UPDATE books SET stock = " . $total . " WHERE id = " . $id;
	$con->query($sql);
}

//⑤SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
// if (/* ⑤の処理を書く */){
// 	//⑥SESSIONの「error2」に「ログインしてください」と設定する。
// 	//⑦ログイン画面へ遷移する。
// }

//⑧データベースへ接続し、接続情報を変数に保存する
$host = 'localhost';
$user_name = 'root';
$db_name = 'zaiko2020_yse';
$password = '';
$mysqli = new mysqli($host, $user_name, $password, $db_name);

if ($mysqli->connect_error) {
	echo $mysqli->connect_error;
	exit();
} else {
	//echo 'ok' . PHP_EOL;
	//⑨データベースで使用する文字コードを「UTF8」にする
	$mysqli->set_charset('utf8');
}

//⑩書籍数をカウントするための変数を宣言し、値を0で初期化する
$book_quantity = 0;

//⑪POSTの「books」から値を取得し、変数に設定する。
foreach ($_POST['books'] as $books) {
	//⑫POSTの「stock」について⑩の変数の値を使用して値を取り出す。
	$update_stock = $_POST['stock'][$book_quantity];

	// 半角数字以外の文字が設定されていないかを「is_numeric」関数を使用して確認する。
	// 半角数字以外の文字が入っていた場合はif文の中に入る。
	// var_dump($update_stock);
	if (!is_numeric($update_stock)) {
		//⑬SESSIONの「error」に「数値以外が入力されています」と設定する。
		$_SESSION['error'] = '数値以外が入力されています';

		//⑭「include」を使用して「nyuka.php」を呼び出す。
		include 'nyuka.php';

		//⑮「exit」関数で処理を終了する。
		exit;
	}

	//⑯「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に⑪の処理で取得した値と⑧のDBの接続情報を渡す。
	$book_data_1 = getByid($books, $mysqli)->fetch_assoc();

	//⑰ ⑯で取得した書籍の情報の「stock」と、⑩の変数を元にPOSTの「stock」から値を取り出し、足した値を変数に保存する。

	$book_total = $book_data_1['stock'] + $_POST['stock'][$book_quantity];

	//⑱ ⑰の値が100を超えているか判定する。超えていた場合はif文の中に入る。
	if ($book_total > 100) {
		//⑲SESSIONの「error」に「最大在庫数を超える数は入力できません」と設定する。
		$_SESSION['error'] = '最大在庫数を超える数は入力できません';

		//⑳「include」を使用して「nyuka.php」を呼び出す。
		include('nyuka.php');

		//㉑「exit」関数で処理を終了する。
		exit;
	}

	//㉒ ⑩で宣言した変数をインクリメントで値を1増やす。
	$book_quantity++;
}

// var_dump($_POST['add']);
// ㉓POSTでこの画面のボタンの「add」に値が入ってるか確認する。
// 値が入っている場合は中身に「ok」が設定されていることを確認する。

if (!empty($_POST['add'])) {
	if ($_POST['add'] == 'ok') {
		//㉔書籍数をカウントするための変数を宣言し、値を0で初期化する。
		$number_of_books_1 = 0;

		//㉕POSTの「books」から値を取得し、変数に設定する。
		foreach ($_POST['books'] as $book) {
			//㉖「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に㉕の処理で取得した値と⑧のDBの接続情報を渡す。
			$book_data_3 = getByid($book, $mysqli)->fetch_assoc();

			//㉗ ㉖で取得した書籍の情報の「stock」と、㉔の変数を元にPOSTの「stock」から値を取り出し、足した値を変数に保存する。
			$book_total_number = $book_data_3['stock'] + $_POST['stock'][$number_of_books_1];

			//㉘「updateByid」関数を呼び出す。その際に引数に㉕の処理で取得した値と⑧のDBの接続情報と㉗で計算した値を渡す。
			updateByid($book, $mysqli, $book_total_number);

			//㉙ ㉔で宣言した変数をインクリメントで値を1増やす。
			$number_of_books_1++;
		}
		//㉚SESSIONの「success」に「入荷が完了しました」と設定する。
		$_SESSION['success'] = '入荷が完了しました。';

		//㉛「header」関数を使用して在庫一覧画面へ遷移する。
		header("Location: http://localhost/yse2020/zaiko_ichiran.php");
	}
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<title>入荷確認</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>

<body>
	<div id="header">
		<h1>入荷確認</h1>
	</div>
	<form action="nyuka_kakunin.php" method="post" id="test">
		<div id="pagebody">
			<div id="center">
				<table>
					<thead>
						<tr>
							<th id="book_name">書籍名</th>
							<th id="stock">在庫数</th>
							<th id="stock">入荷数</th>
						</tr>
					</thead>
					<tbody>
						<?php
						//㉜書籍数をカウントするための変数を宣言し、値を0で初期化する。
						$number_of_books_2 = 0;

						//㉝POSTの「books」から値を取得し、変数に設定する。
						foreach ($_POST['books'] as $post_book) {

							// ㉞「getByid」関数を呼び出し、変数に戻り値を入れる。その際引数に㉜の処理で取得した値と⑧のDBの接続情報を渡す。
							// $book_data = getByid($Number_of_books,$mysqli)->fetch_assoc();
							$book_data_2 = getByid($post_book, $mysqli)->fetch_assoc();
						?>
							<tr>
								<td><?php echo $book_data_2['title']/* ㉟ ㉞で取得した書籍情報からtitleを表示する。 */; ?></td>
								<td><?php echo $book_data_2['stock']/* ㊱ ㉞で取得した書籍情報からstockを表示する。 */; ?></td>
								<td><?php echo $_POST['stock'][$number_of_books_2]/* ㊱ POSTの「stock」に設定されている値を㉜の変数を使用して呼び出す。 */; ?></td>
							</tr>
							<input type="hidden" name="books[]" value="<?php echo $post_book/* ㊲ ㉝で取得した値を設定する */; ?>">
							<input type="hidden" name="stock[]" value='<?php echo $_POST['stock'][$number_of_books_2]/* ㊳POSTの「stock」に設定されている値を㉜の変数を使用して設定する。 */; ?>'>
						<?php
							//㊴ ㉜で宣言した変数をインクリメントで値を1増やす。
							$number_of_books_2++;
						}
						?>
					</tbody>
				</table>
				<div id="kakunin">
					<p>
						上記の書籍を入荷します。<br>
						よろしいですか？
					</p>
					<button type="submit" id="message" formmethod="POST" name="add" value="ok">はい</button>
					<button type="submit" id="message" formaction="nyuka.php">いいえ</button>
				</div>
			</div>
		</div>
	</form>
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>

</html>