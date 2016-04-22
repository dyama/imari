<?php

class Post
{
  public $user = '';
  public $text = '';
  public $date = 0;
  public $files = array();
  public $index = 0;

  public function __construct(...$args)
  {
    if (count($args) == 1) {
      $this->from_s($args[0]);
    }
    elseif (count($args) == 4) {
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

  public function from_s($line)
  {
    $ar = explode(',', trim($line));
    $this->date  = array_shift($ar);
    $this->user  = array_shift($ar);
    $files = explode('|', array_shift($ar));
    $this->files = array_filter($files, function($f){ return $f && file_exists("s/".$f); });
    $this->text  = base64_decode(array_shift($ar));
  }

  public function to_s()
  {
    $files = implode("|", $this->files);
    $text = $this->text;
    return $this->date.",".$this->user.",".$files.",".base64_encode($text)."";
  }

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

  public static function valid_text($text)
  {
    return mb_strlen($text) <= 140 && mb_strlen($text) >= 2;
  }
}
?>
