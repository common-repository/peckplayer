<?php
/*
 Plugin Name: PeckPlayer
 Plugin URI: http://www.scopart.fr/peckplayer/
 Description: Add a fully customizable MP3 player to your pages and posts. Peckplayer is flash mp3 player, it can fit perfectly to your webdesign.
 Version: 1.0.6
 Author: Scopart
 Author URI: http://www.scopart.fr/
 License: GPL2
 */

 // If you got your de-/activation/uninstall class in another file, uncomment the following line:
include_once plugin_dir_path( __FILE__ ).'PeckPlayerDBManager.class.php';
include_once plugin_dir_path( __FILE__ ).'PeckPlayer.class.php';
include_once plugin_dir_path( __FILE__ ).'PeckPlayerWidget.class.php';

// register a function - or better a class function - to run on activation/deactivation
// You can use __FILE__ even when your class is not in the file, that contains the plugin comment
// Add this to your main/init file (that has the plugin header comment)
register_activation_hook( __FILE__, array( 'PeckPlayer', 'on_activate' ) );
register_deactivation_hook( __FILE__, array( 'PeckPlayer', 'on_deactivate' ) );
register_uninstall_hook( __FILE__, array( 'PeckPlayer', 'on_uninstall' ) );
add_action('plugins_loaded', array( 'PeckPlayer', 'on_load' ));
?>
