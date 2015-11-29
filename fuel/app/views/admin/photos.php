<h2>Photographs</h2>

<?= Html::anchor('admin/index', 'Back to main'); ?>
<br>
<br>

<?php if ($photos): ?>
    <table class="bordered">
        <tr>
            <th>Image</th>
            <th>Caption</th>
            <th>Filename</th>
            <th>Size</th>
            <th>Type</th>
            <th>Comments</th>
            <th>View</th>
            <th>Remove</th>
        </tr>
        <?php foreach ($photos as $photo): ?>
            <tr>
                <td><?= Html::anchor('admin/photos_edit/' . $photo->id, Asset::img('main/thumb/'.$photo->filename, array('width' => '100'))); ?></td>
                <td><?= $photo->caption; ?></td>
                <td><?= $photo->filename; ?></td>
                <td><?= Num::format_bytes($photo->size); ?></td>
                <td><?= $photo->type; ?></td>
                <td><?= Html::anchor('admin/comments/' . $photo->id, $photo->comment_count); ?></td>
                <td><?= Html::anchor('admin/photos_edit/' . $photo->id, 'Edit'); ?></td>
                <td><?= Html::anchor('admin/photos_delete/' . $photo->id, 'Delete'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No Photos</p>
<?php endif; ?>
<br>
<?= Html::anchor('admin/photos_new', 'Upload a new photograph'); ?>