<?php
/**
 * Plugin Name: Extended RSS Feed
 * Plugin URI:  https://schouwenburg.com
 * Description: Generates RSS feed with title, subtitle and hashtag (of tags)
 * Version:     1.0
 * Author:      Robert Schouwenburg
 * Author URI:  https://schouwenburg.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Copyright (C) 2019 Robert Schouwenburg
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

namespace RobertSchouwenburg\ExtendedRSSFeed;

/**
 * Registers our custom feed
 */
function register() {
	add_feed( 'extended-rss2', __NAMESPACE__ . '\generate_content' );
}
add_action( 'init', __NAMESPACE__ . '\register' );

/**
 * Generates the content of our custom feed
 */
function generate_content() {

	add_filter( 'the_title_rss', __NAMESPACE__ . '\change_title' );
	
	if ( file_exists( ABSPATH . WPINC . '/feed-rss2.php' ) ) {
		require( ABSPATH . WPINC . '/feed-rss2.php' );
	}

	remove_filter( 'the_title_rss', __NAMESPACE__ . '\change_title' );
}

/**
 Changes the post title to include substitle and tags (as hashtags)
 
 @param string $title the post title
 
 @return string the new title
 */
function change_title( $title ) {

	if ( get_post_meta( get_the_ID(), 'wps_subtitle', true) ) {
	
		$title .= '. ' . get_post_meta( get_the_ID(), 'wps_subtitle', true) . '.'; 
		
	}

	if ( has_tag() ) {
	
		foreach( get_the_tags() as $tag ) {
			$title .= ' #' . str_replace(' ', '', strtolower($tag->name));
		}
	}
	
	return $title;
}
