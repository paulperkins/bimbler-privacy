<?php 
    /*
    Plugin Name: Bimbler Privacy
    Plugin URI: http://www.bimblers.com
    Description: Plugin to make content visible only to logged-in users.
    Author: Paul Perkins
    Version: 0.1
    Author URI: http://www.bimblers.com
    */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
        die;
} // end if

require_once( plugin_dir_path( __FILE__ ) . 'class-bimbler-privacy.php' );

Bimbler_Privacy::get_instance();
