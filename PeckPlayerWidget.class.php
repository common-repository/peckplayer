<?php
if ( ! class_exists('PeckPlayerWidget' ) ) :

class PeckPlayerWidget extends WP_Widget
{
	function __construct() {
		// Instantiate the parent object
		parent::WP_Widget( false, 'PeckPlayer', array("description" => __('Your custom PeckPlayer.', PeckPlayer::LOCALIZATION_DOMAIN)) );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', $instance['title']);
		$id = $instance['id'];
		$sounds = $instance['sounds'];
		
		echo $before_widget;
		if($title)
		{
        	echo $before_title . $title . $after_title;
		}
		echo PeckPlayer::show_peckplayer(array('id' => $id, 'sound' => $sounds));
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
	    $instance['title'] = $new_instance['title'];
	    $instance['id'] = $new_instance['id'];
	    $instance['sounds'] = str_replace(',', '|', $new_instance['sounds']);
	    return $instance;
	}

	function form( $instance ) {
		$title = esc_attr($instance['title']);
		$id = esc_attr($instance['id']);
    	$sounds = esc_attr($instance['sounds']);
		$peckplayers = PeckPlayerDBManager::get_all();
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e("Title", PeckPlayer::LOCALIZATION_DOMAIN); ?> : </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('id'); ?>">PeckPlayer : </label>
			<select class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>">
	        	<? foreach( $peckplayers as $peckplayer ): ?>
	        	<option value="<?php echo $peckplayer->id; ?>" <?php if($peckplayer->id == $id ):?> selected="selected" <?php endif;?>><? echo $peckplayer->name; ?> (<? echo PeckPlayer::get_version_display($peckplayer->version); ?>)</option>
	        	<? endforeach; ?>
	        </select>
		    <small><a href="<?php echo admin_url( 'admin.php?page='.PeckPlayer::ADMIN_FORM_PAGE_TITLE ); ?>"><?php _e('Add a PeckPlayer', PeckPlayer::LOCALIZATION_DOMAIN); ?></a></small>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('sounds'); ?>"><?php _e("List of sounds", PeckPlayer::LOCALIZATION_DOMAIN); ?> : </label>
		    <input class="widefat" id="<?php echo $this->get_field_id('sounds'); ?>" name="<?php echo $this->get_field_name('sounds'); ?>" type="text" value="<?php echo $sounds; ?>" />
		    <small><?php _e('Url of sounds, separated by a comma.', PeckPlayer::LOCALIZATION_DOMAIN) ?></small>
		</p>
		<?php
	}
}

endif;
?>