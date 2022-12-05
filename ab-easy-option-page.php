<?php
/**
 * Plugin Name: AB Easy Options page
 * Description: Add an option page to your website.
 * Plugin URI: https://antoinebernier.com/plugins/ab-easy-option-page/
 * Version: 0.1
 * Requires at least: 6.0
 * Requires PHP: 7.2
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

if (is_admin()) {
    include "classes/admin/admin-menu.php";
    include "classes/admin/admin-page.php";
    include "classes/admin/admin-fields.php";
    include "classes/admin/updater.php";

    add_action('admin_menu', 'ABOP\Classes\Admin\AdminMenu::create_menu_page');
}
include "traits/singleton.php";
include "classes/option-manager.php";
include "classes/option.php";
include "includes/helpers.php";