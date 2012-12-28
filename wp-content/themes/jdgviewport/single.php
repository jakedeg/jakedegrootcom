<?php
/*
Template Name: Single
*/
?>



<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<link href="style.css" rel="stylesheet" type="text/css" />

	<div id="mid" class="single">
		<div class="panel-single" id="post-<?php the_ID(); ?>" title="<?php the_title() ?>">
			<div class="wrapper">
				<?php $image = get_post_meta($post->ID, 'single_image', true); if ($image=="") { $image = get_post_meta($post->ID, 'lead_image', true); }
				$media_type = get_post_meta($post->ID, 'lead_type', true);
				if ($media_type != 'flash') { ?>
					<img src="<?php echo $image; ?>" alt="" width="940" height="300" />
				<? } ?>
				<div class="post-title-single">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				</div> <!--.post-title-single-->
		  </div> <!--.wrapper-->
		</div> <!--.panel-single #post-->
	</div> <!--#mid .single-->
    
<div id="container">
 	<div id="credits">
    <table>
  		<tr>
    		<td>
			  <?php $key="Subtitle:"; if (get_custom_field($key)!='') { echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
			  <?php $key="By:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Translated By:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Adapted By:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Music by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Lyrics by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Music and Lyrics by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Lyrics and Book by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Book by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Music, Lyrics, and Book by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
          </td>
                  <td>
              <?php $key="Director:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Musical Director:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Choreographer:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Scenery by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Costumes by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Sound by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Video by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
                  </td>
              		<td>
              <?php $key="Producer:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Company:"; if (get_custom_field($key)!='') {echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Venue:"; if (get_custom_field($key)!='') {?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Venue 2:"; if (get_custom_field($key)!='') {?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="City:"; if (get_custom_field($key)!='') {?> <?php echo automaticSeoLinksChange(get_custom_field($key));?>, <?php } ?>
              <?php $key="State:"; if (get_custom_field($key)!='') {?> <?php echo automaticSeoLinksChange(get_custom_field($key));?> <br /> <?php } ?>
              <?php $key="Month:"; if (get_custom_field($key)!='') {?> <?php echo automaticSeoLinksChange(get_custom_field($key));?>, <?php } ?>
              <?php $key="Year:"; if (get_custom_field($key)!='') {?> <?php echo automaticSeoLinksChange(get_custom_field($key));} ?>
				</td>
      </tr>
      </table>
  </div> <!--#credits-->
  <div id ="contents">
  
   <div id="excerpt"> 
    <table width=100%>
  <tr>
    <td width=75% rowspan="2">
      <?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
      </td>
    <td align="right" class="aligntop">
	  <div class="postnav">
      <?php next_post_link('Next: %link','%title &laquo;',true) ?><br />
      <?php previous_post_link('Previous: %link','%title &raquo;',true) ?>
      </div> <!--.postnav-->  
      </td>
  </tr>
  <tr>
    <td align="right" class="alignbot">
     <div class="postnav">
	<?php the_tags( '<p>See More: ', ', ', '</p>'); ?>
    </div> <!--.postnav-->  
    </td>
  </tr>
    </table>
    </div> <!--#excerpt-->

	<div id ="gallery">
		<?php $key="Gallery ID"; if (get_custom_field($key)<1) {?> No gallery available at this time.<?php 
		} else {
			$scode='[simpleviewer gallery_id='.get_custom_field('Gallery ID').']';
            echo do_shortcode($scode);
            $key="Photos by:"; if (get_custom_field($key)!='') { echo $key; ?> <?php echo automaticSeoLinksChange(get_custom_field($key));}
            } ?>
  	</div> <!--#gallery-->

	<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>  

  </div> <!--#contents-->    
</div> <!--#container-->


<?php endwhile; else: ?>

	<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>



<div class="clearer"></div>

<?php get_footer(); ?>
