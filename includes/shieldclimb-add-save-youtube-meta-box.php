<?php
// Add custom meta box for YouTube video link
add_action('add_meta_boxes', 'shieldclimb_add_youtube_video_meta_box');
function shieldclimb_add_youtube_video_meta_box() {
    add_meta_box(
        'shieldclimb_youtube_video_meta_box',
        'YouTube Video Link',
        'shieldclimb_youtube_video_meta_box_callback',
        'product',
        'normal',
        'high'
    );
}

// Callback function to display the meta box content
function shieldclimb_youtube_video_meta_box_callback($post) {
    wp_nonce_field('shieldclimb_save_youtube_video_link', 'shieldclimb_youtube_video_link_nonce');
    $youtube_video_link = get_post_meta($post->ID, '_shieldclimb_youtube_video_link', true);
    
    echo '<label for="shieldclimb_youtube_video_link">';
    esc_html_e('Enter YouTube Video Link [Replace with your Video ID: https://www.youtube.com/embed/Video_ID]: ', 'shieldclimb-product-page-youtube');
    echo '</label><br>';
	echo '</label><br>';
    echo '<textarea id="shieldclimb_youtube_video_link" name="shieldclimb_youtube_video_link" rows="1" cols="80">' . esc_textarea($youtube_video_link) . '</textarea>';
}

// Save the meta box input
add_action('save_post', 'shieldclimb_save_youtube_video_link_meta_box_data');
function shieldclimb_save_youtube_video_link_meta_box_data($post_id) {
    // Verify nonce existence first
    if (!isset($_POST['shieldclimb_youtube_video_link_nonce'])) {
        return;
    }

    // Verify nonce - properly unslashed and sanitized
    if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['shieldclimb_youtube_video_link_nonce'])), 'shieldclimb_save_youtube_video_link')) {
        return;
    }

    // Check for autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if ('product' !== get_post_type($post_id)) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Process and save the YouTube link
    if (isset($_POST['shieldclimb_youtube_video_link'])) {
        // Proper security sequence: unslash -> sanitize -> validate
        $youtube_video_link = sanitize_text_field(wp_unslash($_POST['shieldclimb_youtube_video_link']));
        
        // Additional URL-specific validation
        $youtube_video_link = esc_url_raw($youtube_video_link);
        
        // Only save if it's a valid URL
        if (filter_var($youtube_video_link, FILTER_VALIDATE_URL)) {
            update_post_meta($post_id, '_shieldclimb_youtube_video_link', $youtube_video_link);
        }
    }
}
?>