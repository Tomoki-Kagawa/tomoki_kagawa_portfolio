<?php
/*
*management_model.php
*/
/*
* 商品登録ページのボタン処理
*/
function formManagementBtn($db){
  if(isset($_POST['product_name'])){
    $product_name = htmlspecialchars($_POST['product_name'], ENT_QUOTES, 'UTF-8');
  }  
  if(isset($_POST['product_description'])){
    $product_description = htmlspecialchars($_POST['product_description'], ENT_QUOTES, 'UTF-8');
  }
  if(isset($_POST['price'])){
    $price = htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8');
  }
  if(isset($_POST['stock_qty'])){
    $stock_qty = htmlspecialchars($_POST['stock_qty'], ENT_QUOTES, 'UTF-8');
  }
  if(isset($_FILES['image']['name'])){
    $image = $_FILES['image']['name'];
    $image_save = './image/'.basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'],$image_save);
  }
  if(isset($_POST['public_flg'])){
    $public_flg=$_POST['public_flg'];
  }
  //登録ボタンが押された時
  if(isset($_POST["management_btn"])){
    if($product_name!==""&&$product_description!==""&&$price!==""&&$image!==""&&$stock_qty!==""){
        $table_name='ec_product';
        $date =date('Y-m-d H:i:s');
        $data=['product_name'=>$product_name,'product_description'=>$product_description,'price'=>$price,'public_flg'=>$public_flg,'create_date'=>$date,'update_date'=>$date];
        dbInsert($db,$table_name,$data);
        $product_id="";
        $data=["product_id"=>$product_id];
        $join="";
        $select_if=['product_name'=>$product_name];
        $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
        $select_id=$select_data[0]["product_id"];
        $table_name='ec_stock';
        $data=['product_id'=>$select_id,'stock_qty'=>$stock_qty,'create_date'=>$date,'update_date'=>$date];
        dbInsert($db,$table_name,$data);
        $table_name='ec_image';
        $data=['product_id'=>$select_id,'image_name'=>$image,'create_date'=>$date,'update_date'=>$date];
        dbInsert($db,$table_name,$data);
        $_SESSION["log"]=$product_name."を登録しました";
        ob_clean();
        header("Location: ./management.php");
        exit();
    }
    else{
      $_SESSION["log"]="空白があると登録できません";
      ob_clean();
      header("Location: ./management.php");
      exit();
    }
  }
}
/*
* 商品登録ページの表示
*/
function formManagementDisplay(){
?>
  <div class="management_form" >
    <h3>商品登録フォーム</h3>
    <form method="post" enctype="multipart/form-data" id="management_form">
       <div><label for="product_name" class="labelsize">商品名</label><input type="text" id="product_name" class="text_width" name="product_name"></div>
       <div id="name_error" class="error-message">商品名を入力してください。</div>
       <div><label for="product_description" class="labelsize">説明文</label><textarea id="product_description" class="text_width" name="product_description"></textarea></div>
       <div id="description_error" class="error-message">説明文を入力してください。</div>
       <div><label for="price" class="labelsize">価格(円)</label><input type="text" id="price" class="text_width" name="price"></div>
       <div id="price_error" class="error-message">価格は1以上の整数を入力してください。</div>
       <div><label for="count" class="labelsize">在庫数(個)</label><input type="text" id="stock_qty" name="stock_qty" class="text_width" ></div>
       <div id="stock_qty_error" class="error-message">数量は1以上の整数を入力してください。</div>
       <div><label for="image_file" class="labelsize">商品画像</label><input type="file" name="image" class="image" id="image" ></div>
       <div id="image_error" class="error-message">JPEG または PNG 形式の画像を選択してください。</div>
       <div><label for="public_flg" class="labelsize">公開状態</label>
       <label><input type="radio" name="public_flg" class="public_flg"  value="1" checked>公開にする</label>
       <label><input type="radio" name="public_flg" class="public_flg"  value="0">非公開にする</label>
      </div>  
      <input class="btn" name="management_btn" type="submit" value="登録" id="management_btn">
    </form>
  </div>
  <?php
}

