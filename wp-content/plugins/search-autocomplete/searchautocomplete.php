<?php
/**
 * Plugin Name: Search Autocomplete
 * Plugin URI: http://hereswhatidid.com/search-autocomplete
 * Description: Adds jQuery Autocomplete functionality to the default WordPress search box.
 * Version: 2.0.0
 * Author: Gabe Shackle
 * Author URI: http://hereswhatidid.com
 */  
class SearchAutocomplete {
	protected static $options_field = "sa_settings";
	protected static $options_field_ver = "sa_settings_ver";
	protected static $options_field_current_ver = "2.0";
	protected static $options_default = array(
		'autocomplete_search_id' => '#s',
		'autocomplete_minimum' => 3,
		'autocomplete_numrows' => 10,
		'autocomplete_hotlink_titles' => 1,
		'autocomplete_hotlink_keywords' => 1,
		'autocomplete_hotlink_categories' => 1,
		'autocomplete_posttypes' => array('post'),
		'autocomplete_taxonomies' => array(),
		'autocomplete_sortorder' => 'posts',
		'autocomplete_theme' => '/redmond/jquery-ui-1.9.2.custom.min.css',
	);

	var $pluginUrl,
		$defaults,
		$options;

	public function __construct() {
		$this->initVariables();
		add_action('wp_enqueue_scripts', array($this, 'initScripts'));
		add_action('admin_enqueue_scripts', array($this, 'initAdminScripts'));
		$this->initAjax();
		// init admin settings page
		add_action('admin_menu', array($this, 'adminSettingsMenu'));
		add_action('admin_init', array($this, 'adminSettingsInit')); // Add admin init functions
	}

	public function initVariables() {
		$this->pluginUrl = plugin_dir_url(__FILE__);
		$options = get_option(self::$options_field);

		$this->options = ($options !== false) ? array_merge(self::$options_default, $options) : self::$options_default;
	}

	public function initScripts() {
		$localVars = array(
			'ajaxurl' => admin_url( 'admin-ajax.php'),
			'fieldName' => $this->options['autocomplete_search_id'],
			'minLength' => $this->options['autocomplete_minimum']
		);
		wp_enqueue_style('SearchAutocomplete-theme', plugins_url( 'themes'.$this->options['autocomplete_theme'] , __FILE__ ), array(), '1.9.2');
		if (wp_script_is('jquery-ui-autocomplete', 'registered')) {
			wp_enqueue_script('SearchAutocomplete', plugins_url( 'search-autocomplete.min.js' , __FILE__ ), array('jquery-ui-autocomplete'), '1.0.0', true);
		} else {
			wp_register_scrit('jquery-ui-autocomplete', plugins_url('js/jquery-ui-1.9.2.custom.min.js', __FILE__ ), array('jquery-ui'), '1.9.2', true);
			wp_enqueue_script('SearchAutocomplete', plugins_url( 'search-autocomplete.min.js' , __FILE__ ), array('jquery-ui-autocomplete'), '1.0.0', true);
		}		
		wp_localize_script('SearchAutocomplete', 'SearchAutocomplete', $localVars);
	}

	public function initAdminScripts() {
		$localAdminVars = array(
			'defaults' => self::$options_default
		);
		wp_enqueue_script('SearchAutocompleteAdmin', plugins_url( 'js/admin-scripts.js' , __FILE__ ), array('jquery-ui-sortable'), '1.0.0', true);
		wp_localize_script('SearchAutocompleteAdmin', 'SearchAutocompleteAdmin', $localAdminVars);
	}

	public function initAjax() {
		add_action('wp_ajax_autocompleteCallback', array($this, 'acCallback'));
		add_action('wp_ajax_nopriv_autocompleteCallback', array($this, 'acCallback'));
	}

	public function acCallback() {
		global $wpdb;
		$resultsPosts = array();
		$resultsTerms = array();
		$term = sanitize_text_field($_GET['term']);
		if (count($this->options['autocomplete_posttypes']) > 0) {
			$tempPosts = get_posts(array(
				's' => $term,
				'numberposts' => $this->options['autocomplete_numrows'],
				'post_type' => $this->options['autocomplete_posttypes'],
			));
			foreach($tempPosts as $post) {
				$resultsTerms[] = array(
					'title' => $post->post_title,
					'url' => get_permalink($post->ID),
				);
			}
		}
		if (count($this->options['autocomplete_taxonomies']) > 0) {
			$queryStringTaxonomies = 'SELECT term.name as post_title, term.slug as guid, tax.taxonomy, 0 AS content_frequency, 0 AS title_frequency FROM '.$wpdb->term_taxonomy.' tax '.
				'LEFT JOIN '.$wpdb->terms.' term ON term.term_id = tax.term_id WHERE 1 = 1 '.
				'AND term.name LIKE "%'.$term.'%" '.
				'ORDER BY tax.count DESC '.
				'LIMIT 0, '.$this->options['autocomplete_numrows'];
			$tempTerms = $wpdb->get_results($queryStringTaxonomies);
			foreach($tempTerms as $term) {
				$resultsTerms[] = array(
					'title' => $term->post_title,
					'url' => get_term_link($term->guid, $term->taxonomy),
				);
			}
		}
		if ($this->options['autocomplete_sortorder'] == 'posts') {
			$results = array_merge($resultsPosts, $resultsTerms);
		} else {
			$results = array_merge($resultsTerms, $resultsPosts);
		}
		echo json_encode(array('results' => array_slice($results, 0, $this->options['autocomplete_numrows'])));
		die();
	}

