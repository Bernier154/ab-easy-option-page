# AB Easy Option Page
AB Easy Option Page is a dev oriented plugin to easily add an option page to a Wordpress website. I strive to make is as easy as possible to customize it via hooks. This plugin was created as a need for websites that didn't needed ACF but needed some sort of updatable option for the admins.

---

<br>

# Usage

## Register an option
Register the options you need in your functions.php.
| Arg name      | Possible value |
| ------------- | ------------- |
| name          | The nice name of the option |
| slug          | The slugified name of the option. Only use letters and undescores. |
| type          | The type of field. Accepts: text, email, tel, date, textarea, wysiwyg, number, image, checkbox, select, color. |
| description   | The description show with the option in the admin panel. (Optional) |
| options       | If your Option is a Select, provide an associative array of the options (Optional) |
### Example:
```php
    function abop_register_options(){
        abop_register_option([
            "name"=>"My nice option",
            "description" => "This option appear in the footer.",
            "slug"=>"nice_option",
            "type"=> "text"
        ]);
    }
    add_action('init','abop_register_options');
   
```

<br>

## Retrieve an option
| Arg name      | Possible value |
| ------------- | ------------- |
| option_slug   | string |
### Example:
```php
    <p><?php echo esc_html(abop_get_option('nice_option')) ?></p>
```

<br>

---
## DEBUG information
If your option won't register, debug messages can be printed to the debug log.
To get the various message in the debug log, declare this constant in wp_config : 
```php
define('ABOP_DEBUG',true);
// Make sure that WP_DEBUG AND WP_DEBUG_LOG are defined in your wp-config.php

```
---
## Mentions
Thanks to [rudrastyh](https://github.com/rudrastyh) for creating a tutorial for self hosting updates. His work was used to make the update server possible.