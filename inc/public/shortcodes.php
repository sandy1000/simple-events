<?php

namespace PT_Simple_Events;
use PT_Simple_Events\Plugin;

/**
 * The Class to display shortcodes.
 */
class Shortcodes{
    public function __construct() {
        add_shortcode( 'pt_listevents', [$this,'pt_shortcode_displ_eventlist'] );
        add_shortcode( 'pt_event_filter', [$this,'pt_shortcode_filter_event'] );
    }

    // Creating Shortcodes to display events [pt_listevents num="2" type="webinar"]
    public function pt_shortcode_displ_eventlist($atts){

        // Parse your shortcode settings with it's defaults
        $atts = shortcode_atts( array(
            'num' => '5',
            'type'=> ''
        ), $atts, 'pt_listevents' );

        // Extract shortcode atributes
        extract( $atts );

        // Define output var
        $output = '';

        // Define query
        $event1 = current_time( 'Y-m-d' );
        $query_args = array(
            'post_type'      => 'simple_event',
            'posts_per_page' => $num,
            'order'          => 'ASC',
            'orderby'        => '_simple_event_date',
            'meta_query' => array(
                'relation'    => 'OR',
                array(
                    'key'     => '_simple_event_date',
                    'value'   => $event1,
                    'compare' => '>=',
                    'type'    => 'DATE',
                ),
            )
        );

        // Event by term if defined
        if ( $type ) {

            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'simple_event_type',
                    'field'    => 'slug',
                    'terms'    => $type,
                ),
            );

        }

        // Query posts
        $custom_query = new \WP_Query( $query_args );

        // Add content if we found posts via our query
        if ( $custom_query->have_posts() ) {

            // Open div wrapper around loop
            $output .= '<div class="event_lists">';

            // Loop through posts
            while ( $custom_query->have_posts() ) {

                // Sets up post data
                $custom_query->the_post();

                // This is the output for your entry so what you want to do for each post.
                $output .= '<div class="event"><a href="'.$type.get_permalink().'">' . get_the_title() . '</a><br /><span>'.get_post_meta( get_the_ID(), '_simple_event_date', true ).', '.get_post_meta( get_the_ID(), '_simple_event_venue', true ).'</span></div>';

            }

            // Close div wrapper around loop
            $output .= '</div>';

            // Restore data
            wp_reset_postdata();

        }

        wp_enqueue_style('pt-simple-events');
        // Return your shortcode output
        return $output;

    }

     // Creating Shortcodes to filter events [pt_event_filter]
     public function pt_shortcode_filter_event($atts){

        // Define output var
        $output = '';
        $output .= '<div id="simple_event_filters"><h2>'.__('Search Events','pt-simple-event').'</h2>';

         // get event types
         $event_types = get_terms( 'simple_event_type' );
         if ( ! empty( $event_types ) && ! is_wp_error( $event_types ) ){
             $output .= '<select name="se_event_type"><option value="-1">'.__('select type','pt-simple-event').'</option>';
             foreach ( $event_types as $term ) {
                 $output .= '<option value="'.$term->name.'">'.$term->name.'</option>';
             }
             $output .=  '</select>';
        }                 
                
        $output .= '<input type="date" name="se_event_date" />';       
        $output .= '</div>';

        $output .='<div class="simple_event_filter_lists"></div>';

        wp_enqueue_style('pt-simple-events');
        wp_enqueue_script('pt-simple-events');
        return $output;
     }

  
} 