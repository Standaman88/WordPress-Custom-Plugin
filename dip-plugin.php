<?php
/**
* @package dipplugin
*/
/*
Plugin Name: dip plugin
Plugin URI: http://dip.com/plugin
Description: This is my first attempt on writing a custom plugin for this amazing coding challenge.
Version: 1.0.0
Author: Dipanjan "dip" Banerjee
Author URI: http://dip.com
License: GPLv2 or later
Text Domain: dip-plugin
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/




// custom post type

function awesome_custom_post_type ()
{
	$labels = array(
		'name' => 'property',
		'singular_name' => 'property',
		'add_new' => 'Add Property Item',
		'all_items' => 'All Items',
		'add_new_item' => 'Add Item',
		'edit_item' => 'Edit Item',
		'new_item' => 'New Item',
		'view_item' => 'View Item',
		'search_item' => 'Search Property',
		'not_found' => 'No items found',
		'not_found_in_trash' => 'No items found in trash',
		'parent_item_colon' => 'Parent Item',
);
$args = array(
	'labels' => $labels,
	'public' => true,
	'has_archive' => true,
	'publicly queryable' => true,
	'query_var' => true,
	'rewrite' => true,
	'capability_type' => 'post',
	'hierarchical' => false,
	'supports' => array(
		'title',
		'editor',
		'comments',
		'excerpt',
		'custom-fields',
		'trackback',
		'thumbnail',
		'revisions',
		'author',
		'page-attributes',
		'post-formats',
		'page',
	),
	'taxonomies' => array('category', 'post_tag'),
	'menu_position' => 5,
	'exclude_from_search' => false
);
register_post_type('property',$args);
}
add_action('init', 'awesome_custom_post_type');


// custom taxonomy

function awesome_custom_taxonomies()
{
	//add new taxonomy hierarchical
	$labels = array(
		'name' => 'PropertyStatus',
		'singular_name' => 'PropertyStatus',
		'search_items' => 'Seacrh PropertyStatus',
		'all_items' => 'All PropertyStatus',
		'parent_item' => 'Parent PropertyStatus',
		'parent_item_colon' => 'Parent PropertyStatus:',
		'edit_item' => 'Edit PropertyStatus',
		'update_item' => 'Update PropertyStatus',
		'add_new_item' => 'Add New PropertyStatus',
		'new_item_name' => 'New PropertyStatus name',
		'menu_name' => 'PropertyStatus'
	);

	$args = array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'PropertyStatus')
	);

	register_taxonomy('PropertyStatus', array('property'),$args);



	//add new taxonomy NOT hierarchical
}

add_action('init' , 'awesome_custom_taxonomies', 0);




// custom meta box

add_action('add_meta_boxes', 'property_details_box');

function property_details_box()
{
	add_meta_box(
		'property_details_box',
		__('property details', 'dipplugin_textdomain'),
		'property_details_box_content',
		'property',
		'normal',
		'high'
	);
}


function property_details_box_content($post)
{
	wp_nonce_field(plugin_basename(_FILE_), 'property_details_box_content_nonce');
	echo '<label for="property_details_price">Price: </label>';
	echo '<input type="text" id="property_details_price" name="property_details_price" placeholder="enter price">';
	echo '<p> --- and --- </p>';
	echo '<label for="property_details_location">Location: </label>';
	echo '<input type="text" id="property_details_location" name="property_details_location" placeholder="enter location">';
	echo '<p> --- and --- </p>';
	echo '<label for="property_details_date of construction">Date of Construction: </label>';
	echo '<input type="text" id="property_details_date of construction" name="property_details_date of construction" placeholder="enter date of construction">';
}
	add_action('save_post', 'property_details_box_save');

	function property_details_box_save($post_id)
	{
		// Stop WP from clearing custom fields on autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		// Prevent quick edit from clearing custom fields
        if (defined('DOING_AJAX') && DOING_AJAX)
            return;

		if (!wp_verify_nonce($_POST['property_details_box_content_nonce'], plugin_basename(_FILE_)))
			return;
		if ('page' == $_POST['post_type'])
		{
			if (!current_user_can('edit_page', $post_id))
				return;

		}
		else
		{
			if (!current_user_can('edit_post', $post_id))
				return;
		}

		$property_details_price = $_POST['property_details_price'];
		update_post_meta( $post_id, 'property_details_price', $property_details_price);

		$property_details_location = $_POST['property_details_location'];
		update_post_meta( $post_id, 'property_details_location', $property_details_location);
		
		$property_details_dateofconstruction = $_POST['property_details_date of construction'];
		update_post_meta( $post_id, 'property_details_date of construction', $property_details_dateofconstruction);
		
	}




/*
* Plugin Name: WordPress ShortCode
* Description: Creating custom WordPress shortcode.
* Version: 1.0.0
* Author: Dipanjan "dip" Banerjee
* Author URI: http://dip.com
*/

// Example 1 : WP Shortcode to display form on any page or post.
function display()
{
add_shortcode('test', 'display');
}


// Listing meta values as columns on admin listing of properties
add_filter( 'manage_property_posts_columns', 'smashing_filter_posts_columns' );

function smashing_filter_posts_columns( $columns ) {
  $columns['price'] = __( 'Price', 'smashing' );
  $columns['location'] = __( 'Location', 'smashing' );
  $columns['date of construction'] = __( 'Date of Construction', 'smashing' );
  return $columns;
}


?>