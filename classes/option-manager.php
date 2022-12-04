<?php 
namespace ABOP\Classes;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class OptionManager {

    use \ABOP\Traits\Singleton;

    private $option_prefix = 'abop_';
    private $registered_options = [];


    private function fetch_option($slug){
        $option = get_option($this->option_prefix.'$slug');
        if($option){
            $this->registered_options[$slug]['last_fetched'] = $option;
        }
        return $option;
    }

    private function register_args_validator($args){
        $rules = [
            'name' => ['required','string'],
            'description' => ['string'],
            'slug' => ['required','slugified','string'],
            'type' => ['required','is:text,textarea,wysiwig,number,image'],
        ];

        foreach($rules as $field => $rules){
            foreach($rules as $rule){

                //if !set and not required
                if(!isset($args[$field]) && !in_array('required',$rules)){
                    continue;
                }

                //simple rules
                switch($rule){
                    case 'required':
                        if(!isset($args[$field]) || $args[$field] == ""){
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
                        if(!is_string($args[$field])){
                            abop_debug_log("Could not register option. '$field' must be a string.");
                            return false;
                        }
                    break;
                }

                // is: rule
                if(strpos($rule,'is:') !== false){
                    $values = explode(',',explode(':',$rule)[1]);
                    if(!in_array($args[$field],$values)){
                        abop_debug_log("Could not register option. '$field' must be one of these values: ".implode(',',$values).".");
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function get_registered_options(){
        return $this->registered_options;
    }

    public function register_option($args){
        if(!$this->register_args_validator($args)){
            return false;
        }

        if(in_array($args['slug'],array_values($this->registered_options))){
            abop_debug_log("Could not register option. {$args['slug']} is already registered.");
            return false;
        }

        $args['last_fetch'] = null;
        $this->registered_options[$args['slug']] = $args;

        return true;
    }

    public function get_option($slug,$force_refetch = false){
        if(isset($this->registered_options[$slug])){
            if(isset($this->registered_options[$slug]['last_fetched']) && $this->registered_options[$slug]['last_fetched'] !== null  && !$force_refetch){
                return $this->registered_options[$slug]['last_fetched'];
            }else {
                return $this->fetch_option($slug);
            }
        }
        return false;
    }

}
