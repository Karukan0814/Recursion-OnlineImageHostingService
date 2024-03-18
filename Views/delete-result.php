<?php


  

?>
<div>

<div>
    <?php if ($result): ?>
        <p>Your img has been deleted successfully!</p>
    <?php else: ?>
        <p>Something wrong happened.</p>
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-info"><?= htmlspecialchars($error); ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>