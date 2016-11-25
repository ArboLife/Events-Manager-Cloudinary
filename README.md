# events-manager-image-size-with-crop-for-cloudinary.php
Events Manager Image size with crop for Cloudinary.

To use this code you need those 2 plugins installed:
- Events Manager
  source: https://wordpress.org/plugins/events-manager/
- Cloudinary - Image management and manipulation in the cloud + CDN
  source: https://wordpress.org/plugins/cloudinary-image-management-and-manipulation-in-the-cloud-cdn/developers/

Deployment: this code can either be installed as a plugin or pasted in the functions.php of a child theme.

Usage: #_CUSTOMEVENTIMAGE{x,y}
Shows the event image thumbnail, x and y are width and height respectively, both being numbers e.g. #_EVENTIMAGE{100,100}. If 0 is used for either width or height, the corresponding dimension will be proportionally sized

The shortcode #_CUSTOMEVENTIMAGE is used in the same way the original Events Manager shortcode #_EVENTIMAGE is used, as documented here: http://wp-events-plugin.com/documentation/placeholders/

Please note that this plugin has not been tested with the Disable thumbnails option turned on. Please keep it off if you're using this code.
