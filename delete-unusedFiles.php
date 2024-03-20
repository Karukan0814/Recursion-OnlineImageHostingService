<?php

spl_autoload_extensions(".php");
spl_autoload_register();

use Helpers\DatabaseHelper;




// テーブルからexpire_datetimeが過去になっているファイルを削除
$deletedImages = DatabaseHelper::deleteAllExpiredImages();


// ログファイル出力
$logFile = __DIR__ . "/logs/logs.log";
$message = "";

if (!is_null($deletedImages) && count($deletedImages) > 0) {

    // 実際のファイルを削除する
    $fileDirectory = __DIR__ . "/img"; // プロジェクトのルートディレクトリに対する相対パス


    // $this->log('fileDirectory :' . $fileDirectory);
    $deletedimgs = [];
    foreach ($deletedImages as $image) {
        // ファイルのパスを作成
        $filePath = $fileDirectory . "/" . $image['uid'] . rawurlencode($image['name']);

        // ファイルが存在する場合は削除
        if (file_exists($filePath)) {

            unlink($filePath);
            $deletedimgs[] = $image['uid'] . rawurlencode($image['name']);
        }
    }
    // ログファイル出力
    $message = "deleted images below :\n" . implode(',', $deletedimgs)."\n";
}else{
    $message = "No expired image.\n";
    
}
file_put_contents($logFile, $message, FILE_APPEND);
