<?php
/**
 * @package WordPress
 * @subpackage Fast_Blog_Theme
 * @since Fast Blog 1.0
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

  <!-- BEGIN HEAD -->
  <head>
    <meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
    <meta name="description" content="<?php bloginfo( 'description' ); ?>" />
    <?php get_template_part( 'og', 'header' ); ?>
    <title><?php wp_title( '&laquo;', TRUE, 'right' ); ?><?php bloginfo( 'name' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/schemes/<?php fastblog_option( 'scheme' ); ?>/style.css" />
    <!--[if lt IE 8]>
    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/ie7.css" type="text/css" media="screen" />
    <![endif]-->
    <?php get_template_part( 'style', 'header' ); ?>
    <?php if ( $favicon = fastblog_get_option( 'favicon' ) ): ?>
      <link rel="icon" type="image/png" href="<?php echo $favicon; ?>" />
    <?php else: ?>
      <link rel="icon" type="image/png" href="<?php bloginfo( 'stylesheet_directory' ); ?>/schemes/<?php fastblog_option( 'scheme' ); ?>/images/favicon.png" />
    <?php endif; ?>
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php
      get_template_part( 'script', 'header' );
      $template_dir = get_bloginfo( 'template_directory' );
      $fonts = array_unique( tb_array_content( array_values( fastblog_get_option( 'typography/fonts' ) ) ) );
      wp_enqueue_script( 'jquery' );
      if (FASTBLOG_DEV_VERSION)
      {
        wp_enqueue_script( 'cufon',    $template_dir.'/js/cufon-yui.js' );
        wp_enqueue_script( 'fancybox', $template_dir.'/js/jquery.fancybox-1.3.4.pack.js' );
      }
      else
      {
        wp_enqueue_script( '3thpart', $template_dir.'/js/3thpart.min.js' );
      }
      foreach ( $fonts as $font )
      {
        list( $filename, $fontfamily ) = explode( '|', $font, 2 );
        wp_enqueue_script( tb_code_name( $fontfamily ), $template_dir.'/js/fonts/'.$filename );
      }
      wp_enqueue_script( 'fastblog', $template_dir.'/js/fastblog'.( ! FASTBLOG_DEV_VERSION ? '.min' : '').'.js' );
      wp_localize_script( 'fastblog', 'fastblog', array
      (
        'templateDirectory' => get_bloginfo( 'template_directory' ),
        'search' => __( 'search', 'fastblog' )
      ) );
      fastblog_wp_head();
    ?>
  </head>
  <!-- END HEAD -->

  <!-- 0CC1099409F2EFFA3E09912CDA637023 -->

  <!-- BEGIN BODY -->
  <body <?php body_class( FASTBLOG_TUMBLOG ? 'tumblog' : NULL ); ?>>

    <!-- BEGIN BROWSER NOTIFICATION -->
    <div class="browser-notification ie6">
      <div class="text">
        <?php _e( 'Your browser (Internet Explorer 6) is <strong>out of date</strong>. It has known <strong>security flaws</strong> and may <strong>not display all features</strong> of this and other websites. <a href="http://www.browser-update.org/update.html">Learn how to update your browser</a>.', 'fastblog' ); ?>
      </div>
      <div class="close">X</div>
    </div>
    <!-- END BROWSER NOTIFICATION -->

    <!-- BEGIN WRAPPER -->
    <div id="wrapper">

      <!-- BEGIN INNER WRAPPER -->
      <div id="wrapper-inner">

        <!-- BEGIN HEADER -->
        <div id="header" class="container">

          <!-- BEGIN LOGO -->
          <h1 id="logo">
            <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home">
              <?php if ( preg_match( '#^https?://.+\.(png|jpg|jpeg|gif|bmp)$#i', $logo = fastblog_get_option( 'header/logo' ) ) ): ?>
                <img src="<?php echo $logo; ?>" alt="" />
              <?php elseif ( ! $logo ): ?>
                <img src="<?php bloginfo( 'stylesheet_directory' ); ?>/schemes/<?php fastblog_option( 'scheme' ); ?>/images/logo.png" alt="" />
              <?php else: ?>
                <span><?php echo $logo; ?></span>
              <?php endif; ?>
            </a>
          </h1>
          <!-- END LOGO -->

          <?php if ( fastblog_get_option( 'tagline' ) ): ?>
            <!-- BEGIN TAGLINE -->
            <p id="tagline"><?php bloginfo( 'description' ); ?></p>
            <!-- END TAGLINE -->
          <?php endif; ?>

          <!-- BEGIN CONTACT -->
          <div id="contact">
            <div><?php echo tb_newlines_html( fastblog_get_option( 'header/contact' ) ); ?></div>
          </div>
          <!-- END CONTACT -->

        </div>
        <!-- END HEADER -->

        <div class="line full"></div>

        <!-- BEGIN MENU -->
        <div id="menu" class="container">
          <?php if ( fastblog_get_option( 'search' ) ): ?>
            <form action="<?php echo home_url( '/' ); ?>" method="get">
              <div id="search" class="input">
                <input type="text" name="s" value="<?php _e( 'search', 'fastblog' ); ?>" />
                <input type="submit" value="" />
              </div>
            </form>
          <?php endif; ?>
          <?php wp_nav_menu( array
          (
            'theme_location' => 'nav-menu-main',
            'container' => '',
            'menu_class' => '',
            'fallback_cb' => create_function( '', 'fastblog_nav_menu("'.fastblog_get_option( 'menu/content/main' ).'");' )
          ) ); ?>

        </div>
        <!-- END MENU -->

        <div class="line full"></div>

        <!-- BEGIN MAIN -->
        <div id="main" class="container">