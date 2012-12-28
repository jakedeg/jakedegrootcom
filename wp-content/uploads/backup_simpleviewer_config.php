<?php

/* Finding the path to the wp-admin folder */
$iswin = preg_match('/:\\\/', dirname(__file__));
$slash = ($iswin) ? "\\" : "/";

$wp_path = preg_split('/(?=((\\\|\/)wp-content)).*/', dirname(__file__));
$wp_path = (isset($wp_path[0]) && $wp_path[0] != "") ? $wp_path[0] : $_SERVER["DOCUMENT_ROOT"];

/** Load WordPress Administration Bootstrap */
require_once($wp_path . $slash . 'wp-load.php');
require_once($wp_path . $slash . 'wp-admin' . $slash . 'admin.php');

//load_plugin_textdomain( 'simpleviewer', FALSE, 'simpleviewer/langs/');
load_plugin_textdomain( 'simpleviewer', false, dirname(plugin_basename(__FILE__)) . '/languages' );

$title = "Add SimpleViewer Gallery";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php do_action('admin_xml_ns'); ?> <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
<title><?php bloginfo('name') ?> &rsaquo; <?php echo wp_specialchars( $title ); ?> &#8212; WordPress</title>
<?php

wp_admin_css( 'css/global' );
wp_admin_css();
wp_admin_css( 'css/colors' );
wp_admin_css( 'css/ie' );

$hook_suffix = '';
if ( isset($page_hook) )
	$hook_suffix = "$page_hook";
else if ( isset($plugin_page) )
	$hook_suffix = "$plugin_page";
else if ( isset($pagenow) )
	$hook_suffix = "$pagenow";

do_action("admin_print_styles-$hook_suffix");
do_action('admin_print_styles');
do_action("admin_print_scripts-$hook_suffix");
do_action('admin_print_scripts');
do_action("admin_head-$hook_suffix");
do_action('admin_head');


?>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'css/generator.css'; ?>?ver=<?php echo $SimpleViewer->version ?>" type="text/css" media="screen" title="no title" charset="utf-8" />
<script src="<?php echo plugin_dir_url(__FILE__) . 'js/sv.js'; ?>?ver=<?php echo $SimpleViewer->version ?>" type="text/javascript" charset="utf-8"></script>
<!--
	<?php echo wp_specialchars($title); ?> is heavily based on
	SWFObject 2 HTML and JavaScript generator v1.2 <http://code.google.com/p/swfobject/>
	Copyright (c) 2007-2008 Geoff Stearns, Michael Williams, and Bobby van der Sluis
	This software is released under the MIT License <http://www.opensource.org/licenses/mit-license.php>
-->

