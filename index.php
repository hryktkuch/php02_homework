<?php
require_once('funcs.php');

//1.  DB接続します
try {
  //ID:'root', Password: xamppは 空白 ''
  $pdo = new PDO($dsn, $user, $password);
  $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  exit('DBConnectError:'.$e->getMessage());
}

//２．データ取得SQL作成
$stmt = $pdo->prepare("SELECT * FROM memo");
$status = $stmt->execute();

//３．データ表示
$view="";
$num = 0;
if ($status==false) {
    //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .= '<div class="memoCollection" id="memo' . $num . '">';
    $view .= '<div class="memoCollectionSingle" id="content' . $num  . '">' . h($result['content']) . '</div>';
    $view .= '<div style="font-size:x-small">' . h($result['date']) . '</div>';
    $view .= '</div>';
    $num++;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memo App</title>
    <link href="styles.css" rel="stylesheet" />
</head>
<body>
    <div class="app">
        <div class="header">
            <h1 style="line-height:48px;font-size:large;">メモアプリ</h1>
        </div>
        <div class="main">
            <div class="sidebar">
                <?= $view ?>
            </div>
            <div class="content">
                <div class="flex-content">
                    <textarea form="form1" name="textcontent" type="text" id="textcontent" style="width:100%;height:100%;border:none;">ここに選択したメモが表示されるよ
                    </textarea>
                </div>
                <div class="fixed-content">
                    <span id="change" hidden>文章が変更されています</span>
                    <form id="form1" action="?" method="post">
                        <button id="new" type="submit" formaction="new.php" disabled>新しく保存</button>
                        <button id="save" type="submit" formaction="save.php" disabled>上書き保存</button>
                    </form>
                    <button id="discard">変更を破棄</button>
                    <input id="active" form="form1" name="active" hidden></input>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        statusclear = function() {
            for (let j=0; j<num; j++) {
                    $('#memo'+j).css('background-color', 'transparent');
            }
            $('#change').attr({'hidden': 'hidden'});
            $('#new').attr({'disabled': 'disabled'});
            $('#save').attr({'disabled': 'disabled'});
        }
        changedstatus = function() {
            $('#change').removeAttr('hidden');
            $('#new').removeAttr('disabled');
            $('#save').removeAttr('disabled');
        }
        var num = <?= $num ?>;
        //console.log(num);
        for (let i = 0; i < num; i++) {
            $('#memo'+i).on('click', function() {
                //console.log('memo'+i);
                $('#textcontent').val($('#content'+i).text());
                statusclear();
                $('#memo'+i).css('background-color', 'lightgray');
                $('#active').val(i);
            });
        }
        $('#textcontent').on('change', function() {
            changedstatus();
        });
        $('#discard').on('click', function() {
            $('#textcontent').val('');
            statusclear();
        });
    </script>
</body>
</html>