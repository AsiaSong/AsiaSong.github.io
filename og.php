<?php
/**
 * @package WordPress
 * @subpackage Fast_Blog_Theme
 * @since Fast Blog 1.3
 */
?>

<?php if ( have_posts() ): the_post(); ?>
  <meta property="og:description" content="<?php echo strip_tags( wp_trim_excerpt( '' ) ); ?>" />
<?php endif; rewind_posts(); ?>