</head>
<body class="<?php echo apply_filters( 'admin_body_class', '' ); ?>">

	<div class="wrap" id="KFE_Generator">
	
        <h2><img src ="<?php echo plugin_dir_url(__FILE__) . 'img/icon_trans_35x26.png'; ?>" align="top" /><?php echo wp_specialchars($title); ?></h2>
        <form id="build_form" action="" method="post">
		<fieldset> 
			<div id="toggleable2"> 
				<div class="col1"> 
					<label for="title" class="info"><?php _e("Gallery Title",'simpleviewer'); ?>:</label>
				</div> 
				<div class="col2"> 
					<input type="text" id="title" name="title" value="" size="20" />
				</div>

				<div class="clear">&nbsp;</div>

				<div class="col1"> 
					<label class="info"><?php _e("Image Source",'simpleviewer'); ?>:</label>
				</div> 
				<div class="col2"> 
					<select id="library" name="library">
						<option value="wordpress"selected><?php _e("WordPress Library", 'simpleviewer'); ?></option>
                        <option value="flickr"><?php _e('Flickr', 'simpleviewer'); ?></option>
					</select> 
				</div>

				<div class="clear">&nbsp;</div>
                <div class="col1"></div>
                <div class="clear">&nbsp;</div>

                <div id="toggle_flickr">
					<div class="col1">
						<label for="flickrUserName" class="info" >Flickr Username</label>
					</div>
					<div class="col4">
                        <input type="text" id="flickrUserName" name="flickrUserName" value="" size="20" />
					</div>

					<div class="col1">
						<label for="flickrTags" class="info" >Flickr Tag</label>
					</div>
					<div class="col4">
                        <input type="text" id="flickrTags" name="flickrTags" value="" size="20" />
                    </div>

					<div class="clear">&nbsp;</div>
                </div>

					<div class="col1">
						<label for="galleryStyle" class="info" >Gallery Style</label>
					</div>
					<div class="col4">
                        <select id="galleryStyle" name="galleryStyle">
                            <option value="MODERN">Modern</option>
                            <option value="CLASSIC" selected="selected">Classic</option>
                            <option value="COMPACT">Compact</option>
                        </select>
					</div>

					<div class="clear">&nbsp;</div>

					<div class="col1">
						<label for="thumbPosition" class="info" >Thumb Position</label>
					</div>
					<div class="col4">
                        <select id="thumbPosition" name="thumbPosition">
                            <option value="TOP">Top</option>
                            <option value="BOTTOM">Bottom</option>
                            <option selected="selected"value="LEFT">Left</option>
                            <option value="RIGHT">Right</option>
                            <option value="NONE">None</option>
                        </select>
					</div>

					<div class="col1">
						<label for="frameWidth" class="info" >Frame Width, px</label>
					</div>
					<div class="col4">
                        <input type="text" id="frameWidth" name="frameWidth" value="1" size="20" />
					</div>

					<div class="clear">&nbsp;</div>

					<div class="col1">
						<label for="maxImageWidth" class="info" >Max Image Width, px</label>
					</div>
					<div class="col4">
                        <input type="text" id="maxImageWidth" name="maxImageWidth" value="940" size="20" />
                    </div>

					<div class="col1">
						<label for="maxImageHeight" class="info" >Max Image Height, px</label>
					</div>
					<div class="col4">
                        <input type="text" id="maxImageHeight" name="maxImageHeight" value="1500" size="20" />
                    </div>

					<div class="clear">&nbsp;</div>

					<div class="col1">
						<label for="textColor" class="info" >Text Color</label>
					</div>
					<div class="col4">
                        <input type="text" id="textColor" name="textColor" value="ffffff" size="20" />
					</div>

					<div class="col1">
						<label for="frameColor" class="info" >Frame Color</label>
					</div>
					<div class="col4">
                        <input type="text" id="frameColor" name="frameColor" value="333333" size="20" />
					</div>

					<div class="clear">&nbsp;</div>

					<div class="col1">
						<label for="showOpenButton" class="info" >Open Button</label>
					</div>
					<div class="col4">
                        <input type="checkbox" id="showOpenButton" name="showOpenButton" value="true" checked="true" />
                    </div>

					<div class="col1">
						<label for="showFullscreenButton" class="info" >Fullscreen Button</label>
					</div>
					<div class="col4">
                        <input type="checkbox" id="showFullscreenButton" name="showFullscreenButton" value="true" checked="true" />
                    </div>

					<div class="clear">&nbsp;</div>

					<div class="col1">
						<label for="thumbRows" class="info" >Thumbnail Rows</label>
					</div>
					<div class="col4">
                        <input type="text" id="thumbRows" name="thumbRows" value="10" size="20" />
					</div>

					<div class="col1">
						<label for="thumbColumns" class="info" >Thumbnail Columns</label>
					</div>
					<div class="col4">
                        <input type="text" id="thumbColumns" name="thumbColumns" value="2" size="20" />
					</div>

					<div class="clear">&nbsp;</div>

					<div class="col1">
						<label for="gallery_width" class="info" >Gallery Width</label>
					</div>
					<div class="col4">
                        <input type="text" id="gallery_width" name="gallery_width" value="900px" size="20" />
					</div>

					<div class="col1">
						<label for="gallery_height" class="info" >Gallery Height</label>
					</div>
					<div class="col4">
                        <input type="text" id="gallery_height" name="gallery_height" value="600px" size="20" />
                    </div>

					<div class="clear">&nbsp;</div>

					<div class="col1">
						<label for="backgroundColor" class="info" >Background Color</label>
					</div>
					<div class="col4">
                        <input type="text" id="backgroundColor" name="backgroundColor" value="" />
                    </div>

					<div class="col1">
						<label for="transparentBackground" class="info" >Background Transparent</label>
					</div>
					<div class="col4">
                        <input type="checkbox" id="transparentBackground" name="transparentBackground" value="true" />
                    </div>

					<div class="clear">&nbsp;</div>
                    
					<div class="col1">
						<label for="useFlash" class="info" >Use Flash</label>
					</div>
					<div class="col4">
                        <input type="checkbox" id="useFlash" name="useFlash" value="true" checked />
                    </div>

					<div class="clear">&nbsp;</div>

					<div class="col1">
						<label for="prooptions" class="info" >Pro Options</label>
					</div>
					<div class="col2">
                        <textarea id="prooptions" name="prooptions" cols="50" rows="5" ></textarea>
                    </div>

					<div class="clear">&nbsp;</div>

				<div class="clear">&nbsp;</div> 
			</div> 
		</fieldset>

		<div class="col1"> 
			<input type="button" class="button" id="generate" name="generate" value="<?php _e("Add Gallery",'simpleviewer'); ?>" />
		</div> 
    </form>
	</div>
	
	<script type="text/javascript" charset="utf-8">
		// <![CDATA[
		jQuery(document).ready(function(){
			try {
                SV.Flash.Generator.postUrl   = "<?php echo plugin_dir_url(__FILE__) . 'save-gallery.php'; ?>";
				SV.Flash.Generator.initialize();
			} catch (e) {
				throw "<?php _e("SV is not defined. This generator isn't going to put a SV tag in your code.",'simpleviewer'); ?>";
			}
		});
		// ]]>
	</script>

</body>
</html>