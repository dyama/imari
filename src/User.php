<?php

# --------------------
# ユーザー クラス
class User
{
  public $date   = 0;
  public $id     = '';
  public $passwd = '';
  public $text   = '';
  public $url    = '';

  # コンストラクタ
  public function __construct(...$args)
  {
    if (count($args) == 1) {
      $this->from_s($args[0]);
    }
    elseif (count($args) >= 2 && count($args) <= 4) {
      $this->date = time();
      $this->id = $args[0];
      $this->passwd = password_hash($args[1], PASSWORD_DEFAULT);
      $this->text = count($args) >= 3 ? $args[2] : '';
      $this->url  = count($args) == 4 ? $args[3] : '';
    }
    else {
      throw new Exception("Failed to new User.");
    }
  }

  # シリアライズ
  public function to_s()
  {
    $text = $this->text ? base64_encode($this->text) : "";
    $url = $this->url ? base64_encode($this->url) : "";
    return $this->date.",".$this->id.",".$this->passwd.",".$text.",".$url;
  }

  # デシリアライズ
  public function from_s($line)
  {
    $line = trim($line);
    $ar = explode(',', $line);
    $this->date = array_shift($ar);
    $this->id = array_shift($ar);
    $this->passwd = array_shift($ar);
    $text = array_shift($ar);
    $this->text = mb_strlen($text) > 0 ? base64_decode($text) : "";
    $url = array_shift($ar);
    $this->url  = mb_strlen($url) > 0 ? base64_decode($url) : "";
    return true;
  }

  # 認証
  public function auth($passwd_plain)
  {
    return password_verify($passwd_plain, $this->passwd);
  }

  # ID として正しい文字列か
  public static function valid_id($id)
  {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $id);
  }

  # パスワードとして正しい文字列か
  public static function valid_pw($pw)
  {
    return preg_match('/^[a-zA-Z0-9_]{3,20}$/', $pw);
  }

  # 自己紹介として正しい文字列か
  public static function valid_text($text)
  {
    return mb_strlen($text) <= 140;
  }

  # プロフィール画像 URL として正しい文字列か
  public static function valid_url($url)
  {
    return mb_strlen($url) <= 300;
  }
}
?>

