<?php
error_reporting(0);
header( 'Content-Type: application/javascript' );
	function get_root_directory() {
		$arr_directory = explode(DIRECTORY_SEPARATOR,dirname(__FILE__));
		for($i=0;$i<count($arr_directory);$i++) {
			$strreturn = '';
			for($j=0;$j<$i;$j++) {
				$strreturn .= $arr_directory[$j].'/';
			}
			if (@file_exists($strreturn.'wp-config.php')) {
				return $strreturn;
			}
		}
		return '';
	}  
$path = get_root_directory();
require_once($path.'/wp-config.php');
$wpdb = $GLOBALS['wpdb'];
$defaultselector = (version_compare($GLOBALS['wp_version'],'3.1')) ? 'name=["s"]' : '#s';

$options = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name LIKE 'autocomplete_%'");
$arr_options = array();
foreach ($options as $option) {
	$arr_options[$option->option_name] = $option->option_value;
}
$autoid = ((isset($arr_options['autocomplete_search_id'])) && ($arr_options['autocomplete_search_id'] !== '')) ? stripslashes(htmlspecialchars_decode($arr_options['autocomplete_search_id'])) : $defaultselector;
$autominimum = ((isset($arr_options["autocomplete_minimum"])) && ($arr_options["autocomplete_minimum"] !== '')) ? $arr_options["autocomplete_minimum"] : 3;
?>
(function($) {
	$(function() {
		$('<?php echo $autoid; ?>').autocomplete({
			source: '<?php echo dirname($_SERVER["REQUEST_URI"]).'/includes/tags.php'; ?>',
			minLength: <?php echo $autominimum; ?>,
      select: function(event, ui) {
      	if (ui.item.url !== 'none') {
        	location = ui.item.url;
        } else {
      		$(this).val(ui.item.label);
        }
      }
		});
	});
})(jQuery);
<?php
?>