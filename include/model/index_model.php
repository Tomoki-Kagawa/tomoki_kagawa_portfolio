<?php
/*
*index_model.php
*/
/*
* ログインページのボタン処理
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

  //ログインボタンが押された場合データサーバーにinsert
  if(isset($_POST["login_btn"])){
    if(empty($user_name)||empty($password)||$user_name==""||$password==""){
      $select_id="";
      cookieDelete();
      sessionDelete();
      $_SESSION["error_log"]="ユーザー名かパスワードが違います";
      ob_clean();
      header("Location: ./index.php");
      exit();
    }
    else{
      $table_name='ec_user';
      $date =date('Y-m-d H:i:s');
      $user_id="";
      $data=['user_id'=>$user_id,'user_name'=>$user_name,'password'=>$password];
      $join="";
      $select_if=['user_name'=>$user_name];
      $select_data=dbSelect($db,$table_name,$data,$join,$select_if);  
      if(!empty($select_data)){
        $select_id=$select_data[0]["user_id"];
        $select_name=$select_data[0]["user_name"];
        $select_password=$select_data[0]["password"];
        $password_check=password_verify($password,$select_password);
        //パスワードがあっているか
        if($user_name==$select_name&&$password_check==true){
          if($cookie_confirmation==="checked"){ 
            CookieSet($user_name,$password,$cookie_confirmation);
          }
          else{
            cookieDelete();
          }
          sessionSet($select_id,$user_name,$password);
          $table_name="ec_management";
          $data=['user_id'=>$select_id];
          $join='';
          $select_if=['user_id'=>$select_id];
          $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
          foreach($select_data as $row){
            $management_id=$row['user_id'];
          }
          // 管理ユーザーであるかを確認する
          if ($select_id===$management_id) {
            header('Location: ./management.php');
            exit();
          }
          else{
            ob_clean();
            header("Location: ./list.php");
            exit();
          }
        }
        else{
          $cookie_confirmation = "";
          $user_name="";
          $password="";
          cookieDelete();
          sessionDelete();
          $_SESSION["error_log"]="ユーザー名かパスワードが違います";
          ob_clean();
          header("Location: ./index.php");
          exit();
        }
      }
      else{
          $cookie_confirmation = "";
          $user_name="";
          $password="";
          cookieDelete();
          sessionDelete();
          $_SESSION["error_log"]="ユーザー名かパスワードが違います";
          ob_clean();
          header("Location: ./index.php");
          exit();
        }
    }
  }
}
/*
* ログインページの表示
*/
function loginDisplay(){
if($_SERVER['REQUEST_METHOD']!=='POST'&&$_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/index.php'){
    [$user_name,$password,$cookie_confirmation]=cookieManagement();
}
?>
<div class="centerdisplay">  
  <h1 class="section_title">ログイン</h1>
  <form method="post" enctype="multipart/form-data" class="form_flex"> 
    <label for="user_name">ユーザー名<?php if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/registration.php'){echo "<br>5文字以上半角英数字アンダースコア(_)";}?></label><input type="text" id="user_name" name="user_name" value="<?php echo $user_name; ?>"><br>
    <label for="password">パスワード<?php if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/registration.php'){echo "<br>8文字以上半角英数字アンダースコア(_)";}?></label><input type="password" id="password" name="password" value="<?php echo $password; ?>"><br>
    <div>
      <input type="checkbox" name="cookie_confirmation" id="cookie_confirmation" <?php echo $cookie_confirmation?>>
      <label for="cookie_confirmation">次回からログインIDの入力を省略する</label><br>
    </div>
    <input type="submit" name="login_btn" value="ログイン">
    <br>
    <a href="registration.php">新規登録ページへ</a>
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