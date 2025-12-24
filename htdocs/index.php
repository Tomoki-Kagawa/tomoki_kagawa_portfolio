<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
ob_start();
session_start();
// メールファイルの読み込み
require_once './PHPMailer/src/PHPMailer.php';
require_once './PHPMailer/src/Exception.php';
require_once './PHPMailer/src/SMTP.php';
?>
<!DOCTYPE html>
<html lang="en">
  <!--ページ内以外の必要事項-->
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, width=1280">
    <title>Tomoki Kagawa's Portfolio</title>
    <link rel="stylesheet" href="./style.css">
  </head>

  <!--ヘッダー-->
  <body>
    <header class="clearfix background-color" id="Top">
      <h1 class="header-left"><a href="./index.php">Tomoki Kagawa's Portfolio</a></h1>
      <div class="header-right clearfix">
      <details>
        <summary><span><p>menu</p></span></summary>
        <ul class="background-color">
          <li><a href="#self-introduction">自己紹介</a></li>
          <li><a href="#Archives">過去の記録</a></li>
          <li><a href="#gallery">ーギャラリー</a></li>
          <li><a href="#training">ー訓練成果</a></li>
          <li><a href="#except">ー訓練外成果</a></li>
          <li><a href="#Contact">お問い合わせ</a></li>
        </ul>
      </details>
    </header>
    <div class="empty"></div>

    <!--メイン画像-->
    <div class="main"></div>

    <!--自己紹介-->
    <div class="self_introduction container" id="self-introduction">
      <div class="intro">
        <h2 class="Japanese">自己紹介<br><span class="English">Self Introduction</span></h2>
        <div class="explanation">
          <p>　私の名前は、<span class="bold">賀川智紀</span>です。神奈川県座間市出身です。</p>
          <p>　学生の頃は、組み込み技術や福祉について学び、研究室では介護用ロボットの開発をしていました。</p>
          <p>　まだまだ未熟ですが、学習意欲があり、今後より一層成長出来ればと思っております。</p>
          <p>　当ページでは、これまで学習したプログラミング言語で新たに成果物を作成し、まとめました。</p>
          <p>　もしよろしければ、最下部のお問い合わせから、ポートフォリオをご覧いただいたご感想をお送りいただけますと幸いです。</p>
          <p>　何卒、よろしくお願い致します。</p>
        </div>
        <img src="./images/merlion.jpg" alt="merlion.jpg">
      </div>
      <!--好きなもの・趣味 Webサイトbox-->
      <div class="box-parent">
        <!--box1・好きな物-->
        <div class="box1 box-child background-color">
          <h3 class="Japanese">好きなもの・趣味<br><span class="English">Hobbies</span></h3>
          <ul>
            <li class="eat">食事(寿司・ラーメン)</li>
            <li class="sports">スポーツ(テニス・卓球)</li>
            <li class="make">モノづくり(<a href="#PC">自作PC</a>・<a href="#arcadecontroller">アーケードコントローラー</a>)</li>
            <div class="etc_flex"><li class="game">ゲーム(FPS・格闘ゲーム)</li><li class="etc">など</li></div>
          </ul>
        </div>
        <!--box2-->
        <div class="box2 box-child background-color">
          <h3 class="Japanese">プログラミング経験<br><span class="English">Experiences</span></h3>
          <ul>
            <li class="C">C/C++（介護ロボット・<a href="#Arduino/C++">ブロック崩し</a>）</li>
            <li class="Java">Java（Androidアプリ・<a href="#Java">イライラ棒</a>）</li>
            <li class="JavaScript">JavaScript（<a href="#JavaScript">ピアノアプリ</a>）</li>
            <div class="etc_flex"><li class="PHP">PHP（<a href="#PHP">EC Site</a>）</li><li class="etc">など</li></div>
          </ul>
        </div>
      </div>
    </div>
    <!-- 実装サンプル-->
    <div class="Sample container" id="Archives">
      <h2 class="Japanese">過去の記録<br><span class="English">Archives</span></h2>
    </div>

    <!--ギャラリー-->
    <div class="gallery container scroll" id="gallery">
      <h3>ギャラリー</h3>
      <!--ギャラリー親-->
      <div class="gallery-parent">
        <!--画像1-->
        <div class="gallery01 gallery-child">
          <div class="back_color">
            <img src="./images/marina.jpg" alt="marina.jpg">
          </div>
          <h4 class="background-color"><span class="theme">マリーナベイサンズ</span></h4>
          <p>　研修で訪れたシンガポールは、近代的な建物と時折見せる幻想的な風景が印象に残りました。旅行先としてとてもおすすめです。</p>
        </div>
        <!--画像2-->
        <div class="gallery02 gallery-child">
          <div class="back_color">  
            <img src="./images/manipulator.jpg" alt="manipulator.jpg">
          </div>  
          <h4 class="background-color"><span class="theme">移動マニピュレータ</span></h4>
          <p>　大学生の頃、移動式マニピュレータを作成し、Javaで作成したAndroidコントローラーアプリをBluetoothで接続し操作できるものを作成しました。</p>
        </div>
         <!--画像3-->
        <div class="gallery03 gallery-child scroll" id="PC">
          <div class="back_color">  
            <img src="./images/PC.jpg" alt="PC.jpg">
          </div>
          <h4 class="background-color"><span class="theme">自作PC</span></h4>
          <p>　コロナが流行った時期に自作PCを作成し、WindowsとUbuntuをデュアルブートできるものを作成。研究室に行けない時も作業ができる様にしました。</p>
        </div>
        <!--画像4-->
        <div class="gallery04 gallery-child">
          <div class="back_color">  
            <img src="./images/eel.jpg" alt="eel.jpg">
          </div>
          <h4 class="background-color"><span class="theme">ひつまぶし</span></h4>
          <p>　食べることが大好きで、寿司やラーメンが好きです。展示会の出展で名古屋に行った時に食べたひつまぶしが人生で一番おいしかったです。</p>
        </div>
      </div>
    </div>

    <!--成果物-->
    <div class="deliverable container">
      <h3 class="scroll" id="training">訓練成果</h3>
      <p>画像をクリックすることで説明ページに移動することが出来ます</p>
      <p>コードは<a href="https://github.com/Tomoki-Kagawa/dc_work_itebina_kagawa_portfolio" target="_blank" class="git">
        github</a>からご覧いただけますと幸いです。</p>
      <div class="deliverable_child scroll" id="JavaScript">
        <h4 class="background-color"><span class="theme">JavaScript・ピアノ</span></h4>
        <a href="./JavaScript.html"><img src="./images/js_piano.png" alt="js_piano.png"></a>
      </div>
      <div class="deliverable_child scroll" id="PHP">
        <h4 class="background-color"><span class="theme">PHP・EC Site</span></h4>
        <a href="./PHP.html"><img src="./images/ec_site.png" alt="ec_site.png"></a>
      </div>
      <div class="deliverable_child scroll" id="WordPerss">
        <h4 class="background-color"><span class="theme">Wordpress・Sample Site</span></h4>
        <a href="./Wordpress.html"><img src="./images/wordpress.png" alt="ec_site.png"></a>        
      </div>
      <h3 class="scroll" id="except">訓練外成果</h3>
      <div class="deliverable_child scroll" id="Arduino/C++">
        <h4 class="background-color"><span class="theme">Arduino/C++・ブロック崩し</span></h4>
        <a href="./C++.html"><img src="./images/BreakingBlocks2.png" alt="PFC++.png"></a>
      </div>
      <div class="deliverable_child scroll" id="Java">
        <h4 class="background-color"><span class="theme">Java・イライラ棒</span></h4>
        <a href="./Java.html"><img src="./images/Java.png" alt="Java.png"></a>
      </div>
      <div class="deliverable_child scroll" id="arcadecontroller">
        <h4 class="background-color"><span class="theme">アーケードコントローラー</span></h4>
        <a href="./Arcadecontroller.html"><img src="./images/arcadecontroller.jpg" alt="arcadecontroller.jpg"></a>
      </div>
    </div>
    <?php
    //メール送信
    if(isset($_POST["send_btn"])){
      $_SESSION["flg"]="true";
     
      if(isset($_POST['name'])){
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
      }
      if(isset($_POST['email'])){
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
      }
      if(isset($_POST['inquiry'])){
        $inquiry = htmlspecialchars($_POST['inquiry'], ENT_QUOTES, 'UTF-8');
      }
      if(isset($_POST['impression'])){
        $impression = htmlspecialchars($_POST['impression'], ENT_QUOTES, 'UTF-8');
      }
      //メール送信
      emailSend($name,$email,$inquiry,$impression);
      ob_clean();
      header("Location:./index.php");
      exit();
    }
    if($_SESSION["flg"]=="true"){
      $_SESSION["flg"]="false";
      ?>
      <script>alert("送信しました。お問い合わせありがとうございます。")</script>
      <?php
    }
