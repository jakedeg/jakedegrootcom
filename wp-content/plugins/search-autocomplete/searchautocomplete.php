<?php
/**
 * Plugin Name: Search Autocomplete
 * Plugin URI: http://hereswhatidid.com/search-autocomplete
 * Description: Adds jQuery autocomplete functionality to the default Wordpress search box.
 * Version: 1.0.9
 * Author: Gabe Shackle
 * Author URI: http://hereswhatidid.com
 */  
function add_search_js() {
	if (!is_admin()) {
		wp_register_style('autocompletestyles', WP_PLUGIN_URL.'/search-autocomplete/css/'.get_option('autocomplete_theme').'/jquery-ui-1.8.9.custom.css');
		wp_enqueue_style('autocompletestyles');
		wp_enqueue_script('jquery-ui-core');

		wp_register_script('autocompletescripts', WP_PLUGIN_URL.'/search-autocomplete/autocomplete-scripts.php', array('jquery'), '1.0.0');
		wp_enqueue_script('autocompletescripts');
	}
}
add_action('init','add_search_js');
add_option('autocomplete_search_id', '#s');
add_option('autocomplete_minimum', 3);
add_option('autocomplete_hotlink_titles', true);
add_option('autocomplete_hotlink_keywords', true);
add_option('autocomplete_hotlink_categories', true);
add_option('autocomplete_field_posttitle', true);
add_option('autocomplete_field_keywords', true);
add_option('autocomplete_field_categories', true);
add_option('autocomplete_theme', 'ui-lightness');
function autocomplete_options(){
	static $config;
	if(isset($_POST['autocomplete_save'])){
		$nonce=$_REQUEST['_wpnonce'];
		if (! wp_verify_nonce($nonce, 'my-nonce') ) die("Security check");
		update_option('autocomplete_search_id',stripslashes(htmlspecialchars_decode($_POST['autocomplete_search_id'])));
		update_option('autocomplete_minimum',$_POST['autocomplete_minimum']);
		update_option('autocomplete_hotlink_titles',$_POST['autocomplete_hotlink_titles']);
		update_option('autocomplete_hotlink_keywords',$_POST['autocomplete_hotlink_keywords']);
		update_option('autocomplete_hotlink_categories',$_POST['autocomplete_hotlink_categories']);
		update_option('autocomplete_field_posttitle',$_POST['autocomplete_field_posttitle']);
		update_option('autocomplete_field_keywords',$_POST['autocomplete_field_keywords']);
		update_option('autocomplete_field_categories',$_POST['autocomplete_field_categories']);
		update_option('autocomplete_theme',$_POST['autocomplete_theme']);
		echo '<div class="updated"><p>Changes were saved successfully.</p></div>';
	}
	?>
	<div class="wrap">
	<h2>Autocomplete Settings</h2>
	<form method="post" id="autocomplete_options">
    <input type="hidden" name="action" value="update" />
    <?php wp_nonce_field('my-nonce'); ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Search Field Selector</th>
        <td><fieldset>
            <legend class="screen-reader-text"><span>Search Field ID</span></legend>
             <label for="autocomplete_search_id">
                <input name="autocomplete_search_id" type="text" id="autocomplete_search_id" value="<?php echo htmlspecialchars(get_option('autocomplete_search_id')) ?>" class="medium-text" /><br />
                Any valid jQuery selector will work.<br />
                Default search box for Wordpress < 3.2 is '#s'.<br />
                Default search box for Wordpress >= 3.2 is 'name=["s"]'.</label>
          </fieldset></td>
      </tr>
      <tr valign="top">
        <th scope="row">Autocomplete Trigger</th>
        <td><fieldset>
            <legend class="screen-reader-text"><span>Autocomplete Trigger</span></legend>
             <label for="autocomplete_minimum">Trigger the autocomplete if the search contains
                <input name="autocomplete_minimum" type="text" id="autocomplete_minimum" value="<?php echo get_option('autocomplete_minimum') ?>" class="small-text" />
                or more characters. (A minimum of 2 characters is required.)</label>
          </fieldset></td>
      </tr>
      <tr valign="top">
        <th scope="row">Hotlink Items<br /><small>Adjusts the click action on<br />drop down items.</small></th>
        <td><fieldset>
            <legend class="screen-reader-text"><span>Hotlink Items</span></legend>
             <p><label for="autocomplete_hotlink_titles">
                <input name="autocomplete_hotlink_titles" type="checkbox" id="autocomplete_hotlink_titles" value="1" <?php checked( get_option('autocomplete_hotlink_titles'), 1 ); ?> />
                Link to post or page.</label></p>
             <p><label for="autocomplete_hotlink_keywords">
                <input name="autocomplete_hotlink_keywords" type="checkbox" id="autocomplete_hotlink_keywords" value="1" <?php checked( get_option('autocomplete_hotlink_keywords'), 1 ); ?> />
                Link to keyword taxonomy page.</label></p>
             <p><label for="autocomplete_hotlink_categories">
                <input name="autocomplete_hotlink_categories" type="checkbox" id="autocomplete_hotlink_categories" value="1" <?php checked( get_option('autocomplete_hotlink_categories'), 1 ); ?> />
                Link to category taxonomy page.</label></p>
          </fieldset></td>
      </tr>
      <tr valign="top">
        <th scope="row">Search Fields</th>
        <td><fieldset>
            <legend class="screen-reader-text"><span>Search Fields</span></legend>
            <label for="autocomplete_field_posttitle">
              <input name="autocomplete_field_posttitle" type="checkbox" id="autocomplete_field_posttitle" value="1" <?php checked( get_option('autocomplete_field_posttitle'), 1 ); ?> />
              Post titles.</label>
            <br />
            <label for="autocomplete_field_keywords">
              <input name="autocomplete_field_keywords" type="checkbox" id="autocomplete_field_keywords" value="1" <?php checked( get_option('autocomplete_field_keywords'), 1 ); ?> />
              Keywords.</label>
            <br />
            <label for="autocomplete_field_categories">
              <input name="autocomplete_field_categories" type="checkbox" id="autocomplete_field_categories" value="1" <?php checked( get_option('autocomplete_field_categories'), 1 ); ?> />
              Categories.</label>
            <br />
          </fieldset></td>
      </tr>
      <tr valign="top">
        <th scope="row">Drop Down Theme</th>
        <td><fieldset>
            <legend class="screen-reader-text"><span>Drop Down Theme</span></legend>
            <select name="autocomplete_theme" id="autocomplete_theme">
<?php
$dir = "css/*";
$handle = opendir(dirname(__FILE__).DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR);
while (false !== ($file = readdir($handle))) {
	if ((is_dir(dirname(__FILE__).DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.$file)) && ($file !== '.') && ($file !== '..')) {
		echo "<option value='$file'";
		if (get_option('autocomplete_theme') == $file) {
			echo " selected='selected'";
		}
		echo ">".ucwords($file)."</option>\n";  
	}
}
closedir($handle);
?>
             
            </select>
            <p>These themes use the jQuery UI standard theme set up.  You can create and download additional themes here: <a href="http://jqueryui.com/themeroller/" target="_blank">http://jqueryui.com/themeroller/</a>.</p>
            <p>To add a new theme to this plugin you must upload the "/css/" directory in the generated theme to the this plugin's "/css/" directory.  For example, "/wp-content/plugin/searchautocomplete/css/" would be a default install location.</p>
          </fieldset></td>
      </tr>
    </table>
    <p class="submit"><input type="submit" name="autocomplete_save" class="button-primary" value="Save Changes" /></p>
  </form>
  </div>
<?php
}
function autocomplete_adminmenu(){
	add_options_page('Autocomplete Options', 'Autocomplete', 'edit_pages', __FILE__, 'autocomplete_options');
}
add_action('admin_menu','autocomplete_adminmenu',1);

function autocomplete_settings_link($links) { 
  $settings_link = '<a href="options-general.php?page=search-autocomplete/searchautocomplete.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}



$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'autocomplete_settings_link' );

?>