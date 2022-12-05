<?php
namespace ABOP\Classes\Admin;

use ABOP\Classes\OptionManager;

defined( 'ABSPATH' ) || exit;

class AdminPage {

    /**
     * create_menu_page
     *
     * @return void
     */
    public static function create_menu_page(){
        add_menu_page( 
            apply_filters('ABOP_option_page_title',__('Option page','ABOP')), 
            apply_filters('ABOP_option_menu_title',__('Site Options','ABOP')), 
            apply_filters('ABOP_capabilities','edit_posts'), 
            'abop_option_editing_page', 
            __CLASS__.'::generate', 
            'dashicons-admin-generic',
            apply_filters('ABOP_menu_position',59)
        );
    }
    
    /**
     * generate the content of the option page
     *
     * @return void
     */
    public static function generate() {
        $manager = OptionManager::get_instance();
        $options = $manager->get_registered_options();
        ?>
        <div class="wrap">
            <h1><?php echo apply_filters('ABOP_option_page_title', __('Site settings', 'abop')) ?></h1>
            <p><?php echo apply_filters('ABOP_option_page_after_title', '') ?></p>
            <form method="post" action="options.php">
                <table class="form-table">
                    <?php settings_fields('abop_settings');?>
                    <?php do_settings_sections('abop_settings');?>
                    <?php foreach ($options as $option): ?>
                        <?php $option->print_field()?>
                    <?php endforeach;?>
                </table>
                <?php submit_button();?>
            </form>
            <p><?php _e('Option page generated with AB Easy Option Page.','abop') ?></p>
            <!-- <details>
                <summary><?php _e('Developpers tools','abop') ?></summary>
                <p>
                    <a href="#" class="button button-secondary"><?php _e('Purge unused options','abop') ?></a>
                    <?php _e('Will compare the options history with the currently registered options and delete unused ones from the options table.','abop') ?>
                </p>
            </details> -->
        </div>
      <?php
}

}