<?php
use Database\MySQLWrapper;


print_r($image);
print_r($errors);

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
    <div>expire: <?= $image['expire_datetime'] ? htmlspecialchars($image['expire_datetime']) : "never" ?></div>

</div>

<div id="editor" style="width: 100%; height: 80vh; border: 1px solid slategray; position:relative">
<img src=<?= $imagePath?> alt=<?= htmlspecialchars($image['name']) ?>>
</div>



