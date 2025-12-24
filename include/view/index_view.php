<!--
index_view.php
-->
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>EC Site:ログイン</title>
    <link rel="stylesheet" href="../ec_site/assets/style.css">
  </head>
  <body>
  <?php
  // ヘッダー表示
  headerDisplay($db);
  // エラー表示
  errorDisplay();
  // ログインフォーム表示
  loginDisplay($select_data);
  ?> 
  </body>
</html>