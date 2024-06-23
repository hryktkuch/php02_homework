<?php
//共通に使う関数を記述
//XSS対応（ echoする場所で使用！それ以外はNG ）
function h($str){
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$host = "mysql57.hryktkuch.sakura.ne.jp";
$dbName = "hryktkuch_memoapp";
$user = "hryktkuch";

$dsn = "mysql:host={$host};dbname={$dbName};charser=utf8";
?>