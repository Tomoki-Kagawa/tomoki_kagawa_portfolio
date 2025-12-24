<?php
/*
*detail_model.php
*/
/*
* 詳細ページのボタン処理
*/
function detailBtn($db){
  $product_id=$_GET['product_id'];
  if(isset($_POST['product_qty'])){
    $product_qty = htmlspecialchars($_POST['product_qty'], ENT_QUOTES, 'UTF-8');
  }
  //カートに入れるボタンを押した時
  if(isset($_POST['cart_btn'])){
    $product_name=$_POST['product_name'];
    $table_name='ec_cart';
    $user_id=$_SESSION["user_id"];
    $date =date('Y-m-d H:i:s');
    $delete_if=['product_id' => $product_id,'user_id' => $user_id];
    dbDelete($db,$table_name,$delete_if);
    $data=['user_id'=>$user_id,'product_id'=>$product_id,'product_qty'=>$product_qty,'create_date'=>$date,'update_date'=>$date];
    dbInsert($db,$table_name,$data);
    $_SESSION["log"]=$product_name."を".$product_qty."個カートに入れました";
    ob_clean();
    header("Location: ./detail.php?product_id=".$product_id);
    exit();
  }
}

/*
* 詳細ページの処理
*/
function detailProcess($db){
  $product_id=$_GET['product_id'];
  $table_name="ec_product";
  $product_name="";$product_description="";$price="";$public_flg="";$stock_qty="";$image_name="";
  $data=['ec_product.product_id'=>$product_id,'ec_product.product_name'=>$product_name,'ec_product.product_description'=>$product_description,'ec_product.price'=>$price,'ec_product.public_flg'=>$public_flg,'ec_stock.stock_qty'=>$stock_qty,'ec_image.image_name'=>$image_name ];
  $join=[['ec_stock','ec_product.product_id','ec_stock.product_id'],['ec_image','ec_product.product_id','ec_image.product_id']];
  $select_if=['ec_product.product_id'=>$product_id];
  $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
  return $select_data;
}

/*
* 詳細ページの表示
*/
function detailDisplay($select_data){
  if(isset($_POST['product_qty'])){
    $product_qty = htmlspecialchars($_POST['product_qty'], ENT_QUOTES, 'UTF-8');
  }
  foreach($select_data as $row) {
    $product_id=$row["product_id"];
    $image_name=$row["image_name"];
    $product_name=$row["product_name"];
    $product_description=$row["product_description"];
    $price=$row["price"];
    $stock_qty=$row["stock_qty"];
    $public_flg=$row["public_flg"];
    ?>
    <div class="detail_display">
      <div class="detail_content">
        <?php echo'<img src="' .'image/'.$image_name. '" class="image_display">'."<br>";?>
      </div>
      <div class="detail_content">
        <p>商品名：<?php echo $product_name;?></p>
        <p>説明文：<?php echo $product_description;?></p>
        <p>価格(円)：<?php echo $price;?></p>
        <form method="post" enctype="multipart/form-data" class="form_flex">
          <input type="hidden" name="product_name" value="<?php echo $product_name;?>">
          <p>個数(個)：<input type="text" name="product_qty" class="product_qty" value="1"<?php echo $product_qty?>>
          <input type="submit" name="cart_btn" value="カートに入れる">
        </form>
      </div>
    </div>
    <?php
  }
}