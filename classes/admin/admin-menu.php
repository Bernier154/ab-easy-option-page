<?php 
namespace ABOP\Classes\Admin;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class AdminMenu {

    public static function create_menu_page(){
        add_menu_page( 
            apply_filters('ABOP_option_page_title',__('Option page','ABOP')), 
            apply_filters('ABOP_option_menu_title',__('Site Options','ABOP')), 
            apply_filters('ABOP_capabilities','edit_posts'), 
            'abop_option_editing_page', 
            'ABOP\Classes\Admin\AdminPage::generate', 
            'dashicons-admin-generic',
            apply_filters('ABOP_menu_position',59)
        );
    }

}