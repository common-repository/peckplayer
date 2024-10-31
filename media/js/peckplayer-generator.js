jQuery( function($) {
	var plugin_url = 'http://peckplayer.scopart.fr/swf/';
	$.get('/wp-admin/admin-ajax.php?action=pluginurl',
		function(data){
			plugin_url = data + 'media/swf/';
		}
	);
	function getCode(path, wmode){
		path = path ? path : '';
		wmode = wmode ? wmode : 'transparent';
		var flash = $('#flash');
		
		return '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" style="visibility: visible;" width="'+flash.data('width')+'" height="20" id="peckplayer" name="peckplayer"><param name="movie" value="'+path+flash.data('version')+'.swf" /><param name="wmode" value="'+wmode+'" />'+( wmode != 'transparent' ? '<param name="bgcolor" value="#f2f2f2" />' : '')+'<param name="allowscriptaccess" value="always" /><param name="flashvars" value="sounds='+flash.data('sounds')+'&amp;innerButtonColor='+flash.data('innerButtonColor')+'&amp;autostart='+flash.data('autostart')+'&amp;autoreplay='+flash.data('autoreplay')+'&amp;volume='+flash.data('volume')+'&amp;textColor='+flash.data('textColor')+'&amp;bgButtonColor='+flash.data('bgButtonColor')+'" /><!--[if !IE]>--><object type="application/x-shockwave-flash" data="'+path+flash.data('version')+'.swf" width="'+flash.data('width')+'" height="20"><param name="wmode" value="'+wmode+'" />'+( wmode != 'transparent' ? '<param name="bgcolor" value="#f2f2f2" />' : '')+'<param name="allowscriptaccess" value="always" /><param name="flashvars" value="sounds='+flash.data('sounds')+'&amp;innerButtonColor='+flash.data('innerButtonColor')+'&amp;autostart='+flash.data('autostart')+'&amp;autoreplay='+flash.data('autoreplay')+'&amp;volume='+flash.data('volume')+'&amp;textColor='+flash.data('textColor')+'&amp;bgButtonColor='+flash.data('bgButtonColor')+'" /><!--<![endif]--><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a><!--[if !IE]>--></object><!--<![endif]--></object>';
	}
	
	function draw(){
		$('#peckplayer-example').width($('#flash').data('width')+'px');
		$('#flash').html(getCode(plugin_url, 'window'));
	};
		
	if( $('#flash').get(0) !== undefined )
	{
		var dash = /-/g;
		
		var peckplayerWidth = {
			peckplayerminiaudio : '160',
			peckplayerclassicaudio : '190',
			peckplayermultiaudio : '230'
		};
		
		var version = $('input[name="version"]:checked').val();
		$('#flash').data('version', version);
		$('#flash').data('width', peckplayerWidth[version.replace(dash, '')]);
		$('#flash').data('height', '20');
		$('#flash').data('autostart', $('#autostart').attr('checked') === undefined ? false : true);
		$('#flash').data('autoreplay', $('#autoreplay').attr('checked') === undefined ? false : true);
		$('#flash').data('volume', $('#volume').val());
		$('#flash').data('sounds', 'http://peckplayer.scopart.fr/mp3/mazik.mp3');
		var innerButtonColor = $('#inner-button-color-tmp').val(),
			bgButtonColor = $('#bg-button-color-tmp').val(),
			textColor = $('#text-color-tmp').val();
		$('#flash').data('innerButtonColor', innerButtonColor === undefined ? '0xff8c00' : '0x'+innerButtonColor);
		$('#flash').data('bgButtonColor', bgButtonColor === undefined ? '0x000000' : '0x'+bgButtonColor);
		$('#flash').data('textColor', textColor === undefined ? '0xff8c00' : '0x'+textColor);
		$('#inner-button-color-tmp').remove();
		$('#bg-button-color-tmp').remove();
		$('#text-color-tmp').remove();
		draw();
		
		$('input[name="version"]').change(function(){
			var flash = $('#flash');
			var version = $(this).val();
			flash.data('version', version);
			flash.data('width', peckplayerWidth[version.replace(dash, '')]);
			draw();
		});
		
		$('#sounds').change(function(){
			$('#flash').data('sounds', $(this).val());
			draw();
		});
		
		$('#autostart').click(function(){
			$('#flash').data('autostart', $(this).is(':checked'));
			draw();
		});
	
		$('#autoreplay').click(function(){
			$('#flash').data('autoreplay', $(this).is(':checked'));
			draw();
		});
		
		$( "#slider-volume" ).slider({
			range: "min",
			value: $('#flash').data('volume'),
			min: 0,
			max: 100,
			slide: function( event, ui ) {
				$('#volume').val( ui.value );
				$('#volume-value').text( ui.value );
			},
			stop: function(event, ui) {
				$('#flash').data('volume', ui.value);
				draw();
			}
		});
		
		$('#color-inner').ColorPicker({
			flat: true,
			name: 'color-inner',
			color: $('#flash').data('innerButtonColor'),
			onChange: function(hsb, hex, rgb) {
				try
				{
					$("#peckplayer").get(0).setInnerColor(hex);
				}catch(e){
					$("#peckplayer > object").get(0).setInnerColor(hex);
				}
				$('#flash').data('innerButtonColor', '0x'+hex);
			}
		});
		
		$('#color-inner').find('input').each(function(index, elt){
			if( index === 0 )
			{
				$(elt).attr("name", 'inner-button-color');
			}
			else
			{
				$(elt).attr("name", 'inner-button-color-'+index);
				$(elt).val(Math.floor($(elt).val())); // fixbug in chrome
			}
		});
		
		$('#color-bg').ColorPicker({
			flat: true,
			name: 'color-bg',
			color: $('#flash').data('bgButtonColor'),
			onChange: function(hsb, hex, rgb) {
				try
				{
					$("#peckplayer").get(0).setBackgroundButtonColor(hex);
				}catch(e){
					$("#peckplayer > object").get(0).setBackgroundButtonColor(hex);
				}
				$('#flash').data('bgButtonColor', '0x'+hex);
			}
		});
		
		$('#color-bg').find('input').each(function(index, elt){
			if( index === 0 )
			{
				$(elt).attr("name", 'bg-button-color');
			}
			else
			{
				$(elt).attr("name", 'bg-button-color-'+index);
				$(elt).val(Math.floor($(elt).val())); // fixbug in chrome
			}
		});
		
		$('#color-text').ColorPicker({
			flat: true,
			name: 'color-text',
			color: $('#flash').data('textColor'),
			onChange: function(hsb, hex, rgb) {
				try
				{
					$("#peckplayer").get(0).setTextColor(hex);
				}catch(e){
					$("#peckplayer > object").get(0).setTextColor(hex);
				}
				$('#flash').data('textColor', '0x'+hex);
			}
		});
		
		$('#color-text').find('input').each(function(index, elt){
			if( index === 0 )
			{
				$(elt).attr("name", 'text-color');
			}
			else
			{
				$(elt).attr("name", 'text-color-'+index);
				$(elt).val(Math.floor($(elt).val())); // fixbug in chrome
			}
		});
	}
});
