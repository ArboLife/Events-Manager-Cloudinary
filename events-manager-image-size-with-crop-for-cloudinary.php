<?php 

/*
Plugin Name: Events Manager Image size with crop for Cloudinary
Plugin URI: https://github.com/ArboLife/Events-Manager-Cloudinary
Description:  Properly generating images with different sizes than the original, replacing #_EVENTIMAGE with #_CUSTOMEVENTIMAGE
Version: 1.0
Author: ArboLife
Author URI: https://www.arbolife.com/
@ref: http://snippets.webaware.com.au/snippets/stop-events-manager-from-cropping-thumbnails/
*/

add_filter('em_event_output_placeholder', 'events_manager_image_for_cloudinary', 10, 3);

/**
* get event image either by rsizing a registered WordPress image or as a a Cloudinary URL
* @param string $result;
* @param EM_Event $EM_Event
* @param string $event_string
* @result string
*/

function events_manager_image_for_cloudinary($result, $EM_Event, $event_string) {
    if (strpos($event_string, '#_CUSTOMEVENTIMAGE') !== false) {
        preg_match_all("/(#@?_?[A-Za-z0-9]+)({([^}]+)})?/", $event_string, $placeholders);
        $result = '';
        if($EM_Event->get_image_url() != ''){
            if( empty($placeholders[3][0]) ){
                $result = "<img src='".$EM_Event->get_image_url()."' alt='".$EM_Event->event_name."'/>";
            }else{
                $image_size = explode(',', $placeholders[3][0]);
                $image_url = $EM_Event->get_image_url();
                if( is_numeric($image_size[0]) && is_numeric($image_size[1]) && count($image_size) > 1 ){
                    if( EM_MS_GLOBAL && get_current_blog_id() != $EM_Event->blog_id ){
                        switch_to_blog($EM_Event->blog_id);
                        $switch_back = true;
                    }
                    if (strpos($image_url, 'cloudinary') !== false) {
                    //customize Cloudinary URL for the right size
                        $cloudinary_attr ='';
                        $image_attr = '';
                        if( empty($image_size[1]) && !empty($image_size[0]) ){    
                            $image_attr = 'width="'.$image_size[0].'"';
                            $cloudinary_attr = ',w_'.$image_size[0];
                        }elseif( empty($image_size[0]) && !empty($image_size[1]) ){
                            $image_attr = 'height="'.$image_size[1].'"';
                            $cloudinary_attr = 'h_'.$image_size[1];
                        }elseif( !empty($image_size[0]) && !empty($image_size[1]) ){
                            $image_attr = 'width="'.$image_size[0].'" height="'.$image_size[1].'"';
                            $cloudinary_attr = 'c_fill,h_'.$image_size[1].',w_'.$image_size[0];
                        }
                        $url_arr = explode('/', $image_url);
                        array_splice($url_arr, -2, 0, $cloudinary_attr);
                        $new_url = implode('/', $url_arr);
                        $result = "<img src='".$new_url."' alt='".$EM_Event->event_name."' $image_attr />";
                    }else{
                    //if not using Cloudinary, deliver it the normal way
                        $result = get_the_post_thumbnail($EM_Event->ID, $image_size);
                    }
                    if( !empty($switch_back) ){ restore_current_blog(); }            
                }else{
                    $result = "<img src='".$EM_Event->get_image_url()."' alt='".$EM_Event->event_name."'/>";
                }
            }
        }
    }
    return $result;
}
