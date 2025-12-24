<?php
/*
*cookie_session.php
*/
/*
*ログイン確認 
*/
function loginManegement(){ 
  // ログイン中のユーザーであるかを確認する
  if (isset($_SESSION['user_name'])&&isset($_SESSION['password'])&&$_SESSION['user_name']!==""&&$_SESSION['password']!=="") {
    // ログイン中である場合は、list.phpにリダイレクトする
    header('Location: ./list.php');
    exit();
  }
}
/*
* セッション管理
*/
function sessionManagement(){        
    // ログイン中のユーザーであるかを確認する
    if (empty($_SESSION['user_name'])||empty($_SESSION['password'])) {
      // ログイン中ではない場合は、ログアウトする。
      $_SESSION["error_log"]="セッションエラーが発生しました<br>ログインし直してください";
      header('Location: ./logout.php');
      exit();
    } 
}

/*
* cookie管理
*/
function cookieManagement(){
  //cookieに値がある場合、変数に格納する
    if (!empty($_COOKIE['cookie_confirmation'])&&!empty($_COOKIE['user_name'])&&!empty($_COOKIE['password'])) {
      $cookie_confirmation = "checked";
      $user_name= $_COOKIE['user_name'];
      $password= $_COOKIE['password'];
    } 
    else{
      $cookie_confirmation = "";
      $user_name= "";
      $password= "";
    }
    $cookie=[$user_name,$password,$cookie_confirmation];
    return $cookie;
  }
/* 
*セッション設定
*/
function sessionSet($user_id,$user_name,$password){  
  if(!empty($user_id)&&!empty($user_name)&&!empty($password)){ 
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] =  $user_name;
    $_SESSION['password'] =  $password;
  }
  else{
    sessionDelete();
  }
}
/*
*セッション削除 
*/
function sessionDelete(){
  $_SESSION=[];
  session_unset();
  session_destroy();
  session_start();
}
/*
*cookie設定 
*/
function CookieSet($user_name,$password,$cookie_confirmation){
  //Cookieの保存期間
  define('EXPIRATION_PERIOD', 30);
  $cookie_expiration = time() + EXPIRATION_PERIOD * 60*60*24;
  // ユーザー名の保存チェックがされている場合はCookieを保存
  if (isset($user_name)&&isset($password)&&$cookie_confirmation==="checked") {
    setcookie('cookie_confirmation', $cookie_confirmation, $cookie_expiration, "/");
    setcookie('user_name', $user_name, $cookie_expiration, "/");
    setcookie('password', $password, $cookie_expiration, "/");
  } 
}
/*
* cookieの削除
*/
function cookieDelete(){
  // チェックされていない場合はCookieを削除する
    setcookie('cookie_confirmation', '', time() - 3600, "/");
    setcookie('user_name', '', time() - 3600, "/");
    setcookie('password', '', time() - 3600, "/");
}

/*
* ログアウト時のセッション終了
*/
function sessionEnd(){
  // セッション名を取得する
  $session = session_name();
  // セッション変数を削除
  $_SESSION = [];
  // セッションID（ユーザ側のCookieに保存されている）を削除
  if (isset($_COOKIE[$session])) {
    // cookie削除
    setcookie($session, '', time() - 3600, '/');
  }
  session_destroy();
}
