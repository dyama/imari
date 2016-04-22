<?php
$ss = round($conf['upsize'] / 1024 / 1024, 1);
$upcount = $conf['upcount'];
$exts = implode(", ", $conf['exts']);
$maxrep = $conf['maxrep'];
?>
<h2><i class='fa fa-question-circle'></i> ヘルプ</h2>

<p>このシステムの使い方、ルールなどを記載しています。</p>

<ul>
  <li>一度に投稿できる文字数: 2～140文字以内<br>
  <li>一度に投稿できるファイル数: <?php echo $upcount ?> 個 (各 <?php echo $ss ?> MBまで)<br>
  <li>投稿できるファイルの拡張子: <?php echo $exts ?><br>
  <li>一つのスレッドに書き込める返信: <?php echo $maxrep ?> レス<br>
</ul>
