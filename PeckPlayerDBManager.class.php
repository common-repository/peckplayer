<?php
if ( ! class_exists('PeckPlayerDBManager' ) ) :

class PeckPlayerDBManager
{
	const TABLE_NAME = 'peckplayer_config';
	
	static function get_table_name()
	{
		global $wpdb;
		$table_name = self::TABLE_NAME;
		return $wpdb->prefix.$table_name;
	}
	
	static function create_table()
	{
		global $wpdb;
		
		$structure = 'CREATE TABLE IF NOT EXISTS `'.self::get_table_name().'`  (
			        id INT(9) NOT NULL AUTO_INCREMENT,
			        name VARCHAR(100) NOT NULL,
			        version VARCHAR(30) NOT NULL,
			        autostart TINYINT(1) NOT NULL,
			        autoreplay TINYINT(1) NOT NULL,
			        volume TINYINT(3) NOT NULL,
			        inner_button_color VARCHAR(6) NOT NULL,
			        bg_button_color VARCHAR(6) NOT NULL,
			        text_color VARCHAR(6) NOT NULL,
					UNIQUE KEY id (id)
			    );';
		return $wpdb->query($structure);
	}
	
	static function drop_table()
	{
		global $wpdb;
		return $wpdb->query('DROP TABLE IF EXISTS `'.self::get_table_name().'`;'); 
	}
	
	static function clear_table()
	{
		global $wpdb;
		return $wpdb->query('TRUNCATE TABLE `'.self::get_table_name().'`;'); 
	}
	
	static function count()
	{
		global $wpdb;
		return $wpdb->get_var( $wpdb->prepare('SELECT count(*) FROM `'.self::get_table_name().'`;'));
	}
	
	static function get_all()
	{
		global $wpdb;
		return $wpdb->get_results("SELECT id, name, version FROM `".self::get_table_name()."`;");
	}

	static function get( $id )
	{
		global $wpdb;
		
		$select = $wpdb->prepare("SELECT * FROM `".self::get_table_name()."` WHERE id = %d", $id);
		return $wpdb->get_row($select, 0);
	}
	
	static function insert($name, $version, $autostart, $autoreplay, $volume, $innerButtonColor, $bgButtonColor, $textColor )
	{
		global $wpdb;
		
		$start = $autostart ? 1 : 0;
		$loop = $autoreplay ? 1 : 0;
		$insert = $wpdb->prepare("INSERT INTO `".self::get_table_name()."` (name, version, autostart, autoreplay, volume, inner_button_color, bg_button_color, text_color)
					VALUES(%s, %s, %d, %d, %d, %s, %s, %s);", $name, $version, $start, $replay, $volume, $innerButtonColor, $bgButtonColor, $textColor);
		return $wpdb->query($insert);
	}

	static function update($id, $name, $version, $autostart, $autoreplay, $volume, $innerButtonColor, $bgButtonColor, $textColor )
	{
		global $wpdb;
		
		$start = $autostart ? 1 : 0;
		$replay = $autoreplay ? 1 : 0;
		$update = $wpdb->prepare("UPDATE `".self::get_table_name()."` SET name = %s, version = %s, autostart = %d, autoreplay = %d, volume = %d, inner_button_color = %s, bg_button_color = %s, text_color = %s WHERE id = %d LIMIT 1;", $name, $version, $start, $replay, $volume, $innerButtonColor, $bgButtonColor, $textColor, $id);
		return $wpdb->query($update);
	}
	
	static function delete($id)
	{
		global $wpdb;
		$delete = $wpdb->prepare("DELETE FROM `".self::get_table_name()."` WHERE id = %s LIMIT 1;", $id);
		return $wpdb->query($delete);
	}	
}

endif;
?>