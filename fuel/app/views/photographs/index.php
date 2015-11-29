<?php if ($photographs): ?>
    <?php foreach ($photographs as $photo): ?>
        <tr>
        <div style="float: left; margin-left: 20px;">
            <?= Fuel\Core\Html::anchor('photographs/view/' . $photo->id, Asset::img('main/thumb/' . $photo->filename, array('width' => '200', 'alt' => 'image'))); ?>
            <p><?= ucwords($photo->caption); ?></p>
        </div>
    <?php endforeach; ?>
    <div id="pagination" style="clear:both;">
        <?= Pagination::instance('paginate')->render(); ?>
    </div>
<?php else: ?>
    <p>No Photographs To Display Yet.</p>
<?php endif; ?>