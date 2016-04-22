<h2><i class="fa fa-flag"></i> スレッド一覧</h2>

<div>

<?php

$ar = glob($conf['adir']."/*.txt");

# 1ページに表示するアイテム数
$np = 10;

# 指定されたページ番号
$cr = isset($_GET['n']) ? $_GET['n'] : 0;

# 合計ページ数
$size = count($ar);
$tp = intval($size / $np) + ($size % $np == 0 ? 0 : 1);

$c = 0;

foreach (array_reverse($ar) as $fn) {
  if (intval($c++ / $np) != $cr) {
    continue;
  }
  $thread = new Thread();
  if (!$thread->load($fn)) {
    continue;
  }
  print $thread->str_subject();
}

#$ar2 = array();
#$i = 0;
#foreach ($ar as $fn) {
#  $thread = new Thread();
#  if (!$thread->load($fn)) {
#    continue;
#  }
#  $ar2[] = $thread;
#}
#
#foreach ($ar2 as $thread) {
#  if (intval($c++ / $np) != $cr) {
#    continue;
#  }
#  print $thread->str_subject();
#}
?>
  <p style="text-align:center">
<?php
  if ($cr != 0) {
    print "&nbsp;<a href=index.php?p=home&n=0><i class='fa fa-arrow-circle-left'></i> 最初</a>&nbsp;";
  }
  if ($cr > 0) {
    print "&nbsp;<a href=index.php?p=home&n=".($cr-1)."><i class='fa fa-chevron-circle-left'></i> 前</a>&nbsp;";
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
      print "<a href=index.php?p=home&n=$i>$i</a>&nbsp;&nbsp;";
    }
  }
  if ($cr < $tp - 1) {
    print "&nbsp;<a href=index.php?p=home&n=".($cr+1).">次 <i class='fa fa-chevron-circle-right'></i></a>&nbsp;";
  }
  if ($tp-1 != $cr) {
    print "&nbsp;<a href=index.php?p=home&n=".($tp-1).">最後 <i class='fa fa-arrow-circle-right'></i></a>&nbsp;";
  }
?>
  </p>
  <p style="text-align:right"><i class="fa fa-flag"></i> <?php echo $size ?>個のスレッド</p>
</div>
