<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>><head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php bloginfo('name'); ?> <?php if ( is_single() ) { ?> &raquo; Projects <?php } ?> <?php wp_title(); ?></title>

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />


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
 <div id="headerimg">
   <h1>
    <a href="<?php echo get_option('home'); ?>">
       <span><?php bloginfo('name'); ?></span>
       </a>
   </h1>
  </div> <!--#headerimg -->
	<ul class="nav">
		<li class="left"></li>
        <?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary-nav-menu' ) ); ?>
<!--		<li><div class="subscribe"><a href="<?php bloginfo('rss2_url'); ?>">Subscribe</a></div></li>
-->		<li class="right"></li>
	</ul>
	<ul id="searchbox">
		<li><?php include (TEMPLATEPATH . '/searchform.php'); ?></li>
	</ul>







</div> <!--#header -->
