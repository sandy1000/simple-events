<?php

/*************************************************************************

Plugin Name: Simple Events
Plugin URI: http://sandeepstha.com/
Description: Simple Events plugin by OUTSIDE.
Version: 1.0.0
Text Domain: pt-simple-event
Author: Sandeep Shrestha
Author URI: http://sandeepstha.com

**************************************************************************/

/**
 * Class PT_Simple_Events
 */
class PT_Simple_Events {
	public function __construct() {
        include_once plugin_dir_path(__FILE__) . 'inc/admin/Custom-fields.php';
        include_once plugin_dir_path(__FILE__) . 'inc/admin/Gutenberg-block.php';
        include_once plugin_dir_path(__FILE__) . 'inc/public/shortcodes.php';
        include_once plugin_dir_path(__FILE__) . 'inc/Plugin.php';
        new \PT_Simple_Events\Plugin(__FILE__);
    }
}
new PT_Simple_Events();

register_deactivation_hook(__FILE__, function () {
    
});
