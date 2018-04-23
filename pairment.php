<?php

/*
Plugin Name: Pairment
Plugin URI: https://pairment.com/wordpress
Description: Adds Pairment to your website.
Tags: accessibility
Author: Pairment
Author URI: https://pairment.com
Contributors: kevee
Requires at least: 4.1
Tested up to: 5.0
Stable tag: 20180420
Version: 20180420
Requires PHP: 5.2
License: GPL v2 or later
*/

/*
	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version
	2 of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	with this program. If not, visit: https://www.gnu.org/licenses/

	Copyright 2018 Pairment. All rights reserved.
*/

if (!defined('ABSPATH')) die();

/*
 Add settings via the Setting API
*/
function pairment_settings_api_init() {
	add_settings_section(
			'pairment_settings_section',
			'Pairment settings',
			'pairment_setting_section_callback_function',
			'general'
		);

	add_settings_field(
		'pairment_public_id',
		'<label for="pairment_public_id">Pairment Public ID</label>',
		'pairment_settings_public_id',
		'general',
		'pairment_settings_section'
	);

	register_setting( 'general', 'pairment_public_id' );
}

add_action( 'admin_init', 'pairment_settings_api_init' );

function pairment_setting_section_callback_function() {
	echo '<p>Install pairment on your site.</p>';
}

function pairment_settings_public_id() {
	echo '<input name="pairment_public_id" id="pairment_public_id" type="text" value="'. get_option('pairment_public_id') .'" class="code"/>';
}

/*
 Filter the page/post content to wrap it in a special _pairment-content class.
*/
add_filter( 'the_content', 'pairment_content_filter' );

function pairment_content_filter($content) {
	if(!current_user_can('edit_others_pages')) {
		return $content;
	}
	return '<div class="_pairment-content">' . $content . '</div>';
}

/*
 Add the Pairment javascript to the footer.
*/
function pairment_footer() {
	if(!current_user_can('edit_others_pages')) {
		return $content;
	}
  echo '<script type="text/javascript">
      (function(w,d,t,u,n,s,e){w["A11yObject"]=n;w[n]=w[n]||function(){
      (w[n].q=w[n].q||[]).push(arguments);};s=d.createElement(t);
      e=d.getElementsByTagName(t)[0];s.async=1;s.src=u;e.parentNode.insertBefore(s,e);
    })(window,document,"script","//cdn.pairment.com/install/v1/'. get_option('pairment_public_id') .'.js");
</script>';
}

add_action( 'wp_footer', 'pairment_footer' );
