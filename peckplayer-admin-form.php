<?php if( isset($updates_notice) && count($updates_notice) > 0 ): ?>
	<div id="message" class="updated fade">
		<p>
			<?php foreach( $updates_notice as $update_notice ): ?>
			<strong>
			<?php echo $update_notice;?>
			</strong>
			<?php endforeach; ?>
		</p>
	</div>
<? endif; ?>
<?php if( isset($errors_notice) && count($errors_notice) > 0 ): ?>
	<div id="message" class="error fade">
		<p>
			<?php foreach( $errors_notice as $error_notice ): ?>
			<strong>
			<?php echo $error_notice;?>
			</strong>
			<?php endforeach; ?>
		</p>
	</div>
<? endif; ?>
<div class="wrap">
	<div id="icon-peckplayer" class="icon32 icon32-peckplayer"><br></div>
	<h2>
		<?php if(isset($_GET['action']) && $_GET['action'] === 'edit' ):?>
			<?php _e('Edit a PeckPlayer', PeckPlayer::LOCALIZATION_DOMAIN); ?></h2>
		<?php else: ?>
			<?php _e('Add a template', PeckPlayer::LOCALIZATION_DOMAIN); ?></h2>
		<?php endif;?>
	</h2>
	<form id="peckplayer-form" action="" method="POST">
		<div>
			<?php if( isset($id) ):?>
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
			<? endif; ?>
			<table class="form-table">
				<tr>
					<th><?php _e('Name', PeckPlayer::LOCALIZATION_DOMAIN);?></th>
					<td>
					<input id="name" name="name" type="text" <?php if( isset($name)) :?>
					value="<?php echo $name;?>"<?php endif;?> />
					</td>
				</tr>
				<tr>
					<th><?php _e('Version', PeckPlayer::LOCALIZATION_DOMAIN);?></th>
					<td>
					<input type="radio" name="version" <?php if( !isset($version) || (isset($version) && $version === PeckPlayer::PECKPLAYER_VERSION_MINI)) :?>
					checked="checked"<?php endif;?> value="<?php echo PeckPlayer::PECKPLAYER_VERSION_MINI;?>" />
					<?php _e('Mini', PeckPlayer::LOCALIZATION_DOMAIN);?>
					<span class="help">(160x20)</span>
					<br />
					<input type="radio" name="version" <?php if(isset($version) && $version === PeckPlayer::PECKPLAYER_VERSION_CLASSIC) :?>
					checked="checked"<?php endif;?> value="<?php echo PeckPlayer::PECKPLAYER_VERSION_CLASSIC;?>" />
					<?php _e('Classic', PeckPlayer::LOCALIZATION_DOMAIN);?>
					<span class="help">(190x20)</span>
					<br />
					<input type="radio" name="version" <?php if(isset($version) && $version === PeckPlayer::PECKPLAYER_VERSION_MULTI) :?>
					checked="checked"<?php endif;?> value="<?php echo PeckPlayer::PECKPLAYER_VERSION_MULTI;?>" />
					<?php _e('Multi', PeckPlayer::LOCALIZATION_DOMAIN);?>
					<span class="help">(230x20)</span>
					<br />
					</td>
				</tr>
				<tr>
					<th><?php _e('Auto play', PeckPlayer::LOCALIZATION_DOMAIN);?></th>
					<td>
					<input type="checkbox" id="autostart" name="autostart" <?php if((isset($autostart) && $autostart )) :?>
					checked="checked"<?php endif;?> />
					</td>
				</tr>
				<tr>
					<th><?php _e('Loop', PeckPlayer::LOCALIZATION_DOMAIN);?></th>
					<td>
					<input type="checkbox" id="autoreplay" name="autoreplay" <?php if(isset($autoreplay) && $autoreplay ) :?>
					checked="checked"<?php endif;?> />
					</td>
				</tr>
				<tr>
					<th><?php _e('Volume', PeckPlayer::LOCALIZATION_DOMAIN);?></th>
					<td>
					<div id="slider-volume">
					</div>
					<span id="volume-value"><?php if(isset($volume)) :?><?php echo $volume; ?><? else: ?>75<? endif; ?></span>
					<input type="hidden" id="volume" name="volume" value="<?php if(isset($volume)) :?><?php echo $volume; ?><? else: ?>75<? endif; ?>" />
					<div class="clear">
					</div>
					</td>
				</tr>
			</table>
		</div>
		<div>
			<table>
				<tr>
					<td colspan="3" class="align-center">
					<div id="example-container">
						<div id="peckplayer-example">
							<div id="flash">
								<!--[if !IE]>-->
								<script type="text/javascript">
									jQuery(function(){
										swfobject.registerObject("peckplayer", "9.0.0", "'.plugin_dir_url( __FILE__ ) . 'media/swf/expressInstall.swf");
									});
								</script>
								<!--<![endif]-->
								<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="160" height="20" id="peckplayer" name="peckplayer">
									<param name="movie" value="<?php echo plugin_dir_url( __FILE__ ) ;?>media/swf/peckplayer-mini-audio.swf" />
									<param name="wmode" value="window" />
									<param name="bgcolor" value="#f2f2f2" />
									<param name="allowscriptaccess" value="always" />
									<param name="flashvars" value="sounds=http://peckplayer.scopart.fr/mp3/mazik.mp3&amp;innerButtonColor=0xff8c00&amp;autostart=false&amp;autoreplay=false&amp;volume=75&amp;textColor=0xff8c00&amp;bgButtonColor=0x000000" />
									<!--[if !IE]>-->
									<object type="application/x-shockwave-flash" data="<?php echo plugin_dir_url( __FILE__ ) ;?>media/swf/peckplayer-mini-audio.swf" width="160" height="20">
										<param name="wmode" value="window" />
										<param name="bgcolor" value="#f2f2f2" />
										<param name="allowscriptaccess" value="always" />
										<param name="flashvars" value="sounds=http://peckplayer.scopart.fr/mp3/mazik.mp3&amp;innerButtonColor=0xff8c00&amp;autostart=false&amp;autoreplay=false&amp;volume=75&amp;textColor=0xff8c00&amp;bgButtonColor=0x000000" />
									<!--<![endif]-->
										<a href="http://www.adobe.com/go/getflashplayer">
											<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
										</a>
									<!--[if !IE]>-->
									</object>
									<!--<![endif]-->
								</object>
							</div>
						</div>
					</div>
					</td>
				</tr>
				<tr>
					<td class="color-label"><?php _e('Foreground color', PeckPlayer::LOCALIZATION_DOMAIN);?></td>
					<td class="color-label"><?php _e('Background color', PeckPlayer::LOCALIZATION_DOMAIN);?></td>
					<td class="color-label"><?php _e('Text color', PeckPlayer::LOCALIZATION_DOMAIN);?></td>
				</tr>
				<tr>
					<td>
					<input type="hidden" id="inner-button-color-tmp" value="<?php if(isset($innerButtonColor)) :?><?php echo $innerButtonColor; ?><?php else: ?>ff8c00<?php endif; ?>" />
					<div id="color-inner" class="color-selector">
					</div>
					</td>
					<td>
					<input type="hidden" id="bg-button-color-tmp" value="<?php if(isset($bgButtonColor)) :?><?php echo $bgButtonColor; ?><?php else: ?>000000<?php endif; ?>" />
					<div id="color-bg" class="color-selector">
					</div>
					</td>
					<td>
					<input type="hidden" id="text-color-tmp" value="<?php if(isset($textColor)) :?><?php echo $textColor; ?><?php else: ?>ff8c00<?php endif; ?>" />
					<div id="color-text" class="color-selector">
					</div>
					</td>
				</tr>
			</table>
		</div>
		<p class="submit">
			<input class="button-primary" type="submit" value="<?php _e('Save configuration', PeckPlayer::LOCALIZATION_DOMAIN);?>" />
		</p>
	</form>
	<div id="scopart-note">
		Peckplayer <?php _e('is developed by', PeckPlayer::LOCALIZATION_DOMAIN); ?> <a href="http://www.scopart.fr/" target="_blank">Scopart</a> | <?php _e('check the project page', PeckPlayer::LOCALIZATION_DOMAIN); ?> : <a href="http://www.scopart.fr/peckplayer/" target="_blank">Peckplayer</a>.
	</div>
</div>