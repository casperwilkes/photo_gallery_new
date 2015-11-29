<h2>Update <?= $username; ?></h2>

<?= Html::anchor('profile/', '&lt; Return to Profile'); ?>

<p style="font-size:1.1em;">What would you like to edit:</p>

<ul>
    <?php foreach ($menu as $k): ?>
        <li><?= Html::anchor('profile/edit/' . $k, ucfirst($k), array('title' => 'Edit ' . ucfirst($k))); ?></li>
    <?php endforeach; ?>
</ul>
