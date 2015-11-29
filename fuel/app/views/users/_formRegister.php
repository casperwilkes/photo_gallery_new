<?= Form::open(array('class' => 'form-horizontal')); ?>
<fieldset>
    <legend>Register</legend>
    <?= Form::csrf(); ?>
    <div class="form-group">
        <?= Form::label('Username', 'username', array('class' => 'control-label')); ?>
        <?= Form::input('username', Input::post('username'), array('class' => 'form-control', 'placeholder' => 'Username', 'required', 'autofocus')); ?>
    </div>
    <div class="form-group">
        <?= Form::label('Password', 'password', array('class' => 'control-label')); ?>
        <?= Form::password('password', Input::post('password'), array('class' => 'form-control', 'placeholder' => 'Password', 'required')); ?>
    </div>
    <div class="form-group">
        <?= Form::label('Re-Type Password', 'password2', array('class' => 'control-label')); ?>
        <?= Form::password('password2', Input::post('password2'), array('class' => 'form-control', 'placeholder' => 'Re-Type Password', 'required')); ?>
    </div>
    <div class="form-group">
        <?= Form::label('Email Address', 'email', array('class' => 'control-label')); ?>
        <?= Form::input('email', Input::post('email'), array('class' => 'form-control', 'placeholder' => 'Email Address', 'type' => 'email', 'required')); ?>
    </div>
    <div class="form-group">
        <?= Form::submit('submit', 'Register', array('class' => 'btn btn-primary')); ?>
        <?= Form::reset('reset', 'Reset', array('class' => 'btn btn-default')); ?>
    </div>
</fieldset>
<?= Form::close(); ?>