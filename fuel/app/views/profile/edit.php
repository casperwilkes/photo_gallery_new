<h2><?= $username; ?></h2>
<h3>Update  <?= $segment ?></h3>

<?= Html::anchor('profile/edit', '&lt; Return to Edit'); ?>

<br>
<br>

<?= render($form); ?>