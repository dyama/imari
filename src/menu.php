<div class=menubar>
  <a href=index.php?p=home><i class="fa fa-home"></i>&nbsp;ホーム</a>&nbsp;
  <span class=disableitem><i class="fa fa-inbox"></i>&nbsp;メッセージ</span>&nbsp;
  <a href=index.php?p=member><i class="fa fa-users"></i>&nbsp;メンバー</a>&nbsp;
  <a href=index.php?p=config><i class="fa fa-gear"></i>&nbsp;設定</a>&nbsp;
  <a href=index.php?p=help><i class="fa fa-question-circle"></i>&nbsp;ヘルプ</a>&nbsp;
  <a href=index.php?p=logout><i class="fa fa-sign-out"></i>&nbsp;ログアウト</a>&nbsp;
|
  <a href="index.php?p=member&mid=<?php print $_SESSION['id'] ?>" target=_self>
   <?php
   $url = 'noicon.png';
   if (!empty($_SESSION['icon'])) {
      $url = $_SESSION['icon'];
   }
   ?>
     <img src="<?php echo $url ?>" height=16 width=16>
   <?php print $_SESSION['id'] ?></a>
</div>

