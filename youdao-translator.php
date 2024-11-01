<?php
/*
Plugin Name: Youdao Translator
Plugin URI: http://www.leexiang.com/youdao-translator-plugin
Description: Translates a blog by Youdao Translation Engine. First you must apply a key from the website http://fanyi.youdao.com/fanyiapi. After uploading this plugin click 'Activate' and enter your Youdao Tranlation API site and key to enable the translator in the configuration menu.  
Version: 1.0
Author: Li Xiang
Author URI: http://www.leexiang.com/
License: GPL
*/

/*  Copyright 2011  Li Xiang  (email : lixiang.ict@gmail.com)

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307  USA
*/

/* *****INSTRUCTIONS*****

Installation
============
Upload the folder "youdao-translator" into your "wp-content/plugins" directory.
Log in to Wordpress Administration area, choose "Plugins" from the main menu, find "Youdao Translator" 
and click the "Activate" button. From the main menu choose "Options->Youdao Translator" and set 
your Youdao translation API key and site infomation then select "Update Options".

Uninstallation
==============
Log in to Wordpress Administration area, choose "Plugins" from the main menu, find the name of the 
plugin "Youdao Translator", and click the "Deactivate" button.

***********************

Change Log

1.0.0
- Initial release

*/

/* The function will be called when active the plugin */
register_activation_hook(__FILE__,'youdao_tranlator_install'); 

/* The function will be called when stop the plugin */
register_deactivation_hook( __FILE__, 'youdao_tranlator_remove' );

function youdao_tranlator_install() 
{
	/* Add an item at the wp_options table of database */
	add_option("youdao_translator_api_site", "", '', 'yes');
	add_option("youdao_translator_api_key", "", '', 'yes');
}

function youdao_tranlator_remove() 
{
	/* remove relative records from the wp_options table of database */
	delete_option('youdao_translator_api_site');
	delete_option('youdao_translator_api_key');
}

/* Judge whether the user is at the backgroud of WordPress */
if (is_admin()) 
{
	add_action('admin_menu', 'display_youdao_translator_menu');
}

function display_youdao_translator_menu() 
{
	add_options_page('有道翻译设置页面', '有道翻译设置', 'administrator', 'youdao_tranlator', 'youdao_translator_page');
}

function youdao_translator_page() 
{
?>
	<div>
		<h2>有道翻译设置</h2>
		<form method="post" action="options.php">
			<?php
			<?php wp_nonce_field('update-options'); ?>
			<p>网站名称(keyfrom)</p>
			<p>
				<textarea
					name="youdao_translator_api_site"
					id="youdao_translator_api_site"
					cols="80"
					rows="4"><?php echo get_option('youdao_translator_api_site'); ?></textarea>
			</p>
			<p>翻译密钥(key)</p>
			</p>
				<textarea
					name="youdao_translator_api_key"
					id="youdao_translator_api_key"
					cols="80"
					rows="4"><?php echo get_option('youdao_translator_api_key'); ?></textarea>
			</p>
			<p>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="youdao_translator_api_site, youdao_translator_api_key" />

				<input type="submit" value="保存设置" class="button-primary" />
			</p>
		</form>
	</div>
<?php
}

add_filter( 'the_content', 'youdao_tranlator' );

/* Add your applied youdao translation API at the single page */
function youdao_tranlator( $content ) 
{
	if (is_single())
	{
		$content = $content."<div id=\"YOUDAO_SELECTOR_WRAPPER\" style=\"display:none; margin:0; border:0; padding:0; width:320px; height:240px;\"></div>";
		$content = $content."<script type=\"text/javascript\" src=\"http://fanyi.youdao.com/fanyiapi.do?keyfrom=";
		$content = $content.get_option("youdao_translator_api_site");
		$content = $content."&key=";
		$content = $content.get_option("youdao_translator_api_key");
		$content = $content."&type=selector&version=1.0&translate=on\" charset=\"utf-8\"></script>";
	}
	return $content;
}

?>