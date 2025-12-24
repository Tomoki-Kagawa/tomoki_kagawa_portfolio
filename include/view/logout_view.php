<!--
logout_view.php
-->
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>EC Site:ログアウト</title>
    <link rel="stylesheet" href="../ec_site/assets/style.css">
  </head>
  <body>
  <?php
  // ヘッダー表示
  headerDisplay($db);
  // エラー表示
  errorDisplay();
  // ログアウト表示
  logoutDisplay(); 
  ?>
  </body>
</html>