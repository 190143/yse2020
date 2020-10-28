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

                    <input type="hidden" value="" name="books[]">

                <tr>
                    <td></td>
                    <td><input type="text" name='title[]' size='5' maxlength='11' required></td>
                    <td><input type="text" name='author[]' size='5' maxlength='11' required></td>
                    <td><input type="text" name='salesDate[]' size='5' maxlength='11' required></td>
                    <td><input type="text" name='price[]' size='5' maxlength='11' required></td>
                    <td><input type="text" name='stock[]' size='5' maxlength='11' required></td>
                    <td><input type='text' name='in_stock[]' size='5' maxlength='11' required></td>
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