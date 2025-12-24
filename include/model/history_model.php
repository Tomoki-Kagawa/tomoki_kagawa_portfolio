<?php
/*
*history_model.php
*/
/*
*履歴ページボタン処理
*/
function listBtn($db){
  $product_id="";
  $product_qty="";
  //user_namePOST  
  if(isset($_POST['product_id'])){
    $product_id = htmlspecialchars($_POST['product_id'], ENT_QUOTES, 'UTF-8');
  }
  //user_namePOST  
  if(isset($_POST['product_qty'])){
    $product_qty = htmlspecialchars($_POST['product_qty'], ENT_QUOTES, 'UTF-8');
  }
  if(isset($_POST['product_name'])){
    $product_name = htmlspecialchars($_POST['product_name'], ENT_QUOTES, 'UTF-8');
  }
  //カートに入れるボタンを押した時
  if(isset($_POST['cart_btn'])){
    $table_name='ec_cart';
    $user_id=$_SESSION["user_id"];
    $date =date('Y-m-d H:i:s');
    $delete_if=['product_id' => $product_id,'user_id' => $user_id];
    dbDelete($db,$table_name,$delete_if);
    $data=['user_id'=>$user_id,'product_id'=>$product_id,'product_qty'=>$product_qty,'create_date'=>$date,'update_date'=>$date];
    dbInsert($db,$table_name,$data);
    $_SESSION["log"]=$product_name."を".$product_qty."個カートに入れました";
    ob_clean();
    //お気に入りページだった場合
    if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/favorite.php'){
      header("Location: ./favorite.php");
      exit();
    }
    if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/history.php'){
      header("Location: ./history.php");
      exit();
    }
    else{
      header("Location: ./list.php");
      exit();
    }
  }
}
/*
*履歴ページ処理
*/
function listProcess($db){
  //購入履歴ページだった場合
  if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/history.php'){
    $user_id=$_SESSION['user_id'];
    $table_name="ec_product";
    $product_id="";$product_name="";$product_description="";$price="";$public_flg="";$stock_qty="";$image_name="";
    $data=['ec_product.product_id'=>$product_id,'ec_product.product_name'=>$product_name,'ec_product.product_description'=>$product_description,'ec_product.price'=>$price,'ec_product.public_flg'=>$public_flg,'ec_stock.stock_qty'=>$stock_qty,'ec_image.image_name'=>$image_name];
    $join=[['ec_stock','ec_product.product_id','ec_stock.product_id'],['ec_image','ec_product.product_id','ec_image.product_id'],['ec_history','ec_product.product_id','ec_history.product_id']];
    $select_if=['ec_history.user_id'=>$user_id];
    $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
  }
  return $select_data;
}
/*
*履歴ページボタン表示
*/
function listDisplay($select_data){
$count=0;
?>
<div class="image_flex">
<?php
  foreach($select_data as $row) {
    $product_id=$row["product_id"];
    $image_name=$row["image_name"];
    $product_name=$row["product_name"];
    $product_description=$row["product_description"];
    $price=$row["price"];
    $stock_qty=$row["stock_qty"];
    $public_flg=$row["public_flg"];
    $product_qty=$row["product_qty"];
        
    if($public_flg==1&&$stock_qty!=0){
      $count++;
      ?>     
      <div class="list">
        <?php          
          
          //購入履歴ページだった場合
          if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/history.php'){
            ?>
            <div class="name"><?php echo $product_name;?></div>
            <?php echo'<a href="./detail.php?product_id='.$product_id.'"><img src="' .'image/'.$image_name. '">'."</a><br>";?>
            <?php echo $price."(円) ";?>
            <form method="post" enctype="multipart/form-data" class="form_flex">
              <input type="hidden" name="product_id" value="<?php echo $product_id?>">
              <input type="hidden" name="product_name" value="<?php echo $product_name?>">
              <input type="text" name="product_qty" class="product_qty" value="1"<?php echo $product_qty?>>
              <div><input type="submit" name="cart_btn" value="カートに入れる" class="btn_size"></div>
            </form>
          <?php
          }
          ?>
        </div>
      <?php
      }
    }
    $remainder=$count%3;
    if($remainder==2){
      ?><div class="list_empty">a</div><?php
    }
    elseif($remainder){
      ?><div class="list_empty">a</div><?php
      ?><div class="list_empty">a</div><?php
    }
  ?>
  </div>            
  <?php
} 
