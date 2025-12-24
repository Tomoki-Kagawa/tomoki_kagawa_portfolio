<?php
/*
*購入履歴
*/
// 一時バッファー
ob_start();
// Sessionスタート
session_start();
// Constファイル読み込み
require_once '../../include/config/const.php';
// Modelファイル読み込み
require_once '../../include/model/history_model.php';
// 共通ファイル読み込み
require_once '../../include/utility/common.php';
// databaseファイル読み込み
require_once '../../include/utility/database.php';
// Sessionファイル読み込み
require_once '../../include/utility/cookie_session.php';
// セッション管理
sessionManagement();
// データベース接続
$db=dbConnection($db_dsn,$db_login_user,$db_password);
// 履歴ページボタン処理
listBtn($db);
// 履歴リスト処理
$select_data=listProcess($db);
// Viewファイル読み込み
include_once '../../include/view/history_view.php';
// バッファー終了
ob_end_flush();