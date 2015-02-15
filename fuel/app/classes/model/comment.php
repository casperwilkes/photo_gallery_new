<?php

class Model_Comment extends \Orm\Model {

    protected static $_properties = array(
        'id',
        'photograph_id',
        'author',
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

    /**
     * Link to photograph model
     */
    protected static $_belongs_to = array(
        'photograph' => array(
            'key_from' => 'photograph_id',
            'model_to' => 'Model_Photograph',
            'key_to' => 'id',
            'cascade_save' => TRUE,
            'cascade_delete' => TRUE
        )
    );
    protected static $_table_name = 'comments';

    /**
     * Builds the comment form.
     * @param Fieldset $form Form instance to use
     * @return \Fieldset
     */
    public static function populate_build(Fieldset $form) {
        $form->add('author', 'Author')
                ->add_rule('min_length', 3)
                ->add_rule('required');
        $form->add('comment', 'Comment', array('type' => 'textarea'))
                ->set_attribute(array('cols' => 40, 'rows' => 8))
                ->add_rule('min_length', 3)
                ->add_rule('required');
        $form->add('submit', '', array('type' => 'submit', 'value' => 'Add Comment'));
        return $form;
    }

    /**
     * Validates and saves comment
     * @param Fieldset $form Form instance
     * @param int $id Photo id
     * @return array
     */
    public static function validate_comment(Fieldset $form, $id) {
        $val = $form->validation();
        $val->set_message('required', 'A comment is required');
        $val->set_message('min_length', ':label must be over 3 characters long');

        if ($val->run()) {
            $comment = self::forge(array(
                        'photograph_id' => $id,
                        'author' => $form->field('author')->get_attribute('value'),
                        'body' => $form->field('comment')->get_attribute('value')
            ));
            if ($comment and $comment->save()) {
                return array('error' => false);
            } else {
                return array('error' => true);
            }
        } else {
            return array('error' => true, 'message' => $val->show_errors());
        }
    }

}
