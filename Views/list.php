<?php


?>
<div class="container"  style="height: 80vh;">
<div class="row">
        <?php if (is_null($images) || count($images) === 0): ?>
            <div class="col">
                <p>画像は登録されていません。</p>
            </div>
        <?php else: ?>
            <?php foreach ($images as $image): ?>
                <a href="/show?uid=<?= htmlspecialchars($image['uid']) ?>" class="text-decoration-none">
                <div class="col-md-4">

                <div class="card mb-4" style="aspect-ratio: 3 / 2;">
                <img src="<?= '/img/' . htmlspecialchars($image['uid']) . rawurlencode($image['name']) ?>" class="card-img-top" alt="<?= htmlspecialchars($image['name']) ?>" style="object-fit: contain; height: 100%; width: 100%;">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($image['name']) ?></h5>
                    <p class="card-text">viewcount: <?= $image['viewCount'] ? htmlspecialchars($image['viewCount']) : "0" ?></p>
                </div>
            </div>
        </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
