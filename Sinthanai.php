<?php
/*
Plugin Name: Thalaivarin Sinthanai
Description: Display a random Sinthanai of Honourable Leader of Tamileelam in Tamil along with section.
Version: 4.0
Author: Thavapalan Thirunilavan
Author URI: https://profiles.wordpress.org/nilavan/
License: GPL2
*/

/*  Copyright 2019 Thavapalan Thirunilavan

	    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once plugin_dir_path( __FILE__ ) .'Sinthanai-widget.php';

function sinthanai_uninstall() {
	global $wpdb;
	$sinthanai_table = $wpdb->prefix . "sinthanai";
	

	$sql = "DROP TABLE IF EXISTS $sinthanai_table;";
	$wpdb->query( $sql );
	
}

function sinthanai_install() {
	global $wpdb;
	$sinthanai_table = $wpdb->prefix . "sinthanai";

	//Drop tables of previous versions
	sinthanai_uninstall();

	//Creating the tables ... fresh!
	$sql = "CREATE TABLE " . $sinthanai_table . " (
		id INTEGER NOT NULL,
		section_name VARCHAR(50),
		sinthanai TEXT,
		PRIMARY KEY(id)
		)ENGINE=InnoDB DEFAULT CHARSET=utf8;";
	$results = $wpdb->query( $sql );

	// Im lazy. I did not make this a child table...
	

	$sinthanai_file_name = "data/sinthanai.txt";
	$sinthanai_file_path = "'" . plugin_dir_path( __FILE__ ) . $sinthanai_file_name . "'";
	$sinthanai_insert_query = "LOAD DATA LOCAL INFILE $sinthanai_file_path INTO TABLE $sinthanai_table CHARACTER SET UTF8 COLUMNS TERMINATED BY '\t' ENCLOSED BY '\"' LINES TERMINATED BY '\r\n'";
	$result = $wpdb->query( $sinthanai_insert_query );

}

function wp_enqueue_sinthanai_css() {
	if ( !is_admin() ) {
		wp_enqueue_style( 'sinthanai', plugins_url( 'styles/sinthanai.css', __FILE__ ), false, null );
	}
 }

add_action( 'wp_enqueue_scripts', 'wp_enqueue_sinthanai_css' );

register_activation_hook( __FILE__, 'sinthanai_install' );
register_deactivation_hook( __FILE__, 'sinthanai_uninstall' );
