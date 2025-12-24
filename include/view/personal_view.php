<!--
personal_view.php
-->
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>EC Site:個人情報登録</title>
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
  // 個人情報登録フォーム表示
  personalDisplay($select_data);
  ?>
  </body>
</html>