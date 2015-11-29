<?= Form::open(array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data')); ?>
<fieldset>
    <legend>Update Profile Image</legend>
    <?= Form::csrf(); ?>
    <div class="form-group">
        <?= Form::label('Select Image', 'image', array('class' => 'control-label')); ?> 
        <?= Form::file('image'); ?>
    </div>
    <div class="form-group">
        <?= Form::submit('submit', 'Update', array('class' => 'btn btn-primary')); ?>
        <?= Form::reset('reset', 'Reset', array('class' => 'btn btn-default')); ?>
        <?= Html::anchor('profile/edit', 'Cancel', array('class' => 'btn btn-default')); ?>
    </div>
</fieldset>
<?= Form::close(); ?>