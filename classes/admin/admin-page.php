<?php 
namespace ABOP\Classes\Admin;

use ABOP\Classes\OptionManager;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AdminPage {

    public static function generate(){
        
        var_dump((OptionManager::get_instance())->get_registered_options());
        var_dump(abop_get_option('site_title'));
    }

}