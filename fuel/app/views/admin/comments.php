<?= Html::anchor('admin/photos', '&lt; Back') ?>
<br />
<br />
<h2>Comments on <?php echo $photo->caption; ?></h2>
<?= Asset::img('main/thumb/' . $photo->filename, array('height' => '150px')); ?>
<div id="comments">
    <?php
    if ($comments):
        foreach ($comments as $comment) :
            ?>
            <div class="comment" style="margin-bottom: 2em;">
                <div class="author">
                    <?= $comment->user_id; ?> wrote:
                </div>
                <div class="body">
                    <?= $comment->body; ?>
                </div>
                <div class="meta-info" style="font-size: 0.8em;">
                    <?= Helper::date_info($comment->created_at); ?>
                </div>
                <div class="actions" style="font-size: 0.8em;">
                    <?= Fuel\Core\Html::anchor('admin/comments_delete/' . $photo->id . '/' . $comment->id, 'Delete Comment'); ?>
                </div>
            </div>
            <?php
        endforeach;
    else :
        ?>
        No Comments
    <?php endif; ?>
</div>