<?php
/*
Template Name: Page
*/
?>

<?php get_header(); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div id="container">
    
        <div class="panel-simple">
                <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
        </div> <!-- .panel-simple -->
            
    	<div id ="contents">
        	<div id="page-body">	
				<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
					
				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>


<?php endwhile; else: ?>

	<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

            </div> <!-- #page-body -->
		</div> <!-- #contents -->
    </div> <!-- #container -->

<div class="clearer"></div>

<?php get_footer(); ?>
