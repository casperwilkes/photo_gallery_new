<?= Form::open(array('class' => 'form-horizontal')); ?>
<fieldset>
    <legend>Update Bio</legend>
    <?= Form::csrf(); ?>
    <div class="form-group">
        <?= Form::label('Bio', 'bio', array('class' => 'control-label')); ?>
        <?= Form::textarea('bio', $value, array('class' => 'form-control', 'placeholder' => 'What would you like to say about yourself?', 'rows' => 4, 'required', 'autofocus')); ?>
    </div>
    <div class="form-group">
        <?= Form::submit('submit', 'Update', array('class' => 'btn btn-primary')); ?>
        <?= Form::reset('reset', 'Reset', array('class' => 'btn btn-default')); ?>
        <?= Html::anchor('profile/edit', 'Cancel', array('class' => 'btn btn-default')); ?>
    </div>
</fieldset>
<?= Form::close(); ?>