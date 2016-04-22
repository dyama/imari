<?php

include_once "User.php";

# --------------------
# ユーザーリスト クラス
class UserList
{
  public $path = '';
  public $size = 0;
  public $errmsg = '';
  public $items = array();

  # リストファイルから入力
  public function load($file)
  {
    if (!file_exists($file)) {
      $this->errmsg = "ユーザーデータベースが存在しません。";
      return false;
    }
    $fp = fopen($file, "r");
    if (!$fp) {
      $this->errmsg = "ユーザーデータベースを開けません。";
      return false;
    }
    if (!flock($fp, LOCK_SH)) {
      fclose($fp);
      $this->errmsg = "ユーザーデータベースをロックできません。";
      return false;
    }
    array_splice($this->items, 0);
    while (!feof($fp)) {
      $line = fgets($fp);
      $user = new User($line);
      if ($user->id == '') {
        continue;
      }
      array_push($this->items, $user);
    }
    fclose($fp);
    $this->path = $file;
    $this->size = count($this->items);
    $this->errmsg = '';
    return true;
  }

  # リストファイルに出力
  public function save($file = null)
  {
    if (!$file) {
      $file = $this->path;
    }
    $fp = fopen($file, "w");
    if (!$fp) {
      $this->errmsg = "ユーザーデータベースを開けません。";
      return false;
    }
    if (!flock($fp, LOCK_SH)) {
      fclose($fp);
      $this->errmsg = "ユーザーデータベースをロックできません。";
      return false;
    }
    foreach ($this->items as $user) {
      fputs($fp, $user->to_s()."\n");
    }
    fclose($fp);
    $this->path = $file;
    $this->errmsg = '';
    return true;
  }

  # IDからユーザーを検索
  public function id($id)
  {
    $res = array_filter($this->items, function($user) use($id) { return $user->id == $id; });
    if (count($res) == 0) {
      $this->errmsg = "ユーザー $id は存在しません。";
      return null;
    }
    return array_shift($res);
  }

  # IDが存在するか
  public function exist($id)
  {
    return !is_null($this->id($id));
  }

  private function valid_idpw($id, $pw)
  {
    if (!$id || !$pw) {
      $this->errmsg = "IDまたはパスワードが入力されていません。";
      return false;
    }
    if (!User::valid_id($id)) {
      $this->errmsg = "不正なユーザーIDが入力されました。(半角英数3～20字以内)";
      return false;
    }
    if (!User::valid_pw($pw)) {
      $this->errmsg = "不正なパスワードが入力されました。(半角英数3～20字以内)";
      return false;
    }
    return true;
  }

  # IDとパスワードで認証
  public function auth($id, $passwd_plain)
  {
    if (!$this->valid_idpw($id, $passwd_plain)) {
      return false;
    }
    if (!$this->exist($id)) {
      $this->errmsg = "ユーザー $id は存在しません。";
      return false;
    }
    $user = $this->id($id);
    return $user->auth($passwd_plain);
  }

  # ユーザーを追加する
  public function add($id, $passwd_plain, $text = '', $url = '')
  {
    if (!$this->valid_idpw($id, $passwd_plain)) {
      return false;
    }
    if ($this->exist($id)) {
      $this->errmsg = "ユーザー $id は既に存在します。";
      return false;
    }
    $user = new User($id, $passwd_plain, $text, $url);
    array_push($this->items, $user);
    $this->size = count($this->items);
    return true;
  }

  # ユーザーを削除する
  public function drop($id)
  {
    if (!$this->exist($id)) {
      $this->errmsg = "ユーザー $id は存在しません。";
      return false;
    }
    for ($i = 0; $i < $this->size; $i++) {
      if ($this->items[$i]->id == $id) {
        unset($this->items[$i]);
      }
    }
    $this->size = count($this->items);
    return true;
  }

  # ユーザー情報を更新する
  public function update($id, $passwd_plain = null, $text = null, $url = null)
  {
    if (!$this->exist($id)) {
      $this->errmsg = "ユーザー $id は存在しません。";
      return false;
    }
    if (!empty($passwd_plain) && !User::valid_pw($passwd_plain)) {
      $this->errmsg = "不正なパスワードが入力されました。(半角英数3～20字以内)";
      return false;
    }
    if (!empty($text) && !User::valid_text($text)) {
      $this->errmsg = "不正な自己紹介が入力されました。(140字以内)";
      return false;
    }
    if (!empty($url) && !User::valid_url($url)) {
      $this->errmsg = "不正なプロフィール画像URLが入力されました。(300字以内)";
      return false;
    }
    $user = $this->id($id);
    $user->passwd = !empty($passwd_plain) ? password_hash($passwd_plain, PASSWORD_DEFAULT) : $user->passwd;
    $user->text   = !empty($text)         ? $text : $user->text;
    $user->url    = !empty($url)          ? $url : $url->text;
    return true;
  }
}

?>
