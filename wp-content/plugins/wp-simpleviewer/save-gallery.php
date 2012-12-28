<?php

$wp_path = preg_split('/(?=((\\\|\/)wp-content)).*/', dirname(__file__));
$wp_path = (isset($wp_path[0]) && $wp_path[0] != '') ? $wp_path[0] : $_SERVER['DOCUMENT_ROOT'];

require_once($wp_path . '/wp-load.php');

$options = get_option('simpleviwer_options');
if ($options['last_id'] == '') {
	$options['last_id'] == '0';
}
$options['last_id'] = $options['last_id'] + 1;

echo 'gallery_id="' . $options['last_id'] . '"' ;

update_option('simpleviwer_options', $options);

$upload_dir = wp_upload_dir();
$gallery_filename = $upload_dir['basedir'] . '/' . $options['last_id'] . '.xml';

$SimpleViewer->build_gallery($gallery_filename, $_POST);

?>
