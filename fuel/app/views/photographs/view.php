<h2><?= ucwords($photograph->caption); ?></h2>

<p><?= Html::anchor('photographs', '&lt; Back'); ?></p>

<div style="margin-left: 20px;">
    <?= Asset::img('main/' . $photograph->filename); ?>
</div>

<br>

<?php if ($show_form): ?>
    <?= render('comments/_formComment'); ?>
<?php else: ?>
    <?= Html::anchor('login', 'Login to leave a comment'); ?>
<?php endif; ?>

<div id="comments">
    <?php
    if ($comments) :
        foreach ($comments as $comment) :
            ?>
            <div class="comment" style="margin-bottom: 2em; ">
                <div class="author"><?= $comment->user_id; ?> wrote:</div>
                <div class="body"><?= $comment->body; ?></div>
                <div class="meta-info" style="font-size: 0.8em;"><?= Helper::date_info($comment->created_at); ?></div>
            </div>
            <?php
        endforeach;
    else:
        ?>
        <p>No comments yet</p>
    <?php endif; ?>
</div>
