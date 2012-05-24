<?php

class PostBackgroundPluginPost {

	public static $singleton = null;
	public static function singleton() {
		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self;
		}
		return self::$singleton;
	}

	public function __construct() {
		add_action( 'wp_head', array( $this, 'header' ) );
	}

	protected function rule( $selector, $rule ) {
		if ( empty( $rule ) ) {
			return;
		}
		echo "$selector {\n";
		if ( @$rule['color'] ) {
			echo "	background-color: " . @$rule['color'] . ";\n";
		}
		if ( @$rule['image'] ) {
			echo "	background-image: url('" . @$rule['image'] . "');\n";
		}
		if ( @$rule['repeat-x'] && @$rule['repeat-y'] ) {
			echo "	background-repeat: repeat;\n";
		} elseif ( @$rule['repeat-x'] ) {
			echo "	background-repeat: repeat-x;\n";
		} elseif ( @$rule['repeat-y'] ) {
			echo "	background-repeat: repeat-y;\n";
		} else {
			echo "	background-repeat: no-repeat;\n";
		}
		if ( @$rule['position'] ) {
			echo "	background-position: " . @$rule['position'] . ";\n";
		}
		echo "}\n";
	}

	public function header() {
		global $wp_query;

		$options = PostBackgroundPluginOptions::singleton();

		echo "<style type=\"text/css\">\n";

		if ( is_single() ) {
			$postbackground = get_post_meta( get_the_ID(), 'postbackground', true );
			$this->rule( 'body', isset( $postbackground['body'] ) ? $postbackground['body'] : array() );
		}

		while ( $wp_query->have_posts() ) {
			$post = $wp_query->next_post();
			$postbackground = get_post_meta( $post->ID, 'postbackground', true );
			$selector = $options->get_content_selector( $post );
			$this->rule( $selector, isset( $postbackground['content'] ) ? $postbackground['content'] : array() );
		}
		$wp_query->rewind_posts();

		echo "</style>";
	}

}
return PostBackgroundPluginPost::singleton();
