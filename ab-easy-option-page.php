<?php 
/**
 * Plugin Name: AB Easy Options page
 * Description: Add an option page to your website.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(is_admin()){
    include "classes/admin/admin-menu.php";
    include "classes/admin/admin-page.php";
    include "classes/admin/admin-fields.php";
}
include "traits/singleton.php";
include "classes/option-manager.php";
include "includes/hooks.php";
include "includes/helpers.php";