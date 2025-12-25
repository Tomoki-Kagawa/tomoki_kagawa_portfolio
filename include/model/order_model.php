<?php
/*
*order_model.php
*/
/*
*PHPMailer
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
/*
*購入処理
*/
function purchaseProcess($db){
  //最新版ID検索
  $user_id=$_SESSION['user_id'];
  $order_id=0;
  $table_name='ec_order';
  $data=['order_id'=>$order_id];
  $join="";
  $select_if=['user_id'=>$user_id];
  $order_data=dbSelect($db,$table_name,$data,$join,$select_if);
  foreach($order_data as $row) {
    $order_id=$row["order_id"];
  }
  $order_id=(int)$order_id+1;
  //カートの中身を購入済みに移す
  $user_id=$_SESSION['user_id'];
  $table_name="ec_product";
  $product_id="";$product_name="";$product_description="";$price="";$public_flg="";$stock_qty="";$image_name="";$product_qty="";
  $data=['ec_product.product_id'=>$product_id,'ec_product.product_name'=>$product_name,'ec_product.product_description'=>$product_description,'ec_product.price'=>$price,'ec_product.public_flg'=>$public_flg,'ec_stock.stock_qty'=>$stock_qty,'ec_image.image_name'=>$image_name,'ec_cart.product_qty'=>$product_qty];
  $join=[['ec_stock','ec_product.product_id','ec_stock.product_id'],['ec_image','ec_product.product_id','ec_image.product_id'],['ec_cart','ec_product.product_id','ec_cart.product_id']];
  $select_if=['ec_cart.user_id'=>$user_id];
  $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
  foreach($select_data as $row) {
    $product_id=$row["product_id"];
    $image_name=$row["image_name"];
    $product_name=$row["product_name"];
    $product_description=$row["product_description"];
    $price=$row["price"];
    $stock_qty=$row["stock_qty"];
    $public_flg=$row["public_flg"];
    $product_qty=$row["product_qty"];
    
    //購入分を削除した後、履歴に入れる
    $table_name='ec_history';
    $delete_if=['product_id' => $product_id,'user_id' => $user_id];
    dbDelete($db,$table_name,$delete_if);
    $date =date('Y-m-d H:i:s');
    $data=['user_id'=>$user_id,'product_id'=>$product_id,'create_date'=>$date,'update_date'=>$date];
    dbInsert($db,$table_name,$data);
    
    //購入分を購入済みに入れる
    $table_name='ec_order';
    $date =date('Y-m-d H:i:s');
    $data=['order_id'=>$order_id,'user_id'=>$user_id,'product_id'=>$product_id,'product_qty'=>$product_qty,'create_date'=>$date,'update_date'=>$date];
    dbInsert($db,$table_name,$data);
    
    //購入分をカートから消す
    $table_name='ec_cart';
    $user_id=$_SESSION["user_id"];
    $date =date('Y-m-d H:i:s');
    $delete_if=['product_id' => $product_id,'user_id' => $user_id];
    dbDelete($db,$table_name,$delete_if);
    
    //在庫から買った分を消す
    $stock_qty=$stock_qty-$product_qty;
    $table_name="ec_stock";
    $date =date('Y-m-d H:i:s');
    $data=['stock_qty'=>$stock_qty,'update_date'=>$date];
    $update_if=['product_id'=>$product_id];
    dbUpdate($db,$table_name,$data,$update_if);
  }        
}