?>
    <!--お問い合わせフォーム-->
    <div class="form container scroll" id="Contact">
      <h2 class="Japanese">お問い合わせ<br><span class="English">Contact</span></h2>
      <!--h3>お問い合わせ</h3-->
      <form method="post" id="Contact_form">
        <div class="form-parent">
          <div class="form-child">
            <label class="required"><span class="label">お名前</span></label>
            <input type="text" name="name" required>
          </div>
          <div class="form-child">
            <label class="required"><span class="label">メールアドレス</span></label>
            <input type="email" id="email" name="email" required>
          </div>
          <div class="form-child">
            <label class="not_required"><span class="label">お問い合わせ内容</span></label>
            <textarea name="inquiry"></textarea>
          </div>
          <div class="form-child">
            <label class="not_required"><span class="label">ポートフォリオをご覧いただいた感想</span></label>
            <textarea name="impression" ></textarea>
          </div>
          <input class="button" name="send_btn" id="send_btn" type="submit" value="送信">
        </div>
      </form>
    </div>

    <!--フッター-->
    <footer class="background-color">
      <p>Copyright &copy; 2025 Tomoki Kagawa</p>
    </footer>
    <div class="return_top"> <a href="#TOP">TOPに戻る</a></div>
  </body>
</html>
<?php

