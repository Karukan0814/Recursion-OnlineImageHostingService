<?php

use Helpers\DatabaseHelper;
use Helpers\ValidationHelper;
use Response\HTTPRenderer;
use Response\Render\HTMLRenderer;
use Response\Render\JSONRenderer;




return [
    '' => function (): HTTPRenderer {
        //初期表示　スニペットリストページ

        //期限切れのスニペット全てを削除する
        DatabaseHelper::deleteAllExpiredSnippets();

        // 登録してある全てのスニペットを表示
        $snippets = DatabaseHelper::getAllSnippets();
        return new HTMLRenderer('list', ['snippets' => $snippets]);
    },
    'create' => function (): HTTPRenderer {
        //スニペット作成ページ
        // $part = DatabaseHelper::getRandomComputerPart();
        return new HTMLRenderer('new-img', []);
    },
    'register' => function (): HTTPRenderer {
        // 画像の登録→表示ページへ
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 登録する画像のバリデーション（必要なら）
            if (isset($_FILES['image'])) {

                // 一定の時間内にアップロードできるファイルの数やデータ量の制限に引っ掛かっていないか

                // 制限パラメータ
                $maxFiles = 5; // 1時間あたりの最大アップロードファイル数
                $maxSize = 10* 1024 * 1024; // 1時間あたりの最大アップロードサイズ（10MB）

                // 現在の時間
                $currentTime = time();

                // ユーザー識別子（IPアドレスまたはセッションID）
                $userIdentifier = $_SERVER['REMOTE_ADDR']; // または session_id();
                // ユーザーのアップロード履歴を取得
                $uploadHistory = $_SESSION['upload_history'][$userIdentifier] ?? ['count' => 0, 'size' => 0, 'time' => $currentTime];

                // アップロード制限のチェック
                if ($uploadHistory['count'] >= $maxFiles || $uploadHistory['size'] >= $maxSize) {
                    die("Upload limit exceeded. Please try again later.");
                }

                $file = $_FILES['image'];
                $fileNameRes = ValidationHelper::validateText($file['name'] ?? null, 1, 100);
                $fileTypeRes = ValidationHelper::validateFileType($file['type'] ?? null);

                $fileTmpName = $file['tmp_name'];



                if (count($fileNameRes["error"]) > 0 || count($fileTypeRes["error"]) > 0) {
                    $allErrors = array_merge($fileNameRes["error"], $fileTypeRes["error"]);
                    //全てのエラーを初期ページに引き渡す
                    return new HTMLRenderer('new-img', ['errors' => $allErrors]);
                }

                //uidを生成
                $currentTime = new DateTime();
                $timestamp = $currentTime->getTimestamp();
                $randomNumber = random_int(1, 500); // 安全なランダムな整数を生成
                $uid = strval($randomNumber) . strval($timestamp);

                // ファイルの保存処理
                $uploadDirectory = $_SERVER['DOCUMENT_ROOT'] . "/img"; // プロジェクトのルートディレクトリに対する相対パス
                $uploadPath = $uploadDirectory . "/" . $uid . rawurlencode(basename($file['name'])); // アップロード先のパスを作成（ファイル名を含む）

                if (move_uploaded_file($fileTmpName, $uploadPath)) {

                    // ファイルの移動に成功した場合、画像テーブルに登録
                    try {


                        // 削除用URLを作成
                        $randomBytes = random_bytes(16); // 16バイトのランダムなバイトを生成
                        $deleteKey = bin2hex($randomBytes); // バイトを16進数に変換
                        // $deleteURL = "/delete/?key=" . $uniqueKey;

                        //削除日を設定（一時間後に設定しておく）

                        $now = new DateTime(); // 現在時刻を取得
                        $intervalSpec = "PT1H"; // １時間後に失効にする
                        $now->add(new DateInterval($intervalSpec)); // 時間を加える            
                        $expireDateTime = $now->format('Y-m-d H:i:s'); //形式を整える



                        $result = DatabaseHelper::insertImage($uid, $fileNameRes["value"], $fileTypeRes["value"], $deleteKey,  $expireDateTime);

                        // セッションにアップロード履歴を入れる
                        $uploadHistory['count']++;
                        $uploadHistory['size'] += $_FILES['image']['size'];
                        $_SESSION['upload_history'][$userIdentifier] = $uploadHistory;


                        return new HTMLRenderer('register-result', ["image" => $result]);
                    } catch (Exception $e) {
                        $allErrors = [$e->getMessage()];
                        return new HTMLRenderer('new-img', ['errors' => $allErrors]);
                    }
                } else {
                    // ファイルの移動に失敗した場合
                    $allErrors = ["ファイルのアップロードに失敗しました。"];
                    return new HTMLRenderer('new-img', ['errors' => $allErrors]);
                }
            } else {
                $allErrors = ["this file is empty."];
                return new HTMLRenderer('new-img', ['errors' => $allErrors]);
            }
        } else {
            // リクエストがPOSTでないなら作成ページにとばす
            $allErrors = ["リクエストが不正です"];
            return new HTMLRenderer('new-img', ['errors' => $allErrors]);
        }
    },
    'show' => function (): HTTPRenderer {
        // 指定されたスニペットの表示ページ
        $allErrors = [];
        // GETで渡されたuidの受け取り
        $uid = $_GET['uid'] ?? null;
        if (is_null($uid)) {

            $allErrors[] = "uid is necessary.";
            return new HTMLRenderer('show-image', ['errors' => $allErrors]);
        }

        // 閲覧回数とexpire_datetimeの更新
        $updateResult = DatabaseHelper::incrementViewCount($uid);
        if (!$updateResult) {
            $allErrors[] = "updating info failed.";
            return new HTMLRenderer('show-image', ['errors' => $allErrors]);
        }

        // 画像情報の取得

        $image = DatabaseHelper::getImageByUid($uid);

        return new HTMLRenderer('show-image', ['image' => $image]);
    },
    'delete' => function (): HTTPRenderer {
        // 指定されたスニペットの表示ページ
        $allErrors = [];
        // GETで渡されたkeyの受け取り
        $key = $_GET['key'] ?? null;
        if (is_null($key)) {

            $allErrors[] = "secret key is necessary.";
            return new HTMLRenderer('show-image', ['errors' => $allErrors]);
        }

        // 該当のキーのimageの情報を検索
        $image = DatabaseHelper::getImageByDeleteKey($key);
        if (!$image) {
            $allErrors[] = "Image does not exist.";
            return new HTMLRenderer('delete-result', ['errors' => $allErrors]);
        }

        // 該当のimageの情報をテーブルから削除する
        $deleteResult = DatabaseHelper::deleteImageByDeleteKey($key);
        if (!$deleteResult) {
            $allErrors[] = "deleting image failed.";
            return new HTMLRenderer('delete-result', ['errors' => $allErrors]);
        }

        // ファイルの保存処理
        $fileDirectory = $_SERVER['DOCUMENT_ROOT'] . "/img"; // プロジェクトのルートディレクトリに対する相対パス
        $fielPath = $fileDirectory . "/" . $image['uid'] . rawurlencode($image['name']); // アップロード先のパスを作成（ファイル名を含む）
        print_r($fielPath);

        $result = false;
        if (unlink($fielPath)) {
            $result = true;

            return new HTMLRenderer('delete-result', ['result' => $result]);
        }
        return new HTMLRenderer('delete-result', ['result' => $result]);
    },
];
