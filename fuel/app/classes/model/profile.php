<?php

class Model_Profile extends \Orm\Model {

    /**
     * Validation factory method to grab validation methods for profile forms
     * @param string $type The type of form requested.
     * @return Validation
     */
    public static function validate_form($type) {
        switch (strtolower($type)) {
            case 'bio':
                $model = self::validate_bio();
                break;
            case 'image':
                $model = self::validate_image();
                break;
            case 'email':
                $model = self::validate_email();
                break;
            case 'password':
                $model = self::validate_password();
                break;
            default:
                $model = null;
                break;
        }

        return $model;
    }

    /**
     * Adds validation object to bio form.
     * @return Validation
     */
    private static function validate_bio() {
        $val = Validation::forge('bio');
        $val->add('bio', 'Bio', array('type' => 'textarea'))
                ->set_attribute(array('cols' => 50, 'rows' => 8))
                ->add_rule('max_length', 320)
                ->add_rule('trim')
                ->add_rule('required');
        return $val;
    }

    /**
     * Adds validaiton object to email form
     * @return Validation
     */
    private static function validate_email() {
        $val = Validation::forge('email');
        $val->add('email', 'User Email')
                ->add_rule('trim')
                ->add_rule('valid_email')
                ->add_rule('required');
        return $val;
    }

    /**
     * Adds validation object to password form
     * @return Validation
     */
    private static function validate_password() {
        $val = Validation::forge('password');
        $val->add('password1', 'Old Password')
                ->add_rule('required')
                ->add_rule('trim')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 15)
                ->add_rule('required_with', 'password2')
                ->add_rule('required_with', 'password3');
        $val->add('password2', 'New Password')
                ->add_rule('required')
                ->add_rule('trim')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 15)
                ->add_rule('required_with', 'password1')
                ->add_rule('required_with', 'password3');
        $val->add('password3', 'Re-type Password')
                ->add_rule('required')
                ->add_rule('trim')
                ->add_rule('min_length', 3)
                ->add_rule('max_length', 15)
                ->add_rule('required_with', 'password1')
                ->add_rule('required_with', 'password2');
        return $val;
    }

    /**
     * Adds validation object to image form
     * @return Validation
     */
    private static function validate_image() {
        // Return empty validation object because there are no fields to validate.
        return Validation::forge('image');
    }

    /**
     * Saves designated fields to the user table.
     * @param Validation $val Validation object
     * @param Auth $auth Auth object
     * @param string $field Field being modified
     * @return boolean
     */
    public static function save_profile(Validation $val, $auth, $field) {
        try {
            if ($val->run()) {
                // Update based on field //
                // Password exception //
                if ($field == 'password') {
                    $update = $auth->update_user(array(
                        'old_password' => $val->validated('password1'),
                        'password' => $val->validated('password2')
                    ));
                    // Image exception //
                } elseif ($field == 'image') {
                    // Save the image //
                    $image = self::save_image($auth);
                    if ($image) {
                        // Get original image so that we can delete it after saving //
                        $original_image = $auth->get('image');
                        $update = $auth->update_user(array(
                            'image' => $image
                        ));
                    } else {
                        $update = false;
                    }
                } else {
                    $update = $auth->update_user(array(
                        $field => $val->validated($field)
                    ));
                }

                if ($update) {
                    // Saving image was successful so erase old images //
                    if ($field == 'image') {
                        self::image_cleanup($original_image);
                    }
                    Session::set_flash('success', 'Profile was updated');
                    return true;
                } else {
                    Session::set_flash('error', 'Profile could not be updated at this time');
                    return false;
                }
            } else {
                Session::set_flash('error', $val->error());
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            Session::set_flash('error', 'Profile could not be saved at this time.');
            return false;
        }
    }

    /**
     * Handles the saving of the profile image
     * @return boolean
     */
    private static function save_image() {
        try {
            $config = array(
                'auto_process' => FALSE,
                'path' => DOCROOT . 'assets/img/profile',
                'auto_rename' => true,
                'new_name' => substr(md5(uniqid()), 0, 10),
                'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png'),
            );
            // Process uploaded image based on config //
            Upload::process($config);

            // Confirm upload is valid //
            if (Upload::is_valid()) {
                Upload::save(0);
                $upload = Upload::get_files(0);

                // Get a thumbnail image //
                Image::load($upload['saved_to'] . $upload['saved_as'], false, $upload['extension'])->resize(200, null, true)->save($upload['saved_to'] . 'thumb/' . $upload['saved_as']);
                // Get a mini image for comments //
                Image::load($upload['saved_to'] . $upload['saved_as'], false, $upload['extension'])->resize(50, 50, true)->save($upload['saved_to'] . 'mini/' . $upload['saved_as']);

                return $upload['saved_as'];
            } else {
                // Loop through errors and log them //
                foreach (Upload::get_errors(0)['errors'] as $err) {
                    $errors[] = $err['message'];
                }
                Session::set_flash('error', $errors);
                Log::error('Upload Errors: ' . implode(', ', $errors), __METHOD__);
                return false;
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            return false;
        }
    }

    /**
     * Removes and tracks cleaning up of profile images.
     * @param string $current_image Name of the current image before change
     * @return boolean
     */
    public static function image_cleanup($current_image) {
        try {

            if ($current_image !== 'noimg.jpg') {
                $original = File::delete(DOCROOT . 'assets/img/profile/' . $current_image);
                $thumb = File::delete(DOCROOT . 'assets/img/profile/thumb/' . $current_image);
                $mini = File::delete(DOCROOT . 'assets/img/profile/mini/' . $current_image);
            }
            // All images were deleted //
            if ($original and $thumb and $mini) {
                return true;
            }
            // Go through images one by one and see which one failed //
            if (!$original) {
                Log::error('Original profile image did not delete', __METHOD__);
            }
            if (!$thumb) {
                Log::error('Thumb profile image did not delete', __METHOD__);
            }
            if (!$mini) {
                Log::error('Mini profile image did not delete', __METHOD__);
            }
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            return false;
        }
    }

}
