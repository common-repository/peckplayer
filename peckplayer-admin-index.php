<div class="wrap">
	<div id="icon-peckplayer" class="icon32 icon32-peckplayer">
		<br>
	</div>
	<div class="donate float-right">
		<?php _e('You like our work ?', PeckPlayer::LOCALIZATION_DOMAIN); ?><br />
		<?php _e('Feel free to use this button', PeckPlayer::LOCALIZATION_DOMAIN); ?>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="BXYRLZEBBVATW">
			<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
			<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
	<h2>
	<?php _e('My PeckPlayer', PeckPlayer::LOCALIZATION_DOMAIN); ?>
	<a href="?page=<?php echo PeckPlayer::ADMIN_FORM_PAGE_TITLE;?>" class="add-new-h2"><?php _e('Add a template', PeckPlayer::LOCALIZATION_DOMAIN); ?></a>
	</h2>
	<div class="clear"></div>
	<em><?php _e('Create here your templates', PeckPlayer::LOCALIZATION_DOMAIN); ?></em>
	<table class="wp-list-table widefat fixed" cellspacing="0">
		<thead>
			<tr>
				<th scope="col" id="name" class="manage-column"><? _e('Name', PeckPlayer::LOCALIZATION_DOMAIN); ?></th>
				<th scope="col" id="name" class="manage-column"><? _e('PeckPlayer Tag', PeckPlayer::LOCALIZATION_DOMAIN); ?></th>
				<th scope="col" id="type" class="manage-column"><? _e('Version', PeckPlayer::LOCALIZATION_DOMAIN); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" id="name" class="manage-column"><? _e('Name', PeckPlayer::LOCALIZATION_DOMAIN); ?></th>
				<th scope="col" id="name" class="manage-column"><? _e('PeckPlayer Tag', PeckPlayer::LOCALIZATION_DOMAIN); ?></th>
				<th scope="col" id="type" class="manage-column"><? _e('Version', PeckPlayer::LOCALIZATION_DOMAIN); ?></th>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach( $peckplayers as $key => $value ):?>
			<tr class="alternate format-default iedit" valign="top">
				<td>
				<a class="row-title" href="?page=<?php echo PeckPlayer::ADMIN_FORM_PAGE_TITLE;?>&action=edit&id=<?php echo $value->id;?>" title="<?php _e("Edit this line", PeckPlayer::LOCALIZATION_DOMAIN);?>"><?php echo $value->name;?></a>
				<div class="row-actions">
					<span class="edit">
						<a href="?page=<?php echo PeckPlayer::ADMIN_FORM_PAGE_TITLE;?>&action=edit&id=<?php echo $value->id;?>" title="<?php _e("Edit this line", PeckPlayer::LOCALIZATION_DOMAIN);?>"><?php _e("Edit", PeckPlayer::LOCALIZATION_DOMAIN);?></a>
					</span>
					<?php if( 1 != $value->id ):?>
					<span class="trash">
						| <a class="submitdelete" title="<?php _e("Delete", PeckPlayer::LOCALIZATION_DOMAIN);?>" href="?page=<?php echo $_GET['page'];?>&action=delete&id=<?php echo $value->id;?>">
						<?php _e("Delete", PeckPlayer::LOCALIZATION_DOMAIN);?>
						</a>
					</span>
					<?php endif;?>
				</div>
				</td>
				<td>
				<?php if( $value->version === PeckPlayer::PECKPLAYER_VERSION_MULTI ): ?>
				[peckplayer id="<?php echo $value->id;?>" sound="sound1.mp3|sound2.mp3|..."]
				<?php else: ?>
				[peckplayer id="<?php echo $value->id;?>" sound="sound.mp3"]
				<?php endif;?>
				</td>
				<td>
				<?php echo PeckPlayer::get_version_display($value->version); ?>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
</div>
<div id="scopart-note">
	Peckplayer <?php _e('is developed by', PeckPlayer::LOCALIZATION_DOMAIN); ?> <a href="http://www.scopart.fr/" target="_blank">Scopart</a> | <?php _e('check the project page', PeckPlayer::LOCALIZATION_DOMAIN); ?> : <a href="http://www.scopart.fr/peckplayer/" target="_blank">Peckplayer</a>.
</div>
<script type="text/javascript">
	jQuery(function($){
		$('a.submitdelete').click(function(){
			return confirm('<?php _e("Are you sure you want to delete ?", PeckPlayer::LOCALIZATION_DOMAIN);?>');
		});
	});
</script>