<!--
cart_view.php
-->
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>EC Site:カート</title>
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
  //リスト標示
  listDisplay($select_data);
  // 小計表示
  totalDisplay($db);
  // 購入ボタン表示
  transitionButton();
  ?>
  </body>
</html>