<?php
require_once('funcs.php');

$textcontent = $_POST['textcontent'];

//1.  DB接続します
try {
  //ID:'root', Password: xamppは 空白 ''
  $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
  exit('DBConnectError:'.$e->getMessage());
}

//２．データ取得SQL作成
$stmt = $pdo->prepare("INSERT INTO memo (id, content, date) VALUES (NULL, :content, now())");
$stmt->bindValue(':content', $textcontent, PDO::PARAM_STR);

$status = $stmt->execute();

if($status === false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit('ErrorMessage:'.$error[2]);
  }else{
    //５．index.phpへリダイレクト
  header('location: index.php');
}
?>