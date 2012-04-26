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
		return ".post.post-{$post->ID}";
	}

}
return $postbackground_options = PostBackgroundPluginOptions::singleton();
