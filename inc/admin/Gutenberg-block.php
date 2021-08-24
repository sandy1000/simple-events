<?php

namespace PT_Simple_Events;
use PT_Simple_Events\Plugin;
/**
 * The Class.
 */
class Gutenberg_Block {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'init', [$this,'simple_events_slider'] );
        add_action( 'block_categories', [$this,'simple_events_plugin_block_categories'], 10, 2 );
    }

    public function simple_events_slider_render_callback( $block_attributes, $content ) {
        $recent_posts = wp_get_recent_posts( array(
            'post_type' =>'simple_event',
            'numberposts' => 3,
            'post_status' => 'publish',
        ) );
        if ( count( $recent_posts ) === 0 ) {
            return 'No posts';
        }
        $post = $recent_posts[ 0 ];
        $post_id = $post['ID'];
        
        return sprintf(
            '<a class="wp-block-my-plugin-latest-post" href="%1$s">%2$s</a>',
            esc_url( get_permalink( $post_id ) ),
            esc_html( get_the_title( $post_id ) )
        );
    }

    public function simple_events_slider() {


         // Check if Gutenberg is active.
         if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }
    
        // Add block script.
        wp_register_script(
            'simple-event-slider',
            Plugin::p_url( '/assets/blocks/event-slider/event-slider.js', __FILE__ ),
            [ 'wp-blocks', 'wp-element', 'wp-editor' ],
            filemtime( Plugin::p_url( '/assets/blocks/event-slider/event-slider.js', __FILE__ ))
        );
    
        // Add block style.
        wp_register_style(
            'simple-event-slider',
            Plugin::p_url( '/assets/blocks/event-slider/event-slider.css', __FILE__ ),
            [],
            filemtime( Plugin::p_url( '/assets/blocks/event-slider/event-slider.css', __FILE__ ))
        );
     
        register_block_type( 'pt/simple-event-slider', array(
            'api_version' => 2,
            'editor_script' => 'simple-event-slider',
            'render_callback' => [$this,'simple_events_slider_render_callback']
        ) );
     
    }


    //registering new category block
    public function simple_events_plugin_block_categories( $categories ) {
        return array_merge(
            $categories,
            [
                [
                    'slug'  => 'simple-event-blocks',
                    'title' => __( 'Simple Event', 'pt-simple-event' ),
                ],
            ]
        );
    }
    


}