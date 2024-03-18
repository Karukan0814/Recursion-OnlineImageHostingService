<?php
use Database\MySQLWrapper;



// URLクエリパラメータを通じてIDが提供されたかどうかをチェックします。
$uid = $_GET['uid'] ?? null;
if ($uid ){
    
    // データベース接続を初期化します。
    $db = new MySQLWrapper();
    
    try {
        // IDでスニペットを取得するステートメントを準備します。
        $stmt = $db->prepare("SELECT * FROM images WHERE uid = ?");
        $stmt->bind_param('s', $uid);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $image = $result->fetch_assoc();
        if (!$image){
            throw new Exception("this image does not exist");
        }

    } catch (Exception $e) {
        die("Error fetching image by uid: " . $e->getMessage());
    }
}else{
    die("uid is necessary." );

}

// 画像ファイルのパスを作成
$imagePath =  "/../img/" . htmlspecialchars($image['uid']) . htmlspecialchars($image['name']);


?>
<div id="" style="display: flex;  flex-direction: column;">



<div>id: <?= htmlspecialchars($image['uid']) ?></div>

    <div>title: <?= htmlspecialchars($image['name']) ?></div>
    <div >filetype: <?= htmlspecialchars($image['filetype']) ?></div>
    <div>expire: <?= $image['expire_datetime'] ? htmlspecialchars($image['expire_datetime']) : "never" ?></div>

</div>

<div id="editor" style="width: 100%; height: 80vh; border: 1px solid slategray; position:relative">
<img src=<?= $imagePath?> alt=<?= htmlspecialchars($image['name']) ?>>
</div>



