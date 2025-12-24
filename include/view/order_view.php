<!--
order_view.php
-->
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>EC Site:購入完了</title>
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
  // 購入済みリスト表示
  listDisplay($select_data);
  // 小計表示
  totalDisplay($db);
  // 購入時の表示
  completedPurchase();
  ?>
  </body>
</html>