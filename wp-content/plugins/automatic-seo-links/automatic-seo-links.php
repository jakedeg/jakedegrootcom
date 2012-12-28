<?php
/*
Plugin Name: Automatic SEO Links
Plugin URI: http://cvs.aesinformatica.com/download/automatic-seo-links
Description: Forget to put manually your links, just choose a word and a URL and this plugin will replace all matches in the posts of your blog. You can set the title of the link, target, rel and also you can know every moment how many times a word has been changed.
Author: Emilio
Version: 1.41
Author URI: http://emilio.aesinformatica.com
*/

add_filter('the_content', 'automaticSeoLinksChange', 1);
add_action('admin_menu', 'automaticSeoLinksAddOpc'); 
register_activation_hook( __FILE__, 'automaticSeoLinksInstall' );
register_deactivation_hook (__FILE__, 'automaticSeoLinksUnInstall');

$my_table ="automatiSEOlinks";

function automaticSeoLinksAddOpc(){   
      if (function_exists('add_options_page')) {
         add_options_page('Automatic SEO Links', 'Automatic SEO Links', 8, basename(__FILE__), 'automaticSeoLinksMenu');
      }
   }

   
function automaticSeoLinksInstall(){

	global $wpdb,$my_table;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
	$table_name= $wpdb->prefix."automatiSEOlinks";	
	
	$sql = " CREATE TABLE $table_name(
		id mediumint( 9 ) NOT NULL AUTO_INCREMENT ,
		text tinytext NOT NULL ,
		url tinytext NOT NULL ,
		anchortext tinytext NOT NULL ,
		rel tinytext NOT NULL ,
		type tinytext NOT NULL ,
		visits tinytext NOT NULL ,
		PRIMARY KEY ( `id` )	
	) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
	
	$wpdb->query($sql);

   }
   
function automaticSeoLinksUnInstall(){
   
	/*global $wpdb;	
	global $my_table;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
	$table_name= $wpdb->prefix.$my_table;
	$sql = "DROP TABLE $table_name;";
	
	$wpdb->query($sql);*/
   
}

