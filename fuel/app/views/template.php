<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?= $title; ?></title>
        <?= Asset::css('main.css'); ?>
        <?= Asset::js('jquery.min.js'); ?>
        <link rel="icon" href="<?php echo Uri::base(false); ?>favicon.ico" type="image/x-icon" /> 
    </head>
    <body>
        <?php $auth = Auth::instance(); ?>
        <div id="header">
            <?php if ($auth->check() and $auth->get('group', 2) == 1): ?>
                <h1><?= Html::anchor('admin/', 'Photo Gallery: Admin', array('style' => 'text-decoration: none; color: #D4E6F4')); ?></h1>
            <?php else: ?>
                <h1><?= Html::anchor('photographs/', 'Photo Gallery', array('style' => 'text-decoration: none; color: #D4E6F4')); ?></h1>
            <?php endif; ?>
            <div id="log_in_out">
                <?php
                if ($auth->check()) {
                    $ul = array(
                        Html::anchor('profile/', $auth->get_screen_name(), array('class' => 'logout')) . ' ',
                        Html::anchor('logout', 'Logout', array('class' => 'logout'))
                    );
                    $attr = array('id' => 'nav');
                    echo Html::ul($ul, $attr);
                } else {
                    echo Html::anchor('login', 'Login', array('class' => 'login'));
                }
                ?>
            </div>
        </div>
        <div id="main">
            <div id="container">
                <?php if (Session::get_flash('success')): ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong>
                        <p>
                            <?php echo implode('</p><p>', e((array) Session::get_flash('success'))); ?>
                        </p>
                    </div>
                <?php endif; ?>
                <?php if (Session::get_flash('error')): ?>
                    <div class="alert alert-danger">
                        <strong>Error</strong>
                        <p>
                            <?php echo implode('</p><p>', e((array) Session::get_flash('error'))); ?>
                        </p>
                    </div>
                <?php endif; ?>
                <?php echo $content; ?>
            </div>
        </div>
        <footer>
            <div id="footer">
                Copyright 2002-<?php echo date("Y", time()); ?>, Pawtucket Inc.
            </div>
        </footer>
        <script>
            $('#nav').on({
                mouseenter: function() {
                    $('#nav li').css('display', 'block');
                },
                mouseleave: function() {
                    $('#nav li:nth-child(n+2)').css('display', 'none');
                }
            });
        </script>
    </body>
</html>
