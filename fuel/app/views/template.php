<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $title; ?></title>
        <?= Asset::css('main.css'); ?>
    </head>
    <body>
        <?php $auth = Auth::instance(); ?>
        <div id="header">
            <?php if ($auth->check()): ?>
                <h1><?= Html::anchor('admin/', 'Photo Gallery: Admin', array('style' => 'text-decoration: none; color: #D4E6F4')); ?></h1>
            <?php else: ?>
                <h1><?= Html::anchor('photographs/', 'Photo Gallery', array('style' => 'text-decoration: none; color: #D4E6F4')); ?></h1>
            <?php endif; ?>
        </div>
        <div id="main">
            <div id="container">
                <?php if (Session::get_flash('success')): ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong>
                        <p>
                            <?= implode('</p><p>', e((array) Session::get_flash('success'))); ?>
                        </p>
                    </div>
                <?php endif; ?>
                <?php if (Session::get_flash('error')): ?>
                    <div class="alert alert-danger">
                        <strong>Error</strong>
                        <p>
                            <?= implode('</p><p>', e((array) Session::get_flash('error'))); ?>
                        </p>
                    </div>
                <?php endif; ?>
                <?php echo $content; ?>
            </div>
        </div>
        <footer>
            <div id="footer">
                Copyright 2002-<?= date("Y", time()); ?>, Photo-Corp
            </div>
        </footer>
    </body>
</html>