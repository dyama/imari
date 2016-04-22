<?php

# --------------------
# 投稿 クラス
class Post
{
  # 投稿したユーザID
  public $user = '';
  # 本文
  public $text = '';
  # 投稿日
  public $date = 0;
  # 添付ファイル
  public $files = array();
  # (このインスタンスがスレッド内に存在する場合の)レス番号
  public $index = 0;

  # コンストラクタ
  public function __construct(...$args)
  {
    # 呼び出し時の引数が1つの場合、最初の仮引数を文字列とする
    if (count($args) == 1) {
      $this->from_s($args[0]);
    }
    elseif (count($args) == 4) {
      # 4つの場合、日付、ユーザ、添付ファイル、本文の順に認識する。
      $this->date  = isset($args[0]) ? $args[0] : time();
      $this->user  = $args[1];
      if (isset($args[2]) && count($args[2]) > 0) {
        $this->files = array_filter($args[2], function($f){ return $f && file_exists("s/".$f); });
      }
      $this->set_text($args[3]);
    }
    else {
      throw new Exception("Post::__construct()");
    }
  }

  # 文字列より設定する
  public function from_s($line)
  {
    $ar = explode(',', trim($line));
    $this->date  = array_shift($ar);
    $this->user  = array_shift($ar);
    $files = explode('|', array_shift($ar));
    $this->files = array_filter($files, function($f){ return $f && file_exists("s/".$f); });
    $this->text  = base64_decode(array_shift($ar));
  }

  # 文字列に出力する
  public function to_s()
  {
    $files = implode("|", $this->files);
    $text = $this->text;
    return $this->date.",".$this->user.",".$files.",".base64_encode($text)."";
  }

  # 投稿本文を安全な状態に加工して設定する
  public function set_text($text)
  {
    if (!Post::valid_text($text)) {
      return false;
    }
    $text = preg_replace('/</', '&lt;', $text);
    $text = preg_replace('/>/', '&gt;', $text);
    $text = preg_replace('/\n/s', '<br>', $text);
    $text = preg_replace('/&gt;&gt;(\d+)/', '<a href="#$1">&gt;&gt;$1</a>', $text);
    $text = preg_replace('/(https?:\/\/[\w\.\-\/~:\?&=%]+)/', '<a href=$1 target=_blank>$1</a>', $text);
    $text = preg_replace('/>(https?:\/\/[\w\.\-\/~:\?&=%]+\.(je?pg|png|gif))<\/a>/i', '><img src=$1 alt=$1></a>', $text);
    $text = preg_replace('/@([\w]{3,})/', '<a href=https://twitter.com/$1 target=_blank>@$1</a>', $text);
    $this->text = $text;
    return true;
  }

  # 投稿本文が正しいかどうか
  public static function valid_text($text)
  {
    return mb_strlen($text) <= 140 && mb_strlen($text) >= 2;
  }
}
?>
