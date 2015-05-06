<?php
/**
 * Bimbler Privacy
 *
 * @package   Bimbler_Privacy
 * @author    Paul Perkins <paul@paulperkins.net>
 * @license   GPL-2.0+
 * @link      http://www.paulperkins.net
 * @copyright 2014 Paul Perkins
 */

/**
 * Include dependencies necessary... (none at present)
 *
 */

/**
 * Bimbler Privacy
 *
 * @package Bimbler_Privacy
 * @author  Paul Perkins <paul@paulperkins.net>
 */
class Bimbler_Privacy {

        /*--------------------------------------------*
         * Constructor
         *--------------------------------------------*/

        /**
         * Instance of this class.
         *
         * @since    1.0.0
         *
         * @var      object
         */
        protected static $instance = null;

        /**
         * Return an instance of this class.
         *
         * @since     1.0.0
         *
         * @return    object    A single instance of this class.
         */
        public static function get_instance() {

                // If the single instance hasn't been set, set it now.
                if ( null == self::$instance ) {
                        self::$instance = new self;
                } // end if

                return self::$instance;

        } // end get_instance

        /**
         * Initializes the plugin by setting localization, admin styles, and content filters.
         */
        private function __construct() {

        	// Bounce non logged-in users on ride pages.
        	add_action( 'the_content' , array( $this, 'check_logged_in' ) );
        	        	         	
		} // End constructor.
	
		/*
		 * Process post content. If post has 'ride' as the parent category then user must be logged-in
		 * to view the content.
		 */
		function check_logged_in ($content) {

			//error_log ('Checking categories...');

			// Check if 'bimbler_private' post meta is set.
			$meta = get_post_meta (get_the_ID(), 'bimbler_private', true);

			if ((strlen ($meta) != 0) && ('Y' == $meta) && (!is_user_logged_in())) {

				$content = '<div class="bimbler-alert-box notice"><span>Notice: </span>You must be logged in to view this page.</div>';
				return $content;
			}

			// If not explicity barred from public consumption (above test), then see if this is a ride page.
			// Display an excerpt if one exists, just to tease the reader :)
			$categories = wp_get_post_categories (get_the_ID());
			
			if (!isset ($categories)) {
				error_log ('No categories.');
				return $content;
			}
			
			foreach ($categories as $c) {
				$category = get_category ($c);
				
				//error_log ('Checking category \''. $category->name . '\'.');
				
				// We need to get the parent category.
				if (isset ($category->category_parent)) {
						
					$parent = get_cat_name ($category->category_parent);
					
					//error_log ('Checking parent \''. $parent . '\'.');
						
					// Stop here if the user is not logged in.
					if (('Ride' == $parent) && (!is_user_logged_in())) {
						$content = '';
						
						/*if (!empty (get_the_ID())) {
							$excerpt = get_the_ID()->post_excerpt;
							
							//if (0 < strlen (get_the_ID()->post_excerpt)) {
							if (!empty($excerpt)) {
								$content .= '<p><em>' . $excerpt . '</em></p>' . PHP_EOL;
							}
						}*/
						
						$content .= '<div class="bimbler-alert-box notice"><span>Notice: </span>You must be logged in to view this page.</div>';
						
						//error_log ('Category ride and user not logged-in - returning error message.');
						
						return $content;
					}
				}
			}
			
			// Carry on - nothing to see here.
			return $content;
		}	
		
} // End class