function automaticSeoLinksDeleteBD(){
	
	global $wpdb;	
	global $my_table;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
	$table_name= $wpdb->prefix.$my_table;
	$sql = "DROP TABLE $table_name;";
	
	$wpdb->query($sql);
}

   
function automaticSeoLinksMenu(){  
	
	isset($_GET['acc']) ? $_acc=$_GET['acc']:$_acc="showLinks";
	
	if($_POST['url']!=""){
		echo "<br>";
		if($_POST['id']!=""){
			if(automaticSeoLinksUpdateLink($_POST['id'],$_POST['url'],$_POST['text'],$_POST['alt'],$_POST['rel'],$_POST['type']))
				echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>Link correctly updated!</b><br/><br/></div>';
			$_acc="showLinks";
		}
		else{
			if(automaticSeoLinksNewLink($_POST['url'],$_POST['text'],$_POST['alt'],$_POST['rel'],$_POST['type']))
				echo '<div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"><b><br/>Link correctly added!</b><br/><br/></div>';
			else 
				echo '<div id="message" class="error fade" style="background-color: rgb(218, 79, 33);"><br/><b>ERROR! This word is in database!</b><br/><br/></div>';
			$_acc="addLink";
		}
	}else{
		if($_GET['acc']=="del") {
			automaticSeoLinksDeleteLink($_GET['id']); 
			echo '<br><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"> <br/>Link correctly deleted!<br/><br/></div>';
			$_acc="showLinks";
		}
		else if($_GET['acc']=="delBD"){
			automaticSeoLinksDeleteBD();
			echo '<br><div id="message" class="updated fade" style="background-color: rgb(255, 251, 204);"> <br/>BD deleted! now you can desactivated your plugin<br/><br/></div>';
			$_acc="dataBase";
		}
	}  
	
	
	
	

echo '<div class="wrap">
		<h2>Automatic SEO Links</h2>';
		
echo'<ul class="subsubsub">
		<li><a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks">Links</a> |</li>
		<li><a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=addLink">Add Link</a> |</li>
		<li><a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=dataBase">Database</a> |</li>
		<li><a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=contribute">Contribute</a> |</li>
		<li><a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=help">Help</a> </li>
	</ul>
	<br/><br/>
	
	<script>
	function deleteLink(id){
		var opc = confirm("You are going to delete this link, are you sure?");
		if (opc==true) window.location.href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=del&id="+id;
	}
	</script>
';  

if($_acc=="addLink"){

 if (automaticSeoLinksInfoDB()==false) {
		echo "<br/><b>Automatic SEO Links Table deleted</b>! now you can desactivate the plugin in plugins section";
	  }
	  else{

echo '<h3>New Link</h3>

	<fieldset>

	<form method="post" action ="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Word</label>
					</th>
					<td>
						<input type="text" name="text" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> URL</label>
					</th>
					<td>
						<input type="text" name="url" style="width:300px;" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Title</label>
					</th>
					<td>
						<input type="text" name="alt" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Target</label>
					</th>
					<td>
						<select name="type">
							<option value="0"></option>
							<option value="1">_self</option>
							<option value="2">_top</option>
							<option value="3">_blank</option>
							<option value="4">_parent</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Rel</label>
					</th>
					<td>
						<select name="rel">
							<option value="0"></option>
							<option value="1">external</option>
							<option value="2">nofollow</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>


		
		<p class="submit"><input type="submit" name="automaticseolinks" value="Add" /></p>
	</form>
	</fieldset>';
	}
	}
	
	
	else if($_acc=="edit"){

 if (automaticSeoLinksInfoDB()==false) {
		echo "<br/><b>Automatic SEO Links Table deleted</b>! now you can desactivate the plugin in plugins section";
	  }
	  else{
	  
	  $_id = $_GET['id'];
	  $_text = base64_decode($_GET['text']);
	  $_url = base64_decode($_GET['url']);
	  $_anchortext = base64_decode($_GET['anchortext']);
	  $_rel = $_GET['rel'];
	  $_type = $_GET['type'];

echo '<h3>Edit Link</h3>

	<fieldset>

	<form method="post" action ="">
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Word</label>
					</th>
					<td>
						<input type="hidden" name="id" value="'.$_id.'" /><input type="text" value="'.$_text.'" name="text" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> URL</label>
					</th>
					<td>
						<input type="text" name="url" value="'.$_url.'" style="width:300px;" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Title</label>
					</th>
					<td>
						<input type="text" value="'.$_anchortext.'" name="alt" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Target</label>
					</th>
					<td>
						<select name="type">
							<option value="0" '; if($_type == 0) echo "selected='selected'"; echo'></option>
							<option value="1" '; if($_type == 1) echo "selected='selected'"; echo'>_self</option>
							<option value="2" '; if($_type == 2) echo "selected='selected'"; echo'>_top</option>
							<option value="3" '; if($_type == 3) echo "selected='selected'"; echo'>_blank</option>
							<option value="4" '; if($_type == 4) echo "selected='selected'"; echo'>_parent</option>
						</select>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<label for="default_post_edit_rows"> Rel</label>
					</th>
					<td>
						<select name="rel">
							<option value="0" '; if($_rel == 0) echo "selected='selected'"; echo'></option>
							<option value="1" '; if($_rel == 1) echo "selected='selected'"; echo'>external</option>
							<option value="2" '; if($_rel == 2) echo "selected='selected'"; echo'>nofollow</option>
						</select>
					</td>
				</tr>
			</tbody>
		</table>


		
		<p class="submit"><input type="submit" name="automaticseolinks" value="Update" /></p>
	</form>
	</fieldset>';
	}
	}
	
	else if($_acc=="showLinks"){
	  
	   if (automaticSeoLinksInfoDB()==false) {
		echo "<br/><b>Automatic SEO Links Table deleted</b>! now you can desactivate the plugin in plugins section";
	  }
	  else{
	  
	 echo' 
	 <h3>Links </h3>
	 <table class="widefat">
		<thead>
			<tr>
				<th style="display:none" scope="col">Index</th>
				<th scope="col">Text (<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=text&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=text&order=desc">-</a>) </th>
				<th scope="col">URL (<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=url&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=url&order=desc">-</a>)</th>
				<th scope="col">Title (<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=anchortext&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=anchortext&order=desc">-</a>)</th>
				<th scope="col">Rel (<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=rel&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=rel&order=desc">-</a>)</th>
				<th scope="col">Target (<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=type&order=asc">+</a>|<a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=showLinks&orderBy=type&order=desc">-</a>)</th>
				<th scope="col">Shows</th>
				<th scope="col">Delete</th>
				<th scope="col">Edit</th>
			</tr>
		</thead>
		<tbody id="the-comment-list" class="list:comment">
			<tr id="comment-1" class="">
				';
				if($_GET['orderBy']=="") $_GET['orderBy'] = "id";
				if($_GET['order']=="") $_GET['order'] = "desc";
				automaticSeoLinksGetLinks($_GET['orderBy'],$_GET['order']);
				echo'
			</tr>
		</tbody>
		<tbody id="the-extra-comment-list" class="list:comment" style="display: none;"> </tbody>
		</table>

</div>';
}
}
else if($_acc=="dataBase"){
	  
	  if (automaticSeoLinksInfoDB()==false) {
		echo "<br/><b>Automatic SEO Links Table deleted</b>! now you can desactivate the plugin in plugins section";
	  }
	  else{
		  echo '<h3>Database</h3>For preventing you to loose your links when desactivate the plugin, Automatic SEO Links doesn\'t delete the table, 
		  if you want to completely remove this plugin from your blog, just 
		  <b><a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=delBD">click here</a></b> and then desactivate it in plugins section.<br><br>';
		  automaticSeoLinksDataBaseInfo();
	  }
   }
   
   else if($_acc=="contribute"){
	  
	  if (automaticSeoLinksInfoDB()==false) {
		echo "<br/><b>Automatic SEO Links Table deleted</b>! now you can desactivate the plugin in plugins section";
	  }
	  else{
		  echo '<h3>Contribute</h3>
		  I have recieved lots of emails with suggestions, improvements, mistakes and also thanking my job, and I appreciate your contributions but I have to admit that sometimes it took me a lot of time to reply, so that is one of the reasons I have decided to make a forum for Automatic SEO Links.
<br><br>
		  A place where all the people could know what others think and the most important, as I have no so much time
to add improvements to the plugin, I would like people help in the developing, 
so if you want a new feature in this plugin, just do it, and if it is useful and people like it, I would add it to the plugin.
		  <br><br>
		 This plugin is not just for me, is for all of us, if we all work together on this I think we could make a good job.

You have the source code and <a target="blank" href="http://foro.aesinformatica.com/automatic-seo-links/">the place</a> to share your improvements, the only limit is your imagination.
<br><br>
Of course, everyone who collaborate will appear in the plugin as an author.<br><br>
		  
		  <b><a target="blank" href="http://foro.aesinformatica.com/automatic-seo-links/">Automatic SEO Links Forum</a> </b>
		  
		  <h3>Special thanks</h3>
			I want to give thanks to Charles McRobert, Jean-Michel MEYER, Juan Mellido, Tom Gubber and all the people who wrote me thanking my job.
		  ';
	  }
   }
   
   else if($_acc=="help"){
	  
	  if (automaticSeoLinksInfoDB()==false) {
		echo "<br/><b>Automatic SEO Links Table deleted</b>! now you can desactivate the plugin in plugins section";
	  }
	  else{
		  echo '<h3>Help / F.A.Q.</h3>
		  
		  <b>How many words change this plugin?</b><br>
		  It changes one word per post, if you have 1000 post with 5 words each post it would only be replaced one word per post, so 1000 words.
		  
		  <br><br><b>Does it change the post in database?</b><br>
		  No, just change it "on fly" so nothing in database is changed.
		  
		  <br><br><b>It never fails?</b><br>
		  Oh yes, it fails, this plugin has to analyze the code before changing with regular expressions. I am not able to test all
		  the posibilities people can put into their post, so, I have just included more commons regular expressions, if you detect a mistake,
		  please tell us <a target="blank" href="http://foro.aesinformatica.com/automatic-seo-links/">in our forum</a>.
		  
		  <br><br><b>I have found a mistake, where I can go?</b>
		  <br>
		  There is <a target="blank" href="http://foro.aesinformatica.com/automatic-seo-links/">a forum</a> where you can let us know your mistake,
		  if you also share the solution, better :)
		  
		  <br><br><b>I want a new feature in the plugin, what can I do? </b><br>
		  Well, some of you has wrote me asking me for include, as an example, that the plugin replaced all the matches of a word instead of one, 
		  I\'m not going to do that because I hate post where all the words has links, but if you want, you can find more people with the same
		  problem in the forum and maybe you can do it together. Later, if people like, it could be integrated in the plugin.
		  
		  <br><br><b>Is this an Open Source project?</b><br>
		  Yes, something like that, I don\'t care people touch my code even I prefer because I know
		  that when more than one person is working on a project the result is better.
		  
		  

		  ';
	  }
   }
   
   }
   
   function automaticSeoLinksGetTarget($target){
		switch($target){
			case 0: return "-"; break;
			case 1: return "_self"; break;
			case 2: return "_top"; break;
			case 3: return "_blank"; break;
			case 4: return "_parent"; break;
		}
   }
   
   function automaticSeoLinksGetRel($rel){
		switch($rel){
			case 0: return "-"; break;
			case 1: return "external"; break;
			case 2: return "nofollow"; break;
		}
   }
   
   function automaticSeoLinksDataBaseInfo(){
   
		global $wpdb;
		global $my_table;
		
		
		$table_name= $wpdb->prefix.$my_table;
		
				$query = "select count(id) as links ,SUM(visits) as visits from $table_name ";
				$links = $wpdb->get_results($query);
				
				echo 'You have <b>'.$links[0]->links.' links</b> which had been replaced <b>'.$links[0]->visits.' times</b>.';
   }
   
   function automaticSeoLinksGetLinks($orderBy="id",$order="desc"){
   
		global $wpdb;
		global $my_table;
		
		echo '<tbody id="the-comment-list" class="list:comment">
			';
		
		$table_name= $wpdb->prefix.$my_table;
		
				$query = "select * from $table_name order by ".$orderBy." ".$order;
				$links = $wpdb->get_results($query);

				foreach($links as $link){
					echo '<tr id="comment-1" class="">';
					echo '<td style="display:none">'; echo $link->id; echo'</td>';
					echo '<td>'; echo $link->text; echo'</td>';
					echo '<td>'; echo $link->url; echo'</td>';
					echo '<td>'; echo $link->anchortext; echo'</td>';
					echo '<td>'; echo automaticSeoLinksGetRel($link->rel); echo'</td>';
					echo '<td>'; echo automaticSeoLinksGetTarget($link->type); echo'</td>';
					echo '<td>'; echo $link->visits; echo'</td>';
					echo '<td><a href="javascript:deleteLink('.$link->id.');">Delete</a></td>';	
					$_url = base64_encode($link->url);
					$_text = base64_encode($link->text);
					$_anchortext = base64_encode($link->anchortext);
					echo '<td><a href="'.$PHP_SELF.'?page=automatic-seo-links.php&acc=edit&id='.$link->id.'&text='.$_text.'&url='.$_url.'&anchortext='.$_anchortext.'&rel='.$link->rel.'&type='.$link->type.'">Edit</a></td>';
					echo '</tr>';
				}
				
		echo '</tbody>';
   }

	
function automaticSeoLinksNewLink($url,$text,$anchor_text,$rel,$type)
	{
		global $wpdb;
		global $my_table;
		
		$table_name= $wpdb->prefix . $my_table;

				$queryprev = "select `url` from $table_name where `text` = '$text'";
				$result = $wpdb->get_results($queryprev);

				if(count($result)>0) return false;
				
				$query = "INSERT INTO $table_name ( `url`, `text`, `anchortext`,`rel`,`type`,`visits` ) VALUES ";
					$query .= " (
						'".mysql_real_escape_string($url)."',
						'".mysql_real_escape_string($text)."',
						'".mysql_real_escape_string($anchor_text)."',
						'".mysql_real_escape_string($rel)."',
						'".mysql_real_escape_string($type)."',
						'0'
					),";

				$query = substr($query, 0, strlen($query)-1);
				$wpdb->query($query);
				return true;
	}
	
	function automaticSeoLinksUpdateLink($id,$url,$text,$anchor_text,$rel,$type)
	{
		global $wpdb;
		global $my_table;
		
		$table_name= $wpdb->prefix . $my_table;
				
				$query = "UPDATE $table_name set `url` = '".mysql_real_escape_string($url)."',
         				`text` = '".mysql_real_escape_string($text)."' ,
						`anchortext` = '".mysql_real_escape_string($anchor_text)."',
						`rel` = '".mysql_real_escape_string($rel)."',
						`type` = '".mysql_real_escape_string($type)."' where id ='".mysql_real_escape_string($id)."' ";

				$wpdb->query($query);
				return true;
	}
	
function automaticSeoLinksInfoDB(){
	global $wpdb;
	global $my_table;
		
		$table_name= $wpdb->prefix . $my_table;
				
		($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != "") ? $back= true : $back= false;
		return $back;

}

function automaticSeoLinksShowLink($id)
	{
		global $wpdb;
		global $my_table;
		
		$table_name= $wpdb->prefix.$my_table;
				$query = "update $table_name set `visits` = `visits`+1 where id= $id ";	
				$query = substr($query, 0, strlen($query)-1);
				$wpdb->query($query);
	}
	
function automaticSeoLinksDeleteLink($id){
	 global $wpdb;	 
	 global $my_table;
	 
	require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
		
	$table_name= $wpdb->prefix . $my_table;
	$sql = "DELETE FROM $table_name where id = $id;";
	
	$wpdb->query($sql);

}
	
/*This function is based on the Text-link-ads function, insertInLinkAd*/
	
function automaticSeoLinksChange($content='')
	{
		$text = $content;		
		global $wpdb;
		global $my_table;
		global $notAllowToChange;
		
		$table_name= $wpdb->prefix.$my_table;

		$query = "select * from $table_name";
		$links = $wpdb->get_results($query);		

		foreach($links as $link){			
				
			$find = '/'.$link->text.'/i';
				$isFind = false;

				$matches = array();
				preg_match_all($find, $content, $matches, PREG_OFFSET_CAPTURE);
				$matchData = $matches[0];
				

					$noChanges = array(
						'/<h[1-6][^>]*>[^<]*'.$link->text.'[^<]*<\/h[1-6]>/i',
						'/<a[^>]+>[^<]*'.$link->text.'[^<]*<\/a>/i',
						'/href=("|\')[^"\']+'.$link->text.'(.*)[^"\']+("|\')/i',
						'/src=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i',
						'/alt=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i',
						'/title=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i',
						'/content=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i',
						'/<script[^>]*>[^<]*'.$link->text.'[^<]*<\/script>/i',
						'/<embed[^>]+>[^<]*'.$link->text.'[^<]*<\/embed>/i',
						'/wmode=("|\')[^"\']*'.$link->text.'[^"\']*("|\')/i'
					);

					foreach($noChanges as $noChange){
						$results = array();
						preg_match_all($noChange, $content, $results, PREG_OFFSET_CAPTURE);
						$matches = $results[0];

						if(!count($matches) == 0) {
							foreach($matches as $match){
								$start = $match[1];
								$end = $match[1] + strlen($match[0]);
								foreach($matchData as $index => $data){
									if($data[1] >= $start && $data[1] <= $end){
										$matchData[$index][2] = true;
									}
								}
							}
						}		
					}

					foreach($matchData as $index => $match){
						if($match[2] != true){
							$isFind = $match;
							break;
						}
					}

				if(is_array($isFind)){
					$link->type = automaticSeoLinksGetTarget($link->type);
					$link->rel = automaticSeoLinksGetRel($link->rel);
					
					$replacement = '<a href="'.$link->url.'"';
					if ($link->type!="-") $replacement = $replacement.'target="'.$link->type.'"';
					if ($link->rel!="-") $replacement = $replacement.'rel="'.$link->rel.'"';
					$replacement =	$replacement.'title="'.$link->anchortext.'" >'.$isFind[0].'</a>';
					
					automaticSeoLinksShowLink($link->id);
					$content = substr($content, 0, $isFind[1]) . $replacement . substr($content, $isFind[1] + strlen($isFind[0]));
				}

			}


		return $content;
	}

?>
