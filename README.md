# imari

Simple PHP forum system with NO-DB.

**イマリ**は、データベースを必要としないシンプルな PHP 製フォーラムシステムです。
クライアント側も JavaScript を必要としません。非同期通信も行いません。

次のことができます。

* ログインとメンバー登録
* スレッドの作成、閲覧、リスト表示
* 返信の書き込み
* ファイルのアップロード
* スレッド内画像の展開表示、URL自動リンク、Twitter自動リンク
* 安価自動リンク
* メンバーの閲覧、リスト表示
* メンバーアイコンの設定、自己紹介の設定
* 一部レイアウト調整が崩れるものの、スマホでも全機能を利用可能

そんなに多機能ではありませんが、複雑でもないためカスタマイズは簡単だと思います。
自己責任にてお使いください。

## Setup

1. `src` ディレクトリ以下のファイルを PHP が実行可能なところに配置して、必要であれば設定ファイル `init.php` の値を変更します。
2. `archive` (スレッドデータ管理ディレクトリ)および `s` (添付ファイル格納ディレクトリ)をサーバー実行権限で write できるようパーミッションを設定します。
3. `index.php` にアクセスし、メンバー登録をしてログインします。

## Hint

* 今のところ管理者権限的なものはありません。メンテは基本、シェルログインで行います。
* スレッドはUNIXタイムをキーにして管理しています。1秒間に大量のスレッドを作成する可能性がある運用には向いていません。
* 数人の内輪で利用することを想定しています。

## Screenshots

### スレッドリスト

![thread list](http://dyama.org/res/imari-threadlist.jpg)

### スレッドビュー

![thread view](http://dyama.org/res/imari-threadview.jpg)

### メンバーリスト

![member list](http://dyama.org/res/imari-memberlist.jpg)

※メンバーアイコンの一部は、[こちら](http://pringo.blog.shinobi.jp/)をお借りしました。

### 設定

![config](http://dyama.org/res/imari-config.jpg)

## License

MIT License

