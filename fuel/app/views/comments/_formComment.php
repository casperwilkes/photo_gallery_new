<?= Form::open(array('class' => 'form-horizontal')); ?>
<fieldset>
    <legend>Add a Comment</legend>
    <?= Form::csrf(); ?>
    <div class="form-group">
        <?= Form::label('Comment', 'comment', array('class' => 'control-label')); ?>
        <?= Form::textarea('comment', Input::post('comment'), array('class' => 'form-control', 'placeholder' => 'What would you like to say?', 'rows' => 4, 'cols' => 40, 'required')); ?>
    </div>
    <div class="form-group">
        <?= Form::submit('submit', 'Update', array('class' => 'btn btn-primary')); ?>
    </div>
</fieldset>
<?= Form::close(); ?>