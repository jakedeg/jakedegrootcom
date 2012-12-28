<?php get_header(); ?>

	<script src=<?php get_template_directory_uri(); ?>"/wp-content/themes/jdgviewport/js/jquery-1.8.2.min.js"></script>
	<script src=<?php get_template_directory_uri(); ?>"/wp-content/themes/jdgviewport/js/jquery-ui-1.8.20.custom.min.js"></script>

	<script src=<?php get_template_directory_uri(); ?>"/wp-content/themes/jdgviewport/js/jquery.liquid-slider-1.1.min.js"></script>
	<link rel="stylesheet" type="text/css" media="screen" href=<?php get_template_directory_uri(); ?>"/wp-content/themes/jdgviewport/liquid-slider-1.1.css">

    <script>
    jQuery(function(){
      jQuery('#ls1').liquidSlider({
      
                  autoHeight: false,
           slideEaseFunction: "easeOutQuint",

                  
                   autoSlide: true,
           autoSlideInterval: 8000,
           autoSlideControls: true,
       autoSlidePauseOnHover: false, 
       
       hoverArrows: false,
       hideSideArrows: true,
       continuous: false,
       
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

<div id="mid" class="index">

<!-- Liquid Slider Panels -->
      <div class="liquid-slider"  id="ls1">
			<?php query_posts(array('orderby' => 'rand', 'category_name' => featured, 'showposts' => 5)); if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				<div class="panel" id="post-<?php the_ID(); ?>" title="<?php the_title() ?>">
				<h2 class="title"></h2>
					<a href="<?php the_permalink() ?>" rel="bookmark" title="Click to view <?php the_title_attribute(); ?>">
							<?php
							$media_type = get_post_meta($post->ID, 'lead_type', true); 
							$media = get_post_meta($post->ID, 'lead_image', true);
							?>
							<img src="<?php echo $media; ?>" alt="" width="940" height="600" />
							<div class="post-title"> <?php the_title(); ?> </div> <!-- .post-title -->
							<div class="entry"> <?php the_excerpt(); ?> </div> <!-- .entry -->
                    </a>
				</div><!-- .panel -->
				
				<?php endwhile; ?>
			<?php else : ?>
			<?php endif; ?>
			
      </div>  <!-- .liquid-slider -->                        

	</div><!-- .mid -->	

<?php get_footer(); ?>