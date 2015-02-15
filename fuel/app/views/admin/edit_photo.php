<h2>Editing Photograph: <?= $photograph->caption; ?></h2>
<p>
    <?= Html::anchor('admin/', '&lt; Back to Main'); ?>
</p>
<br>
<div style="margin-left: 20px;">
    <?= Fuel\Core\Asset::img($photograph->filename); ?>
</div>
<br>
<?= $form; ?>