<h2>Editing Photograph: <?= $photograph->caption; ?></h2>
<p>
    <?= Html::anchor('admin/photos', '&lt; Back to Main'); ?>
</p>
<br>
<div style="margin-left: 20px;">
    <?= Asset::img('main/' . $photograph->filename); ?>
</div>
<br>
<?= $form; ?>