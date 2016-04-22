<h2>ログイン</h2>
<form target=index.php method=post target=_top>
アカウント: <input type=text name=id><br>
パスワード: <input type=password name=pw><br>
<?php
if ($conf['login']) {
  print "<input type=submit name=login value=ログイン>";
}
if ($conf['regist']) {
  print "<input type=submit name=regist value=新規登録>";
}
?>
</form>

<?php
exit;
?>
