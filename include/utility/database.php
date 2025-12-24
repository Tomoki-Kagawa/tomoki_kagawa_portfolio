<?php
/*
*database.php
*/
/*
* データベース接続を行う
*/
function dbConnection($db_dsn,$db_login_user,$db_password) {
  try{
    // PDOインスタンスの生成
    $db = new PDO($db_dsn,$db_login_user,$db_password);
  } catch (PDOException $e) {
    $_SESSION['error_log']=$e->getMessage();
    exit();
  }
  return $db;
}
