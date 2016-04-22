<form action=index.php method=post enctype=multipart/form-data>
<i class="fa fa-flag"></i>
<?php print ($tid ? "このスレッドに返信:" : "新しいスレッド:"); ?>
<br>
<textarea class=rep name=postbody cols=80 rows=5></textarea><br>
<?php
if ($conf['upload']) {
  print "<input type=file name=files[] multiple>";
  print "<input type=hidden name=MAX_FILE_SIZE value=2097152>";
}
?>
<button type="submit" name="post">
  <i class="fa fa-comment"></i>
  書き込む
</button>
<?php
if ($tid) {
  print "<input type=hidden name=tid value=$tid>";
  print "<a href=index.php?tid=$tid target=_self><i class='fa fa-refresh'></i>&nbsp;リロード</a>&nbsp;";
}
else {
  print "<a href=index.php?p=home target=_self><i class='fa fa-refresh'></i>&nbsp;リロード</a>&nbsp;";
}
?>
<a href=index.php?p=help target=_blank><i class='fa fa-question-circle'></i>&nbsp;ヘルプ</a>&nbsp;
</form>
