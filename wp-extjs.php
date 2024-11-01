<?php
/*
Plugin Name: WP ExtJS
Plugin URI: http://www.mlynn./org/wp-extjs
Tags: extjs, sencha, datagrid, gridpanel, treepanel, javascript, ext-js
Description: WP ExtJS enables Wordpress publishers to leverage the power and flexibility of the ExtJS Javascript Framework available from <a href=http://www.sencha.com>Sencha.com</a>. | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J89ARHMKVMAAN">Donate</a> 
Version: 1.0.4
Author: Michael Lynn
Author URI: http://www.mlynn.org/
*/

/*
	This script cannot be called directly
*/
if ( ! defined( 'ABSPATH' ) )
        die( "Can't load this file directly" );

/*
	Register our activation and unistall hooks
*/
register_activation_hook( __FILE__, 'wpextadd_defaults' );
register_uninstall_hook( __FILE__, 'wpextdelete_plugin_options' );

/*
	Add our actions
*/
add_action( 'init', 'wpextenqueue' );
/* Save additional header and body meta */
add_action('save_post','wpext_save_meta');
add_action('media_buttons', 'wpext_add_media_button', 20);
add_action( 'admin_init', 'wpextinit' );
add_action( 'admin_menu', 'wpextadd_options_page' );

/*
	Add a filter
*/
add_filter('plugin_action_links', 'wpext_plugin_action_links', 10, 2); 

/*
	Add the Shortcodes
*/
add_shortcode("wp-extjs", "wpextshortcode_extjs");
add_shortcode("extjs_snippet", "wpextshortcode_extjs_snippet");

/*! @function wpextshortcode_gridpanel
    @abstract does magical things
    @discussion description of the function
    @param var1 int - variable integer
    @param var2 varchar(255) - variable varchar type
    @result the result or return of the function
*/	
function wpextshortcode_extjs($atts, $content="") {
	return do_shortcode(wpextconditional_shortcode($atts, $content, 'wp-extjs'));
}

/*
	Add a media button to the post editor
*/
function wpext_add_media_button() {
    $url = WP_PLUGIN_URL.'/wp-extjs/inserter.php?tab=add&TB_iframe=true&amp;height=500&amp;width=640';
    if (is_ssl()) $url = str_replace( 'http://', 'https://',  $url ); 
    echo '<a href="'.$url.'" class="thickbox" title="'.__('Add ExtJS Snippet','wp-extjs').'"><img src="'.WP_PLUGIN_URL.'/wp-extjs/images/extjs_snippet.gif" alt="'.__('Add ExtJS Snippet','wp-extjs').'"></a>';
}


/*! @function wpext_plugin_action_links
    @abstract Incorporates a link to the settings for this plugin into the plugin listing table in the admin
    @result $links
*/	
function wpext_plugin_action_links($links, $file) {
    $plugin_file = basename(__FILE__);
    if (basename($file) == $plugin_file) {
        $settings_link = '<a href="options-general.php?page='.$plugin_file.'">'.__('Settings', 'wp-extjs').'</a>';
        array_unshift($links, $settings_link);
    }
    return $links; 
}


/*
	This is where we handle the action shortcode interpolation - right now there's only one wp-extjs
*/
function wpextconditional_shortcode($atts, $content, $condition) {
	$options = get_option('wpextoptions');
	if ( !$options['chk_enable_plugin'] ) {
		return; // If the plugin is not enabled, don't do anything
	}
	extract(shortcode_atts(array(
			'snippet_id' => '',
			'border' => '',
			'width' => $options['txt_page_width'],
			'style' => '',
			'height' => $options['txt_page_height'],
			'scrolling' => $options['chk_enable_scrolling'],
			'title' => ''
	), $atts));
	switch( $condition ) {
		case 'wp-extjs':
			$url = WP_PLUGIN_URL."/wp-extjs/renderer.php?snippet_id=".$atts['snippet_id'];
			if ( isset( $atts['url'] ) ) {
				foreach ( array( 'style', 'url', 'title', 'desc', 'height', 'width', 'border', 'scrolling' ) AS $attr ) {
					if ( isset( $atts[$attr] ) )
						$$attr = $atts[$attr];
				}
			}
			$html="<iframe title='".$title."' src='".$url."' frameborder='".$border."' style='".$style."' scrolling='".$scrolling."' height='".$height."' width='".$width."'></iframe>";
			return $html;
			break;

		default:
	} // end switch
} // end function wepextconditional_shortcode

/*
	delete options table entries ONLY when plugin deactivated AND deleted
*/
function wpextdelete_plugin_options() {
	delete_option('wpextoptions');
}

