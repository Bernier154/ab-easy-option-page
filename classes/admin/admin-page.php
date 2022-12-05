<?php
namespace ABOP\Classes\Admin;

use ABOP\Classes\OptionManager;

defined( 'ABSPATH' ) || exit;

class AdminPage {

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
            <p>Option page generated with AB Easy Option Page.</p>
        </div>
      <?php
}

}