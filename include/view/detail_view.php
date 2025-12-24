<!--
detail_view.php
-->
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>EC Site:商品詳細</title>
    <link rel="stylesheet" href="../ec_site/assets/style.css">
  </head>
  <body>
  <?php
  // ヘッダー表示
  headerDisplay($db);
  // タイトル表示
  pageTitleDisplay();
  // エラー表示
  errorDisplay();
  // 詳細表示
  detailDisplay($select_data);
  ?>
  </body>
</html>