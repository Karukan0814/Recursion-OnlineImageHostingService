<?php

use Database\MySQLWrapper;



if (!$image) {


    print_r("Error fetching image by uid ");
} else {

    $imagePath =  "/../img/" . htmlspecialchars($image['uid']) . htmlspecialchars($image['name']);
}

// 画像ファイルのパスを作成


?>
<div class="container" >

    <div style="display: flex;  flex-direction: column; ">
        <div>
            <?php if (!empty($errors)) : ?>
                <?php foreach ($errors as $error) : ?>
                    <div class="alert alert-info"><?= htmlspecialchars($error); ?></div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <div>id: <?= htmlspecialchars($image['uid']) ?></div>
        <div>title: <?= htmlspecialchars($image['name']) ?></div>
        <div>filetype: <?= htmlspecialchars($image['filetype']) ?></div>
        <div>viewCount: <?= htmlspecialchars($image['viewCount']) ?></div>

    </div>


    <div style="width: 100%;   display: flex; justify-content: center; align-items: center;">
        <img src=<?= $imagePath ?> alt=<?= htmlspecialchars($image['name']) ?> style="max-width: 600px; height: auto;">
    </div>
</div>