<?php
namespace ABOP\Classes\Admin;

defined( 'ABSPATH' ) || exit;
class AdminFields {
    
    /**
     * input_field
     *
     * @param  string $slug
     * @param  string $name
     * @param  string $description
     * @param  string $value
     * @param  string $type
     * @return void
     */
    public static function input_field($slug, $name, $description, $value, $type = 'text') {
        ?>
            <tr valign="top">
                <th scope="row">
                    <?php echo esc_html($name) ?>
                    <small style="opacity:0.75"><br><?php echo $description ?></small>
                </th>
                <td>
                    <input type="<?php echo $type ?>" name="<?php  echo esc_html($slug) ?>" value="<?php echo esc_attr($value) ?>" />
                </td>
            </tr>
        <?php
    }
    
    /**
     * textarea_field
     *
     * @param  string $slug
     * @param  string $name
     * @param  string $description
     * @param  string $value
     * @return void
     */
    public static function textarea_field($slug, $name, $description, $value) {
        ?>
            <tr valign="top">
                <th scope="row">
                    <?php echo esc_html($name) ?>
                    <small style="opacity:0.75"><br><?php echo $description ?></small>
                </th>
                <td>
                    <textarea cols="60" rows="8" name="<?php  echo esc_html($slug) ?>" ><?php echo esc_textarea($value) ?></textarea>
                </td>
            </tr>
        <?php
    }
    
    /**
     * wysiwyg_field
     *
     * @param  string $slug
     * @param  string $name
     * @param  string $description
     * @param  string $value
     * @return void
     */
    public static function wysiwyg_field($slug, $name, $description, $value) {
        if ( ! class_exists( '_WP_Editors', false ) ) {
            require ABSPATH . WPINC . '/class-wp-editor.php';
        }
        ?>
            <tr valign="top">
                <th scope="row">
                    <?php echo esc_html($name) ?>
                    <small style="opacity:0.75"><br><?php echo $description ?></small>
                </th>
                <td>
                    <?php wp_editor($value, $slug, $settings = array('textarea_name'=>$slug) ); ?>
                </td>
            </tr>
        <?php
    }
    
    /**
     * select_field
     *
     * @param  string $slug
     * @param  string $name
     * @param  string $description
     * @param  string $value
     * @param  array $options
     * @return void
     */
    public static function select_field($slug, $name, $description, $value, $options) {
        if ( ! class_exists( '_WP_Editors', false ) ) {
            require ABSPATH . WPINC . '/class-wp-editor.php';
        }
        ?>
            <tr valign="top">
                <th scope="row">
                    <?php echo esc_html($name) ?>
                    <small style="opacity:0.75"><br><?php echo $description ?></small>
                </th>
                <td>
                    <select name="<?php echo esc_attr($slug) ?>">
                        <option value=""><?php _e("Select","abop") ?></option>
                        <?php foreach($options as $key=>$title): ?>
                            <option value="<?php echo esc_attr($key) ?>" <?php echo $value === $key?'selected':'' ?> ><?php echo esc_html($title) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php
    }
    
    /**
     * checkbox_field
     *
     * @param  string $slug
     * @param  string $name
     * @param  string $description
     * @param  string $value
     * @return void
     */
    public static function checkbox_field($slug, $name, $description, $value) {
        if ( ! class_exists( '_WP_Editors', false ) ) {
            require ABSPATH . WPINC . '/class-wp-editor.php';
        }
        ?>
            <tr valign="top">
                <th scope="row">
                    <?php echo esc_html($name) ?>
                    <small style="opacity:0.75"><br><?php echo $description ?></small>
                </th>
                <td>
                    <label for="<?php echo esc_attr($slug) ?>">
						<input name="<?php echo esc_attr($slug) ?>" type="checkbox" id="<?php echo esc_attr($slug) ?>" value="1" <?php echo $value == 1 ?'checked':'' ?> >
						<?php echo esc_html($name) ?>
                        <small style="opacity:0.75"><br><?php echo $description ?></small>
					</label>
                </td>
            </tr>
        <?php
    }

}

