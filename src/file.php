<?php
if (!$login) {
  redirect("アクセス権がありません。","index.php");
}
$f = $_GET['f'];
if (strpos($f, '/') != false) {
  redirect("そのようなファイルはありません。","index.php");
}
elseif (strpos($f, '..') != false) {
  redirect("そのようなファイルはありません。","index.php");
}
elseif (!file_exists("s/".$f)) {
  redirect("そのようなファイルはありません。","index.php");
}
else {
  $ext = preg_replace('/^.*\.([^\.]+)$/', '$1', $f);
  if (!$ext) {
    redirect("そのようなファイルはありません。","index.php");
  }
  else {
    $ext = mb_strtolower($ext);
    $inline = true;
    switch ($ext) {
    case "jpg":  header('Content-Type: image/jpeg'); break;
    case "jpeg": header('Content-Type: image/jpeg'); break;
    case "png":  header('Content-Type: image/png');  break;
    case "gif":  header('Content-Type: image/gif');  break;
    case "mp4":  header('Content-Type: video/mp4');  break;
    case "flv":  header('Content-Type: video/mp4');  break;
    case "3gp":  header('Content-Type: video/3gpp'); break;
    default:
      header('Content-Type: application/octet-stream');
      $inline = false;
      break;
    }
    if ($inline) {
      header('Content-Disposition: inline; filename='.$f);
    }
    else {
      header('Content-Disposition: attachment; filename='.$f);
    }
    readfile("s/".$f);
  }
}
exit;
?>
