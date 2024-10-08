<?php
/**
 * Plugin Name: AB Easy Options page
 * Description: Add an option page to your website.
 * Plugin URI: https://antoinebernier.com/plugins/ab-easy-option-page/
 * Version: 1.0.3
 * Requires at least: 6.0
 * Requires PHP: 8.1
 * Author: Antoine Bernier
 * Author URI: https://antoinebernier.com
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Update URI: https://antoinebernier.com/plugins/ab-easy-option-page/update
 * Text Domain: abop
 * Domain Path: /languages
 */

defined( 'ABSPATH' ) || exit;

define('ABOP_BASE_FILE',__FILE__);
define('ABOP_BASE_DIR',__DIR__);

if (is_admin()) {
    include "classes/admin/admin-page.php";
    include "classes/admin/admin-fields.php";
    include "classes/admin/updater.php";
    add_action('admin_menu', 'ABOP\Classes\Admin\AdminPage::create_menu_page');
}
include "traits/singleton.php";
include "classes/option-manager.php";
include "classes/option.php";
include "includes/helpers.php";

add_action('init', 'abop_localize');
function abop_localize(){
    load_plugin_textdomain('abop', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
}

function abop_deactivate() {
    $manager = abop_option_manager();
    add_action('shutdown',[$manager,'options_cleanup']);
    delete_option('_transient_timeout_abop_update_request');
    delete_option('_transient_abop_update_request');
}
register_deactivation_hook( __FILE__, 'abop_deactivate' );