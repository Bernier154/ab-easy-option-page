<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!function_exists('abop_debug_log')){
    /**
     * abop_debug_log
     *
     * @param  string $value
     * @return void
     */
    function abop_debug_log($value){
        if(defined('ABOP_DEBUG') && ABOP_DEBUG){
            error_log('------------ABOP LOGGING-----------'.PHP_EOL.$value);
        }
    }
}

if(!function_exists('abop_register_option')){
    /**
     * abob_register_option
     *
     * @param  string $options_name
     * @return void
     */
    function abop_register_option($args){
        $manager = abop_option_manager();
        $manager->register_option($args);
    }
}


if(!function_exists('abop_option_manager')){
    /**
     * abop_option_manager
     *
     * @return \ABOP\Classes\OptionManager
     */
    function abop_option_manager(){
        return \ABOP\Classes\OptionManager::get_instance();
    }
}


if(!function_exists('abop_get_option')){    
    /**
     * abop_get_option
     *
     * @param  string $slug
     * @param  bool $force_refetch
     * @return mixed
     */
    function abop_get_option($slug,$force_refetch = false){
        $manager = abop_option_manager();
        $manager->get_option($slug,$force_refetch);
    }
}
