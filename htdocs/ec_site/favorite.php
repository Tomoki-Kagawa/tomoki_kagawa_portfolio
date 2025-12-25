<?php
/*
*お気に入りページ 
*/
// 一時バッファー
ob_start();
// Sessionスタート
session_start();
// Constファイル読み込み
require_once __DIR__ . '/../../include/config/const.php';
// Modelファイル読み込み
require_once __DIR__ . '/../../include/model/favorite_model.php';
// 共通ファイル読み込み
require_once __DIR__ . '/../../include/utility/common.php';
// databaseファイル読み込み
require_once __DIR__ . '/../../include/utility/database.php';
// Sessionファイル読み込み
require_once __DIR__ . '/../../include/utility/cookie_session.php';
// セッション管理
sessionManagement();
// データベース接続
$db=dbConnection($db_dsn,$db_login_user,$db_password);
// お気に入りページボタン処理
listBtn($db);
// お気に入りリスト処理
$select_data=listProcess($db);
// Viewファイル読み込み
include_once __DIR__ . '/../../include/view/favorite_view.php';
// バッファー終了
ob_end_flush();