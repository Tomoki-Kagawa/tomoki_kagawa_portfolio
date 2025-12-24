<?php
/*
*registration_model.php
*/
/*
* ログインページと登録ページの表示
*/
function loginBtn($db){
  // 値の初期化
  $user_name="";
  $password=""; 
  $cookie_confirmation = "";

  if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST["login_btn"])&&!isset($_POST['cookie_confirmation'])){
      cookieDelete();
    }
    //user_namePOST  
    if(isset($_POST['user_name'])){
      $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES, 'UTF-8');
    }
    //passwordPOST
    if(isset($_POST['password'])){
      $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');
    }
    //cookie_confirmationPOST 
    if(isset($_POST['cookie_confirmation'])){
        $cookie_confirmation="checked";
    }
  }
  if($_SERVER['REQUEST_METHOD']!=='POST'&&$_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/index.php'){
    [$user_name,$password,$cookie_confirmation]=cookieManagement();
  }

  //登録ボタンが押された場合データサーバーにinsert
  if(isset($_POST["registration_btn"])){
    $table_name='ec_user';
    $date =date('Y-m-d H:i:s');
    if(validationCheck($user_name,$password)){
      $password_hash=passwordHash($password);
      $data=['user_name'=>$user_name,'password'=>$password_hash,'create_date'=>$date,'update_date'=>$date];
      dbInsert($db,$table_name,$data);
      $_SESSION["log"]="登録しました";
      $_SESSION['error_log']="";
      $user_name="";
      $password="";
      $password_hash="";
      ob_clean();
      header("Location: ./index.php");
      exit();
    }
    else{
      $user_name="";
      $password="";
      $password_hash="";
      ob_clean();
      header("Location: ./registration.php");
      exit();
    }
  }
}
/*
* ログインページと登録ページの表示
*/
function loginDisplay(){
if($_SERVER['REQUEST_METHOD']!=='POST'&&$_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/index.php'){
  [$user_name,$password,$cookie_confirmation]=cookieManagement();
}
?>
  <div class="centerdisplay">
    <h1 class="section_title">ユーザー登録</h1>
    <form method="post" enctype="multipart/form-data" class="form_flex" id="registration_form">
      <label for="user_name">ユーザー名<?php if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/registration.php'){echo "<br>5文字以上半角英数字アンダースコア(_)";}?></label><input type="text" id="user_name" name="user_name" value="<?php echo $user_name; ?>">
      <div id="user_name_error" class="error-message"></div>
      <?php if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/index.php'){?><br><?php } ?>
      <label for="password">パスワード<?php if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/registration.php'){echo "<br>8文字以上半角英数字アンダースコア(_)";}?></label><input type="password" id="password" name="password" value="<?php echo $password; ?>">
      <div id="password_error" class="error-message"></div>
      <?php if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/index.php'){?><br><?php } ?>
      <br>
      <input type="hidden" id="js_vc_password" name="js_vc_password" >
      <input type="submit" name="registration_btn" id="registration_btn" value="登録">
      <br>
      <a href="index.php">ログインページへ</a>
    </form>
  </div>
  <?php
}
//パスワードのハッシュ化
function passwordHash($password){
  $password_hash=password_hash($password, PASSWORD_DEFAULT);
  return $password_hash; 
}
//パスワードのハッシュの照合
function hashCheck($password,$password_hash){
  return password_verify($password,$password_hash);
}
/*
* バリデーション
*/
function validationCheck($user_name,$password){
  if(preg_match('/^[a-zA-Z0-9_]{5,}$/',$user_name)&&preg_match('/^[a-zA-Z0-9_]{8,}$/',$password)){
    return true;
  }
  elseif(!preg_match('/^[a-zA-Z0-9_]{5,}$/',$user_name)&&preg_match('/^[a-zA-Z0-9_]{8,}$/',$password)){
    $_SESSION["error_log"]="ユーザー名は5文字以上かつ半角英数字かアンダースコア(_)にしてください";
    return false;
  }
  elseif(preg_match('/^[a-zA-Z0-9_]{5,}$/',$user_name)&&!preg_match('/^[a-zA-Z0-9_]{8,}$/',$password)){
    $_SESSION["error_log"]="パスワードは8文字以上かつ半角英数字かアンダースコア(_)にしてください";
    return false;
  }
  else{
    $_SESSION["error_log"]="ユーザー名は5文字以上パスワードは8文字以上かつ半角英数字かアンダースコア(_)にしてください";
    return false;
  }
}