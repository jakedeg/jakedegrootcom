<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Blog Archive <?php } ?> <?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if(is_home() || is_category() || is_archive()) : ?>
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery-1.2.1.pack.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery-easing.1.2.pack.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery-easing-compatibility.1.2.pack.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/coda-slider.1.1.1.js" type="text/javascript"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/swfobject.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(window).bind("load", function() {
			jQuery("div#mid").codaSlider()
		});
	</script>
<?php endif; ?>

<?php if(get_background()!="") : ?>
<style type="text/css">
	body {
		background: <?php echo get_background(); ?>;
	}
</style>
<?php endif; ?>

<?php wp_head(); ?>
</head>
<body>
<div id="page">

<div id="header">
	<h1><a href="<?php echo get_option('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
	<ul id="nav">
		<li class="left"></li>
		<?php wp_list_pages('title_li='); ?>
		<li><div class="subscribe"><a href="<?php bloginfo('rss2_url'); ?>">Subscribe</a></div></li>
		<li class="right"></li>
	</ul>
	<ul id="searchbox">
		<li><?php include (TEMPLATEPATH . '/searchform.php'); ?></li>
	</ul>
</div>
