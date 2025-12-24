<?php
/*
*logout_model.php
*/
/*
* ログアウトを表示する 
*/
function logoutDisplay(){
  sessionEnd();
  ?>
  <div class="centerdisplay">
    <h1>ログアウトしました</h1>
  </div>
  <?php
}