    /*
     * Admin Settings
     *
     */
	public function adminSettingsMenu() {
		$page = add_options_page('Search Autocomplete', 'Search Autocomplete', 'manage_options', 'search-autocomplete', array($this, 'settingsPage'));
	}
	public function settingsPage() {
		?>
        <div class="wrap searchautocomplete-settings">
        <?php screen_icon(); ?>
        <h2><?php _e("Search Autocomplete", "search-autocomplete"); ?></h2>
        <form action="options.php" method="post">
        <?php wp_nonce_field(); ?>
        <?php
		settings_fields("sa_settings");
		do_settings_sections("search-autocomplete");
		?>
        <input class="button-primary" name="Submit" type="submit" value="<?php _e("Save settings", "search-autocomplete"); ?>">
        <input class="button revert" name="revert" type="button" value="<?php _e("Revert to Defaults", "search-autocomplete"); ?>">
        </form>
        </div>
        <?php
	}
	public function adminSettingsInit() {
		register_setting(
			self::$options_field,
			self::$options_field,
			array($this, "sa_settings_validate")
		);
		add_settings_section(
			'sa_settings_main', 
			__('Settings', 'search-autocomplete'), 
			array($this, 'sa_settings_main_text'), 
			'search-autocomplete'
		);
		add_settings_field(
			'autocomplete_search_id', 
			__('Search Field Selector', 'search-autocomplete'), 
			array($this, 'sa_settings_field_selector'), 
			'search-autocomplete', 
			'sa_settings_main'
		);
		add_settings_field(
			'autocomplete_minimum',
			__('Autocomplete Trigger','search-autocomplete'),
			array($this, 'sa_settings_field_minimum'),
			'search-autocomplete',
			'sa_settings_main'
		);
		add_settings_field(
			'autocomplete_numrows',
			__('Number of Results','search-autocomplete'),
			array($this, 'sa_settings_field_numrows'),
			'search-autocomplete',
			'sa_settings_main'
		);
		add_settings_field(
			'autocomplete_hotlinks',
			__('Hotlink Items','search-autocomplete'),
			array($this, 'sa_settings_field_hotlinks'),
			'search-autocomplete',
			'sa_settings_main'
		);
		add_settings_field(
			'autocomplete_posttypes',
			__('Post Types','search-autocomplete'),
			array($this, 'sa_settings_field_posttypes'),
			'search-autocomplete',
			'sa_settings_main'
		);
		add_settings_field(
			'autocomplete_taxonomies',
			__('Taxonomies','search-autocomplete'),
			array($this, 'sa_settings_field_taxonomies'),
			'search-autocomplete',
			'sa_settings_main'
		);
		add_settings_field(
			'autocomplete_sortorder',
			__('Order of Types','search-autocomplete'),
			array($this, 'sa_settings_field_sortorder'),
			'search-autocomplete',
			'sa_settings_main'
		);
		add_settings_field(
			'autocomplete_theme',
			__('Theme Stylesheet','search-autocomplete'),
			array($this, 'sa_settings_field_themes'),
			'search-autocomplete',
			'sa_settings_main'
		);
	}
	public function sa_settings_main_text() {}
	public function sa_settings_field_selector() {
		?>
		<input id="autocomplete_search_id" class="regular-text" name="<?=self::$options_field;?>[autocomplete_search_id]" value="<?=$this->options['autocomplete_search_id'];?>">
		<p class="description">
			<?php _e("Any valid CSS selector will work.", "search-autocomplete"); ?><br>
			<?php _e("The default search box for WordPress is '#s'.", "search-autocomplete"); ?></p>
	    <?php
	}
	public function sa_settings_field_minimum() {
		?>
		<input id="autocomplete_minimum" class="regular-text" name="<?=self::$options_field;?>[autocomplete_minimum]" value="<?php echo $this->options['autocomplete_minimum']; ?>">
		<p class="description"><?php _e("The minimum number of characters before the autocomplete triggers.", "search-autocomplete"); ?><br>
	    <?php
	}
	public function sa_settings_field_numrows() {
		?>
		<input id="autocomplete_numrows" class="regular-text" name="<?=self::$options_field;?>[autocomplete_numrows]" value="<?php echo $this->options['autocomplete_numrows']; ?>">
		<p class="description"><?php _e("The total number of results returned.", "search-autocomplete"); ?><br>
	    <?php
	}
	public function sa_settings_field_hotlinks() {
		?>
		
		<p><label>
			<input name="<?=self::$options_field;?>[autocomplete_hotlink_titles]" type="checkbox" id="autocomplete_hotlink_titles" value="1" <?php checked( $this->options['autocomplete_hotlink_titles'], 1 ); ?>>
			<?php _e('Link to post or page.', 'seach-autocomplete'); ?></label><br>
		<label>
			<input name="<?=self::$options_field;?>[autocomplete_hotlink_keywords]" type="checkbox" id="autocomplete_hotlink_keywords" value="1" <?php checked( $this->options['autocomplete_hotlink_keywords'], 1 ); ?>>
			<?php _e('Link to taxonomy (categories, keywords, custom taxonomies, etc...) page.', 'search-autocomplete'); ?></label></p>
		<p class="description"><?php _e('Adjusts the click action on drop down items.', 'search-autocomplete'); ?></p>
	    <?php
	}

