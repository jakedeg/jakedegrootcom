<?php
/**
 * Plugin Name: FlexyTalk - Free Live Chat Widget
 * Plugin URI: http://wordpress.org/extend/plugins/flexytalk-widget/
 * Description: FlexyTalk enables you to chat to your web visitors using your current gmail account. Free lifetime plan with unlimited chats.
 * Version: 2.1
 * Author: FlexyTalk
 */

/**
 * Add function to widgets_init that'll load our widget.
 * 
 */
add_action( 'widgets_init', 'flexytalk_load_widgets' );
add_action('wp_enqueue_scripts', 'Jquery');
add_filter( 'plugin_row_meta', 'flexytalk_register_plugin_links',10,2);

/**
 * Register our widget.
 * 'FlexyTalk_Widget' is the widget class used below.
 *
 * 
 */
function flexytalk_register_plugin_links($links, $file) {
	$base = plugin_basename(__FILE__);
	if ($file == $base) {
		$links[] = '<a href="widgets.php">' . __('GO TO WIDGETS','flexytalk_widget') . '</a>';
		$links[] = '<a href="http://wordpress.org/extend/plugins/flexytalk-widget/faq/" target="_blank">' . __('FAQ','flexytalk_widget') . '</a>';
		$links[] = '<a href="Mailto:support@flexytalk.com">' . __('Support','flexytalk_widget') . '</a>';
	}
	return $links;
}

function Jquery()
{
 wp_enqueue_script('jquery');
 wp_enqueue_script('jquery-ui-core');
 wp_enqueue_script('jquery-ui-dialog');
}
function flexytalk_load_widgets() {
	register_widget( 'FlexyTalk_Widget' );
}

/**
 * FlexyTalk Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update. 
 *
 * 
 */
class FlexyTalk_Widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function FlexyTalk_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'FlexyTalk', 'description' => __('Chat with your website visitors.', 'FlexyTalk') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 700, 'height' => 350, 'id_base' => 'flexytalk-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'flexytalk-widget', __('FlexyTalk Free Live Chat Widget', 'FlexyTalk Free Live Chat Widget'), $widget_ops, $control_ops );

	}

	/**
	 * How to display the widget on the screen.
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$email=$instance['email'];
		$btnLayout=$instance['btnLayout'];
		$btnText=$instance['btnText'];
		$btnPosition=$instance['btnPosition'];
		$ff=$instance['ff'];
		
		
		

		/* Before widget (defined by themes). */
		echo $before_widget;

		$style="";
		$widgetid="";
