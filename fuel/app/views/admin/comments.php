<?= Html::anchor('admin/list_photos', '&lt; Back to Main') ?>
<br />
<br />
<h2>Comments on <?= $photo->caption; ?></h2>
<div id="comments">
    <?php
    if ($comments):
        foreach ($comments as $comment) :
            ?>
            <div class="comment" style="margin-bottom: 2em;">
                <div class="author">
                    <?= $comment->author; ?> wrote:
                </div>
                <div class="body">
                    <?= $comment->body; ?>
                </div>
                <div class="meta-info" style="font-size: 0.8em;">
                    <?= Helper::date_info($comment->created_at); ?>
                </div>
                <div class="actions" style="font-size: 0.8em;">
                    <?= Fuel\Core\Html::anchor('admin/delete_comment/' . $comment->id, 'Delete Comment'); ?>
                </div>
            </div>
            <?php
        endforeach;
    else :
        ?>
        No Comments
    <?php endif; ?>
</div>