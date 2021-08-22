<?php

namespace PT_Simple_Events;
use PT_Simple_Events\Plugin;
/**
 * The Class.
 */
class Meta_Fields {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save'         ) ); 
    }
 
    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'simple_event');
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'simple_events',
                __( 'Simple Events', 'pt-simple-event' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'normal',
                'high'
            );
        }
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 

        // Check if our nonce is set.
        if ( ! isset( $_POST['simple_events_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['simple_events_custom_box_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'simple_events_custom_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        // Sanitize the user input.
        $event_venue = sanitize_text_field( $_POST['pt_event_venue'] );
        $event_date = sanitize_text_field( $_POST['pt_event_date'] );

        // update post meta   
        update_post_meta( $post_id, '_simple_event_venue', $event_venue );
        update_post_meta( $post_id, '_simple_event_date', $event_date);  

       
    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'simple_events_custom_box', 'simple_events_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $event_venue = get_post_meta( $post->ID, '_simple_event_venue', true );
        $event_date = get_post_meta( $post->ID, '_simple_event_date', true );
 
        // Display the form, using the current value.
        ?>
        <div class="<?php echo Plugin::SETTING_KEY.'_customfields';?>">  

        <div class="form-item">
            <label for="pt_event_venue">
                <?php _e( 'Venue', 'pt-simple-event' ); ?>
            </label>
            <input type="text" id="pt_event_venue" name="pt_event_venue" value="<?php echo esc_attr( $event_venue ); ?>" size="25" />
        </div>    


        <div class="form-item">
                <label for="pt_event_date">
                    <?php _e( 'Event Date', 'pt-simple-event' );
                    ?>
                </label>
                <input type="date" min="<?php echo date('Y-m-d');?>" id="pt_event_date" name="pt_event_date" value="<?php echo $event_date;?>" size="25" />
        </div>  
              
        </div>
        <?php
    }

}