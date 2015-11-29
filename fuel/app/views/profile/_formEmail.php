<?= Form::open(array('class' => 'form-horizontal')); ?>
<fieldset>
    <legend>Update Email</legend>
    <?= Form::csrf(); ?>
    <div class="form-group">
        <?= Form::label('Email', 'email', array('class' => 'control-label')); ?>
        <?= Form::Input('email', $value, array('class' => 'form-control', 'placeholder' => 'Email Address', 'type' => 'email', 'required', 'autofocus')); ?>
    </div>
    <div class="form-group">
        <?= Form::submit('submit', 'Update', array('class' => 'btn btn-primary')); ?>
        <?= Form::reset('reset', 'Reset', array('class' => 'btn btn-default')); ?>
        <?= Html::anchor('profile/edit', 'Cancel', array('class' => 'btn btn-default')); ?>
    </div>
</fieldset>
<?= Form::close(); ?>