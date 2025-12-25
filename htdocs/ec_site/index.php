<?php
/*
*ログインページ
*/
//一時バッファー
ob_start();
//Sessionスタート
session_start();
// Constファイル読み込み
require_once __DIR__ . '/../../include/config/const.php';
// Modelファイル読み込み
require_once __DIR__ . '/../../include/model/index_model.php';
//共通ファイル読み込み
require_once __DIR__ . '/../../include/utility/common.php';
//databaseファイル読み込み
require_once __DIR__ . '/../../include/utility/database.php';
//Sessionファイル読み込み
require_once __DIR__ . '/../../include/utility/cookie_session.php';
//ログイン時のセッション管理
loginManegement();
//データベース接続
$db=dbConnection($db_dsn,$db_login_user,$db_password);
//ログインボタン
loginBtn($db);
// Viewファイル読み込み
include_once __DIR__ . '/../../include/view/index_view.php';
// バッファー終了
ob_end_flush();