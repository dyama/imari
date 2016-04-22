<?php
$users = new UserList();

if (!$users->load($conf['memberfile'])) {
  redirect($users->errmsg);
}
$id = $_SESSION['id'];

print "<h2><i class='fa fa-gear'></i> 設定</h2>";

if (!$users->exist($id)) {
  print "ユーザー $id は存在しません。";
}
else {
  $user = $users->id($id);
  $text = $user->text;
  $url =  $user->url;
?>

<p>ここでは、各種設定を行うことができます。</p>

<h3><i class="fa fa-user"></i> 個人設定</h3>

<form action=index.php method=post target=_self>
<i class="fa fa-lock"></i> パスワード<br>
<span class=timestamp>半角英数3～20文字。未入力の場合、変更しません。</span><br>
<input type=text name=pw size=20 maxlength=20 value="" autocomplete=off><br>
<i class="fa fa-comment-o"></i> 自己紹介<br>
<span class=timestamp>140文字まで。プロフィールとともに表示されます。</span><br>
<input type=text name=ptext size=70 maxlength=140 value="<?php echo $text ?>"><br>
<i class="fa fa-picture-o"></i> プロフィール画像URL<br>
<span class=timestamp>大きさ128×128ピクセル程度を推奨。</span><br>
<input type=text name=purl size=70 maxlength=300 value="<?php echo $url ?>"><br>
<button type=submit name=configure>
<i class="fa fa-edit"></i>
更新する
</button>
<br>
</form>

<h3><i class="fa fa-rocket"></i> 退会する</h3>

<p>退会機能はまだありません。</p>

<?php
}
?>