if($instance['btnPosition']=="1")
	$style="position:fixed;bottom:10px;right:10px";
	if($instance['btnPosition']=="2")
	$style="position:fixed;bottom:10px;left:10px";
	if($instance['btnPosition']=="3")
	$style="position:fixed;top:10px;right:10px";
	if($instance['btnPosition']=="4")
	$style="position:fixed;top:10px;left:10px";
	if($instance['btnPosition']=="5")
	$style="";
	
	if($instance['WidgetID']=="")
	{
		$widgetid="QUICKTRY__".$instance['email'];
	}
	else
	{
		$widgetid=$instance['WidgetID'];
	}
	
	
	
	

	
	
		/* Display name from widget settings if one was input. */
		$htmlCode="<link href='//app.flexytalk.com/btn/". $instance['btnLayout'].".css' rel='stylesheet' type='text/css' /><div class='flexytalk' style='z-index:2147483647;".$style."' data-flexytalk-title='".$instance['WindowTitle']."' data-flexytalk='".$widgetid."' data-flexytalk-ff='".$instance['ff']."' ><a href='#' id='dialog_link' class='ft-button dialog-link'><span class='iconchat'></span>".$instance['btnText']."</a></div><script src='//app.flexytalk.com/js/FlexyTalk.js' ></script>";

		if ( $htmlCode)
			printf( $htmlCode);

	

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		
		$instance['email'] = $new_instance['email'];
		$instance['btnText'] = $new_instance['btnText'];
		$instance['btnLayout'] = $new_instance['btnLayout'];
		$instance['btnPosition'] = $new_instance['btnPosition'];
		$instance['WindowTitle'] = $new_instance['WindowTitle'];
		$instance['WidgetID'] = $new_instance['WidgetID'];
		$instance['ff'] = $new_instance['ff'];

		
		return $instance;
	}

	/**
	 * Displays the widget settings controls on the widget panel.
	 
	 */
	function form( $instance ) {

/* Set up some default widget settings. */
		$defaults = array( 'btnText' => __('CLICK TO CHAT', ''), 'btnLayout' => __('cupertino', ''), 'email' => __('YourGmailAccount', ''), 'btnPosition'=>__("1",""), 'WindowTitle' => __('LIVE CHAT', ''), 'ff' => __('10', ''), 'WidgetID' => __('', ''));
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

<span>Enter the Gmail account you'll use to answer chats requests coming from your website. A Google Hosted Domain or any Jabber account will also do OK.</span><br />
<p> 
<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e('Gmail Account (email):', 'hybrid'); ?></label>
<input style="width:300px" id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" value="<?php echo $instance['email']; ?>" style="width:100%;" />
 </p>
<br /><hr />
<span>Enter the WIDGET ID.  (only for <a href="http://panel.flexytalk.com/account/create" target="_blank">permium plan</a>. Free users please leave blank)</span><br /><br />
<p> 
<label for="<?php echo $this->get_field_id( 'WidgetID' ); ?>"><?php _e('Widget Id:', 'hybrid'); ?></label>
<input style="width:300px" id="<?php echo $this->get_field_id( 'WidgetID' ); ?>" name="<?php echo $this->get_field_name( 'WidgetID' ); ?>" value="<?php echo $instance['WidgetID']; ?>" style="width:100%;" /> 
<br /><hr />
<p>
<span>Choose the button layout. Check out the mouse over effects of each button on your real WordPress site.</span>
<table>

<tr>
<td>

<input class="radio" type="radio" <?php checked( $instance['btnLayout'], true ); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/hot-sneaks.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="humanity" <?php checked( $instance['btnLayout'], "humanity"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/humanity.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="lefrog" <?php checked( $instance['btnLayout'], "lefrog"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/lefrog.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="mint-choc" <?php checked( $instance['btnLayout'], "mint-choc"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/mint-choc.png', __FILE__ ); ?>"/></label>
</td>
</tr>
<tr>
<td>

<input class="radio" type="radio" style="bottom:10px" value="overcast" <?php checked( $instance['btnLayout'], "overcast"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/overcast.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="pepper-grinder" <?php checked( $instance['btnLayout'], "pepper-grinder"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/pepper-grinder.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="redmond" <?php checked( $instance['btnLayout'], "redmond"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/redmond.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="smoothness" <?php checked( $instance['btnLayout'], "smoothness"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/smoothness.png', __FILE__ ); ?>"/></label>
</td>
</tr>
<tr>
<td>

<input class="radio" type="radio" value="south-street" <?php checked( $instance['btnLayout'], "south-street"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/south-street.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="start" <?php checked( $instance['btnLayout'], "start"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/start.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="sunny" <?php checked( $instance['btnLayout'], "sunny"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/sunny.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="swanky-purse" <?php checked( $instance['btnLayout'], "swanky-purse"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/swanky-purse.png', __FILE__ ); ?>"/></label>
</td>
</tr>
<tr>
<td>

<input class="radio" type="radio" value="trontastic" <?php checked( $instance['btnLayout'], "trontastic"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/trontastic.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="ui-darkness" <?php checked( $instance['btnLayout'], "ui-darkness"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/ui-darkness.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="ui-lightness" <?php checked( $instance['btnLayout'], "ui-lightness"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/ui-lightness.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="vader" <?php checked( $instance['btnLayout'], "vader"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/vader.png', __FILE__ ); ?>"/></label>
</td>
</tr>
<tr>
<td>

<input class="radio" type="radio" value="black-tie" <?php checked( $instance['btnLayout'], "black-tie"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/black-tie.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="flick" <?php checked( $instance['btnLayout'], "flick"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/flick.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="excite-bike" <?php checked( $instance['btnLayout'], "excite-bike"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/excite-bike.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="eggplant" <?php checked( $instance['btnLayout'], "eggplant"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/eggplant.png', __FILE__ ); ?>"/></label>
</td>
</tr>
<tr>
<td>

<input class="radio" type="radio" value="dot-luv" <?php checked( $instance['btnLayout'], "dot-luv"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/dot-luv.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="dark-hive" <?php checked( $instance['btnLayout'], "dark-hive"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/dark-hive.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="cupertino" <?php checked( $instance['btnLayout'], "cupertino"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/cupertino.png', __FILE__ ); ?>"/></label>
</td>
<td>

<input class="radio" type="radio" value="blitzer" <?php checked( $instance['btnLayout'], "blitzer"); ?> id="<?php echo $this->get_field_id( 'btnLayout' ); ?>" name="<?php echo $this->get_field_name( 'btnLayout' ); ?>" /> 
			<label for="<?php echo $this->get_field_id( 'btnLayout' ); ?>"><img src="<?php echo plugins_url( 'img/blitzer.png', __FILE__ ); ?>"/></label>
</td>
</tr>
</table>
</p>
<hr />
<span>Enter the text to be displayed on the "chat" button </span><br /><br />
<p> 
<label for="<?php echo $this->get_field_id( 'btnText' ); ?>"><?php _e('Button Text:', 'hybrid'); ?></label>
<input id="<?php echo $this->get_field_id( 'btnText' ); ?>" name="<?php echo $this->get_field_name( 'btnText' ); ?>" value="<?php echo $instance['btnText']; ?>" style="width:300px" />
 </p>
<br>
<hr />
<span>Enter the Widget's window Title (displayed as the header on the chat widiget) </span><br /><br />
<p> 
<label for="<?php echo $this->get_field_id( 'WindowTitle' ); ?>"><?php _e('Window Title:', 'hybrid'); ?></label>
<input id="<?php echo $this->get_field_id( 'WindowTitle' ); ?>" name="<?php echo $this->get_field_name( 'WindowTitle' ); ?>" value="<?php echo $instance['WindowTitle']; ?>" style="width:300px" />
 </p>
<br>
<hr />
<span> Position on Page</span> <br /><br />

<input name="<?php echo $this->get_field_name( 'btnPosition' ); ?>" class="radio" type="radio" value="1" <?php checked( $instance['btnPosition'], "1"); ?> id="<?php echo $this->get_field_id( 'btnPosition' ); ?>"  />
<label for="<?php echo $this->get_field_id( 'btnPosition' ); ?>"><?php _e('Fixed: Always visible on the bottom right corner', 'hybrid'); ?></label><br /><br />

<input name="<?php echo $this->get_field_name( 'btnPosition' ); ?>" class="radio" type="radio" value="2" <?php checked( $instance['btnPosition'], "2"); ?> id="<?php echo $this->get_field_id( 'btnPosition' ); ?>"  />
<label for="<?php echo $this->get_field_id( 'btnPosition' ); ?>"><?php _e('Fixed: Always visible on the bottom left corner', 'hybrid'); ?></label><br /><br />

<input name="<?php echo $this->get_field_name( 'btnPosition' ); ?>" class="radio" type="radio" value="3" <?php checked( $instance['btnPosition'], "3"); ?> id="<?php echo $this->get_field_id( 'btnPosition' ); ?>"  />
<label for="<?php echo $this->get_field_id( 'btnPosition' ); ?>"><?php _e('Fixed: Always visible on the top right corner', 'hybrid'); ?></label><br /><br />

<input name="<?php echo $this->get_field_name( 'btnPosition' ); ?>" class="radio" type="radio" value="4" <?php checked( $instance['btnPosition'], "4"); ?> id="<?php echo $this->get_field_id( 'btnPosition' ); ?>"  />
<label for="<?php echo $this->get_field_id( 'btnPosition' ); ?>"><?php _e('Fixed: Always visible on the top left corner', 'hybrid'); ?></label><br /><br />
		
<input name="<?php echo $this->get_field_name( 'btnPosition' ); ?>" class="radio" type="radio" value="5" <?php checked( $instance['btnPosition'], "5"); ?> id="<?php echo $this->get_field_id( 'btnPosition' ); ?>"  />
<label for="<?php echo $this->get_field_id( 'btnPosition' ); ?>"><?php _e('Flow: Displayed as a button on the sidebar', 'hybrid'); ?></label>

<br />	
<hr />
<span>Select the form factor (size) of the widget </span>
<table>

<tr>
<td>

<input class="radio" value="9" type="radio" <?php checked( $instance['ff'], '9'); ?> id="<?php echo $this->get_field_id( 'ff' ); ?>" name="<?php echo $this->get_field_name( 'ff' ); ?>" /> <label for="<?php echo $this->get_field_id( 'ff' ); ?>">XS</label>
</td>
<td>

<input class="radio" value="10" type="radio" <?php checked( $instance['ff'], '10'); ?> id="<?php echo $this->get_field_id( 'ff' ); ?>" name="<?php echo $this->get_field_name( 'ff' ); ?>" /> <label for="<?php echo $this->get_field_id( 'ff' ); ?>">S</label>
</td>
<td>
<input class="radio" value="11" type="radio" <?php checked( $instance['ff'], '11'); ?> id="<?php echo $this->get_field_id( 'ff' ); ?>" name="<?php echo $this->get_field_name( 'ff' ); ?>" /> <label for="<?php echo $this->get_field_id( 'ff' ); ?>">M</label>
</td>
<td>
</tr>
<tr>
<td>
<input class="radio" value="12" type="radio" <?php checked( $instance['ff'], '12'); ?> id="<?php echo $this->get_field_id( 'ff' ); ?>" name="<?php echo $this->get_field_name( 'ff' ); ?>" /> <label for="<?php echo $this->get_field_id( 'ff' ); ?>">L</label>
</td>
<td>
<input class="radio" value="13" type="radio" <?php checked( $instance['ff'], '13'); ?> id="<?php echo $this->get_field_id( 'ff' ); ?>" name="<?php echo $this->get_field_name( 'ff' ); ?>" /> <label for="<?php echo $this->get_field_id( 'ff' ); ?>">XL</label>
</td>
<td>
<input class="radio" value="14" type="radio" <?php checked( $instance['ff'], '14'); ?> id="<?php echo $this->get_field_id( 'ff' ); ?>" name="<?php echo $this->get_field_name( 'ff' ); ?>" /> <label for="<?php echo $this->get_field_id( 'ff' ); ?>">XXL</label>
</td>
</tr>
</table>


<br />
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Ffacebook.com%2Fflexytalk&amp;send=false&amp;layout=standard&amp;width=350&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:350px; height:35px;" allowTransparency="true"></iframe>
	<?php
	}
}

?>