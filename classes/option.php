<?php
namespace ABOP\Classes;

use ABOP\Classes\Admin\AdminFields;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Option {
    private $option_prefix;
    private $name;
    private $description;
    private $slug;
    private $type;
    private $value;
    private $cached = false;

    /**
     * _contruct
     *
     * @param  array $args
     * @return void
     */
    public function __construct($args) {
        $this->option_prefix = $args['option_prefix'];
        $this->name        = $args['name'];
        $this->description = isset($args['description']) ? $args['description'] : '';
        $this->slug        = $args['slug'];
        $this->type        = $args['type'];
        $this->options     = isset($args['options']) ? $args['options'] : [];
    }

    /**
     * print_field
     *
     * @return void
     */
    public function print_field() {
        switch($this->type){
            case 'text':
            case 'color':
            case 'email':
            case 'number':
            case 'tel':
            case 'date':
                AdminFields::input_field($this->option_prefix.$this->slug,$this->name,$this->description,$this->get_value(),$this->type);
            break;
            case 'textarea':
                AdminFields::textarea_field($this->option_prefix.$this->slug,$this->name,$this->description,$this->get_value());
            break;
            case 'wysiwyg':
                AdminFields::wysiwyg_field($this->option_prefix.$this->slug,$this->name,$this->description,$this->get_value());
            break;
            case 'select':
                AdminFields::select_field($this->option_prefix.$this->slug,$this->name,$this->description,$this->get_value(),$this->options);
            break;
            case 'select':
                AdminFields::select_field($this->option_prefix.$this->slug,$this->name,$this->description,$this->get_value(),$this->options);
            break;
            case 'checkbox':
                AdminFields::checkbox_field($this->option_prefix.$this->slug,$this->name,$this->description,$this->get_value());
            break;
        }
    }

    /**
     * get_name
     *
     * @return string
     */
    public function get_name() {
        return $this->name;
    }

    /**
     * get_slug
     *
     * @return string
     */
    public function get_slug() {
        return $this->slug;
    }

    /**
     * get_type
     *
     * @return string
     */
    public function get_type() {
        return $this->type;
    }

    /**
     * get_description
     *
     * @return string
     */
    public function get_description() {
        return $this->description;
    }

    /**
     * get_value
     *
     * @return mixed
     */
    public function get_value($force_refetch = false) {
        if($this->cached && !$force_refetch){
            return $this->value;
        }else{
            return $this->fetch_value(); 
        }
    }

    /**
     * set_value
     *
     * @return void
     */
    public function set_value($value) {
        $this->value = $value;
    }
    
    /**
     * fetch_value
     *
     * @return mixed
     */
    private function fetch_value() {
        $option = get_option($this->option_prefix . $this->slug);
        if ($option) {
            $this->value = $option;
            $this->cached = true;
        }
        return $option;
    }

}