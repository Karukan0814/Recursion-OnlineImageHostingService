<?php
use Database\MySQLWrapper;



if(!$image){

    
    die("Error fetching image by uid " );
}else{

    $imagePath =  "/../img/" . htmlspecialchars($image['uid']) . htmlspecialchars($image['name']);
}

// 画像ファイルのパスを作成


?>
<div id="" style="display: flex;  flex-direction: column;">



<div>id: <?= htmlspecialchars($image['uid']) ?></div>

    <div>title: <?= htmlspecialchars($image['name']) ?></div>
    <div >filetype: <?= htmlspecialchars($image['filetype']) ?></div>
    <div>viewCount: <?= htmlspecialchars($image['viewCount']) ?></div>

</div>


<div id="editor" style="width: 100%; height: 80vh; border: 1px solid slategray; display: flex; justify-content: center; align-items: center;">
    <img src=<?= $imagePath ?> alt=<?= htmlspecialchars($image['name']) ?> style="max-width: 600px; height: auto;">
</div>


