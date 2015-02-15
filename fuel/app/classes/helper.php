<?php

class Helper {

    /**
     * Outputs a formatted date string.
     * @param int $date The date input
     * @param string $date_format The format to output 
     * 
     * Options:
     *  - date
     *  - date_short
     *  - date_time_short
     *  - date_time_long
     * @return string Formatted output
     */
    public static function date_info($date, $date_format = '') {
        // You can add your own here //
        switch ($date_format) {
            case('date'):
                $format = '%B %d, %Y';
                break;
            case('date_short'):
                $format = '%m/%d/%Y';
                break;
            case('date_time_short'):
                $format = '%m/%d/%y %I:%M';
                break;
            case('date_time_long'):
            default:
                $format = '%B %d, %Y at %I:%M %p';
                break;
        }
        return Date::forge($date)->format($format);
    }

    /**
     * Lets you get an inside peek of what a variable contains.
     * @param mixed $var The variable in question.
     * @param string $name An optional display name for variable.
     * @param bool $force_var If true, forces var_dump.
     */
    public static function see_var($var, $name = NULL, $force_var = FALSE) {
        echo "<pre>";
        if (strlen($name)) {
            echo $name . ':<br />';
        }
        if ($force_var) {
            var_dump($var);
        } else {
            if (is_array($var)) {
                print_r($var);
            } else {
                var_dump($var);
            }
        }
        echo "</pre>";
    }

}

?>