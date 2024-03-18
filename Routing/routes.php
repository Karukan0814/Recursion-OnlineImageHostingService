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
                $uploadPath = $uploadDirectory . "/" .$uid. basename($file['name']); // アップロード先のパスを作成（ファイル名を含む）

                if (move_uploaded_file($fileTmpName, $uploadPath)) {

                    // ファイルの移動に成功した場合、画像テーブルに登録
                    try {


                        // 削除用URLを作成
                        $randomBytes = random_bytes(16); // 16バイトのランダムなバイトを生成
                        $uniqueKey = bin2hex($randomBytes); // バイトを16進数に変換
                        $deleteURL = "/delete/?key=" . $uniqueKey;

                        //削除日を設定（一時間後に設定しておく）

                        $now = new DateTime(); // 現在時刻を取得
                        $intervalSpec = "PT1H"; // １時間後に失効にする
                        $now->add(new DateInterval($intervalSpec)); // 時間を加える            
                        $expireDateTime = $now->format('Y-m-d H:i:s'); //形式を整える



                        $result = DatabaseHelper::insertImage($uid, $fileNameRes["value"], $fileTypeRes["value"], $deleteURL,  $expireDateTime);

                        // SQLでファイルの情報をテーブルに格納できたら、ファイル本体をフォルダに格納する            

                        return new HTMLRenderer('register-result', ["uid" => $result["uid"]]);
                    } catch (Exception $e) {
                        // $allErrors = ["registering this file was failed."];
                        return new HTMLRenderer('new-img', ['errors' => $e->getMessage()]);
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

        return new HTMLRenderer('show-image', []);
    },
];
