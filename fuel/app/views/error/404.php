<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>oops</title>
        <?= Asset::css('main.css'); ?>
    </head>
    <body>
        <div id="header">
            <h1><?= Html::anchor('photographs/', 'Photo Gallery', array('style' => 'text-decoration: none; color: #D4E6F4')); ?></h1>
        </div>
        <div id="main">
            <div id="container">
                <h2><?= $title; ?></h2>
                <p>We can't find that</p>
                <?= Html::anchor('/', 'Go Back', array('title' => 'Go Back Home')); ?>
            </div>
        </div>
        <footer>
            <div id="footer">
                Copyright 2002-<?= date("Y", time()); ?>, Photo-Corp
            </div>
        </footer>
    </body>
</html>
