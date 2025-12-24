<?php
/*
*購入ページ 
*/
// 一時バッファー
ob_start();
// Sessionスタート
session_start();
// メールファイルの読み込み
require_once '../../htdocs/PHPMailer/src/PHPMailer.php';
require_once '../../htdocs/PHPMailer/src/Exception.php';
require_once '../../htdocs/PHPMailer/src/SMTP.php';
// Constファイル読み込み
require_once '../../include/config/const.php';
// Modelファイル読み込み
require_once '../../include/model/order_model.php';
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
//データベース購入処理
purchaseProcess($db);
// 購入済み一覧リスト処理
$select_data=listProcess($db);
//メール送信関数
emailSend($db,$select_data);
// Viewファイル読み込み
include_once '../../include/view/order_view.php';
// バッファー終了
ob_end_flush();