<?php
/**
 * renderer.php - Part of the wp-extjs wordpress plugin
 * Renders Javascript Snippets
 * @author Michael Lynn
 * @version $Id$
 * @copyright Michael Lynn, 25 November, 2010
 **/

include_once('../../../wp-config.php');
include_once('../../../wp-config.php');
include_once('../../../wp-load.php');
include_once('../../../wp-admin/includes/taxonomy.php');
include_once('../../../wp-includes/wp-db.php');
$options = get_option('wpextoptions');
$snippet_id = $_GET['snippet_id'];
$post = get_post($snippet_id);
$addl_head = get_post_meta($post->ID, "wpext_additional_header",true);  
$addl_body = get_post_meta($post->ID, "wpext_additional_body",true);  

?>
<html> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <link rel="stylesheet" type="text/css" href="<?php echo $options['txt_libpath'];?>/resources/css/ext-all.css" /> 
	<?php 
	if ($options['txt_theme_css']) { 
		echo "<link rel='stylesheet' type='text/css' href='".$options['txt_theme_css']."'/>\n";
	}
	?>
    <!-- ExtJS library: base/adapter --> 
    <script type="text/javascript" src="<?php echo $options['txt_libpath'];?>/adapter/ext/ext-base.js"></script> 
    <!-- ExtJS library: all widgets --> 
    <script type="text/javascript" src="<?php echo $options['txt_libpath'];?>/ext-all.js"></script> 
	<?php print_r($addl_head); ?>
<script type="text/javascript">
<?php
	echo "$post->post_content\n";

?>
</script> 
</head> 
<body> 
	<!--Additional Snippet Body -->
	<?php print_r($addl_body); ?>
</body> 
</html>
