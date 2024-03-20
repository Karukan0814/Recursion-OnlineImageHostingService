<?php


// プロトコルを取得
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';

// ホスト名を取得
$host = $_SERVER['HTTP_HOST'];


// 完全なURLを組み立てる
$linkUrl = $protocol . '://' . $host . "/show?uid=" . $image["uid"];
$deleteUrl = $protocol . '://' . $host . "/delete?key=" . $image["deleteKey"];

?>
<div class="container" >

    <div>
        <?php if (!empty($errors)) : ?>
            <?php foreach ($errors as $error) : ?>
                <div class="alert alert-info"><?= htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?= $image ?
        '<p>Your text has been registered successfully! Link is below.</p>' .
        '<a href="' . htmlspecialchars($linkUrl) . '">' . htmlspecialchars($linkUrl) . '</a>' .
        '<p>If you would like to delete this img, please click the link below!</p>' .
        '<a href="' . htmlspecialchars($deleteUrl) . '">' . htmlspecialchars($deleteUrl) . '</a>' :
        '<p>Registering your snippet was failed!</p>' ?>
</div>