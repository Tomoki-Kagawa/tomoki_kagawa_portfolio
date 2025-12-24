<!--
favorite_view.php
-->
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>EC Site:お気に入り</title>
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
  // お気に入りリスト表示
  listDisplay($select_data);
  // カートへ移動ボタン表示
  transitionButton();
  ?>
  </body>
</html>