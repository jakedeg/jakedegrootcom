<?php
error_reporting(0);

function clean($str = '', $html = false) {
	if (empty($str)) return;

	if (is_array($str)) {
			foreach($str as $key => $value) $str[$key] = clean($value, $html);
	} else {
		if (get_magic_quotes_gpc()) $str = stripslashes($str);

		if (is_array($html)) $str = strip_tags($str, implode('', $html));
		elseif (preg_match('|<([a-z]+)>|i', $html)) $str = strip_tags($str, $html);
		elseif ($html !== true) $str = strip_tags($str);

		$str = trim($str);
	}
	return $str;
}
function arrayUnique($array, $preserveKeys = false)  
{  
    // Unique Array for return  
    $arrayRewrite = array();  
    // Array with the md5 hashes  
    $arrayHashes = array();  
    foreach($array as $key => $item) {  
        // Serialize the current element and create a md5 hash  
        $hash = md5(serialize($item));  
        // If the md5 didn't come up yet, add the element to  
        // to arrayRewrite, otherwise drop it  
        if (!isset($arrayHashes[$hash])) {  
            // Save the current element hash  
            $arrayHashes[$hash] = $hash;  
            // Add element to the unique Array  
            if ($preserveKeys) {  
                $arrayRewrite[$key] = $item;  
            } else {  
                $arrayRewrite[] = $item;  
            }  
        }  
    }  
    return $arrayRewrite;  
} 
$term = clean($_GET['term']); 
if ($term != '') {
	
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
	$wpdb =& $GLOBALS['wpdb'];
	$options = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."options WHERE option_name LIKE 'autocomplete_%'");
	$arr_options = array();
	foreach ($options as $option) {
		$arr_options[$option->option_name] = $option->option_value;
	}
	$return_arr = array();	
	if ($arr_options["autocomplete_field_posttitle"] == 1) {
		$titles = $wpdb->get_results("SELECT post_title As name, ID as post_id, guid AS url, 1 cnt FROM ".$wpdb->prefix."posts t WHERE post_status='publish' and (post_type='post' OR post_type='page') and post_date < NOW() and post_title LIKE '%".mysql_real_escape_string($term)."%' ORDER BY post_title");
		foreach ($titles as $word){
			$row_array['label'] = $word->name;
			if ($arr_options["autocomplete_hotlink_titles"] == 1) {
				$row_array['url'] = get_permalink($word->post_id);
			} else {
				$row_array['url'] = 'none';
			}
			array_push($return_arr,$row_array);
		}
	}
	if (($arr_options["autocomplete_field_categories"] == 1) || ($arr_options["autocomplete_field_keywords"] == 1)) {
		$str_sql = "SELECT t.term_id, name, t.slug, tt.taxonomy, sum( count ) cnt FROM ".$wpdb->prefix."terms t, ".$wpdb->prefix."term_taxonomy tt WHERE t.term_id = tt.term_id AND (";
		if ($arr_options["autocomplete_field_categories"] == 1) {
			$str_sql .= "tt.taxonomy = 'category'";
		}
		if (($arr_options["autocomplete_field_categories"] == 1) && ($arr_options["autocomplete_field_keywords"] == 1)) {
			$str_sql .= " OR ";
		}
		if ($arr_options["autocomplete_field_keywords"] == 1) {
			$str_sql .= "tt.taxonomy = 'post_tag'";
		}
		$str_sql .= ") AND name LIKE '%".mysql_real_escape_string($term)."%' GROUP BY t.name ORDER BY cnt DESC";
		$titles = $wpdb->get_results($str_sql);
		foreach ($titles as $word){
			$row_array['label'] = $word->name;
			if ($arr_options["autocomplete_hotlink_keywords"] == 1) {
				$row_array['url'] = get_term_link($word->slug, $word->taxonomy);
			} else {
				$row_array['url'] = 'none';
			}
			array_push($return_arr,$row_array);
		}
	}
	$result = arrayUnique($return_arr);
	echo json_encode($return_arr);
}
?>