/*
* 表のボタン処理
*/
function managemenBtn($db){
  if(isset($_POST['product_name'])){
    $product_name = htmlspecialchars($_POST['product_name'], ENT_QUOTES, 'UTF-8');
  }
  //公開状態変更ボタンを押した時  
  if(isset($_POST['public_flg_btn'])){
    $product_id = $_POST['product_id'];
    $public_flg = $_POST['public_flg'];
    $table_name="ec_product";
    $date =date('Y-m-d H:i:s');
    $data=['public_flg'=>$public_flg,'update_date'=>$date];
    $update_if=['product_id'=>$product_id];
    dbUpdate($db,$table_name,$data,$update_if);
    $_SESSION["log"]="公開状態を変更しました";
    ob_clean();
    header("Location: ./management.php");
    exit(); 
  }
  //削除ボタンを押した時
  if(isset($_POST['delete_btn'])){
    $product_id = $_POST['product_id'];
    $table_name="ec_image";
    $delete_if=['product_id' => $product_id];
    dbDelete($db,$table_name,$delete_if);
    $table_name="ec_stock";
    dbDelete($db,$table_name,$delete_if);
    $table_name="ec_product";
    dbDelete($db,$table_name,$delete_if);
    $_SESSION["log"]="削除しました";
    ob_clean();
    header("Location: ./management.php");
    exit();
  }
  //ストック変更ボタンを押した時
  if(isset($_POST['stock_change_btn'])){
    $product_id = $_POST['product_id'];
    $stock_qty = $_POST['stock_qty'];  
    $table_name="ec_stock";
    $date =date('Y-m-d H:i:s');
    $data=['stock_qty'=>$stock_qty,'update_date'=>$date];
    $update_if=['product_id'=>$product_id];
    dbUpdate($db,$table_name,$data,$update_if);
     $_SESSION["log"]=$product_name."を".$stock_qty."個に変更しました";
    ob_clean();
    header("Location: ./management.php");
    exit();
  }
}
/*
* 表の処理
*/
function managemenProcess($db){
  $table_name="ec_product";
  $product_id="";$product_name="";$product_description="";$price="";$public_flg="";$stock_qty="";$image_name="";
  $data=['ec_product.product_id'=>$product_id,'ec_product.product_name'=>$product_name,'ec_product.product_description'=>$product_description,'ec_product.price'=>$price,'ec_product.public_flg'=>$public_flg,'ec_stock.stock_qty'=>$stock_qty,'ec_image.image_name'=>$image_name ];
  $join=[['ec_stock','ec_product.product_id','ec_stock.product_id'],['ec_image','ec_product.product_id','ec_image.product_id']];
  $select_if="";
  $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
  return $select_data;
}
/*
* 表の表示
*/
function managementDisplay($select_data){
  ?>
  <table>
    <tr>
      <th>商品番号</th>
      <th>商品画像</th>
      <th>商品名</th>
      <th>説明文</th>
      <th>価格(円)</th>
      <th>在庫数(個)</th>
      <th>公開状態</th>
      <th>削除</th>
    </tr>
    <?php
    foreach($select_data as $row) {
    $product_id=$row["product_id"];
    $image_name=$row["image_name"];
    $product_name=$row["product_name"];
    $product_description=$row["product_description"];
    $price=$row["price"];
    $stock_qty=$row["stock_qty"];
    $public_flg=$row["public_flg"];
  
    
   
      if($public_flg==1&&$stock_qty!=0){?>
        <tr>
        <?php
      }
      else{?>
        <tr id=gray>
        <?php
      }
      ?>
      <form method="post">
        <td><input type="hidden" name="product_id" value="<?php echo $product_id;?>"><?php echo $product_id;?></td>
        <td><?php echo '<img src="' .'image/'.$image_name.'"class="td_image_display">';?></td>
        <td><input type="hidden" name="product_name" value="<?php echo $product_name;?>"><?php echo $product_name;?></td>
        <td class="product_description"><?php echo $product_description;?></td>
        <td><?php echo $price;?></td>
        <td><input type="text" name="stock_qty" class="stock_qty" value="<?php echo $stock_qty?>"><input type="submit" name="stock_change_btn" value="変更する"></td>
        <?php
        if($public_flg==1){?>
          <td>
          <input type="hidden" name="public_flg" value="0">
          <input type="submit" name="public_flg_btn" value="公開しない"></td>
          </td>
          <?php
        }
        else{?>
          <td>
          <input type="hidden" name="public_flg" value="1">
          <input type="submit" name="public_flg_btn" value="公開する"></td>
          </td>
          <?php 
        }?>
        <td><input type="submit" name="delete_btn" value="削除する"></td>
      </form>
    </tr>  
    <?php
    }
  ?>
  </table>
  <?php
}
//管理権限チェック
function managerConfirmation($db){
  $user_id=$_SESSION['user_id'];
  $table_name="ec_management";
  $data=['user_id'=>$user_id];
  $join='';
  $select_if=['user_id'=>$user_id];
  $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
  foreach($select_data as $row){
    $management_id=$row['user_id'];
  }
  // 管理ユーザーであるかを確認する
  if ($user_id!==$management_id) {
    // ログイン中である場合は、list.phpにリダイレクトする
    header('Location: ./list.php');
    exit();
  }
}
/*
* バリデーション
*/
function validationManagementCheck($price,$stock_qty){
  if(preg_match('/^[0-9]+$/',$price)&&preg_match('/^[0-9]+$/',$stock_qty)){
    return true;
  }
  elseif(!preg_match('/^[0-9]+$/',$price)&&preg_match('/^[0-9]+$/',$stock_qty)){
    $_SESSION["error_log"]="価格には0以上の整数を入れてください";
    return false;
  }
  elseif(preg_match('/^[0-9]+$/',$price)&&!preg_match('/^[0-9]+$/',$stock_qty)){
    $_SESSION["error_log"]="在庫数には0以上の整数を入れてください";
    return false;
  }
  else{
    $_SESSION["error_log"]="価格と在庫数には0以上の整数を入れてください";
    return false;
  }
}