<?= Form::open(array('class' => 'form-horizontal')); ?>
<fieldset>
    <legend>Update Password</legend>
    <?= Form::csrf(); ?>
    <div class="form-group">
        <?= Form::label('Old Password', 'password1', array('class' => 'control-label')); ?>
        <?= Form::password('password1', Input::post('password1'), array('class' => 'form-control', 'placeholder' => 'Old Password', 'required', 'autofocus')); ?>
    </div>
    <div class="form-group">
        <?= Form::label('New Password', 'password2', array('class' => 'control-label')); ?>
        <?= Form::password('password2', Input::post('password2'), array('class' => 'form-control', 'placeholder' => 'New Password', 'required')); ?>
    </div>
    <div class="form-group">
        <?= Form::label('Re-Type Password', 'password3', array('class' => 'control-label')); ?>
        <?= Form::password('password3', Input::post('password3'), array('class' => 'form-control', 'placeholder' => 'Re-Type Password', 'required')); ?>
    </div>
    <div class="form-group">
        <?= Form::submit('submit', 'Update', array('class' => 'btn btn-primary')); ?>
        <?= Form::reset('reset', 'Reset', array('class' => 'btn btn-default')); ?>
        <?= Html::anchor('profile/edit', 'Cancel', array('class' => 'btn btn-default')); ?>
    </div>
</fieldset>
<?= Form::close(); ?>