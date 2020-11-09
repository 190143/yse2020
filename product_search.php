<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/ichiran.css" type="text/css" />
    <title>商品検索</title>
</head>

<body>
    <!-- ヘッダ -->
    <div id="header">
        <h1>商品検索</h1>
    </div>

    <!-- メニュー -->
    <div id="menu">
        <nav>
            <ul>
                <li><a href="zaiko_ichiran.php?page=1">書籍一覧</a></li>
            </ul>
        </nav>
    </div>

    <form action="search_result.php" method="post">
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
                <table>
                    <thead>
                        <tr>
                            <th id="id">キーワード</th>
                            <th id="book_name">発売年代</th>
                            <th id="author">金額</th>
                            <th id="salesDate">在庫数</th>
                        </tr>
                    </thead>
                    <?php

                    ?>
                    <tr>
                        <!-- キーワード -->
                        <td><input type='text' name='keyword' size='5'></td>
                        <!-- 発売年代 -->
                        <td>
                            <select name="salesDate">
                                <option value="1"></option>
                                <option value="1970">1970年代</option>
                                <option value="1980">1980年代</option>
                                <option value="1990">1990年代</option>
                                <option value="2000">2000年代</option>
                                <option value="2010">2010年代</option>
                                <option value="2020">2020年代</option>
                            </select>
                        </td>
                        <!-- 金額 -->
                        <td>
                            <select name="price">
                                <option value="1"></option>
                                <option value="400">400円代</option>
                                <option value="500">500円代</option>
                                <option value="600">600円代</option>
                                <option value="700">700円代</option>
                                <option value="800">800円代</option>
                                <option value="900">900円代</option>
                                <option value="1000">1000円代</option>
                                <option value="2000">2000円代</option>
                            </select>
                        </td>
                        <!-- 在庫数 -->
                        <td>
                            <select name="stock">
                                <option value="1"></option>
                                <option value="10">10冊未満</option>
                                <option value="20">20冊未満</option>
                                <option value="30">30冊未満</option>
                                <option value="40">40冊未満</option>
                                <option value="50">50冊未満</option>
                                <option value="noMore">50冊以上</option>
                            </select>
                        </td>
                    </tr>
                    <?php

                    ?>
                </table>
                <button type="submit" id="kakutei" formmethod="POST" name="decision" value="1">検索</button>
            </div>
        </div>
    </form>
    <!-- フッター -->
    <div id="footer">
        <footer>株式会社アクロイト</footer>
    </div>
</body>

</html>