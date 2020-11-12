<?php
/**
 * Template Name: Iframe_Page
 * Template Post Type: post, page
 * This template will only display the content you entered in the page editor
 */
?>
<!DOCTYPE html>
<html <?php language_attributes();?>>
<head>
    <meta charset="<?php bloginfo('charset');?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head();?>
</head>
<body <?php body_class();?>>
    <div id="content" class="site-content">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
while (have_posts()): the_post();
    the_content();
endwhile; // End of the loop.
?>
		</main><!-- #main -->
	</div><!-- #primary -->

</div><!-- #content -->
<?php wp_footer();?>
</body>
</html>