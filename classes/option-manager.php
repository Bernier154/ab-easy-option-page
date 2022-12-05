<?php
namespace ABOP\Classes;

use ABOP\Classes\Option;

defined( 'ABSPATH' ) || exit;
class OptionManager {

    use \ABOP\Traits\Singleton;

    private $option_prefix      = 'abop_';
    private $registered_options = [];

    public function get_registered_options() {
        return $this->registered_options;
    }

    public function register_option($args) {
        if (!$this->register_args_validator($args)) {
            return false;
        }

        if (in_array($args['slug'], array_values($this->registered_options))) {
            abop_debug_log("Could not register option. {$args['slug']} is already registered.");
            return false;
        }

        $args['option_prefix'] = $this->option_prefix;

        $this->registered_options[$args['slug']] = new Option($args);

        if (is_admin()) {
            register_setting('abop_settings', $this->option_prefix . $args['slug']);
        }

        return true;
    }

    private function register_args_validator($args) {
        $rules = [
            'name'        => ['required', 'string'],
            'description' => ['string'],
            'slug'        => ['required', 'slugified', 'string'],
            'type'        => ['required', 'is:text,email,tel,date,textarea,wysiwyg,number,image,checkbox,select,color'],
            'options'     => ['array'],
        ];

        foreach ($rules as $field => $rules) {
            foreach ($rules as $rule) {

                //if !set and not required
                if (!isset($args[$field]) && !in_array('required', $rules)) {
                    continue;
                }

                //simple rules
                switch ($rule) {
                case 'required':
                    if (!isset($args[$field]) || $args[$field] == "") {
                        abop_debug_log("Could not register option. '$field' is a required field.");
                        return false;
                    }
                    break;
                case 'slugified':
                    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-] /', $args[$field]) || str_contains($args[$field], ' ')) {
                        abop_debug_log("Could not register option. '$field' must not contain special characters or spaces.");
                        return false;
                    }
                    break;
                case 'string':
                    if (!is_string($args[$field])) {
                        abop_debug_log("Could not register option. '$field' must be a string.");
                        return false;
                    }
                    break;
                case 'array':
                    if (!is_array($args[$field])) {
                        abop_debug_log("Could not register option. '$field' must be an array.");
                        return false;
                    }
                    break;
                }

                // is: rule
                if (strpos($rule, 'is:') !== false) {
                    $values = explode(',', explode(':', $rule)[1]);
                    if (!in_array($args[$field], $values)) {
                        abop_debug_log("Could not register option. '$field' must be one of these values: " . implode(',', $values) . ".");
                        return false;
                    }
                }
            }
        }

        return true;
    }

}
