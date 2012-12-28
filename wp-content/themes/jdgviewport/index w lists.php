<?php get_header(); ?>

	<div id="mid" class="index">
		
			<?php $c=1; ?>
        
		<!-- Start slider -->
		<div class="coda-slider-wrapper">

			<div class="coda-slider preload" id="coda-slider-1">
			<?php query_posts(array('orderby' => 'rand', 'category_name' => featured, 'showposts' => 5)); if (have_posts()) : while (have_posts()) : the_post(); ?>
			
				<div class="panel" id="post-<?php the_ID(); ?>" title="<?php the_title() ?>">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="Click to view <?php the_title_attribute(); ?>">
                    <div class="panel-wrapper">
							   <?php
							$media_type = get_post_meta($post->ID, 'lead_type', true); 
							$media = get_post_meta($post->ID, 'lead_image', true);
							?>
								<img src="<?php echo $media; ?>" alt="" width="940" height="600" />
						
						<div class="post-title">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="Click to view <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						</div>
						<div class="entry">
							<?php the_excerpt(); ?>
						</div> <!-- .entry -->
					</div><!-- .panel-wrapper -->
                    </a>
                    
                    		<?php $title_arr[$c] = get_post_meta($post->ID, 'Project Name:', true) ; ?>
                            <?php $plink_arr[$c] = get_permalink();?>
                            <?php $att_arr[$c] = get_post_meta($post->ID, 'Project Name:', true) ;?>
                            
                            <?php $c++; ?>

				</div><!-- .panel -->
				
				<?php endwhile; ?>
	
			<?php else : ?>
		
			<?php endif; ?>
	
			</div><!-- .coda-slider preload -->    
    	</div><!--.coda-slider-wrapper -->
        
                                <script type="text/javascript"> 
        jQuery(document).ready(function($){
                                $().ready(function() {
                                        $('#coda-slider-1').codaSlider({
											autoSlide:true,
											autoSlideInterval: 8000,
											autoSlideStopWhenClicked: true,
											dynamicArrows: false,
											dynamicTabs: true,
											dynamicTabsPosition: "bottom",
											});
                                });
        });
                        </script> 
                        
       	<div id="container">
    
        	<div class="conents">
            <div id="page-body">
            	<table width="100%">
                  <tr>
                    <td>
                    	<h2>Featured Projects:</h2>
                          <ul>
                             <li><a href="<?php echo $plink_arr["1"] ?>" rel="bookmark" title="Click to view <?php echo $att_arr["1"]; ?>"><?php echo $title_arr["1"]; ?></a></li>
                             <li><a href="<?php echo $plink_arr["2"] ?>" rel="bookmark" title="Click to view <?php echo $att_arr["2"]; ?>"><?php echo $title_arr["2"]; ?></a></li>
                             <li><a href="<?php echo $plink_arr["3"] ?>" rel="bookmark" title="Click to view <?php echo $att_arr["3"]; ?>"><?php echo $title_arr["3"]; ?></a></li>
                             <li><a href="<?php echo $plink_arr["4"] ?>" rel="bookmark" title="Click to view <?php echo $att_arr["4"]; ?>"><?php echo $title_arr["4"]; ?></a></li>
                             <li><a href="<?php echo $plink_arr["5"] ?>" rel="bookmark" title="Click to view <?php echo $att_arr["5"]; ?>"><?php echo $title_arr["5"]; ?></a></li>
                          </ul>
                   	</td>
                    <td align="right">
                    	<h2>Recent Credits:</h2>
                        <ul>
                        <?php if(function_exists("kalinsPost_show")){kalinsPost_show("recent-credits");} ?>
                    	</ul>
                    </td>
                  </tr>
                </table>
            </div> <!--#page-body -->

    	</div> <!--.contents -->
    </div> <!--#container -->
	</div><!-- .mid -->

                        
    <div class="coda-nav-left" id="coda-nav-left-1"><a href="#" title="Slide left"><img src="<?php bloginfo('template_directory'); ?>/images/left.png" alt="Left" /></a></div>
	<div class="coda-nav-right" id="coda-nav-right-1"><a href="#" title="Slide right"><img src="<?php bloginfo('template_directory'); ?>/images/right.png" alt="Right" /></a></div>

		

<?php get_footer(); ?>