/*
*購入済みリスト処理
*/
function listProcess($db){
  $user_id=$_SESSION["user_id"];
  if(isset($_POST['product_qty'])){
    $product_qty = htmlspecialchars($_POST['product_qty'], ENT_QUOTES, 'UTF-8');
  }
  //注文ページだった場合
  if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/order.php'){
    $order_id=0;
    $table_name='ec_order';
    $data=['order_id'=>$order_id];
    $join="";
    $select_if=['user_id'=>$user_id];
    $order_data=dbSelect($db,$table_name,$data,$join,$select_if);
    foreach($order_data as $row) {
      $order_id=$row["order_id"];
    }
    $table_name="ec_product";
    $product_id="";$product_name="";$product_description="";$price="";$public_flg="";$stock_qty="";$image_name="";
    $data=['ec_product.product_id'=>$product_id,'ec_product.product_name'=>$product_name,'ec_product.product_description'=>$product_description,'ec_product.price'=>$price,'ec_product.public_flg'=>$public_flg,'ec_stock.stock_qty'=>$stock_qty,'ec_image.image_name'=>$image_name,'ec_order.product_qty'=>$product_qty];
    $join=[['ec_stock','ec_product.product_id','ec_stock.product_id'],['ec_image','ec_product.product_id','ec_image.product_id'],['ec_order','ec_product.product_id','ec_order.product_id']];
    $select_if=['ec_order.user_id'=>$user_id,'ec_order.order_id'=>$order_id];
    $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
  }
  return $select_data;
}
/*
*購入済みリスト表示
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
      $price=$row["price"];
      $stock_qty=$row["stock_qty"];
      $public_flg=$row["public_flg"];
      $product_qty=$row["product_qty"];
         
      if($public_flg==1&&$stock_qty!=0){
        $count++;
        ?>     
        <div class="list">
          <?php          
            //購入ページだった場合
            if($_SERVER['REQUEST_URI']=='/ebina/0003/ec_site/order.php'){
              ?>
              <div class="name"><?php echo $product_name;?></div>
              <?php echo'<img src="' .'image/'.$image_name. '">'."<br>";
              echo $price."(円) ";
              echo $product_qty."個";
            ?>
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

/*
*購入完了表示 
*/
function completedPurchase(){
  ?>
  <h1 class="complete_purchase">ご購入いただきありがとうございます</h1>
  <?php
}

/*
*購入メールを送る
*/
function emailSend($db,$select_data){
  //購入品情報取得
  $total=0;
  $subtotal=0;
  $main_message="";
  foreach($select_data as $row) {
    $product_name=$row["product_name"];
    $price=$row["price"];
    $product_qty=$row["product_qty"];
    $subtotal=$price*$product_qty;
    $total+=$subtotal;
    //購入ページだった場合
    $main_message.=$product_name.'を'.$product_qty.'個　'. '単価'.$price.'円　小計'.$subtotal.'円'.PHP_EOL;
  }
  
  //メール情報取得
  $user_id=$_SESSION["user_id"];
  $table_name="ec_personal";
  $personal_name="";$email_address="";
  $data=['personal_name'=>$personal_name,'email_address'=>$email_address];
  $join="";
  $select_if=['user_id'=>$user_id];
  $select_data=dbSelect($db,$table_name,$data,$join,$select_if);
  foreach($select_data as $row) {
    $personal_name=$row["personal_name"];
    $to=$row["email_address"];
  }
  
  $subject="ご購入いただきありがとうございます";
  $message=$personal_name."様ご購入いただきありがとうございます".PHP_EOL."購入品は下記の通りです".PHP_EOL.$main_message."合計".$total."円";
  $from_subject="ご購入いただきました";
  $from_message=$personal_name."様にご購入いただきました".PHP_EOL."購入品は下記の通りです".PHP_EOL.$main_message."合計".$total."円";

  mb_language('Japanese');
  mb_internal_encoding('UTF-8');
  $mail = new PHPMailer(true);
  $mail->CharSet = 'utf-8';
  
  //個人情報呼び出し
  require_once __DIR__ . '/../../htdocs/PHPMailer/src/config.php';

  try {
    //購入者様用メール
    $mail->isSMTP();                         
    $mail->Host = $config['host'];  
    $mail->SMTPAuth = true;                 
    $mail->Username = $config['username'];
    $mail->Password = $config['password']; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = $config['port'];
    $mail->setFrom($config['username'],'Portfolio:ECSITE'); 
    $mail->addAddress($to,$personal_name.'様'); 
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->send();

    //お店宛メール
    $mail->clearAddresses();
    $mail->addAddress($config['username'],'Portfolio:ECSITE');
    $mail->Subject = $from_subject;
    $mail->Body    = $from_message;
    $mail->send();
  } catch (Exception $e) {
    echo "メール送信エラー: {$mail->ErrorInfo}";
  }
}
