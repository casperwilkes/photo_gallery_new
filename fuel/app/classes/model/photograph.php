<?php

use Orm\Model;

class Model_Photograph extends Model {

    protected static $_properties = array(
        'id',
        'id',
        'filename',
        'type',
        'size',
        'caption',
        'created_at',
        'updated_at',
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
     * Link to comments model
     */
    protected static $_has_many = array(
        'comments' => array(
            'key_from' => 'id',
            'motel_to' => 'Model_Comment',
            'key_to' => 'photograph_id',
            'cascade_save' => TRUE,
            'cascade_delete' => TRUE
        )
    );

    /**
     * Validates edit form.
     * @param string $factory Name of the validation field
     * @return \Validation
     */
    public static function validate($factory) {
        $val = Validation::forge($factory);
        $val->add_field('caption', 'Caption', 'required|max_length[255]');

        return $val;
    }

    /**
     * Removes photo from assets/img dir
     * @param string $photo Photo name
     * @return boolean
     */
    public static function remove_photo($photo) {
        try {
            $del = File::delete(DOCROOT . 'assets/img/' . $photo);
        } catch (Exception $e) {
            Log::error($e, __METHOD__);
            return FALSE;
        }
        return $del;
    }

}
