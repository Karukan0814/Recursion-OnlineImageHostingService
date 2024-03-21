# Recursion-Online Image Hosting Service

![service-image](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/26a5d8c88af43029e10e0edb17eec431182262e7/assets/OnlineImageHostingServiceDemo.gif)

# 概要

[Imgur](https://imgur.com/) のように、ユーザーアカウントを必要とせずに画像をアップロード、共有、表示できるオンライン画像ホスティングサービス。
ユーザーが自分の画像をアップロードするためのシンプルなウェブインターフェースを提供する。アップロード後、画像はサーバ上に保存され、画像のための一意な URL が生成される。ユーザーはこのリンクを他の人と共有し、リンクにアクセスするだけで画像を表示可能。

# 使用方法

1. 画像のアップロード
   アップロード画面から画像をアップロードできる。形式は一般的な画像形式である jpeg, png, gif に限る。
   画像をアップロード完了すると、2 つのリンク先がが表示される。
   上は画像の表示ページ。下は削除用 URL。アップロードしたユーザーがこの URL にアクセスすると画像がサーバーから削除される。

   ![service-image](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/main/assets/uploadImgsDemo.gif)

2. 画像の一覧
   アップロードされた画像の一覧はホーム画面から閲覧できる。

   ![service-image](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/main/assets/list_example.png)

3. 一日あたりアップロード回数とファイルサイズの制限
   　定数は[Constants.php](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/main/Helpers/Constants.php)で管理している。
   初期値は以下のように設定している。

```
    const EXPIRE_SPAN = 'P1D'; // アップロードしたファイルの有効期限
    const MAX_FILES=5;//一日当たりのアップロードファイル数
    const MAX_FILESIZE_SUM=10* 1024 * 1024;//一日当たりのアップロードサイズ上限
    const MAX_FILESIZE_UPLOAD=2 * 1024 * 1024;//一回当たりのアップロードサイズ上限
```

4. 画像の有効期限と期限切れファイルの自動削除
   [delete-unusedFiles.php](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/main/delete-unusedFiles.php)を cron ジョブで動かすことで期限切れ画像を自動削除することが可能。ジョブの設定方法は開発環境の構築で記述。

# 開発環境の構築

開発環境を Docker を使用して立ち上げることが可能。以下、その手順。

1. 当該レポジトリをローカル環境にコピー

2. 環境変数ファイルの準備
   　.env ファイルをルートフォルダ直下に用意し、以下を記述して保存する。

```
DATABASE_NAME=practice_db
DATABASE_USER=任意のユーザー名
DATABASE_USER_PASSWORD=任意のパスワード
DATABASE_HOST=db


```

3. Docker ビルド
   　以下を実行してビルド。なお、以下は Docker がインストール済みであることを前提とする。

```
docker compose build
```

4. Docker 立ち上げ
   　以下を実行してコンテナを立ち上げ。

```
docker compose up -d
```

5. DB Migration 実行
   　以下を実行して初期テーブルの構築。

```
docker-compose exec web php console migrate --init
```

6. 動作確認
   　[http://localhost:8080/](http://localhost:8080/)にアクセスして動作確認

# Recursion-Online Image Hosting Service

![service-image](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/26a5d8c88af43029e10e0edb17eec431182262e7/assets/OnlineImageHostingServiceDemo.gif)

# 概要

[Imgur](https://imgur.com/) のように、ユーザーアカウントを必要とせずに画像をアップロード、共有、表示できるオンライン画像ホスティングサービス。
ユーザーが自分の画像をアップロードするためのシンプルなウェブインターフェースを提供する。アップロード後、画像はサーバ上に保存され、画像のための一意な URL が生成される。ユーザーはこのリンクを他の人と共有し、リンクにアクセスするだけで画像を表示可能。

# 使用方法

1. 画像のアップロード
   アップロード画面から画像をアップロードできる。形式は一般的な画像形式である jpeg, png, gif に限る。
   画像をアップロード完了すると、2 つのリンク先がが表示される。
   上は画像の表示ページ。下は削除用 URL。アップロードしたユーザーがこの URL にアクセスすると画像がサーバーから削除される。

   ![service-image](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/main/assets/uploadImgsDemo.gif)

2. 画像の一覧
   アップロードされた画像の一覧はホーム画面から閲覧できる。

   ![service-image](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/main/assets/list_example.png)

3. 一日あたりアップロード回数とファイルサイズの制限
   　定数は[Constants.php](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/main/Helpers/Constants.php)で管理している。
   初期値は以下のように設定している。

```
    const EXPIRE_SPAN = 'P1D'; // アップロードしたファイルの有効期限
    const MAX_FILES=5;//一日当たりのアップロードファイル数
    const MAX_FILESIZE_SUM=10* 1024 * 1024;//一日当たりのアップロードサイズ上限
    const MAX_FILESIZE_UPLOAD=2 * 1024 * 1024;//一回当たりのアップロードサイズ上限
```

4. 画像の有効期限と期限切れファイルの自動削除
   [delete-unusedFiles.php](https://github.com/Karukan0814/Recursion-OnlineImageHostingService/blob/main/delete-unusedFiles.php)を cron ジョブで動かすことで期限切れ画像を自動削除することが可能。ジョブの設定方法は開発環境の構築で記述。

# 開発環境の構築

開発環境を Docker を使用して立ち上げることが可能。以下、その手順。

1. 当該レポジトリをローカル環境にコピー

2. 環境変数ファイルの準備
   　.env ファイルをルートフォルダ直下に用意し、以下を記述して保存する。

```
DATABASE_NAME=practice_db
DATABASE_USER=任意のユーザー名
DATABASE_USER_PASSWORD=任意のパスワード
DATABASE_HOST=db


```

3. Docker ビルド
   　以下を実行してビルド。なお、以下は Docker がインストール済みであることを前提とする。

```
docker compose build
```

4. Docker 立ち上げ
   　以下を実行してコンテナを立ち上げ。

```
docker compose up -d
```

5. DB Migration 実行
   　以下を実行して初期テーブルの構築。

```
docker-compose exec web php console migrate --init
```

6. 動作確認
   　[http://localhost:8080/](http://localhost:8080/)にアクセスして動作確認

7. Cron ジョブの設定
   　期限切れの画像を一日に一度、一括削除するバッチ処理を動かす。
   　※以下は WSL 環境下での設定方法です。
   - ①. WSL で cron ジョブの設定ファイルを開く
     ```
     crontab -e
     ```
   - ②. 以下を追加の上、保存
     ```
     0 0 * * * cd /mnt/[プロジェクトのディレクトリ] && /usr/bin/php delete-unusedFiles.php
     ```
   - ③. cron ジョブをスタートさせる
     ```
     sudo service cron start
     ```
