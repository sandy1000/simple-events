<?php

namespace PT_Simple_Events;

class Plugin{

    const SETTING_KEY = "pt_simple_event";

    const TEXTDOMAIN = 'pt-simple-event';

    const VERSION = '1.0.0';

    /**
     * @var mixed
     */
    static $file = null;


    /** @var null $instance */
    public static $instance = null;

    /**
     * Array of custom settings/options
     **/
    private $options;

      /**
     * @param $file
     */
    public function __construct($file)
    {
        load_plugin_textdomain(self::TEXTDOMAIN, false, self::p_dir('languages/'));
        self::$file     = $file;
        self::$instance = new \stdClass();
        self::$instance->cfields   = new \PT_Simple_Events\Meta_Fields();
        self::$instance->shortcodes   = new \PT_Simple_Events\Shortcodes();
        add_action('init', [$this, 'init']);
        
    }


    public function after_theme_setup() {
    }

    public function init() {

        //register event post type
        $args = array(
            'public'    => true,
            'label'     => __( 'Event', 'pt-simple-event' ),
            'menu_icon' => 'dashicons-book',
            'supports'  => array( 'title', 'editor', 'author', 'thumbnail'),
        );
        register_post_type( 'simple_event', $args );


        //register event type taxonomy
        $labels = array(
            'name'                       => _x( 'Event Types', 'taxonomy general name', 'pt-simple-event' ),
            'singular_name'              => _x( 'Event Type', 'taxonomy singular name', 'pt-simple-event' ),
        );
        
        $args = array(
            'hierarchical'          => true,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
        );
        register_taxonomy( 'simple_event_type', 'simple_event', $args );
    }

      /**
     * @param $path
     */
    public static function p_dir($path = '') {
        return trailingslashit(dirname(self::$file)) . trim($path, '/');
    }

    /**
     * @param $path
     */
    public static function p_url($path = '') {
        return plugins_url(trim($path, '/'), self::$file);
    }

}