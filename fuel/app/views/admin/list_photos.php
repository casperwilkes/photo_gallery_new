<h2>Photographs</h2>
<?= Html::anchor('admin/index', '&lt; Back to main'); ?>
<br>
<br>
<?php if ($photos): ?>
    <table class="bordered">
        <tr>
            <th>Image</th>
            <th>Filename</th>
            <th>Caption</th>
            <th>Size</th>
            <th>Type</th>
            <th>Comments</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php foreach ($photos as $photo): ?>
            <tr>
                <td><?= Html::anchor('photographs/view/' . $photo->id, Asset::img($photo->filename, array('width' => '100'))); ?></td>
                <td><?= $photo->filename; ?></td>
                <td><?= $photo->caption; ?></td>
                <td><?= Num::format_bytes($photo->size); ?></td>
                <td><?= $photo->type; ?></td>
                <td><?= Html::anchor('admin/comments/' . $photo->id, $photo->comment_count); ?></td>
                <td><?= Html::anchor('admin/edit_photo/' . $photo->id, 'Edit'); ?></td>
                <td><?= Html::anchor('admin/delete_photo/' . $photo->id, 'Delete'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No Photos</p>
<?php endif; ?>
<br>
<?= Html::anchor('admin/upload_photo', 'Upload a new photograph'); ?>