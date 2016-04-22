<?php

  session_start();
  $login = isset($_SESSION['login']);

  # ファイルに対するアクセス
  if (isset($_GET['f'])) {
    include "file.php";
  }

  include_once "init.php";
  include_once "UserList.php";
  include_once "Thread.php";

  # ログアウト
  function logout($msg)
  {
    global $conf;
    $_SESSION = array();
    print "<meta http-equiv=refresh content=3;URL=index.php>";
    print "<div class=rep>";
    if ($msg) {
      print "$msg<br><br>";
    }
    print "ページを切り替えています。数秒お待ちください。 ...";
    print "</div>";
    exit;
  }

  # 遷移
  function redirect($msg, $url)
  {
    if (!$url) {
      $url = "index.php";
    }
    print "<meta http-equiv=refresh content=3;URL=$url>";
    print "<div class=rep>";
    if ($msg) {
      print "$msg<br><br>";
    }
    print "ページを切り替えています。数秒お待ちください。 ...";
    print "</div>";
    exit;
  }

  function upload_files($postid)
  {
    global $conf;
    $n = array();
    $count = count($_FILES['files']['name']);
    if ($count > $conf['upcount']) {
      redirect("一度にアップロードできるのは".$conf['upcount']."までです。");
    }
    for ($i=0; $i<$count; $i++) {
      if ($_FILES['files']['error'][$i] != 0) {
        continue;
      }
      $orgfile = $_FILES["files"]["name"][$i];
      $tmpfile = $_FILES["files"]["tmp_name"][$i];
      if (is_uploaded_file($tmpfile)) {
        if (filesize($tmpfile) > $conf['upsize']) {
          # サイズ超過
          continue;
        }
        $ext = preg_replace('/^.*\.([^\.]+)$/', '$1', $orgfile);
        if (!$ext) {
          # 禁止拡張子
          continue;
        }
        $ext = mb_strtolower($ext);
        if (!in_array($ext, $conf['exts'])) {
          continue;
        }
        $file = "s/".$postid."_".$i.".".$ext;
        $filename = $postid."_".$i.".".$ext;
        if (move_uploaded_file($tmpfile, $file)) {
          chmod($file, 0604);
          array_push($n, $filename);
        }
      }
    }
    return $n;
  }

  ################################################################

  include "header.php";

  session_start();
  $id = isset($_POST['id']) ? $_POST['id'] : "";
  $pw = isset($_POST['pw']) ? $_POST['pw'] : "";

  ### ログイン前

  if (!isset($_SESSION['login'])) {
    if (isset($_POST['login'])) {
      # ログイン処理
      if (!$conf['login']) { 
        logout("システム設定によりログインは無効になっています。");
      }
      $users = new UserList();
      if (!$users->load($conf['memberfile'])) {
        logout($users->errmsg);
      }
      if (!$users->auth($id, $pw)) {
        logout($users->errmsg ? $users->errmsg : "ログインに失敗しました。");
      }
      session_start();
      $_SESSION['login'] = true;
      $_SESSION['id'] = $id;
      $_SESSION['icon'] = $users->id($id)->url;
      redirect("ログインに成功しました。ようこそ！", "index.php?p=home");
    }
    elseif (isset($_POST['regist'])) {
      # 登録処理
      if (!$conf['regist']) {
        logout("システム設定により新規のユーザー登録は無効になっています。");
      }
      $users = new UserList();
      if (!$users->load($conf['memberfile'])) {
        logout($users->errmsg);
      }
      if (!$users->add($id, $pw)) {
        logout($users->errmsg);
      }
      if (!$users->save()) {
        logout($users->errmsg);
      }
      logout("ユーザー登録が完了しました。ログインしてください。");
    }
    # 未ログイン
    else {
      if (isset($_GET['p'])) {
        logout("");
      }
      else {
        include "loginform.php";
      }
    }
  }
  # ログアウト処理
  elseif (isset($_GET['p']) && $_GET['p'] == "logout") {
    logout("またね！");
  }

  ### これ以降はログイン済み

  # 設定更新
  if (isset($_POST['configure'])) {
    $users = new UserList();
    if (!$users->load($conf['memberfile'])) {
      redirect($users->errmsg);
    }
    $id = $_SESSION['id'];
    if (!$users->exist($id)) {
      redirect("ユーザー $id は存在しません。");
    }
    if (!$users->update($id, $_POST['pw'], $_POST['ptext'], $_POST['purl'])) {
      redirect($users->errmsg);
    }
    if (!$users->save()) {
      redirect($users->errmsg);
    }
    redirect("更新しました。", "index.php?p=member&mid=$id");
  }

  # 投稿モード
  if (isset($_POST['post'])) {
    if (!file_exists($conf['adir'])) {
      redirect("アーカイブディレクトリが見つかりません。", "index.php");
    }
    $tid = time();
    $thread = new Thread();
    # 既存スレッド
    if (isset($_POST['tid'])) {
      $tid = $_POST['tid'];
      if (!file_exists($conf['adir']."/$tid.txt")) {
        redirect("スレッドIDが見つかりません。", "index.php");
      }
      if (!$thread->load($conf['adir']."/$tid.txt")) {
        redirect($thread->errmsg);
      }
      if ($thread->size >= $conf['maxrep']) {
        redirect("レスがいっぱいのため、このスレッドには書き込めません。", "index.php");
      }
    }
    else {
      $thread->path = $conf['adir']."/$tid.txt";
    }
    $date = time();
    # アップロード
    $files = array();
    if ($conf['upload']) {
      $files = upload_files($date);
    }
    if (!$thread->add($date, $_SESSION['id'], $files, $_POST['postbody'])) {
      redirect($thread->errmsg);
    }
    if (!$thread->save()) {
      redirect($thread->errmsg);
    }
    redirect("投稿しました。", "index.php?tid=$tid");
  }

  # これ以降はメニューを表示

  include "menu.php";
  print "<div class=main>";

  if (isset($_GET['p']) && $_GET['p'] == 'help') {
    # ヘルプ
    include "help.php";
  }
  elseif (isset($_GET['p']) && $_GET['p'] == 'member') {
    if (isset($_GET['mid'])) {
      # プロフィール
      $id = $_GET['mid'];
      include "profile.php";
    }
    else {
      # メンバーリスト
      include "memberlist.php";
    }
  }
  elseif (isset($_GET['p']) && $_GET['p'] == 'config') {
    # 設定画面
    include "config.php";
  }
  elseif (isset($_GET['tid'])) {
    # スレッドモード
    $tid = $_GET['tid'];
    if (preg_match('/^\d+$/', $tid) && file_exists($conf['adir']."/".$tid.".txt")) {
      $thread = new Thread();
      if (!$thread->load($conf['adir']."/$tid.txt")) {
        redirect($thread->errmsg);
      }
      print $thread->str_thread();
      if ($thread->size > $conf['maxrep'] || $thread->size < 1) {
        print "レスがいっぱいのため、このスレッドには書き込めません。";
      }
      else {
        include "postform.php";
      }
    }
    else {
      print "スレッドが見つかりませんでした。";
    }
  }
  else {
    # サブジェクトモード
    include "threadlist.php";
    include "postform.php";
  }
  print "</div>";

  include "footer.php";
?>
