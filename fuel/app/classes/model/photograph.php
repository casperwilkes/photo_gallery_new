<?php

class Model_Photograph extends Orm\Model {

    protected static $_properties = array(
        'id',
        'filename',
        'type',
        'size',
        'caption',
        'created_at',
        'updated_at',
    );
    protected static $_has_many = array(
        'comments' => array(
            'key_from' => 'id',
            'model_to' => 'Model_Comment',
            'key_to' => 'photograph_id',
            'cascade_save' => true,
            'cascade_delete' => true,
        )
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => false,
        ),
    );

    /**
     * Creates edit form 
     * @param Fieldset $form Fieldset object
     * @param Model_Photograph $photo Photo object
     * @return Fieldset
     */
    public static function populate_edit(Fieldset $form, Model_Photograph $photo) {
        $form->add('caption', 'Caption:')
                ->add_rule('trim')
                ->add_rule('required')
                ->add_rule('min_length', 5)
                ->add_rule('max_length', 255)
                ->set_value($photo->caption);
        $form->add('submit', ' ', array('type' => 'submit', 'value' => 'Edit'));
        return $form;
    }

    /**
     * Creates the upload form
     * @param Fieldset $form
     * @return Fieldset
     */
    public static function populate_upload(Fieldset $form) {
        $form->set_config('form_attributes', array('enctype' => 'multipart/form-data'));
        $form->add('image', 'Image', array('type' => 'file'));
        $form->add('caption', 'Caption')
                ->add_rule('trim')
                ->add_rule('required')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 255);
        $form->add('submit', '', array('type' => 'submit', 'value' => 'Upload Image'));
        return $form;
    }

    /**
     * Saves the uploaded image and places it where it should be in the tree and creates a thumbnail
     * @param Fieldset $form
     * @return boolean
     */
    public static function save_upload(Fieldset $form) {
        try {
            // Run validation on form //
            $val = $form->validation();

            if ($val->run()) {
                $config = array(
                    'auto_process' => FALSE,
                    'path' => DOCROOT . 'assets/img/main/',
                    'auto_rename' => true,
                    'new_name' => substr(md5(uniqid()), 0, 10),
                    'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
                );

                Upload::process($config);
                if (Upload::is_valid()) {
                    Upload::save(0);
                    $upload = Upload::get_files(0);

                    // Get a thumbnail image //
                    Image::load($upload['saved_to'] . $upload['saved_as'], false, $upload['extension'])->resize(200, null, true)->save($upload['saved_to'] . 'thumb/' . $upload['saved_as']);

                    $photograph = Model_Photograph::forge(array(
                                'filename' => $upload['saved_as'],
                                'type' => $upload['type'],
                                'size' => $upload['size'],
                                'caption' => $val->validated('caption'),
                    ));

                    if ($photograph and $photograph->save()) {
                        Session::set_flash('success', 'Added photograph: ' . $photograph->caption);
                        return true;
                    } else {
                        Session::set_flash('error', 'Could not save photograph.');
                        return false;
                    }
                } else {
                    // Loop through errors and log them //
                    foreach (Upload::get_errors(0)['errors'] as $err) {
                        $errors[] = $err['message'];
                    }
                    Session::set_flash('error', $errors);
                    Log::error('Upload Errors: ' . implode(', ', $errors), __METHOD__);
                    return false;
                }
            } else {
                Session::set_flash('error', $val->error());
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            Session::set_flash('error', 'Could not process edit request at this time');
            return false;
        }
    }

    /**
     * Saves the edits preformed on photo
     * @param Fieldset $form Edit form
     * @param Model_Photograph $photo Photo being edited
     * @return boolean
     */
    public static function save_edit(Fieldset $form, Model_Photograph $photo) {
        try {
            // Run validation on form //
            $val = $form->validation();

            if ($val->run()) {
                $photo->set(array(
                    'caption' => $val->validated('caption')
                ));
                if ($photo and $photo->save()) {
                    Session::set_flash('success', 'Photograph updated succesfully');
                    return true;
                } else {
                    Session::set_flash('error', 'Photograph was not updated succesfully');
                    return false;
                }
            } else {
                Session::set_flash('error', $val->error());
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            Session::set_flash('error', 'Could not process edit request at this time');
            return false;
        }
    }

    /**
     * Removes Photograph from database and assets directory //
     * @param int $id ID of photograph to remove
     * @return boolean
     * @todo write method to remove photograph from assets dir
     */
    public static function remove_photo($id = null) {
        try {
            $photo = self::find($id);
            $current_image = $photo->filename;
            if ($photo->delete()) {
                self::image_cleanup($current_image);
                Session::set_flash('success', 'Photo was removed');
                return true;
            } else {
                Session::set_flash('error', 'Photo was not removed');
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            Session::set_flash('error', 'Photo could not be removed at this time');
            return false;
        }
    }

    /**
     * Removes and tracks cleaning up of images.
     * @param string $current_image Name of the current image before change
     * @return boolean
     */
    private static function image_cleanup($current_image) {
        try {

            $original = File::delete(DOCROOT . 'assets/img/main/' . $current_image);
            $thumb = File::delete(DOCROOT . 'assets/img/main/thumb/' . $current_image);

            // All images were deleted //
            if ($original and $thumb) {
                return true;
            }
            // Go through images one by one and see which one failed //
            if (!$original) {
                Log::error('Original image did not delete', __METHOD__);
            }
            if (!$thumb) {
                Log::error('Thumb image did not delete', __METHOD__);
            }

            return false;
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            return false;
        }
    }

}
