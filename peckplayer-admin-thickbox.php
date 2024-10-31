<h3 class="media-title"><?php _e("Add a Peckplayer and choose your sound in library", PeckPlayer::LOCALIZATION_DOMAIN);?></h3>
<div id="message-container">
</div>
<form action="" method="POST">
	<p>
		<?php _e('Choose your peckplayer version and drag your sound', PeckPlayer::LOCALIZATION_DOMAIN);?>
	</p>
	<div id="version-container">
		<label for="version"><?php _e('Template', PeckPlayer::LOCALIZATION_DOMAIN);?></label> : 
		<select id="version" name="version">
			<?php $count = 1; $length = count($peckplayers);
                  foreach($peckplayers as $peckplayer):?>
				<option <?php echo $count == $length ? 'selected=selected ' : '';?>value="<?php echo $peckplayer->id;?>" data="<?php echo $peckplayer->version;?>"><?php echo $peckplayer->name;?> (<?php echo PeckPlayer::get_version_display($peckplayer->version);?>)</option>
			<?php $count++;
                  endforeach; ?>
		</select>
	</div>
	<div>
        <div id="peckplayer-sound-container" class="float-right">
        	<div><label><?php _e('PeckPlayer sounds', PeckPlayer::LOCALIZATION_DOMAIN);?></label></div>
        	<div>
	            <ul id="peckplayer-sound" class="connectedSortable">
	            </ul>
        	</div>
        </div>
        <div id="library-sound-container" class="float-left">
        	<div><label><?php _e('Library sounds', PeckPlayer::LOCALIZATION_DOMAIN);?></label></div>
            <div>
	            <ul id="library-sound" class="connectedSortable">
	            	<?php $audios =& get_children( 'post_type=attachment&post_mime_type=audio/mpeg&post_parent=null' ); ?>
					<?php foreach ( (array) $audios as $attachment_id => $attachment ) : ?>
					<li id="<?php echo $attachment->guid; ?>"><?php echo $attachment->post_title; ?></li>
					<?php endforeach; ?>
	            </ul>
            </div>
        </div>
        <div id="sound-control">
        	<div id="arrow-right"></div>
        	<div id="arrow-left"></div>
        	<div class="clear"></div>
        </div>
        <div class="clear"></div>
	</div>
	<p class="submit">
		<input class="button" type="submit" value="<?php _e('Validate', PeckPlayer::LOCALIZATION_DOMAIN);?>" />
	</p>
</form>
<script type="text/javascript">
jQuery(function($) {
	$( "#peckplayer-sound, #library-sound" ).sortable({
		connectWith: ".connectedSortable"
	}).disableSelection();
	$('form input').click(function(){
		var id = $('#version').val(),
			version = $('#version :selected').attr('data');
			sounds = $('#peckplayer-sound').sortable('toArray');
		$('#message-container').empty();
		if( sounds.length > 1 && version != '<?php echo PeckPlayer::PECKPLAYER_VERSION_MULTI; ?>' )
		{
			$('#message-container').html('<div id="message" class="error fade"><p><strong><?php _e("Multi version is the only one which can play multiple sounds.", PeckPlayer::LOCALIZATION_DOMAIN); ?></strong></p></div>');
		}
		else
		{
            try{
    			var s = sounds.join('|');
    			send_to_editor('[peckplayer id="'+id+'" sound="'+s+'"]');
    			tb_remove();
            }
            catch(e)
            {
                $('#message-container').html('<div id="message" class="error fade"><p><strong><?php _e("Unable to insert PeckPlayer code.<br />Please paste yourself this code into editor :", PeckPlayer::LOCALIZATION_DOMAIN); ?></strong></p><code>[peckplayer id="'+id+'" sound="'+s+'"]</code></div>');
            }
		}
		return false;
	});
});
</script>