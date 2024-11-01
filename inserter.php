<?php
/**
 * inserter.php - Part of the wp-extjs wordpress plugin
 *
 * @author Michael Lynn
 * @version $Id$
 * @copyright Michael Lynn, 25 November, 2010
 **/

/**
 * Define DocBlock
 **/

/** Load WordPress Administration Bootstrap */
if(file_exists('../../../wp-load.php')) {
    require_once("../../../wp-load.php");
} else if(file_exists('../../wp-load.php')) {
    require_once("../../wp-load.php");
} else if(file_exists('../wp-load.php')) {
    require_once("../wp-load.php");
} else if(file_exists('wp-load.php')) {
    require_once("wp-load.php");
} else if(file_exists('../../../../wp-load.php')) {
    require_once("../../../../wp-load.php");
} else if(file_exists('../../../../wp-load.php')) {
    require_once("../../../../wp-load.php");
} else {

    if(file_exists('../../../wp-config.php')) {
        require_once("../../../wp-config.php");
    } else if(file_exists('../../wp-config.php')) {
        require_once("../../wp-config.php");
    } else if(file_exists('../wp-config.php')) {
        require_once("../wp-config.php");
    } else if(file_exists('wp-config.php')) {
        require_once("wp-config.php");
    } else if(file_exists('../../../../wp-config.php')) {
        require_once("../../../../wp-config.php");
    } else if(file_exists('../../../../wp-config.php')) {
        require_once("../../../../wp-config.php");
    } else {
        echo '<p>Failed to load bootstrap.</p>';
        exit;   
    }

}

global $wp_db_version;
if ($wp_db_version < 8201) { 
    // Pre 2.6 compatibility (BY Stephen Rider)
    if ( ! defined( 'WP_CONTENT_URL' ) ) {
        if ( defined( 'WP_SITEURL' ) ) define( 'WP_CONTENT_URL', WP_SITEURL . '/wp-content' );
        else define( 'WP_CONTENT_URL', get_option( 'url' ) . '/wp-content' );
    }
    if ( ! defined( 'WP_CONTENT_DIR' ) ) define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
    if ( ! defined( 'WP_PLUGIN_URL' ) ) define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
    if ( ! defined( 'WP_PLUGIN_DIR' ) ) define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
}

require_once(ABSPATH.'wp-admin/admin.php');

wp_enqueue_script( 'common' );
wp_enqueue_script( 'jquery' );

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
 <STYLE type="text/css">
   .widefat thead th a {
		color: #333 !important;
		text-decoration: underline;
	}
 </STYLE>
    <title><?php bloginfo('name') ?> &rsaquo; <?php _e('ExtJS Snippets'); ?> &#8212; <?php _e('WordPress'); ?></title>
    <?php
	wp_enqueue_style( 'global' );
	wp_enqueue_style( 'wp-admin' );
	wp_enqueue_style( 'colors' );
	wp_enqueue_style( 'media' );
	do_action('admin_print_styles');
	do_action('admin_print_scripts');
	do_action('admin_head');
	if ( isset($content_func) && is_string($content_func) )
		do_action( "admin_head_{$content_func}" );
?>
</head>
<body style="margin: 10px 10px 10px 10px;">
<?php

$paged = ($_GET['paged']) ? $_GET['paged'] : 1;
$args = array(
	'post_type' => 'snippet',
	'posts_per_page' => 5,
	'paged' => $paged
	);
query_posts($args);

if ( have_posts() ) {
	print "<div class=wrap><img src='".WP_PLUGIN_URL."/wp-extjs/images/wpextjs_logo_50x50.png'><h2>ExtJS Snippets</h2></div>";
	print "<h3>Click the Insert Button to insert a snippet shortcode into your page or post</h3>";
?>
<div class="navigation">
  <div class="alignleft"><?php previous_posts_link('&laquo; Previous') ?></div>
  <div class="alignright"><?php next_posts_link('More &raquo;') ?></div>
</div>
	<table class='widefat post fixed' cellpadding='0' cellspacing='0'>
		<thead>
		<tr>
			<th scope='col' id='id' width=34>ID</th>
			<th scope='col' id='title' width=89>Title</th>
			<th scope='col' id='body' width=345>Snippet Content</th>
			<th scope='col' id='action'>Action</th>
		</tr>
<?php
	while ( have_posts() ) {
		the_post();
		$html = '[wp-extjs snippet_id="'.$post->ID.'"]';
		$body = get_the_content();
		?>
		<tr><td><?php echo $post->ID;?></td>
			<td><?php echo $post->post_title;?></td>
			<td>
				Shortcode: <b><?php echo "$html";?></b><br>Snippet Source:<br>
					<textarea disabled rows=10 cols=38><?php echo $body; ?></textarea>
			</td>
			<td>
				<INPUT type="submit" class="button button-primary insertdownload" id='snippet_<?php echo $post->ID;?>' name="insertintopost" value="Insert Shortcode" /><br>
			</td>
		</tr>
<?php
	}
	print "</table>";
} else {
	echo "No Snippets Found";
}
?>
	<script type="text/javascript">
		/* <![CDATA[ */
		//jQuery('#insertshortcode').click(function(){
		//var win = window.dialogArguments || opener || parent || top; 
		//if (jQuery('#insertshortcode').val()>0) win.send_to_editor('[wp-extjs snippet_id="' + jQuery('#insertshortcode').val() + '"');
		//else win.send_to_editor('<?php echo $html; ?>');
		//});
		jQuery('.insertdownload').click(function(){
            var win = window.dialogArguments || opener || parent || top; 
            var did = jQuery(this).attr('id');
            did=did.replace('snippet_', '');
            win.send_to_editor('[wp-extjs snippet_id="' + did + '"]');
        });     
             
		/* ]]> */
	</script>
<?php

//Reset Query
wp_reset_query();
?>
