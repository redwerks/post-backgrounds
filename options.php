<?php

class PostBackgroundPluginOptions {

	public static $singleton = null;
	public static function singleton() {
		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self;
		}
		return self::$singleton;
	}

	public function __construct() {

	}

	public function get_content_selector( $post ) {
		$selector = ".post.post-{$post->ID}";
		$selector = apply_filters( 'post_background_content_selector', $selector, $post );
		return $selector;
	}

}
return $postbackground_options = PostBackgroundPluginOptions::singleton();
