<h2><?= $username; ?> profile</h2>

<p><?= Html::anchor('photographs/', '&lt; Back to Main'); ?></p>

<div id="prof_image" style="float: left;">
    <?= Asset::img('profile/thumb/' . $image); ?>
</div>

<div id="bio" style="float: left; margin-left: 20px; border: 1px inset; width: 400px;">
    <div style="background-color: aliceblue; text-align: center; font-size: 1em; border-bottom: 1px solid black">
        User Bio
    </div>
    <p style="padding: 0 1em;"><?= $bio; ?></p>
</div>

<?php if (Auth::get_screen_name() == $username): ?>
    <div style="clear: left; overflow: hidden;">
        <p><?= Html::anchor('profile/edit', 'Edit Profile'); ?></p>
        <p><?= Html::anchor('logout', 'Logout'); ?></p>
    </div>
<?php endif; ?>