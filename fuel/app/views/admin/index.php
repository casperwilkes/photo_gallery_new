<h2>Menu</h2>
<ul>
    <?php foreach ($subnav as $nav): ?>
        <li><?= Html::anchor($nav['path'], $nav['value']) ?></li>
    <?php endforeach; ?>
</ul>