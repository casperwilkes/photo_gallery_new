<?php

class Model_Comment extends \Orm\Model {

    protected static $_properties = array(
        'id',
        'photograph_id',
        'user_id',
        'body',
        'created_at',
        'updated_at',
    );
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => false,
        ),
    );
    protected static $_belongs_to = array(
        'users' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false
        )
    );
    protected static $_has_one = array(
        'user' => array(
            'key_from' => 'user_id',
            'model_to' => 'Model_User',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );
    protected static $_table_name = 'comments';

    /**
     * Adds a validation object to comment form
     * @return Validation
     */
    public static function validate_comment() {
        $val = Validation::forge('comment');
        $val->add('comment', 'Comment')
                ->add_rule('trim')
                ->add_rule('min_length', 3)
                ->add_rule('required');
        // Custom Error Message //
        $val->set_message('required', 'A comment is required');
        $val->set_message('min_length', 'Comment must be over 3 characters long');
        return $val;
    }

    /**
     * Saves the comment to the database.
     * @param Validation $val
     * @param int $id The id of the image to save to
     * @param Auth $auth The user's Auth object
     * @return boolean
     */
    public static function save_comment(Validation $val, $id, $auth) {
        try {
            if ($val->run()) {
                $comment = self::forge(array(
                            'photograph_id' => $id,
                            'user_id' => $auth->get_screen_name(),
                            'body' => $val->validated('comment')
                ));

                if ($comment and $comment->save()) {
                    Session::set_flash('success', 'Comment saved successfully');
                    return true;
                } else {
                    Session::set_flash('error', 'Problem submitting comment, please try again');
                    return false;
                }
            } else {
                Session::set_flash('error', $val->error());
                return false;
            }
        } catch (Exception $e) {
            Session::set_flash('error', 'Could not process comment at this time');
            Log::error($e, __METHOD__);
            return false;
        }
    }

    /**
     * Deletes a comment from the database
     * @param int $id ID of comment to remove
     * @return boolean
     */
    public static function remove_comment($id = null) {
        try {
            $comment = self::find($id);

            if ($comment) {
                if ($comment->delete()) {
                    Session::set_flash('success', 'Comment deleted successfully');
                    return true;
                } else {
                    Session::set_flash('error', 'Could not delete comment at this time');
                    return false;
                }
            } else {
                Session::set_flash('Could not locate comment at this time');
                return false;
            }
        } catch (Exception $e) {
            Session::set_flash('error', 'A problem occured while deleting the comment');
            Log::error($e, __METHOD__);
            return false;
        }
    }

}
