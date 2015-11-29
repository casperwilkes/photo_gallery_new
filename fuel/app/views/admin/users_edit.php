<h2>Edit User: <?= $user->username; ?></h2>

<p><?= Html::anchor('admin/users', 'Back to Users'); ?></p>

<?= Asset::img('profile/thumb/' . $image); ?>

<?= $edit_form; ?>