<h2><i class='fa fa-users'></i> メンバー</h2>

<div>

<p>このシステムに参加中のメンバーです。退会したり、削除されたメンバーは表示されません。</p>

<?php
$users = new UserList();
if (!$users->load($conf['memberfile'])) {
  redirect($users->errmsg);
}

# 1ページに表示するアイテム数
$np = 10;

# 指定されたページ番号
$cr = isset($_GET['n']) ? $_GET['n'] : 0;

# 合計ページ数
$tp = intval($users->size / $np) + ($users->size % $np == 0 ? 0 : 1);

$c = 0;
foreach (array_reverse($users->items) as $user) {
  if (intval($c++ / $np) != $cr) {
    continue;
  }

  $date = date("m/d H:i", $user->date);
  $id   = $user->id;
  $text = $user->text;
  $url  = $user->url ? $user->url : "noicon.png";
  ?>
  <div class=member>
  <a href=index.php?p=member&mid=<?php echo $id ?>>
  <img src="<?php echo $url ?>" width=64 height=64 align=left class=threadimage>
  </a>
  <div style=padding-left:10px;margin-left:10px>
  <span class=username><a href=index.php?p=member&mid=<?php echo $id ?>><?php echo $id ?></a></span>
  <span class=timestamp><i class="fa fa-birthday-cake"></i> <?php echo $date ?></span><br>
  <?php
  if ($text) {
    print "<span class=>$text</span><br>\n";
  }
  ?>
  </div>
  <br clear=all>
  </div>
<?php
}
?>
  <p style="text-align:center">
<?php
  if ($cr != 0) {
    print "&nbsp;<a href=index.php?p=member&n=0><i class='fa fa-arrow-circle-left'></i> 最初</a>&nbsp;";
  }
  if ($cr > 0) {
    print "&nbsp;<a href=index.php?p=member&n=".($cr-1)."><i class='fa fa-chevron-circle-left'></i> 前</a>&nbsp;";
  }
  $a = 3;
  for ($i=0; $i < $tp; $i++) {
    if ($i < $cr - $a || $i > $cr + $a) {
      continue;
    }
    if ($i == $cr) {
      print "[$i]&nbsp;&nbsp;";
    }
    else {
      print "<a href=index.php?p=member&n=$i>$i</a>&nbsp;&nbsp;";
    }
  }
  if ($cr < $tp - 1) {
    print "&nbsp;<a href=index.php?p=member&n=".($cr+1).">次 <i class='fa fa-chevron-circle-right'></i></a>&nbsp;";
  }
  if ($tp-1 != $cr) {
    print "&nbsp;<a href=index.php?p=member&n=".($tp-1).">最後 <i class='fa fa-arrow-circle-right'></i></a>&nbsp;";
  }
?>
  </p>

<p style="text-align:right"><i class="fa fa-users"></i> <?php echo $users->size ?>人のメンバー</p>

</div>
