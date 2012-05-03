<?php
/*
Plugin Name: Post Backgrounds
Plugin URI: https://github.com/redwerks/post-backgrounds
Description: Allows posts and pages to have individual custom backgrounds. Both as body backgrounds and as content backgrounds.
Author: Redwerks
Author URI: http://redwerks.org/
Version: 0.9.1
*/

require_once( dirname( __FILE__ ) . '/options.php' );
if ( is_admin() ) {
	require_once( dirname( __FILE__ ) . '/admin.php' );
} else {
	require_once( dirname( __FILE__ ) . '/post.php' );
}
