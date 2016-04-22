<?php

include_once "Post.php";

# --------------------
# スレッド クラス
class Thread
{
  public $date = 0;
  public $lastdate = 0;
  public $size = 0;
  public $posts = array();
  public $path = '';
  public $user = '';
  public $title = '';
  public $id = 0;
  public $errmsg = '';

  # スレッドファイルを読み込み自身に内容を設定する
  public function load($file)
  {
    $fp = fopen($file, 'r');
    if (!$fp) {
      $this->errmsg = 'スレッドファイルを開けませんでした。';
      return false;
    }
    if (!flock($fp, LOCK_SH)) {
      fclose($fp);
      $this->errmsg = 'スレッドファイルをロックできませんでした。';
      return false;
    }
    $no = 0;
    $this->posts = array();
    while (!feof($fp)) {
      $post = new Post(fgets($fp));
      if ($post->date == 0) {
        continue;
      }
      array_push($this->posts, $post);
    }
    fclose($fp);
    $this->size = count($this->posts);
    $this->path = $file;
    if ($this->size > 0) {
      $title = mb_strimwidth($this->posts[0]->text, 0, 78, "...");
      $title = str_replace('<br>', ' ', $title);
      $this->title = $title;
      $this->date = $this->posts[0]->date;
      $this->user = $this->posts[0]->user;
      $this->lastdate = $this->posts[$this->size - 1]->date;
    }
    $this->id = preg_replace('/^.*\/(\d+)\.txt$/i', '$1', $file);
    return true;
  }

  # 自身の内容をスレッドファイルに書き出す
  public function save($file = null)
  {
    if (!$file) {
      $file = $this->path;
    }
    $fp = fopen($file, "w+");
    if (!$fp) {
      $this->errmsg = 'スレッドファイルを開けませんでした。';
      return false;
    }
    if (!flock($fp, LOCK_SH)) {
      fclose($fp);
      $this->errmsg = 'スレッドファイルをロックできませんでした。';
      return false;
    }
    foreach ($this->posts as $post) {
      fputs($fp, $post->to_s()."\n");
    }
    fclose($fp);
    chmod($file, 0606);
    return true;
  }

  # スレッドの末尾に投稿を追加する
  public function add($date, $userid, $files, $text)
  {
    if (!Post::valid_text($text)) {
      $this->errmsg = "本文が短いか長すぎます。(2～140文字)";
      return false;
    }
    $post = new Post($date, $userid, $files, $text);
    array_push($this->posts, $post);
    $this->size = count($this->posts);
    $this->lastdate = $post->date;
    if ($this->size == 1) {
      $title = mb_strimwidth($this->posts[0]->text, 0, 78, "...");
      $title = str_replace('<br>', ' ', $title);
      $this->title = $title;
      $this->date = $this->posts[0]->date;
      $this->user = $this->posts[0]->user;
    }
    return true;
  }

  # このスレッド内に最初に現れる添付画像ファイル名を取得する
  private function get_image()
  {
    foreach ($this->posts as $post) {
      foreach ($post->files as $file) {
        if (!preg_match('/.*\.(jpg|png|gif)$/', $file)) {
          continue;
        }
        if (file_exists("s/".$file)) {
          return $file;
        }
      }
    }
    return null;
  }

  # 添付ファイル数の合計を返す
  private function nb_attached()
  {
    $res = 0;
    foreach ($this->posts as $post) {
      foreach ($post->files as $file) {
        if (mb_strlen($file) > 0) {
          $res++;
        }
      }
    }
    return $res;
  }

  # サブジェクトモードの HTML アイテム文字列を生成する
  public function str_subject()
  {
    $res = '<div class=member>';
    $res .= "<a href=index.php?tid=".$this->id.">";
    $f = $this->get_image();
    if (!isset($f)) {
      $f = 'noicon.png';
    }
    else {
      $f = "index.php?f=$f";
    }
    $a = $this->nb_attached();
    $cdate = date("m/d H:i", $this->date);
    $date = date("m/d H:i", $this->lastdate);
    $res .= "<img src=$f width=64 height=64 align=left class=threadimage>";
    $res .= '<span>';
    $res .= "<b>".$this->title."</b></a><br>";
    $res .= "<span class=timestamp>";
    $res .= "<i class='fa fa-comments'></i> ".$this->size."&nbsp;";
    $res .= "<i class='fa fa-paperclip'></i> ".$a."&nbsp;&nbsp;<br>";
    $res .= "<i class='fa fa-calendar'></i> ".$date."&nbsp;";
    $res .= "<i class='fa fa-birthday-cake'></i> ".$cdate."&nbsp;";
    $res .= "<i class='fa fa-user'></i> <a href=index.php?p=member&mid=".$this->user.">".$this->user."</a></span>";
    $res .= '<br clear=all>';
    $res .= '</span>';
    $res .= '</div>';
    return $res;
  }

  # スレッドビューモードの HTML 文字列を生成する
  public function str_thread()
  {
    $res = '';
    $no = 0;
    foreach ($this->posts as $post) {
      $sno = $no + 1;
      $postuid = $post->user;
      $postdate = date("m/d H:i", $post->date);
      $postmesg = $post->text;
      $files = array_filter($post->files, function($f){ return $f && file_exists("s/".$f); });

      $res .= "<div class=rep>\n";
      $res .= "<a name=$sno></a>\n";
      $res .= "<div class=timestamp>$sno : <b><a href=index.php?p=member&mid=$postuid>$postuid</a></b> $postdate</div>\n";
      $res .= "<div class=msgbody>$postmesg </div>\n";
      if (count($files) > 0) {
        $res .= "<hr>\n";
        foreach ($files as $a) {
          if (preg_match('/\.(je?pg|png|gif)$/i', $a)) {
            $res .= "<a href=?f=$a target=_blank><img src=?f=$a height=200px></a>\n";
          }
          elseif (preg_match('/\.(mp4|flv|3gp)$/i', $a)) {
            $res .= "<a href=?f=$a target=_blank><video src=?f=$a height=200px></a>\n";
          }
          else {
            $size = filesize("s/$a");
            $size = round($size / 1024, 1);
            $res .= "<a href=?f=$a target=_blank>$a</a> size:$size KB<br>\n";
          }
        }
      }
      $res .= "</div>\n";
      $no++;
    }
    return $res;
  }
}
?>
