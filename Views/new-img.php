<?php

use Database\MySQLWrapper;

// データベース接続を初期化します
$db = new MySQLWrapper();



//スニペットを作成するための初期画面



// phpによる処理をすべき場合は前段に記述

?>
<div class="container">
<div>
    <?php if (!empty($errors)) : ?>
        <?php foreach ($errors as $error) : ?>
            <div class="alert alert-info"><?= htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
    <div class="row">
        <div class="col">
            <h2>画像アップロード</h2>
            <form id="upload-form" action="register" method="post" enctype="multipart/form-data">
                <input type="file" id="image-input" name="image" accept="image/*">
                <button type="submit">upload</button>
            </form>
            <div id="preview"></div>


        </div>
    </div>
</div>
<script>
    document.getElementById('image-input').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview');
                preview.innerHTML = '<img src="' + e.target.result + '" alt="Image preview">';
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('upload-form').addEventListener('submit', function(event) {
        event.preventDefault(); // まずフォームの送信を停止

        const fileInput = document.getElementById('image-input');
        const file = fileInput.files[0];

        // ファイルがアップロードされているか確認
        if (!file) {
            alert('ファイルが選択されていません。');
            return;
        }

        // ファイルの形式を確認
        const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!validImageTypes.includes(file.type)) {
            alert('JPEG、PNG、またはGIF形式の画像を選択してください。');
            return;
        }

        // すべてのチェックが通った場合、フォームを送信
        this.submit();
    });
</script>