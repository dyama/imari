# imari

Simple PHP forum system with NO-DB.

**イマリ**は、データベースを必要としないシンプルな PHP 製フォーラムシステムです。

次のことができます。

* ログインとメンバー登録
* スレッドの作成、閲覧、リスト表示
* 返信の書き込み
* ファイルのアップロード
* スレッド内画像の展開表示
* メンバーの閲覧、リスト表示

そんなに多機能ではありませんが、複雑でもないためカスタマイズは簡単だと思います。
自己責任にてお使いください。

## Setup

1. `src` ディレクトリ以下のファイルを PHP が実行可能なところに配置して、`init.php` の値を変更します。
2. `archive` および `s` ディレクトリをサーバー実行権限で write できるようパーミッションを設定します。
3. `index.php` にアクセスし、メンバー登録をしてログインします。

※ 今のところ管理者権限的なものはありません。メンテは基本、シェルログインで行います。

## Screenshots

### スレッドリスト

![thread list](http://dyama.org/res/imari-threadlist.jpg)

### スレッドビュー

![thread view](http://dyama.org/res/imari-threadview.jpg)

### メンバーリスト

![member list](http://dyama.org/res/imari-memberlist.jpg)

### 設定

![config](http://dyama.org/res/imari-config.jpg)

## License

MIT License