/*
	Define default option settings
*/
function wpextadd_defaults() {
    $tmp = get_option('wpextoptions');
    if(($tmp['chk_default_options_db']=='1')||(!is_array($tmp))) {
		delete_option('wpextoptions'); 
		$arr = array(
			"txt_libpath" => "/extjs",
			'txt_theme_css' => '/extjs/resources/css/xtheme-gray.css', 
			'txt_page_height' => '300',
			'txt_page_width' => '400',
			chk_enable_plugin=>TRUE,
			chk_enable_scrolling=>FALSE
		);
		update_option('wpextoptions', $arr);
	}
}

function wpextenqueue() {
	wp_enqueue_style( "wp-extjs-css", WP_PLUGIN_URL.'/wp-extjs/css/style.css' ); 
	
	$tmp = get_option( 'wpextoptions' );
	if ( $tmp['chk_enable_plugin'] ) {
		wp_enqueue_style( "extjs", $tmp['txt_libpath']."/resources/css/ext-all.css" ); 
		wp_enqueue_script( "extjs", $tmp['txt_libpath']."/adapter/ext/ext-base.js" ); 
		wp_enqueue_script( "extjs", $tmp['txt_libpath']."/ext-all.js" );
	
		$labels = array(
			'name' 					=> __('ExtJS Snippets', 'ExtJS Snippets'),
			'singular_name'			=> __('Snippet', 'ExtJS Snippet'),
			'add_new' 				=> __('Add New', 'snippet'),
			'add_new_item' 			=> __('Add New Snippet'),
			'edit_item' 			=> __('Edit Snippet'),
			'new_item' 				=> __('New Snippet'),
			'view_item' 			=> __('View Snippet'),
			'search_items' 			=> __('Search Snippets'),
			'not_found' 			=>  __('No snippets found'),
			'not_found_in_trash' 	=> __('No snippets found in Trash'), 
			'parent_item_colon' 	=> ''
		);
	
		$args = array(
			'labels' 				=> $labels,
			'public' 				=> false,
			'publicly_queryable' 	=> true,
			'show_ui' 				=> true, 
			'query_var' 			=> true,
			'rewrite' 				=> true,
			'capability_type' 		=> 'post',
			'hierarchical' 			=> false,
			'menu_position' 		=> null,
			'menu_icon' 			=> WP_PLUGIN_URL.'/wp-extjs/images/wpext_appicon.png',
			'supports' 				=> array('title','editor','author','revisions')
		); 
	
		register_post_type( 'snippet',$args );
		add_filter( 'post_updated_messages','snippet_updated_messages' );
	}
}
/**
 * snippet_updated_messages
 * @abstract returns the array of messages required for wordpress to communicate with the user about the custom post type ExtJS_Snippets
 * @return $messages array
 * @author Michael Lynn
 **/
