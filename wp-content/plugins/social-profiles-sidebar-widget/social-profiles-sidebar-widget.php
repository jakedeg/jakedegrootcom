<?php
/**
 * Plugin Name: Social Profiles Sidebar Widget
 * Plugin URI: http://blog.ppfeufer.de/wordpress-plugin-social-profiles-sidebar-widget/
 * Description: Add a sidebarwidget for social profiles in your blog. Supports several sets of icons and different iconsizes.
 * Version: 1.7.1
 * Author: H.-Peter Pfeufer
 * Author URI: http://ppfeufer.de
 */
define('SOCIALPROFILESSIDEBARWIDGETVERSION', '1.7.1');

class Social_Profiles_Sidebar extends WP_Widget {
	/**
	 * Einige notwendige Variablen definieren
	 */
	protected $var_sPluginDir = '../wp-content/plugins/social-profiles-sidebar-widget/';
	protected $var_sIconsetDir = '/wp-content/plugins/social-profiles-sidebar-widget/iconsets/';
	protected $var_sWhat = 'dir';
	protected $var_bRecursive = false;
	protected $array_profileImages = array();

	/**
	 * Constructor
	 */
	function Social_Profiles_Sidebar() {
		if(function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('social-profiles-sidebar-widget', PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/languages', dirname(plugin_basename(__FILE__)) . '/languages');
		}

		$widget_ops = array(
			'classname' => 'social_profiles_sidebar',
			'description' => __('The Social Profiles Sidebar', 'social-profiles-sidebar-widget')
		);

		$control_ops = array(
			'width' => 400
		);

		$this->WP_Widget('social_profiles_sidebar', __('The Social Profiles Sidebar', 'social-profiles-sidebar-widget'), $widget_ops, $control_ops);
	}

	/**
	 * Widgetformular erstellen
	 * @param $instance
	 */
	function form($instance) {
		$array_profiles = array();
		$instance = wp_parse_args((array) $instance, array(
			'pluginversion' => '',
			'title' => '',
			'selected_iconset' => 'none',
			'iconsize' => 'none',
			'iconposition' => 'center',
			'profile' => array(),
			'profile_linktext' => array()
		));

		$title = strip_tags($instance['title']);
		$selected_iconset = htmlspecialchars($instance['selected_iconset']);
		$selected_iconsize = htmlspecialchars($instance['iconsize']);
		$target_blank = $instance['target_blank'] ? 'checked="checked"' : '';
		$rel_nofollow = $instance['rel_nofollow'] ? 'checked="checked"' : '';
		$iconposition = $instance['iconposition'];

		/**
		 * Array an das neue Format anpassen, wenn nötig.
		 * Dazu wird das erste Element des Profilarrays geprüft.
		 */
		$var_mixedFirstElement = array_values($instance['profile']);
		if(!is_array($var_mixedFirstElement['0'])) {
			foreach($instance['profile'] as $key => $value) {
				$array_profiles[$key]['position'] = '';
				$array_profiles[$key]['link'] = $value;
				$array_profiles[$key]['text'] = $instance['profile_linktext'][$key . '_linktext'];
			}
		} else {
			$array_profiles = $instance['profile'];
		}

		/**
		 * Verzeichnis nach Iconsets durchsuchen
		 */
		$array_dir = $this->verzeichnis_auslesen('../' . $this->var_sIconsetDir, $this->var_sWhat, $this->var_bRecursive);

		/**
		 * Wenn Iconsets vorhanden sind
		 */
		if($array_dir) {
			/**
			 * Daten des Iconsets auslesen und in einem Array unterbringen
			 */
			for($count_i = 0; $count_i < count($array_dir); $count_i++) {
				if(file_exists('../' . $this->var_sIconsetDir . $array_dir[$count_i] . '/iconset.xml')) { // Wenn iconset.xml existiert
					$var_xmlUrl = '../' . $this->var_sIconsetDir . $array_dir[$count_i] . '/iconset.xml'; // XML feed file/URL
					$var_xmlStr = file_get_contents($var_xmlUrl);
					$obj_xmlObj = simplexml_load_string($var_xmlStr);

					$array_iconsetData[$array_dir[$count_i]] = $this->XMLToArray($obj_xmlObj);
				}
			}

			// Title
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Title:', 'social-profiles-sidebar-widget') . '</strong></p>';
			echo '<p><input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $instance['title'] . '" size="48" /></p>';

			// Iconset
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Iconset:', 'social-profiles-sidebar-widget') . '</strong></p>';
			echo '<p>' . __('Please choose an iconset and type in your profilelinks.', 'social-profiles-sidebar-widget') . '</p>';
			echo '<p><select id="' . $this->get_field_id('selected_iconset') . '" name="' . $this->get_field_name('selected_iconset') . '" onchange="wpWidgets.save(jQuery(this).closest(\'div.widget\'),0,1,0);">' . "\n";
			echo '<option value="none">' . __('Your choice', 'social-profiles-sidebar-widget') . '</option>' . "\n";

			if($array_iconsetData && is_array($array_iconsetData)) {
				foreach($array_iconsetData as $key => $value) {
					if($selected_iconset == $key) {
						$selected = ' selected="selected"';
					} else {
						$selected = '';
					}

					echo '<option value="' . $key . '"' . $selected . '>' . $value['icons']['name'] . '</option>' . "\n";
				}
			}

			echo '</select></p>';
			echo '<p>' . sprintf(__('Feel free to %s more Iconsets', 'social-profiles-sidebar-widget'), '<a href="http://blog.ppfeufer.de/wordpress-plugins/iconsets-fuer-das-social-profiles-sidebar-widget/" target="_blank">' . __('download', 'social-profiles-sidebar-widget') . '</a>') . '.</p>';

			/**
			 * Daten des Iconsets anzeigen
			 */
			if($selected_iconset == 'none') {
				echo '<p style="background-color:#f00; padding:10px; font-weight:bold;">' . __('Please select an iconset !!!', 'social-profiles-sidebar-widget') . '</p>';
			} else {
				echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Selected iconset:', 'social-profiles-sidebar-widget') . '</strong></p>';
				if($array_iconsetData[$selected_iconset]['icons']['screenshot'] && $array_iconsetData[$selected_iconset]['icons']['screenshot'] != '') {
					echo '<p style="float:left; margin-right:10px;"><img src="' . '../' . $this->var_sIconsetDir . $selected_iconset . '/' . $array_iconsetData[$selected_iconset]['icons']['screenshot'] . '" width="125" height="125" alt="Screenshot: ' . $array_iconsetData[$selected_iconset]['icons']['name'] . '" /></p>';
				}

				if($array_iconsetData[$selected_iconset]['icons']['uri']) {
					echo '<p><strong>' . __('Selected iconset', 'social-profiles-sidebar-widget') . ':</strong><br /><a href="' . $array_iconsetData[$selected_iconset]['icons']['uri'] . '" target="_blank">' . $array_iconsetData[$selected_iconset]['icons']['name'] . '</a></p>';
				} else {
					echo '<p><strong>' . __('Selected iconset', 'social-profiles-sidebar-widget') . ':</strong><br />' . $array_iconsetData[$selected_iconset]['icons']['name'] . '</p>';
				}

				if($array_iconsetData[$selected_iconset]['author']['uri']) {
					echo '<p><strong>' . __('Author', 'social-profiles-sidebar-widget') . ':</strong><br /><a href="' . $array_iconsetData[$selected_iconset]['author']['uri'] . '" target="_blank">' . $array_iconsetData[$selected_iconset]['author']['name'] . '</a></p>';
				} else {
					echo '<p><strong>' . __('Author', 'social-profiles-sidebar-widget') . ':</strong><br />' . $array_iconsetData[$selected_iconset]['author']['name'] . '</p>';
				}

				if($array_iconsetData[$selected_iconset]['icons']['flattr'] == 1) {
					echo '<p><strong>' . __('Like this set? Support the author.', 'social-profiles-sidebar-widget') . '</strong><br /><a class="FlattrButton" style="display:none;" rev="flattr;button:compact;" href="' . $array_iconsetData[$selected_iconset]['icons']['uri'] . '"></a></p>';
				}

				// Info additional Iconset
				if($array_iconsetData[$selected_iconset]['icons']['type'] == 'additional') {
					// Downloadlink für das zusätzliche Iconset erzeugen
					if($array_iconsetData[$selected_iconset]['icons']['download'] == 'none') {
						$var_sDownloadlinkIconset = __('this iconset', 'social-profiles-sidebar-widget');
					} else {
						$var_sDownloadlinkIconset = '<a href="' . $array_iconsetData[$selected_iconset]['icons']['download'] . '">' . __('this iconset', 'social-profiles-sidebar-widget') . '</a>';
					}

					echo '<p style="clear:both;"></p>';
					echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Info:', 'social-profiles-sidebar-widget') . '</strong></p>';
					echo '<p style="background-color:#ff0; padding:5px;">' . sprintf(__('This is an <strong>additional</strong> iconset.<br />You have to reinstall %s after you update the plugin.', 'social-profiles-sidebar-widget'), $var_sDownloadlinkIconset) . '</p>';
				}

				// Icongrösse
				echo '<p style="clear:both;"></p>';
				echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Iconsize:', 'social-profiles-sidebar-widget') . '</strong></p>';

				$array_IconSizes = $this->verzeichnis_auslesen('../' . $this->var_sIconsetDir . $selected_iconset . '/', 'dir', $this->var_bRecursive);

				if(!in_array($selected_iconsize, $array_IconSizes)) {
					$selected_iconsize = 'none';
				}

				$array_EntrysortOtpions = array(
					'none' => array(
						'selection' => ($selected_iconsize === 'none') ? ' selected="selected"' : '',
						'translation' => __('Your choice', 'social-profiles-sidebar-widget')
					)
				);

				foreach($array_IconSizes as $key) {
					$array_EntrysortOtpions[$key] = array(
						'selection' => ($selected_iconsize === $key) ? ' selected="selected"' : '',
						'translation' => __($key . ' Pixel', 'social-profiles-sidebar-widget')
					);
				}

				echo '<p><select id="' . $this->get_field_id('iconsize') . '" name="' . $this->get_field_name('iconsize') . '" onchange="wpWidgets.save(jQuery(this).closest(\'div.widget\'),0,1,0);">' . "\n";

				foreach($array_EntrysortOtpions as $arraykey => $arrayvalue) {
					echo '<option value="' . $arraykey . '"' . $arrayvalue['selection'] . '>' . $arrayvalue['translation'] . '</option>';
				}

				echo '</select></p>';

				/**
				 * Inputfelder für Netzwerke anzeigen
				 */
				if($selected_iconsize == 'none') {
					echo '<p style="background-color:#f00; padding:10px; font-weight:bold;">' . __('Please select an iconsize !!!', 'social-profiles-sidebar-widget') . '</p>';
				} else {
					$this->array_profileImages = $this->verzeichnis_auslesen('../' . $this->var_sIconsetDir . $selected_iconset . '/' . $selected_iconsize . '/', 'files', $this->var_bRecursive);

					// Supported Profiles
					echo '<p style="clear:both;"></p>';
					echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Supported Profiles:', 'social-profiles-sidebar-widget') . '</strong></p>';

					// Hinweis bei 16px
					if($selected_iconsize == '16x16') {
						echo '<p style="background-color:#ff0; padding:5px;">' . __('You selected the 16px iconsize.<br />The icons will be shown in a list among each other with a link beside. You have to enter a linktext for each of your profiles.<br />And, it should be styled via CSS at your own.<br /><code>span.social-profiles-sidebar-profileicon {}</code><br /><code>span.social-profiles-sidebar-linktext {}</code>', 'social-profiles-sidebar-widget') . '</p>';

					}

					foreach($this->array_profileImages as $value) {
						echo '<span style="display:inline-block; text-align:center; padding:2px 5px;">' . ucfirst(str_replace('.png', '', $value)) . '</span>';
					}

					/**
					 * Profillinks nach Postionsnummer sortieren.
					 */
					$array_ProfilesSorted = $this->profile_sort_by_position($array_profiles, 'reverse');
					foreach ($array_ProfilesSorted as $key => $values) {
						if(in_array($values['profile'] . '.png', $this->array_profileImages)) {
							array_unshift($this->array_profileImages, $values['profile'] . '.png');
						}
					}

					$this->array_profileImages = array_values(array_unique($this->array_profileImages));

					// Profilelinks
					echo '<p style="clear:both;"></p>';
					echo '<input id="' . $this->get_field_id('profiles-control') . '" name="' . $this->get_field_name('profiles-control') . '" type="hidden" value="save-profiles" size="35" />';
					echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('My Profiles:', 'social-profiles-sidebar-widget') . '</strong></p>';
					echo '<div id="socialIconOrderWrapper">';

					foreach($this->array_profileImages as $value) {
						$var_sProfilename = ucfirst(str_replace('.png', '', $value));
						$var_sProfileFieldName = str_replace('.png', '', $value);
						$var_sProfileFieldNameLinktext = $var_sProfileFieldName . '_linktext';

						echo '<p style="background-color:#fff; padding-bottom:15px;">';
						echo '<span style="display:block;"><span style="display:inline-block; width:100px; font-weight:bold;">' . $var_sProfilename . ': </span><span style="display:inlilne-block; float:right; cursor:move; padding:2px;">move</span></span>';
						echo '<span style="display:block; clear:both;"><span style="display:inline-block; width:100px; clear:both;">' . __('Link:', 'social-profiles-sidebar-widget') . '</span><input id="' . $this->get_field_id($var_sProfileFieldName) . '[link]" class="socialIconOrderLinkField" name="' . $this->get_field_name($var_sProfileFieldName) . '[link]" type="text" value="' . $array_profiles[$var_sProfileFieldName]['link'] . '" size="35" /><br />';
						echo '<span style="display:inline-block; width:100px;">' . __('Linktext:', 'social-profiles-sidebar-widget') . '</span><input id="' . $this->get_field_id($var_sProfileFieldName) . '[text]" name="' . $this->get_field_name($var_sProfileFieldName) . '[text]" type="text" value="' . $array_profiles[$var_sProfileFieldName]['text'] . '" size="35" /><br />';
						echo '<input id="' . $this->get_field_id($var_sProfileFieldName) . '[position]" class="socialIconOrderPositionField" name="' . $this->get_field_name($var_sProfileFieldName) . '[position]" type="hidden" value="' . $array_profiles[$var_sProfileFieldName]['position'] . '" size="2" /></span></p>';
					}

					echo '</div>';

					/**
					 * Per Drag and Drop sortieren.
					 * Danke an Simon (http://sharjes.de)
					 */
					echo '<script type="text/javascript">';
					echo '/* <![CDATA[ */';
					echo 'jQuery(document).ready(function() {';
					echo '	jQuery(\'#socialIconOrderWrapper\').sortable({';
					echo '			revert: 300,';
					echo '			update: function(event, ui) {';
					echo '				var link = jQuery(\'#socialIconOrderWrapper .socialIconOrderLinkField\');';
					echo '				var pos = jQuery(\'#socialIconOrderWrapper .socialIconOrderPositionField\');';
					echo '				for (var i = 0, iMax = pos.length; i < iMax; i++) {';
					echo '					if (link[i].value === \'\') {';
					echo '						pos[i].value = -1;';
					echo '					}';
					echo '					else {';
					echo '						pos[i].value = i;';
					echo '					}';
					echo '				}';
					echo '			}';
					echo '	});';
					echo '});';
					echo '/* ]]> */';
					echo '</script>';
				}
			}

			// weitere Optionen
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Other options:', 'social-profiles-sidebar-widget') . '</strong></p>';
			echo '<p><span style="display:inline-block; width:100px;">' . __('Open link in', 'social-profiles-sidebar-widget') . ': </span><input class="checkbox" type="checkbox" ' . $target_blank . ' id="' . $this->get_field_id('target_blank') . '" name="' . $this->get_field_name('target_blank') . '" /> ' . __('new window', 'social-profiles-sidebar-widget') . '</p>';
			echo '<p><span style="display:inline-block; width:100px;">' . __('Linkrelation', 'social-profiles-sidebar-widget') . ': </span><input class="checkbox" type="checkbox" ' . $rel_nofollow . ' id="' . $this->get_field_id('rel_nofollow') . '" name="' . $this->get_field_name('rel_nofollow') . '" /> ' . __('rel="nofollow"', 'social-profiles-sidebar-widget') . '</p>';

			if($selected_iconsize != '16x16') {
				echo '<p><span style="display:inline-block; width:100px;">' . __('Iconposition', 'social-profiles-sidebar-widget') . ': </span>';
				$array_IconpositionOtpions = array(
					'left' => array(
						'selection' => ($iconposition === 'left') ? ' selected="selected"' : '',
						'translation' => __('left', 'social-profiles-sidebar-widget')
					),
					'center' => array(
						'selection' => ($iconposition === 'center') ? ' selected="selected"' : '',
						'translation' => __('center', 'social-profiles-sidebar-widget')
					),
					'right' => array(
						'selection' => ($iconposition === 'right') ? ' selected="selected"' : '',
						'translation' => __('right', 'social-profiles-sidebar-widget')
					)
				);

				echo '<select id="' . $this->get_field_id('iconposition') . '" name="' . $this->get_field_name('iconposition') . '">';
				foreach($array_IconpositionOtpions as $arraykey => $arrayvalue) {
					echo '<option value="' . $arraykey . '"' . $arrayvalue['selection'] . '>' . $arrayvalue['translation'] . '</option>';
				}
				echo '</select>';
			}

			// Flattr
			echo '<p style="clear:both;"></p>';
			echo '<p style="border-bottom: 1px solid #DFDFDF;"><strong>' . __('Like this Plugin? Support the developer.', 'social-profiles-sidebar-widget') . '</strong></p>';
			echo '<p><a href="http://flattr.com/thing/87594/WordPress-Plugin-Social-Profiles-Sidebar-Widget" target="_blank"><img src="http://api.flattr.com/button/flattr-badge-large.png" alt="Flattr this" title="Flattr this" border="0" /></a></p>';

			echo '<div id="selected-iconset-data">';
			echo '</div>';
		}

		echo '<p style="clear:both;"></p>';
	}

	/**
	 * Widget erstellen
	 * @param unknown_type $args
	 * @param unknown_type $instance
	 */
	function widget($args, $instance) {
		extract($args);

		echo $before_widget;

		$title = (empty($instance['title'])) ? '' : apply_filters('widget_title', $instance['title']);

		if(!empty($title)) {
			echo $before_title . $title . $after_title;
		}

		echo $this->social_profiles_sidebar_output($instance, 'widget');
		echo $after_widget;
	}

	/**
	 * Optionen updaten
	 * @param $new_instance
	 * @param $old_instance
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$new_instance = wp_parse_args((array) $new_instance, array(
			'pluginversion' => '',
			'title' => '',
			'selected_iconset' => 'none',
			'iconsize' => 'none',
			'target_blank' => 0,
			'rel_nofollow' => 0,
			'iconposition' => 'center',
			'profile' => array()
		));

		$this->array_profileImages = $this->verzeichnis_auslesen('../' . $this->var_sIconsetDir . $new_instance['selected_iconset'] . '/' . $new_instance['iconsize'] . '/', 'files', $this->var_bRecursive);

		$instance['pluginversion'] = (string) SOCIALPROFILESSIDEBARWIDGETVERSION;
		$instance['title'] = (string) strip_tags($new_instance['title']);
		$instance['selected_iconset'] = (string) strip_tags($new_instance['selected_iconset']);
		$instance['iconsize'] = (string) strip_tags($new_instance['iconsize']);
		$instance['target_blank'] = $new_instance['target_blank'] ? 1 : 0;
		$instance['rel_nofollow'] = $new_instance['rel_nofollow'] ? 1 : 0;
		$instance['iconposition'] = (string) strip_tags($new_instance['iconposition']);

		if($new_instance['profiles-control'] == 'save-profiles') {
			unset($instance['profile_linktext']);
			unset($instance['profile']);

			foreach($this->array_profileImages as $value) {
				$value = strtolower(str_replace('.png', '', $value));
				$instance['profile'][$value]['position'] = (int) strip_tags($new_instance[$value]['position']);
				$instance['profile'][$value]['link'] = (string) strip_tags($new_instance[$value]['link']);
				$instance['profile'][$value]['text'] = (string) strip_tags($new_instance[$value]['text']);
			}
		}

		return $instance;
	}

	/**
	 * XML parsen
	 *
	 * @param string $x_xml
	 */
	function XMLToArray($arrObjData, $arrSkipIndices = array()) {
		$arrData = array();

		// if input is object, convert into array
		if(is_object($arrObjData)) {
			$arrObjData = get_object_vars($arrObjData);
		}

		if(is_array($arrObjData)) {
			foreach($arrObjData as $index => $value) {
				if(is_object($value) || is_array($value)) {
					$value = $this->XMLToArray($value, $arrSkipIndices); // recursive call
				}

				if(in_array($index, $arrSkipIndices)) {
					continue;
				}

				$arrData[$index] = $value;
			}
		}

		return $arrData;
	}

	/**
	 * Funktion zum Auslesen eines Verzeichnisses
	 *
	 * @param $path string Verzeichnispfad relativ zum aufrufenden Script
	 * @param $what string Verzeichnisse und/oder Dateien? ('all', 'dir', 'file')
	 * @param $rekursiv bool Unterverzeichnisse mit auflisten? ( true, false)
	 *
	 * @return array
	 */
	function verzeichnis_auslesen($path, $what = 'all', $rekursiv = true) {
		$path = preg_replace('~(.*)/$~', '\\1', $path); // letzten Slash aus dem String entfernen
		$list = array();
		$directory = opendir($path);

		while($dir_entry = readdir($directory)) {
			switch($what) {
				case 'file':
					if(is_file("$path/$dir_entry")) $list[] = $rekursiv ? "$path/$dir_entry" : $dir_entry;
					break;

				case 'dir':
					if(is_dir("$path/$dir_entry") && !preg_match('~^\.\.?$~', $dir_entry)) $list[] = $rekursiv ? "$path/$dir_entry" : $dir_entry;
					break;

				default:
					if(!preg_match('~^\.\.?$~', $dir_entry)) $list[] = $rekursiv ? "$path/$dir_entry" : $dir_entry;
			}

			// Unterverzeichnisse?
			if(!preg_match('~^\.\.?$~', $dir_entry) && is_dir("$path/$dir_entry") && $rekursiv) {
				$list2 = $this->verzeichnis_auslesen("$path/$dir_entry", $what, $rekursiv);

				foreach($list2 as $dir_entry2) {
					$list[] = $dir_entry2;
				}
			}
		}

		closedir($directory);
		sort($list);

		return $list;
	}

	function social_profiles_sidebar_output($args = array(), $position) {
		$var_sIconUri = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "", plugin_basename(__FILE__)) . 'iconsets/' . $args['selected_iconset'] . '/' . $args['iconsize'] . '/';
		$var_sIconPath = PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)) . '/iconsets/' . $args['selected_iconset'] . '/' . $args['iconsize'] . '/';
		$var_sAlign = ($args['iconposition'] == 'right') ? ' text-align:right;' : '';
		$var_sLinktarget = ($args['target_blank'] == 1) ? ' target="_blank"' : '';
		$var_sLinkRelation = ($args['rel_nofollow'] == 1) ? ' rel="nofollow"' : '';

		$array_profiles = array();

		/**
		 * Array an das neue Format anpassen, wenn nötig.
		 * Dazu wird das erste Element des Profilarrays geprüft.
		 */
		$var_mixedFirstElement = array_values($args['profile']);
		if(!is_array($var_mixedFirstElement['0'])) {
			foreach($args['profile'] as $key => $value) {
				$array_profiles[$key]['position'] = '';
				$array_profiles[$key]['link'] = $value;
				$array_profiles[$key]['text'] = $args['profile_linktext'][$key . '_linktext'];
			}
		} else {
			$array_profiles = $args['profile'];
		}

		// Profile nach Position sortieren.
		$array_ProfilesSorted = $this->profile_sort_by_position($array_profiles);

		if($args['iconsize'] == '16x16') {
			echo '<ul>';

			foreach($array_ProfilesSorted as $key => $values) {
				if($values['link'] == '') {
					continue;
				} else {
					if(file_exists($var_sIconPath . $values['profile'] . '.png')) {
						echo '<li><span class="social-profiles-sidebar-profileicon" style="display:inline-block;"><a' . $var_sLinktarget . $var_sLinkRelation . ' href="' . $values['link'] . '"><img src="' . $var_sIconUri . $values['profile'] . '.png" alt="' . $values['text'] . '" title="' . $values['text'] . '" /></a></span><span class="social-profiles-sidebar-linktext" style="display:inline-block;"><a' . $var_sLinktarget . $var_sLinkRelation . ' href="' . $values['link'] . '">' . $values['text'] . '</a></span></li>';
					}
				}
			}

			echo '</ul>';
		} else {
			if($args['iconposition'] == 'center') {
				echo '<script type="text/javascript">' . "\n";
				echo '/* <![CDATA[ */' . "\n";
				echo 'function centerSocialProfilesWidgets() {' . "\n";
				echo '	centerSocialProfilesWidgets = function () {};' . "\n";
				echo '	var wrapperDiv = document.getElementById(\'social-profiles-widget-wrapper\');' . "\n";
				echo '	var sidebarLi = wrapperDiv.parentNode;' . "\n";
				echo '	var liWidth = sidebarLi.clientWidth;' . "\n";
				echo '	var icon = sidebarLi.getElementsByTagName(\'span\')[0];' . "\n";
				echo '	if (icon) {' . "\n";
				echo '		var iconWidth = icon.clientWidth;' . "\n";
				echo '		var padding = Math.floor((liWidth % iconWidth) / 2);' . "\n";
				echo '		wrapperDiv.style.paddingLeft = padding + \'px\';' . "\n";
				echo '	}' . "\n";
				echo '}' . "\n";
				echo '/* ]]> */' . "\n";
				echo '</script>' . "\n";
			}

			echo '<div id="social-profiles-widget-wrapper" style="margin-top:10px;' . $var_sAlign . '">';

			if($args['selected_iconset'] == 'none' || $args['iconsize'] == 'none') {
				echo '<p>' . __('Widget has to be configured properly', 'social-profiles-sidebar-widget') . '</p>';

				if($args['selected_iconset'] == 'none') {
					echo '<p>' . __('No iconset selected', 'social-profiles-sidebar-widget') . '</p>';
				} elseif($args['iconsize'] == 'none') {
					echo '<p>' . __('No iconsize selected', 'social-profiles-sidebar-widget') . '</p>';
				}
			} else {
				foreach($array_ProfilesSorted as $key => $values) {
					if($values['link'] == '') {
						continue;
					} else {
						if(file_exists($var_sIconPath . $values['profile'] . '.png')) {
							if($args['iconposition'] == 'left' || $args['iconposition'] == 'right') {
								echo '<span style="display:inline-block;"><a' . $var_sLinktarget . $var_sLinkRelation . ' href="' . $values['link'] . '"><img src="' . $var_sIconUri . $values['profile'] . '.png" alt="' . $values['text'] . '" title="' . $values['text'] . '" /></a></span>';
							} elseif($args['iconposition'] == 'center') {
								echo '<span style="display:inline-block;"><a' . $var_sLinktarget . $var_sLinkRelation . ' href="' . $values['link'] . '"><img src="' . $var_sIconUri . $values['profile'] . '.png" alt="' . $values['text'] . '" title="' . $values['text'] . '" onload="centerSocialProfilesWidgets();" /></a></span>';
							}
						}
					}
				}
			}

			echo '</div>';


			echo '<div style="clear: both;"></div>';
		}
	}

	/**
	 * Profile nach ihrer Positionsnummer sortieren.
	 * Diese Funktion sortiert vor der Ausgabe im Frontend
	 * alle angegebenen Profile nach ihrer Positionsnummer.
	 * Dabei werden die profile bevorzugt, welche auch eine
	 * Positionsnummer haben. Profile ohne Positionsnummer
	 * werden hinten dran gehängt.
	 *
	 * Ist kein Link für ein Profil hinterlegt, wird es nicht beachtet.
	 *
	 * @param array $array_profiles
	 * @since 1.3.8
	 */
	function profile_sort_by_position($array_profiles = '', $var_sOrder = '') {
		$array_ProfilesSorted = array();

		/**
		 * Alle Profile die eine Positionsnummer haben zu erst.
		 */
		foreach($array_profiles as $profile => $values) {
			if($values['position'] != '-1') {
				/**
				 * Prüfen ob es den Index im Array schon gibt.
				 */
				while (is_array($array_ProfilesSorted[$values['position']])) {
					$values['position']++;
				}

				if($values['link']) {
					$array_ProfilesSorted[$values['position']]['profile'] = $profile;
					$array_ProfilesSorted[$values['position']]['link'] = $values['link'];
					$array_ProfilesSorted[$values['position']]['text'] = $values['text'];
				}
			}
		}

		/**
		 * Nun die Profile die keine Positionsnummer haben.
		 */
//		foreach($array_profiles as $profile => $values) {
//			if($values['position'] == '-1') {
//				$values['position'] = 1;
//
//				/**
//				 * Prüfen ob es den Index im Array schon gibt.
//				 */
//				while (is_array($array_ProfilesSorted[$values['position']])) {
//					$values['position']++;
//				}
//
//				if($values['link']) {
//					$array_ProfilesSorted[$values['position']]['profile'] = $profile;
//					$array_ProfilesSorted[$values['position']]['link'] = $values['link'];
//					$array_ProfilesSorted[$values['position']]['text'] = $values['text'];
//				}
//			}
//		}

		if($var_sOrder == 'reverse') {
			krsort($array_ProfilesSorted);
		} else {
			ksort($array_ProfilesSorted);
		}

		return $array_ProfilesSorted;
	}
}

