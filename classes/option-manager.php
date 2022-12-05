<?php
namespace ABOP\Classes;

use ABOP\Classes\Option;

defined('ABSPATH') || exit;
class OptionManager {
    use \ABOP\Traits\Singleton;

    private $option_prefix      = 'abop_';
    private $registered_options = [];
    private $options_history;

    /**
     * initialize the plugin option history.
     * this history is used at the uninstall to cleanup de db.
     *
     * @return void
     */
    protected function __construct() {
        $this->options_history = get_option('ab_op_options_history', []);
    }

    /**
     * getter for the current registered options
     *
     * @return void
     */
    public function get_registered_options() {
        return $this->registered_options;
    }

    /**
     * Add the option to the option history if it is not in it;
     *
     * @param  string $option_name
     * @return void
     */
    private function maybe_option_history($option_name) {
        if (!in_array($option_name, $this->options_history)) {
            $this->options_history[] = $option_name;
            update_option('ab_op_options_history', $this->options_history);
        }
    }

    /**
     * Register an option to the current instance of the option manager.
     *
     * @param  array $args
     * @return void
     */
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
        $this->maybe_option_history($this->option_prefix . $args['slug']);

        return true;
    }
    
    /**
     * Validate the args of the register option function. Return true or false depending on the args validity.
     *
     * @param  array $args
     * @return bool
     */
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
