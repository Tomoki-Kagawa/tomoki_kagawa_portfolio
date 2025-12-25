<?php
/*
* 商品登録ページ
*/
// 一時バッファー
ob_start();
// Sessionスタート
session_start();
// Constファイル読み込み
require_once __DIR__ . '/../../include/config/const.php';
// Modelファイル読み込み
require_once __DIR__ . '/../../include/model/management_model.php';
// 共通ファイル読み込み
require_once __DIR__ . '/../../include/utility/common.php';
// databaseファイル読み込み
require_once __DIR__ . '/../../include/utility/database.php';
// Sessionファイル読み込み
require_once __DIR__ . '/../../include/utility/cookie_session.php';
// データベース接続
$db=dbConnection($db_dsn,$db_login_user,$db_password);
// 管理者
managerConfirmation($db);
// セッション管理
sessionManagement();
// 登録ボタンの処理
formManagementBtn($db);
//表のボタンを押した時
managemenBtn($db);
//表の処理
$select_data=managemenProcess($db);
// Viewファイル読み込み
include_once __DIR__ . '/../../include/view/management_view.php';
//　Script.読み込み
?><script src="./assets/script.js"></script>
<?php
// バッファー終了
ob_end_flush();
?>