add_action('widgets_init', create_function('', 'return register_widget("Social_Profiles_Sidebar");'));

/**
 * Changelog bei Pluginupdate ausgeben.
 *
 * @since 1.6.3
 */
if(!function_exists('social_profiles_sidebar_widget_update_notice')) {
	function social_profiles_sidebar_widget_update_notice() {
		$url = 'http://plugins.trac.wordpress.org/browser/social-profiles-sidebar-widget/trunk/readme.txt?format=txt';
		$data = '';

		if(ini_get('allow_url_fopen')) {
			$data = file_get_contents($url);
		} else {
			if(function_exists('curl_init')) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$data = curl_exec($ch);
				curl_close($ch);
			} // END if(function_exists('curl_init'))
		} // END if(ini_get('allow_url_fopen'))


		if($data) {
			$matches = null;
			$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote(SOCIALPROFILESSIDEBARWIDGETVERSION) . '\s*=|$)~Uis';

			if(preg_match($regexp, $data, $matches)) {
				$changelog = (array) preg_split('~[\r\n]+~', trim($matches[1]));

				echo '</div><div class="update-message" style="font-weight: normal;"><strong>What\'s new:</strong>';
				$ul = false;
				$version = 99;

				foreach($changelog as $index => $line) {
					if(version_compare($version, SOCIALPROFILESSIDEBARWIDGETVERSION, ">")) {
						if(preg_match('~^\s*\*\s*~', $line)) {
							if(!$ul) {
								echo '<ul style="list-style: disc; margin-left: 20px;">';
								$ul = true;
							} // END if(!$ul)


							$line = preg_replace('~^\s*\*\s*~', '', $line);
							echo '<li>' . $line . '</li>';
						} else {
							if($ul) {
								echo '</ul>';
								$ul = false;
							} // END if($ul)


							$version = trim($line, " =");
							echo '<p style="margin: 5px 0;">' . htmlspecialchars($line) . '</p>';
						} // END if(preg_match('~^\s*\*\s*~', $line))
					} // END if(version_compare($version, SOCIALPROFILESSIDEBARWIDGETVERSION,">"))
				} // END foreach($changelog as $index => $line)


				if($ul) {
					echo '</ul><div style="clear: left;"></div>';
				} // END if($ul)


				echo '</div>';
			} // END if(preg_match($regexp, $data, $matches))
		} // END if($data)
	} // END function social_profiles_sidebar_widget_update_notice()
} // END if(!function_exists('social_profiles_sidebar_widget_update_notice'))

if(is_admin()) {
	if(ini_get('allow_url_fopen') || function_exists('curl_init')) {
		add_action('in_plugin_update_message-' . plugin_basename(__FILE__), 'social_profiles_sidebar_widget_update_notice');
	}
}
?>