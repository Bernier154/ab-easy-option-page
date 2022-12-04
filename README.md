# AB Easy Option Page

## Register an option
```php
    abop_register_option([
        "name"=>"Option nice name",
        "description" => "Option's description."
        "slug"=>"option_slug",
        "type"=> "text" | "textarea" | "wysiwig" | "number" | "image"
    ]);
```

## DEBUG information
To get the various message in the debug log, declare this constant in wp_config : 
```php
define('ABOP_DEBUG',true);
``` 