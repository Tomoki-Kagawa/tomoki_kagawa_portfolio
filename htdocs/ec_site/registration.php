<?php
/*
*ユーザー登録 
*/
// 一時バッファー
ob_start();
// Sessionスタート
session_start();
// Constファイル読み込み
require_once __DIR__ . '/../../include/config/const.php';
// Modelファイル読み込み
require_once __DIR__ . '/../../include/model/registration_model.php';
// 共通ファイル読み込み
require_once __DIR__ . '/../../include/utility/common.php';
// databaseファイル読み込み
require_once __DIR__ . '/../../include/utility/database.php';
// Sessionファイル読み込み
require_once __DIR__ . '/../../include/utility/cookie_session.php';
// データベース接続
$db=dbConnection($db_dsn,$db_login_user,$db_password);
//登録ボタンを押した時
loginBtn($db);
// Viewファイル読み込み
include_once __DIR__ . '/../../include/view/registration_view.php';
//　Script.読み込み
?>
<script src="./assets/script.js"></script>
<?php
// バッファー終了
ob_end_flush();
?>