function snippet_updated_messages( $messages ) {
	global $post, $post_ID;

	$messages['ExtJS_Snippet'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('ExtJS Snippet updated. <a href="%s">View snippet</a>'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __('ExtJS Snippet updated.'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('ExtJS Snippet restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('ExtJS Snippet published. <a href="%s">View snippet</a>'), esc_url( get_permalink($post_ID) ) ),
		7 => __('ExtJS Snippet saved.'),
		8 => sprintf( __('ExtJS Snippet submitted. <a target="_blank" href="%s">Preview snippet</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __('ExtJS Snippet scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview snippet</a>'),
		  // translators: Publish box date format, see http://php.net/date
		  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __('ExtJS Snippet draft updated. <a target="_blank" href="%s">Preview snippet</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);

	return $messages;
}

/**
 * wpextinit
 * @abstract registers valid settings for our plugin
 * @return void
 * @author Michael Lynn
 **/
function wpextinit(){
	wp_enqueue_style( WP_PLUGIN_URL.'/wp-extjs/css/style.css' );
	register_setting( 'wpextplugin_options', 'wpextoptions', 'wpextvalidate_options' );
	add_meta_box("wpext-meta", "Additional ExtJS Snippet Options", "wpext_options", "snippet", "normal", "high");  
}
/**
 * wpext_save_meta 
 * @abstract called by add_action to save the additional_header and additional_body fields
 * @return void
 * @author Michael Lynn
 **/
function wpext_save_meta(){  
    global $post;  
    update_post_meta($post->ID, "wpext_additional_header", $_POST["wpext_additional_header"]);  
    update_post_meta($post->ID, "wpext_additional_body", $_POST["wpext_additional_body"]);  
}

/**
 * wpext_options
 * @abstract Displays optional meta box inputs
 * @return void
 * @author Michael Lynn
 **/
function wpext_options() {
	global $post;
	$custom = get_post_custom($post->ID);  
    $addl_head = $custom["wpext_additional_header"][0];
    $addl_body = $custom["wpext_additional_body"][0];
?>
Use these optional fields to provide additional content in the head and body section of your rendered snippet.  This can be useful if you want to use extjs methods such as renderTo.  Keep in mind this should be syntactially correct HTML or Javascript.
<p>
	<label>Additional Header Content:<br></label><textarea rows=5 cols=80 name="wpext_additional_header"><?php echo $addl_head; ?></textarea><p>
	<label>Additional Body Content:</label><br><textarea rows=5 cols=80 name="wpext_additional_body"><?php echo $addl_body; ?></textarea>
<?php
}
/**
 * wpextadd_options_page
 * @abstract add our options page and define the controlling function
 * @return void
 * @author Michael Lynn
 **/
function wpextadd_options_page() {
	add_options_page('WP ExtJS Options Page', 'WP ExtJS', 'manage_options', __FILE__, 'wpextrender_form');
}

/**
 * wpextrender_form
 * @abstract Renders Plugin Options Page
 * @return void
 * @author Michael Lynn
 **/
function wpextrender_form() {
	settings_fields('wpextplugin_options'); 
	$options = get_option('wpextoptions');
	?>
	<script type="text/javascript">
	    function toggleVisibility(hs,co) {
	        var h = document.getElementById(hs);
	        var c = document.getElementById(co);
	        if(c.style.display == 'block') { 
	          	c.style.display = 'none';
		  		h.innerHTML = '[+]';
	        } else {
	          	c.style.display = 'block';
		  		h.innerHTML = '[-]';
			}
	    }
	</script>
	<STYLE type="text/css">
		.widefat thead th a {
			color: #333 !important;
			text-decoration: underline;
		};
	</STYLE>
<div class="wrap">
	<h2>WP ExtJS Options</h2>
</div>
	<?php
	if (!file_exists( $_SERVER{'DOCUMENT_ROOT'} . $options['txt_libpath'])) {
		?>
		<div class=updated settings-error><p align=center><b>Warning:</b> ExtJS does not appear to be installed in the directory specified (<?php echo $_SERVER['DOCUMENT_ROOT'].$options['txt_libpath'];?>)</p>
		Please make sure you have downloaded and installed ExtJS from <a href=http://www.sencha.com>http://www.sencha.com/</a>.
		<br>Once you have downloaded and installed ExtJS into a web accessible directory under your server's web root, make sure the value of the option below for the ExtJS Library Path is specified correctly.<p>WP-ExtJS will not function properly until you have installed ExtJS</div>
		<?php
	}
	if ( !$options['chk_donated'] ) {
		?>
<div style="text-align:center; background: #eeeeee; margins: 5px 5px 5px 5px;" id='wpextdonate'>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="hosted_button_id" value="J89ARHMKVMAAN">
	<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><br>
	<small>Like this plugin?  Please <a href=http://wordpress.org/extend/plugins/wp-extjs/>rate</a> it highly and maybe even consider donating to help keep this plugin alive and free.</small>
	<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br />
</div>
<?php 
}
?>
<form method="post" action="options.php">
<?php settings_fields('wpextplugin_options'); ?>
<p>
<p align=center>
<a href="http://wordpress.org/extend/plugins/wp-extjs/changelog/" target="_blank"><?php echo __('Changelog', 'wp-extjs'); ?></a> |
<a href="http://wordpress.org/extend/plugins/wp-extjs/faq/" target="_blank"><?php echo __('FAQ', 'wp-extjs'); ?></a> |
<a href="http://wordpress.org/extend/plugins/wp-extjs/" target="_blank"><?php echo __('Rate This', 'wp-extjs'); ?></a> |
<a href="http://www.mlynn.org" target="_blank"><?php echo __('Author Web Site', 'wp-extjs'); ?></a> 
</p>
<h3>Usage</h3>
Available shortcodes <a id='hide_shortcodes' style='text-decoration:none;' href='#' onclick="javascript:toggleVisibility('hide_shortcodes','shortcodes');">[+]</a>
<p>
<div id='shortcodes' style='display: none'>
Use these shortcodes to incorporate ExtJS Components in your blog posts
<h4>Shortcodes</h4>
<ul STYLE="list-style-type: square; list-style-position: inside">
	<li>[wpext-extjs snippet_id=909] - Includes a snippet into the current page or post.
</ul>
<p>
<h4>Optional Attributes</h4>
<ul>
<li>style - specifies the CSS style attributes to use when displaying the rendered snippet window
<li>height - specifies the height of the inline frame window for displaying the rendered snippet
<li>width - displays the width of the inline frame window for the renderered snippet
<li>border - controls whether the inline frame will be displayed with a frameborder - 1 or 0
<li>title - specifies a title for the inline frame window
</ul>
</div>
<h3>Options</h3>
	<table class="widefat post fixed">
		<thead>
			<tr>
			<th width=120>Setting</th>
			<th>Value</th>
			</tr>
		</thead>
		<tr>
			<th width=120 scope="row">Donation</th>
			<td>
				<input name="wpextoptions[chk_donated]" id="chk_donated" type="checkbox" <?php if( $options['chk_donated'] ) echo 'checked="checked"'; ?> />
				<label for="wpextoptions[chk_donated]">I have donated to help contribute for the development of this plugin.</label>
			</td>
		</tr>
		<tr>
			<th width=120 scope="row">Plugin Status</th>
			<td>
				  <input name="wpextoptions[chk_enable_plugin]" id="chk_enable_plugin" type="checkbox" <?php if( $options['chk_enable_plugin'] ) echo 'checked="checked"'; ?> />
				  <label for="chk_enable_plugin">Enable WP ExtJS Plugin</label>
			</td>
		</tr>

		<tr>
			<th width=120 scope="row">ExtJS Library Path</th>
			<td>
				<label><input size=40 name="wpextoptions[txt_libpath]" value="<?php echo $options['txt_libpath'];?>">
				<span style="color:#666666;margin-left:2px;"><br>Specify the full url path to the ExtJS Library - you can download ExtJS from <a href=http://www.sencha.com/download>Sencha.com</a></span>
			</td>
		</tr>
		<tr>
			<th width=120 scope="row">Theme CSS File URL</th>
			<td>
				<label><input size=40 name="wpextoptions[txt_theme_css]" value="<?php echo $options['txt_theme_css'];?>">
				<span style="color:#666666;margin-left:2px;"><br>Specify the URL Path Relative to your document root to the ExtJS CSS Theme File</span>
			</td>
		</tr>
		<tr>
			<th width=120 scope="row">Default Snippet Page Height</th>
			<td>
				<label><input size=10 name="wpextoptions[txt_page_height]" value="<?php echo $options['txt_page_height'];?>">
				<span style="color:#666666;margin-left:2px;"><br>This controls the default page height when displaying snippets in your posts or pages.  This can be overridden in posts using shortcode params for height/width</span>
			</td>
		</tr>
		<tr>
			<th width=120 scope="row">Default Snippet Page Width</th>
			<td>
				<label><input size=10 name="wpextoptions[txt_page_width]" value="<?php echo $options['txt_page_width'];?>">
				<span style="color:#666666;margin-left:2px;"><br>This controls the default page width when displaying snippets in your posts or pages.  This can be overridden in posts using shortcode params for width/width</span>
			</td>
		</tr>
		<tr>
			<th width=120 scope="row">Enabling Scrolling in Snippet Window by Default?</th>
			<td>
				<label><input type="checkbox" name="wpextoptions[chk_enable_scrolling]" value="<?php echo $options['chk_enable_scrolling'];?>">
				<span style="color:#666666;margin-left:2px;"><br>This controls whether the inline frame displaying the rendered snippet will automatically scroll.  Use can also specify scrolling=yes/no within the wp-extjs shortcode. 
			</td>
		</tr>
	</table>
	<p class="submit" align=center>
	<input type="submit" class="button-primary" value="Save Changes" />
	</p>
</form>
<p align=center>
Follow me on <a href=http://www.twitter.com/mlynn>twitter</a>, or visit my <a href=http://www.mlynn.org/>weblog</a>.
<br>
<small>WP-ExtJS is not affiliated with the developers of ExtJS, Sencha or Wordpress in any way.</small>
<p>
<?php
}// end render form

/**
 * wpextvalidate_options
 * @abstract Validate / Strips html from input fields
 * @return void
 * @author Michael Lynn
 **/
function wpextvalidate_options($input) {
	
	$input['txt_libpath'] =  wp_filter_nohtml_kses($input['txt_libpath']);
	$input['txt_theme_css'] =  wp_filter_nohtml_kses($input['txt_theme_css']);
	$input['txt_page_height'] =  wp_filter_nohtml_kses($input['txt_page_height']);
	$input['txt_page_width'] =  wp_filter_nohtml_kses($input['txt_page_width']);
	return $input;
}
