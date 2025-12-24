<?php
/*
*商品詳細ページ
*/
// 一時バッファー
ob_start();
// Sessionスタート
session_start();
// Constファイル読み込み
require_once '../../include/config/const.php';
// Modelファイル読み込み
require_once '../../include/model/detail_model.php';
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
//ボタンの処理
detailBtn($db);
//詳細の処理
$select_data=detailProcess($db);
// Viewファイル読み込み
include_once '../../include/view/detail_view.php';
// バッファー終了
ob_end_flush();