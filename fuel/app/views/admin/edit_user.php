<h2>Edit User: <?= $user->username; ?></h2>
<p><?= Html::anchor('admin/', '&lt; Back to Main'); ?></p>
<p>Use this panel to edit the email and password for this account.</p>
<?php
if (isset($errors)) {
    echo $errors;
}
?>
<?= $edit_form; ?>