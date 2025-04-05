<?php

add_action('woocommerce_product_thumbnails', 'shieldclimb_display_youtube_video_link_in_gallery', 20);
function shieldclimb_display_youtube_video_link_in_gallery() {
    global $product;

    // Get the YouTube video link from the custom field
    $youtube_video_link = get_post_meta($product->get_id(), '_shieldclimb_youtube_video_link', true);

    if ($youtube_video_link) {
        // Parse the YouTube URL to extract video ID or playlist ID
        $video_id = '';
        $is_playlist = false;
        
        // Check if it's a regular video URL
        if (preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $youtube_video_link, $matches)) {
            $video_id = $matches[1];
        } 
        // Check if it's a playlist URL
        elseif (preg_match('/[&?]list=([^&]+)/', $youtube_video_link, $matches)) {
            $video_id = $matches[1];
            $is_playlist = true;
        }

        if ($video_id) {
            // Build the embed URL with parameters
            $embed_url = $is_playlist 
                ? "https://www.youtube.com/embed/videoseries?list={$video_id}"
                : "https://www.youtube.com/embed/{$video_id}";
                
            $embed_url = add_query_arg(array(
                'autoplay' => 1,       // Autoplay the video
                'mute' => 1,           // Mute by default (required for autoplay in most browsers)
                'loop' => $is_playlist ? 0 : 1, // Loop single videos (playlists loop by default)
                'playlist' => $is_playlist ? '' : $video_id, // Needed for single video loop
                'controls' => 1,       // Show controls
                'rel' => 0,             // Don't show related videos at the end
                'modestbranding' => 1,  // Less YouTube branding
                'enablejsapi' => 1      // Enable JS API for potential future interactions
            ), $embed_url);

            // Responsive iframe container
            echo '<div class="shieldclimb-youtube-video-container" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;margin-top:15px;">';
            echo '<iframe style="position:absolute;top:0;left:0;width:100%;height:100%;" 
                   src="' . esc_url($embed_url) . '" 
                   title="Product Video" 
                   frameborder="0" 
                   allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                   allowfullscreen></iframe>';
            echo '</div>';
            
            // Optional: Add some basic CSS to ensure proper display
            echo '<style>
                .shieldclimb-youtube-video-container iframe {
                    max-width: 100%;
                }
                .woocommerce-product-gallery .shieldclimb-youtube-video-container {
                    margin-bottom: 15px;
                }
            </style>';
        }
    }
}

?>