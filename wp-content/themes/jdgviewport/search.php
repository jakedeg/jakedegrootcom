<?php get_header(); ?>

<?php global $query_string; ?>
<?php query_posts( $query_string . '&cat=-23' ); ?>

	<script src=<?php get_template_directory_uri(); ?>"/wp-content/themes/jdgviewport/js/jquery-1.8.2.min.js"></script>
	<script src=<?php get_template_directory_uri(); ?>"/wp-content/themes/jdgviewport/js/jquery-ui-1.8.20.custom.min.js"></script>

	<script src=<?php get_template_directory_uri(); ?>"/wp-content/themes/jdgviewport/js/jquery.liquid-slider-1.1.min.js"></script>
	<link rel="stylesheet" type="text/css" media="screen" href=<?php get_template_directory_uri(); ?>"/wp-content/themes/jdgviewport/liquid-slider-1.1.css">

    <script>
    jQuery(function(){
      jQuery('#ls2').liquidSlider({
      
                  autoHeight: true,
           slideEaseFunction: "easeOutQuint",


                  continuous: false,

               dynamicArrows: true,
      dynamicArrowsGraphical: true,
        dynamicArrowLeftText: "&#171; left",
       dynamicArrowRightText: "right &#187;",
              hideSideArrows: true,
      hideSideArrowsDuration: 750,
                 hoverArrows: false,
          hoverArrowDuration: 250,

       
                 dynamicTabs: true,
            dynamicTabsAlign: "center",
         dynamicTabsPosition: "bottom",
          panelTitleSelector: "h2.title",
                  crossLinks: true,
                     
          keyboardNavigation: true,

        hideArrowsWhenMobile: false,   
      
          });
      
    });
    
    </script>  
    
<div id="mid" class="archive">



<!-- Liquid Slider Panels -->
      <div class="liquid-slider"  id="ls2">
		<?php $c=0; ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php if ($c==0) : ?> <div class="panel"> <h2 class="title"></h2> <?php endif; ?>
				
				<div class="wrapper-archive">
					<?php $image = get_post_meta($post->ID, 'archive_image', true); if ($image=="") { $image = get_post_meta($post->ID, 'lead_image', true); } ?>
					<img src="<?php echo $image; ?>" alt="" width="175" height="230" />
				
                	<div class="post-title">
               		<a href="<?php the_permalink() ?>" rel="bookmark" title="Click to view <?php the_title_attribute(); ?>"><span><?php the_title(); ?></span></a>
					</div> <!--.post-title -->
                    
                </div> <!-- .wrapper-archive -->
                
				<?php $c++; if ($c==8){ $c=0; ?>
                </div> <!-- .panel -->
				<?php } endwhile; ?>
				<?php if ($c==0) { ?>
                <div class="panel">
				<?php	} ?>
						<div class="more-button">
							<?php previous_posts_link('&laquo; Newer Results') ?>
							<br/><br/>
							<?php next_posts_link('Older Results &raquo;') ?>
						</div> <!-- .more-button -->
                </div> <!-- .panel -->

			<?php else : ?>
		
			<?php endif; ?>   
		</div><!--.ls2-wrapper -->
</div><!-- .mid -->
	
    <div id="archive-filter">
    <?php
    global $wp_query;
    $total_results = $wp_query->found_posts;
    echo nl2br("\n\n\n");echo $total_results; echo nl2br(" Results Found")
    ?>
	</div>
    



<?php get_footer(); ?>

