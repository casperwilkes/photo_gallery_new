<h2><?= ucwords($photograph->caption); ?></h2>
<p><?= Html::anchor('photographs', '&lt; Back'); ?></p>
<div style="margin-left: 20px;">
    <?= Asset::img($photograph->filename, array('height' => '600', 'width' => '800', 'alt' => $photograph->caption, 'title' => $photograph->caption)); ?>
</div>
<div id="comments">
    <?php
    if ($comments) {
        foreach ($comments as $comment) {
            ?>
            <div class="comment" style="margin-bottom: 2em; ">
                <div class="author">
                    <?= $comment->author; ?> wrote:
                </div>
                <div class="body">
                    <?= $comment->body; ?>
                </div>
                <div class="meta-info" style="font-size: 0.8em;">
                    <?= Helper::date_info($comment->created_at); ?>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<p>No comments yet</p>';
    }
    ?>
</div>
<?php
if (isset($errors)) {
    echo $errors;
}
?>
<?= $form_comments; ?>