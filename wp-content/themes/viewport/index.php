<?php get_header(); ?>

	<div id="mid" class="index">
		
		<!-- Start slider -->
		<div class="stripViewer">
			<div class="panelContainer">
			
			<?php if (have_posts()) : ?>
		
				<?php while (have_posts()) : the_post(); ?>
			
				<div class="panel" id="post-<?php the_ID(); ?>" title="<?php the_title() ?>">
					<div class="wrapper">
						<?php
							$media_type = get_post_meta($post->ID, 'lead_type', true); 
							$media = get_post_meta($post->ID, 'lead_image', true);
							
							/* Display relevant code based on media type */
							
							if(stristr($media, '.flv') || $media_type == 'flash') {
							
								/* Grab video preview image if the user has added one */
								
								$video_preview = get_post_meta($post->ID, 'video_preview', true); ?>
								<span id="video-<?php the_ID(); ?>" class="flashvideo" style="height: 600px; width: 940px;">
									<script type="text/javascript">
									var s<?php the_ID(); ?> = new SWFObject("<?php bloginfo('template_directory'); ?>/flash/player.swf","n0","940","600","7");
									s<?php the_ID(); ?>.addParam("allowfullscreen","true");
									s<?php the_ID(); ?>.addParam("allowscriptaccess","always");
									s<?php the_ID(); ?>.addParam("wmode","transparent");
									s<?php the_ID(); ?>.addVariable("javascriptid","n0");
									s<?php the_ID(); ?>.addVariable("height","600");
									s<?php the_ID(); ?>.addVariable("width","940");
									s<?php the_ID(); ?>.addVariable("searchbar","false");
									s<?php the_ID(); ?>.addVariable("screencolor","0x000000");
									s<?php the_ID(); ?>.addVariable("overstretch","true");
									s<?php the_ID(); ?>.addVariable("showeq","false");
									s<?php the_ID(); ?>.addVariable("showicons","true");
									s<?php the_ID(); ?>.addVariable("shownavigation","true");
									s<?php the_ID(); ?>.addVariable("showstop","false");
									s<?php the_ID(); ?>.addVariable("showdigits","true");
									s<?php the_ID(); ?>.addVariable("showdownload","false");
									s<?php the_ID(); ?>.addVariable("usefullscreen","true");
									s<?php the_ID(); ?>.addVariable("autoscroll","false");
									s<?php the_ID(); ?>.addVariable("displayheight","600");
									s<?php the_ID(); ?>.addVariable("thumbsinplaylist","true");
									s<?php the_ID(); ?>.addVariable("autostart","false");
									s<?php the_ID(); ?>.addVariable("bufferlength","3");
									s<?php the_ID(); ?>.addVariable("repeat","false");
									s<?php the_ID(); ?>.addVariable("rotatetime","5");
									s<?php the_ID(); ?>.addVariable("shuffle","false");
									s<?php the_ID(); ?>.addVariable("smoothing","true");
									s<?php the_ID(); ?>.addVariable("volume","100");
									s<?php the_ID(); ?>.addVariable("enablejs","true");
									s<?php the_ID(); ?>.addVariable("linkfromdisplay","false");
									s<?php the_ID(); ?>.addVariable("linktarget","_self");
									s<?php the_ID(); ?>.addVariable("searchlink","http://search.longtail.tv/?q=");
									s<?php the_ID(); ?>.addVariable("file","<?php echo $media; ?>");
									s<?php the_ID(); ?>.addVariable("image","<?php echo $preview_image; ?>");
									s<?php the_ID(); ?>.write("video-<?php the_ID(); ?>");
									</script>
								</span>
								
							<? } elseif(stristr($media, 'iframe') || $media_type == 'iframe') {
								
								/* Insert Iframe directly, stripping slashes from links etc */
							
								echo stripslashes($media);
								
							   } else {
							   
							   	/* Else default behaviour is to display image */ ?>
							   
								<img src="<?php echo $media; ?>" alt="" width="940" height="600" />
								
							<? }
						?>
						
						<div class="post-title">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						</div>
						<div class="entry">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>
				
				<?php endwhile; ?>
				
				<div class="panel" id="nav-panel">
					<div class="wrapper">
						<img src="<?php echo get_wherenext_image(); ?>" alt="" width="940" height="600" />
						<div class="post-title">
							Where next?
						</div>
						<div class="entry">
							<span class="big"><a href="<?php bloginfo('comments_rss2_url'); ?>" class="rss-big">Recent Comments</a></span>
							<ul><li></li><?php dp_recent_comments(); ?></ul>
							<span class="big"><span class="left"><?php previous_posts_link('&laquo; Newer Entries') ?></span>
							<span class="right"><?php next_posts_link('Older Entries &raquo;') ?></span></span>
						</div>
					</div>
				</div>
				
			<?php else : ?>
		
			<?php endif; ?>
	
			</div><!-- .panelContainer -->
		</div><!--.stripViewer -->
		
	</div><!-- .mid -->
	
	<div class="stripNavL" id="stripNavL0"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/left.png" alt="Left" /></a></div>
	<div class="stripNavR" id="stripNavR0"><a href="#"><img src="<?php bloginfo('template_directory'); ?>/images/right.png" alt="Right" /></a></div>

<?php get_footer(); ?>