<?php
if ( ! class_exists('PeckPlayer' ) ) :
/**
 * This class triggers functions that run during activation/deactivation & uninstallation
 */
class PeckPlayer
{
    // Set this to true to get the state of origin, so you don't need to always uninstall during development.
    const STATE_OF_ORIGIN = false;

	const LOCALIZATION_DOMAIN = 'peckplayer';
	
	// Admin menu constants
	const ADMIN_INDEX_PAGE_TITLE = 'peckplayer';
	const ADMIN_FORM_PAGE_TITLE = 'peckplayer-form';
	const ADMIN_MENU_TITLE = 'PeckPlayer';
	const ADMIN_MENU_SLUG = 'peckplayer';
	
	// PeckPlayer versions
	const PECKPLAYER_VERSION_MINI = 'peckplayer-mini-audio';
	const PECKPLAYER_VERSION_CLASSIC = 'peckplayer-classic-audio';
	const PECKPLAYER_VERSION_MULTI = 'peckplayer-multi-audio';
	
    function __construct( $case = false )
    {
		global $wpdb;
		
		// Force the use of an argument to build object
        if ( ! $case )
            wp_die( 'Busted! You should not call this class directly', 'Doing it wrong!' );

        switch( $case )
        {
        	// Plugin activation
            case 'activate' :
				// Creating database table to store peckplayer configuration
				PeckPlayerDBManager::create_table();
				if( PeckPlayerDBManager::count() == 0 )
				{
					// Insert a default peckplayer if there is no row in table
					PeckPlayerDBManager::insert('default', self::PECKPLAYER_VERSION_MINI, false, true, 75, 'ff8c00', '000000', 'ff8c00');
				}
                break;

			// Plugin deactivation
            case 'deactivate' : 
                break;

			// Plugin uninstallation
            case 'uninstall' :
				// Drop database table
		        PeckPlayerDBManager::drop_table();
                break;
				
			// Plugin loading
			case 'load' :
				if( is_admin() )
				{
					// Init admin menu
					add_action('admin_menu', array( &$this, 'admin_menu' ));
					// Init admin scripts and css
					add_action('admin_enqueue_scripts', array( &$this, 'admin_enqueue_scripts' ));
					// Init media buttons
					add_action('media_buttons', array( &$this, 'add_media_button_select'), 11);
					add_action('wp_ajax_peckplayer', array(&$this, 'add_peckplayer_in_posts'));
					add_action('wp_ajax_pluginurl', array(&$this, 'get_plugin_url'));
				}
				// Init wp scripts
				add_action('wp_enqueue_scripts', array( &$this, 'wp_enqueue_scripts' ));
				// Init peckplayer widget
				add_action('widgets_init', array(&$this, 'register_states_widget'));
				// Init peckplayer shortcode
                add_shortcode('peckplayer', array(&$this, 'show_peckplayer'));
				// Init localization
				load_plugin_textdomain( 'peckplayer', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
				break;
        }
    }

    static function on_activate()
    {
        new PeckPlayer( 'activate' );
    }

    /**
     * Do nothing like removing settings, etc. 
     * The user could reactivate the plugin and wants everything in the state before activation.
     * Take a constant to remove everything, so you can develop & test easier.
     */
    static function on_deactivate()
    {
        $case = 'deactivate';
        if ( self::STATE_OF_ORIGIN )
            $case = 'uninstall';

        new PeckPlayer( $case );
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     */
    static function on_uninstall()
    {
        // important: check if the file is the one that was registered with the uninstall hook (function)
        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;
		
        new PeckPlayer( 'uninstall' );
    }

	static function on_load()
	{
		new PeckPlayer( 'load' );
	}

	static function get_version_display( $version )
	{
		if( $version === self::PECKPLAYER_VERSION_MINI )
		{
			return __('Mini', self::LOCALIZATION_DOMAIN);
		}
		elseif( $version === self::PECKPLAYER_VERSION_CLASSIC )
		{
			return __('Classic', self::LOCALIZATION_DOMAIN);
		}
		elseif( $version === self::PECKPLAYER_VERSION_MULTI )
		{
			return __('Multi', self::LOCALIZATION_DOMAIN);
		}
	}
	
	// Peckplayer HTML for shortcode and widget
	static function show_peckplayer($atts)
	{
		extract(shortcode_atts(array(
			'id' => 1,
			'sound' => '',
		), $atts));
		
		$html = '';
		$peckplayer = PeckPlayerDBManager::get($id);
		if( $peckplayer )
		{
			if( $peckplayer->version == self::PECKPLAYER_VERSION_MINI )
			{
				$width = 160;
			}
			elseif( $peckplayer->version == self::PECKPLAYER_VERSION_CLASSIC )
			{
				$width = 190;
			}
			elseif( $peckplayer->version == self::PECKPLAYER_VERSION_MULTI )
			{
				$width = 230;
			}
			
			$id = mt_rand();
			
			$html = '<script type="text/javascript">
						jQuery(function(){
							var flashvars = {};
							flashvars.sounds = "'.$sound.'";
							flashvars.innerButtonColor = "0x'.$peckplayer->inner_button_color.'";
							flashvars.autostart = "'.($peckplayer->autostart ? 'true' : 'false').'";
							flashvars.autoreplay = "'.($peckplayer->autoreplay ? 'true' : 'false').'";
							flashvars.volume = "'.$peckplayer->volume.'";
							flashvars.textColor = "0x'.$peckplayer->text_color.'";
							flashvars.bgButtonColor = "0x'.$peckplayer->bg_button_color.'";

							var params = {};
							params.wmode = "transparent";
							params.allowscriptaccess = "always";

							var attributes = {};
							attributes.id = "peckplayer-'.$id.'";
							attributes.name = "peckplayer-'.$id.'";

							swfobject.embedSWF("'.plugin_dir_url( __FILE__ ) . 'media/swf/'.$peckplayer->version.'.swf", "peckplayer-'.$id.'", "'.$width.'", "20", "10.0.0","'.plugin_dir_url( __FILE__ ) . 'media/swf/expressInstall.swf", flashvars, params, attributes);
						});
					</script>
					<div id="peckplayer-'.$id.'">
				    	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'.$width.'" height="20" id="peckplayer-'.$id.'" name="peckplayer-'.$id.'">
							<param name="movie" value="'.plugin_dir_url( __FILE__ ) . 'media/swf/'.$peckplayer->version.'.swf" />
							<param name="wmode" value="transparent" />
							<param name="allowscriptaccess" value="always" />
							<param name="flashvars" value="sounds='.$sound.'&amp;innerButtonColor=0x'.$peckplayer->inner_button_color.'&amp;autostart='.($peckplayer->autostart ? 'true' : 'false').'&amp;autoreplay='.($peckplayer->autoreplay ? 'true' : 'false').'&amp;volume='.$peckplayer->volume.'&amp;textColor=0x'.$peckplayer->text_color.'&amp;bgButtonColor=0x'.$peckplayer->bg_button_color.'" />
							<!--[if !IE]>-->
							<object type="application/x-shockwave-flash" data="'.plugin_dir_url( __FILE__ ) . 'media/swf/'.$peckplayer->version.'.swf" width="'.$width.'" height="20">
								<param name="wmode" value="transparent" />
								<param name="allowscriptaccess" value="always" />
								<param name="flashvars" value="sounds='.$sound.'&amp;innerButtonColor=0x'.$peckplayer->inner_button_color.'&amp;autostart='.($peckplayer->autostart ? 'true' : 'false').'&amp;autoreplay='.($peckplayer->autoreplay ? 'true' : 'false').'&amp;volume='.$peckplayer->volume.'&amp;textColor=0x'.$peckplayer->text_color.'&amp;bgButtonColor=0x'.$peckplayer->bg_button_color.'" />
							<!--<![endif]!-->
								<a href="http://www.adobe.com/go/getflashplayer">
									<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
								</a>
							<!--[if !IE]>-->
							</object>
							<!--<![endif]-->
						</object>
				    </div>';
		}
		
		return $html;
	}
	
	/************************************************************************************/

	function get_plugin_url(){
		echo plugin_dir_url( __FILE__ );
		exit();
	}
	
	function add_media_button_select(){
		$peckplayers = PeckPlayerDBManager::get_all();
	    echo '<a id="add_peckplayer" href="/wp-admin/admin-ajax.php?action=peckplayer&width=640&height=500" class="thickbox" title="'.__("Add a PeckPlayer", PeckPlayer::LOCALIZATION_DOMAIN).'"><img src="'.plugin_dir_url(__FILE__).'media/images/media-button-peckplayer.png" widht="13" height="13" alt="'.__("Add a PeckPlayer").'" /></a>';
	}
	
	function add_peckplayer_in_posts(){
		$peckplayers = PeckPlayerDBManager::get_all();
		include dirname(__FILE__) . '/peckplayer-admin-thickbox.php';
		exit();
	}
	
	function wp_enqueue_scripts( $hook )
	{
		wp_enqueue_script('jquery');
		wp_deregister_script('swfobject');
		wp_register_script('swfobject', 'http'.($_SERVER['SERVER_PORT'] == 443 ? 's' : '').'://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js', array('jquery'), '2.2');
		wp_enqueue_script('swfobject');
	}
	
	function admin_enqueue_scripts( $hook )
	{
		if( self::ADMIN_MENU_SLUG.'_page_'.self::ADMIN_FORM_PAGE_TITLE == $hook )
		{
			wp_register_style('jqueryui.css', 'http'.($_SERVER['SERVER_PORT'] == 443 ? 's' : '').'://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
			wp_register_style('colorpicker.css', plugin_dir_url( __FILE__ ) . 'media/css/colorpicker.css');
			wp_enqueue_style('jqueryui.css');
			wp_enqueue_style('colorpicker.css');
			
			wp_deregister_script('swfobject');
			wp_register_script('swfobject', 'http'.($_SERVER['SERVER_PORT'] == 443 ? 's' : '').'://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js', array('jquery'), '2.2');
			wp_register_script('colorpicker.js', plugin_dir_url( __FILE__ ) . 'media/js/colorpicker.js', array('jquery'));
			wp_register_script('jqueryui.js', 'http'.($_SERVER['SERVER_PORT'] == 443 ? 's' : '').'://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js', array('jquery'));
			wp_register_script('peckplayer-generator.js', plugin_dir_url( __FILE__ ) . 'media/js/peckplayer-generator.js', array('jquery', 'colorpicker.js', 'jqueryui.js'));
			wp_enqueue_script('swfobject');
			wp_enqueue_script('colorpicker.js');
			wp_enqueue_script('jqueryui.js');
			wp_enqueue_script('peckplayer-generator.js');
		}
		wp_register_style('peckplayer.css', plugin_dir_url( __FILE__ ) . 'media/css/peckplayer.css');
		wp_enqueue_style('peckplayer.css');
	}
	
	function admin_menu()
	{
		add_menu_page(self::ADMIN_INDEX_PAGE_TITLE, self::ADMIN_MENU_TITLE, 1, self::ADMIN_MENU_SLUG, array(&$this, 'admin_menu_index'), plugin_dir_url( __FILE__ ) . 'media/images/peckplayer-menu.png');
		add_submenu_page(self::ADMIN_MENU_SLUG, __("Add a PeckPlayer", self::LOCALIZATION_DOMAIN), __("Add", self::LOCALIZATION_DOMAIN), 1, self::ADMIN_FORM_PAGE_TITLE, array(&$this, 'admin_menu_form'));
	}

	function admin_menu_index()
	{
		if( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['id'] ))
		{
			if( PeckPlayerDBManager::delete( $_GET['id'] ) > 0 )
			{
				?>
				<div id="message" class="updated fade">
					<p>
						<strong>
						<?php _e('PeckPlayer deleted.', self::LOCALIZATION_DOMAIN);?>
						</strong>
					</p>
				</div>
				<?php
			}
			else
			{
				?>
				<div class="error settings-error">
					<p>
						<strong>
						<?php _e('PeckPlayer is already deleted.', self::LOCALIZATION_DOMAIN);?>
						</strong>
					</p>
				</div>
				<?php
			}
		}
		$peckplayers = PeckPlayerDBManager::get_all();
		include dirname(__FILE__) . '/peckplayer-admin-index.php';
	}

	function admin_menu_form()
	{
		if( count( $_POST ) > 0  )
		{
			$messages = array(
				'NAME_EMPTY' => __('Please enter a name.', self::LOCALIZATION_DOMAIN), 
				'VERSION_EMPTY' => __('Please select a version.', self::LOCALIZATION_DOMAIN), 
				'VERSION_INVALID' => __('The version you select is invalid.', self::LOCALIZATION_DOMAIN), 
				'INNER_BUTTON_COLOR_EMPTY' => __('Please select a color for the foreground of the button.', self::LOCALIZATION_DOMAIN), 
				'BG_BUTTON_COLOR_EMPTY' => __('Please select a color for the background of the button.', self::LOCALIZATION_DOMAIN), 
				'TEXT_COLOR_EMPTY' => __('Please select a color for the text.', self::LOCALIZATION_DOMAIN)
			);
			$errors = Array();
			if( !isset( $_POST['name'] ) || $_POST['name'] == '' ){
				$errors['name'] = $messages['NAME_EMPTY'];
			}
			else
			{
				$name = $_POST['name'];
			}
			
			if( !isset( $_POST['version'] ) || $_POST['version'] == '' ){
				$errors['vesion'] = $messages['VERSION_EMPTY'];
			}
			elseif( isset( $_POST['version'] ) && $_POST['version'] != self::PECKPLAYER_VERSION_MINI && $_POST['version'] != self::PECKPLAYER_VERSION_CLASSIC && $_POST['version'] != self::PECKPLAYER_VERSION_MULTI ){
				$errors['version'] = $messages['VERSION_INVALID'];
			}
			else
			{
				$version = $_POST['version'];
			}
			
			if( !isset( $_POST['inner-button-color'] ) || $_POST['inner-button-color'] == '' ){
				$errors['inner-button-color'] = $messages['INNER_BUTTON_COLOR_EMPTY'];
			}
			else
			{
				$innerButtonColor = $_POST['inner-button-color'];	
			}
		
			if( !isset( $_POST['bg-button-color'] ) || $_POST['bg-button-color'] == '' ){
				$errors['bg-button-color'] = $messages['BG_BUTTON_COLOR_EMPTY'];
			}
			else
			{
				$bgButtonColor = $_POST['bg-button-color'];
			}
			
			if( !isset( $_POST['text-color'] ) || $_POST['text-color'] == '' ){
				$errors['text-color'] = $messages['TEXT_COLOR_EMPTY'];
			}
			else
			{
				$textColor = $_POST['text-color'];
			}
			
			$autostart = isset( $_POST['autostart'] ) ? true : false;
			$autoreplay = isset( $_POST['autoreplay'] ) ? true : false;
			$volume = isset( $_POST['volume'] ) ? $_POST['volume'] : 75;
			if( count($errors) === 0 )
			{
				$updates_notice = array();
				if( isset( $_POST['id'] ) )
				{
					$id = $_POST['id'];
					if( PeckPlayerDBManager::update($id, $name, $version, $autostart, $autoreplay, $volume, $innerButtonColor, $bgButtonColor, $textColor) > 0 )
					{
						$updates_notice[] .= __('Options saved.', self::LOCALIZATION_DOMAIN);
					}
				}
				else
				{
					if( PeckPlayerDBManager::insert($name, $version, $autostart, $autoreplay, $volume, $innerButtonColor, $bgButtonColor, $textColor) )
					{
						global $wpdb;
						$id = $wpdb->insert_id;
						$updates_notice[] .= __('Options saved.', self::LOCALIZATION_DOMAIN);
					}
				}
			}
			else
			{
				$errors_notice = array();
				$i = 0;
				foreach( $errors as $error )
				{
					$error_notice = '';
					if( $i > 0 )
					{
						$error_notice .= '<br />';
					}
					$error_notice .= $error;
					$errors_notice[] .= $error_notice;
					$i++;
				}
			}
		}
		elseif( isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id']) )
		{
			$peckplayer = PeckPlayerDBManager::get($_GET['id']);
			if( $peckplayer )
			{
				$id = $peckplayer->id;
				$name = $peckplayer->name;
				$version = $peckplayer->version;
				$autostart = $peckplayer->autostart;
				$autoreplay = $peckplayer->autoreplay;
				$volume = $peckplayer->volume;
				$innerButtonColor = $peckplayer->inner_button_color;
				$bgButtonColor = $peckplayer->bg_button_color;
				$textColor = $peckplayer->text_color;
			}
		}
		include dirname(__FILE__) . '/peckplayer-admin-form.php';
	}

	function register_states_widget() {
   		return register_widget('PeckPlayerWidget');
	}

	/**
	* trigger_error()
	*
	* @param (string) $error_msg
	* @param (boolean) $fatal_error | catched a fatal error - when we exit, then we can't go further than this point
	* @param unknown_type $error_type
	* @return void
	*/
	function error( $error_msg, $fatal_error = false, $error_type = E_USER_ERROR )
	{
		if( isset( $_GET['action'] ) && 'error_scrape' == $_GET['action'] )
		{
			echo "{$error_msg}\n";
			if ( $fatal_error )
			exit;
		}
		else
		{
			trigger_error( $error_msg, $error_type );
		}
	}
}
endif;
?>
