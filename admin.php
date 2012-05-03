<?php

class PostBackgroundPluginAdmin {

	public static $singleton = null;
	public static function singleton() {
		if ( is_null( self::$singleton ) ) {
			self::$singleton = new self;
		}
		return self::$singleton;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'save_post', array( $this, 'onsave' ), 1, 2 );
		add_action( 'delete_post', array( $this, 'ondelete' ) );
	}

	public function admin_menu() {
		add_meta_box( 'postbackground-content', __( 'Post Content Area Background', 'postbackground' ), array( $this, 'metabox_content' ), 'post', 'normal', 'core' );
		add_meta_box( 'postbackground-content', __( 'Page Content Area Background', 'postbackground' ), array( $this, 'metabox_content' ), 'page', 'normal', 'core' );
		add_meta_box( 'postbackground-body', __( 'Body Post Background', 'postbackground' ), array( $this, 'metabox_body' ), 'post', 'normal', 'core' );
		add_meta_box( 'postbackground-body', __( 'Body Page Background', 'postbackground' ), array( $this, 'metabox_body' ), 'page', 'normal', 'core' );
	}

	public function onsave( $post_id, $post ) {
		if ( !$post_id ) $post_id = $_POST['post_ID'];
		if ( !$post_id ) return $post;

		if ( !isset( $_POST['postbackground_content_noncename'] )
			|| !wp_verify_nonce( $_POST['postbackground_content_noncename'], plugin_basename(__FILE__) )
			|| !isset( $_POST['postbackground_body_noncename'] )
			|| !wp_verify_nonce( $_POST['postbackground_body_noncename'], plugin_basename(__FILE__) ) )
		{
			return $post;
		}

		update_post_meta( $post_id, 'postbackground', $_POST['postbackground'] );
	}

	public function ondelete( $post_id ) {
		delete_post_meta( $post_id, 'postbackground' );
	}

	private $postbackground;
	public function getmeta( $section ) {
		if ( !isset( $this->postbackground ) ) {
			global $post_id;
			$this->postbackground = get_post_meta( $post_id, 'postbackground', true );
		}
		return isset( $this->postbackground[$section] ) ? $this->postbackground[$section] : array();
	}

	public function metabox_content() {
		$data = $this->getmeta( 'content' );
		wp_nonce_field( plugin_basename(__FILE__), 'postbackground_content_noncename' ); ?>
<div>
	<p>Custom content area background. Shows up behind the content. Visible on all pages, including archives.</p>
<?php
		$this->box( 'content', $data ); ?>
</div>
<?php
	}

	public function metabox_body() {
		$data = $this->getmeta( 'body' );
		wp_nonce_field( plugin_basename(__FILE__), 'postbackground_body_noncename' ); ?>
<div>
	<p>Custom full body background. Covers the entire html document. Only visible on the post/page itself.<p>
<?php
		$this->box( 'body', $data ); ?>
</div>
<?php
	}

	public function box( $section, $data ) { ?>
	<style type="text/css" scoped>
		#postbackground-<?php echo $section; ?>-table {
			width: 100%;
			margin: 0;
			background-color: #F9F9F9;
			border: 1px solid #DFDFDF;
			border-spacing: 8px;
			-moz-border-radius: 3px;
			-webkit-border-radius: 3px;
			border-radius: 3px;
			font-size: 1.1em;
		}
		#postbackground-<?php echo $section; ?>-table th {
			text-align: right;
			vertical-align: middle;
			width: 25%;
			font-weight: normal;
		}
	</style>
	<table id="postbackground-<?php echo $section; ?>-table">
		<tr>
			<th scope="row"><?php _e( 'Image URL', 'post-background' ); ?></th>
			<td>
				<input type="text" name="postbackground[<?php echo $section; ?>][image]" value="<?php echo esc_attr( @$data['image'] ); ?>">
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e( 'Color', 'post-background' ); ?></th>
			<td>
				<input type="text" name="postbackground[<?php echo $section; ?>][color]" value="<?php echo esc_attr( @$data['color'] ); ?>">
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e( 'Repeat', 'post-background' ); ?></th>
			<td>
				<label><input type="checkbox" name="postbackground[<?php echo $section; ?>][repeat-x]"<?php if ( @$data['repeat-x'] ) { ?> checked<?php } ?>> <?php _e( 'Repeat X', 'post-background' ); ?></label>
				<label><input type="checkbox" name="postbackground[<?php echo $section; ?>][repeat-y]"<?php if ( @$data['repeat-y'] ) { ?> checked<?php } ?>> <?php _e( 'Repeat Y', 'post-background' ); ?></label>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e( 'Position', 'post-background' ); ?></th>
			<td>
				<input type="text" name="postbackground[<?php echo $section; ?>][position]" value="<?php echo esc_attr( @$data['position'] ); ?>">
			</td>
		</tr>
	</table>
<?php
	}

}
return PostBackgroundPluginAdmin::singleton();
