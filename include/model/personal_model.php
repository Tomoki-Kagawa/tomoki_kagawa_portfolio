<?php
/*
*personal_model.php
*/
/*
*個人情報入力フォームボタン処理
*/
function personalBtn($db){
  $user_id="";$personal_name="";$tel="";$address="";$email_address="";
  
  $user_id=$_SESSION['user_id'];
  
  if(isset($_POST['personal_name'])){
    $personal_name = htmlspecialchars($_POST['personal_name'], ENT_QUOTES, 'UTF-8');
  }
  if(isset($_POST['tel'])){
    $tel = htmlspecialchars($_POST['tel'], ENT_QUOTES, 'UTF-8');
  }
  if(isset($_POST['email_address'])){
    $email_address = htmlspecialchars($_POST['email_address'], ENT_QUOTES, 'UTF-8');
  }  
  if(isset($_POST['address'])){
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
  }

  if(isset($_POST['personal_btn'])){
    $table_name='ec_personal';
    $date =date('Y-m-d H:i:s');
    $user_id=$_SESSION['user_id'];
    $delete_if=['user_id' => $user_id];
    dbDelete($db,$table_name,$delete_if);
    $data=['user_id' => $user_id,'personal_name'=>$personal_name,'tel'=>$tel,'address'=>$address,'email_address'=>$email_address,'create_date'=>$date,'update_date'=>$date];
    dbInsert($db,$table_name,$data);
    $_SESSION["log"]="個人情報を更新しました";
    ob_clean();
    header("Location: ./personal.php");
    exit();
  }
}
/*
*個人情報入力フォーム処理
*/
function personalProcess($db){
  $user_id="";$personal_name="";$tel="";$address="";$email_address="";
  
  $user_id=$_SESSION['user_id'];
  
  if(isset($_POST['personal_name'])){
    $personal_name = htmlspecialchars($_POST['personal_name'], ENT_QUOTES, 'UTF-8');
  }
  if(isset($_POST['tel'])){
    $tel = htmlspecialchars($_POST['tel'], ENT_QUOTES, 'UTF-8');
  }
  if(isset($_POST['email_address'])){
    $email_address = htmlspecialchars($_POST['email_address'], ENT_QUOTES, 'UTF-8');
  }  
  if(isset($_POST['address'])){
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
  }

  $table_name="ec_personal";
  $data=['personal_name'=>$personal_name,'tel'=>$tel,'address'=>$address,'email_address'=>$email_address];
  $join="";
  $select_if=['user_id'=>$user_id];
  $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
  return $select_data;
}
/*
*個人情報入力フォーム表示 
*/
function personalDisplay($select_data){
  foreach($select_data as $row){
    $personal_name=$row["personal_name"];
    $tel=$row["tel"];
    $address=$row["address"];
    $email_address=$row["email_address"];    
  }
  if(empty($personal_name)||empty($tel)&&empty($address)&&empty($email_address)){
    $personal_name="";$tel="";$address="";$email_address="";
  }
  ?>
  <div class="management_form">
    <h3>個人情報登録フォーム</h3>
    <form method="post" enctype="multipart/form-data">
      <div><label for="tel" class="labelsize">名前</label><input type="text" class="input_right" id="personal_name"  name="personal_name" value="<?php echo $personal_name; ?>"></div>
      <div><label for="tel" class="labelsize">電話番号</label><input type="text" class="input_right" id="tel" name="tel" value="<?php echo $tel; ?>"></div>
      <div><label for="email_address" class="labelsize">メールアドレス</label><input type="text" class="input_right" id="email_address" name="email_address" value="<?php echo $email_address; ?>"></div>
      <div><label for="address" class="labelsize">住所</label><input type="text" class="input_right" id="address" name="address" value="<?php echo $address; ?>"></div>
      <input class="btn" name="personal_btn" type="submit" value="登録">
   </form>
  </div>
  <?php
}