	public function sa_settings_field_taxonomies() {
		$selectedTaxonomies = $this->options['autocomplete_taxonomies'];
		$args = array(
			'public' => true,
		);
		$output = 'objects';
		$taxonomies = get_taxonomies($args, $output);
		?><p><?php
		foreach($taxonomies as $taxonomy) {
		?>
		<label>
			<input name="<?=self::$options_field;?>[autocomplete_taxonomies][]" class="autocomplete_taxonomies" id="autocomplete_taxonomies-<?=$taxonomy->name?>" type="checkbox" value="<?=$taxonomy->name?>" <?php checked( in_array($taxonomy->name, $selectedTaxonomies), true ); ?>>
			<?=$taxonomy->labels->name?></label><br>    
		<?php
		}
		?></p>
		<p class="description"><?php _e('Check the taxonomies to include in the autocomplete drop down.', 'search-autocomplete'); ?></p>
	    <?php
	}

	public function sa_settings_field_posttypes() {
		$selectedTypes = $this->options['autocomplete_posttypes'];
		$args = array(
			'public' => true,
		);
		$output = 'objects';
		$postTypes = get_post_types($args, $output);
		?><p><?php
		foreach($postTypes as $postType) {
		?>
		<label>
			<input name="<?=self::$options_field;?>[autocomplete_posttypes][]" class="autocomplete_posttypes" id="autocomplete_posttypes-<?=$postType->name?>" type="checkbox" value="<?=$postType->name?>" <?php checked( in_array($postType->name, $selectedTypes), true ); ?>>
			<?=$postType->labels->name?></label><br>
		<?php
		}
		?></p>
		<p class="description"><?php _e('Check the post types to include in the autocomplete drop down.', 'search-autocomplete'); ?></p>
	    <?php
	}

	public function sa_settings_field_themes() {
	?>
	<select name="<?=self::$options_field;?>[autocomplete_theme]" id="autocomplete_theme">
	<?php
	$globFilter = dirname(__FILE__).'\css\*\*.css';
	foreach( glob($globFilter) as $stylesheet) {
		$newSheet = str_replace(dirname(__FILE__).'\css', '', $stylesheet);
		$newSheet = str_replace('\\', '/', $newSheet);
		printf('<option value="%s"%s>%s</option>', $newSheet, ($newSheet == $this->options['autocomplete_theme']) ? ' selected="selected"' : '', $newSheet);
	}
		?>
	</select>
	<p class="description"><?php _e('These themes use the jQuery UI standard theme set up.  You can create and download additional themes here: <a href="http://jqueryui.com/themeroller/" target="_blank">http://jqueryui.com/themeroller/</a>', 'search-autocomplete'); ?>.</p>
    <p class="description"><?php _e('To add a new theme to this plugin you must upload the "/css/" directory in the generated theme to the this plugin\'s "/css/" directory.  For example, "/wp-content/plugin/search-autocomplete/css/" would be a default install location', 'search-autocomplete'); ?>.</p>
	<p class="description"><?php _e('The minified (compressed) version of the CSS for ThemeRoller themes typically contain ".min" within their file name.', 'search-autocomplete'); ?></p>
	    <?php
	}

	public function sa_settings_field_sortorder() {
	?>
	<select name="<?=self::$options_field;?>[autocomplete_sortorder]" id="autocomplete_sortorder">
		<option value="posts" <?php selected($this->options['autocomplete_sortorder'], 'posts'); ?>><?php _e('Posts First', 'search-autocomplete'); ?></option>
		<option value="terms" <?php selected($this->options['autocomplete_sortorder'], 'terms'); ?>><?php _e('Taxonomies First', 'search-autocomplete'); ?></option>
	</select>
	<p class="description"><?php _e('When using multiple types (posts or taxonomies) this controls what order they are sorted in within the autocomplete drop down.', 'search-autocomplete'); ?></p>
	<?php
	}

	public function sa_settings_validate($input) {
		$valid = array_merge(self::$options_default, $input);
		return $valid;
	}
}
$SearchAutocomplete = new SearchAutocomplete();