/*
*問い合わせメールを送る
*/
function emailSend($name,$email,$inquiry,$impression){
  $subject="PortFolio:".$name."様からお問い合わせです";
  $message=$name."様からお問い合わせです。".PHP_EOL."メールアドレス：".$email.PHP_EOL."お問い合わせ内容：".$inquiry.PHP_EOL."PortFolioを見た感想：".$impression;
  $from_subject="PortFolio:お問い合わせ控え";
  $from_message=$name."様お問い合わせいただきありがとうございます。".PHP_EOL."下記の内容でお問い合わせしました。".PHP_EOL."--".PHP_EOL.$message.PHP_EOL."--";
  
  mb_language('Japanese');
  mb_internal_encoding('UTF-8');
  $mail = new PHPMailer(true);
  $mail->CharSet = 'utf-8';
  
  //個人情報呼び出し
  require_once './PHPMailer/src/config.php';

  try {
    //作成者宛用メール
    $mail->isSMTP();
    $mail->Host = $config['host'];  
    $mail->SMTPAuth = true;                 
    $mail->Username = $config['username'];
    $mail->Password = $config['password'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = $config['port'];
    $mail->setFrom($email,$name.'様'); 
    $mail->addAddress($config['username'],'Portfolio:tomoki'); 
    $mail->Subject = $subject;
    $mail->Body = $message;
    $mail->send();

    //お問い合わせ主宛メール
    $mail->clearAddresses();
    $mail->addAddress($email,$name.'様');
    $mail->Subject = $from_subject;
    $mail->Body    = $from_message;
    $mail->send();
  } catch (Exception $e) {
    // エラーの場合
    echo "メール送信エラー: {$mail->ErrorInfo}";
  }

}

ob_end_flush();
?>
