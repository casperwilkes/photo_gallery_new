<?= Form::open(array('class' => 'form-horizontal')); ?>
<fieldset>
    <legend>Login</legend>
    <?= Form::csrf(); ?>
    <div class="form-group">
        <?= Form::label('Username', 'username', array('class' => 'control-label')); ?>
        <?= Form::input('username', Input::post('username'), array('class' => 'form-control', 'placeholder' => 'Username or Email', 'required', 'autofocus')); ?>
    </div>
    <div class="form-group">
        <?= Form::label('Password', 'password', array('class' => 'control-label')); ?>
        <?= Form::password('password', Input::post('password'), array('class' => 'form-control', 'placeholder' => 'Password', 'required')); ?>
    </div>
    <div class="form-group">
        <?= Form::submit('submit', 'Login', array('class' => 'btn btn-primary')); ?>
    </div>
</fieldset>
<?= Form::close(); ?>