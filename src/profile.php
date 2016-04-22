<?php
$users = new UserList();
if (!$users->load($conf['memberfile'])) {
  redirect($users->errmsg);
}
print "<h2><i class='fa fa-user'></i> プロフィール</h2>";

if (!$users->exist($id)) {
  print "ユーザー $id は存在しません。";
}
else {
  $user = $users->id($id);
  $date = date("m/d H:i", $user->date);
  $id   = $user->id;
  $text = $user->text;
  $url  = $user->url ? $user->url : "noicon.png";
?>

  <img src="<?php echo $url ?>" width=128 height=128 align=left><br>
  <div>
  <h3><?php echo $id ?></h3>
  <?php echo $text ?><br>
  <span class=timestamp><?php echo $date ?></span><br>
  </div>
  <br clear=all>
  <!-- <h3>参加スレッド一覧</h3> -->
<?php
}
?>
