<?php
# 設定

# ログインを許可する
$conf['login'] = true;

# メンバー登録を許可する
$conf['regist'] = true;

# スレッドを格納するアーカイブディレクトリのパス
$conf['adir'] = './archive';

# メンバー管理ファイルのパス
$conf['memberfile'] = 'member.php';

# スレッドあたりの最大レス数
$conf['maxrep'] = 100;

# ファイルのアップロードを許可する
$conf['upload'] = true;

# アップロード可能サイズ(bytes)
$conf['upsize'] = 1024 * 1024 * 2;

# 一度にアップロードできるファイル数
$conf['upcount'] = 10;

# アップロード可能拡張子
$conf['exts'] = array('jpg', 'jpeg', 'png', 'gif', 'mp4', '3gp', 'mov', 'flv', 'zip', '7z', 'cbz', 'xz', 'gz', 'bz2');

?>
