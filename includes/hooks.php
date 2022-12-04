<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}



// ADMIN HOOKS 
if(is_admin()){

    add_action('admin_menu', 'ABOP\Classes\Admin\AdminMenu::create_menu_page');

}
