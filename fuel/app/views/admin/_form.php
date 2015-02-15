<?= Form::open(array("class" => "form-horizontal", 'enctype' => 'multipart/form-data')); ?>
<fieldset>
    <div class="form-group">
        <?= Form::label('Image', 'image', array('class' => 'control-label')); ?>
        <?= Form::file('image') ?>
    </div>
    <div class="form-group">
        <?= Form::label('Caption', 'caption', array('class' => 'control-label')); ?>
        <?= Form::input('caption', Input::post('caption', isset($photograph) ? $photograph->caption : ''), array('class' => 'col-md-4 form-control', 'placeholder' => 'Caption')); ?>
    </div>
    <div class="form-group">
        <label class='control-label'>&nbsp;</label>
        <?= Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		
    </div>
</fieldset>
<?= Form::close(); ?>