<?php
/**
 * @package WordPress
 * @subpackage Fast_Blog_Theme
 * @since Fast Blog 1.0
 */
?>

          <div class="clear"></div>

        </div>
        <!-- END MAIN -->

        <div class="line full"></div>

        <!-- BEGIN FOOTER -->
        <div id="footer" class="container">
          <p id="copyright">
            <?php
              if ( $footer = fastblog_get_option( 'footer' ) ):
                echo $footer;
              else:
            ?>
            &copy; <?php echo date( 'Y' ); ?> <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
            <?php endif; ?>&nbsp;&nbsp;Theme by <a target="_blank" href="http://jixu.in">jixu.in</a>
          </p>
          <?php wp_nav_menu( array
          (
            'theme_location' => 'nav-menu-footer',
            'container' => '',
            'menu_class' => '',
            'depth' => 1,
            'fallback_cb' => create_function( '', 'fastblog_nav_menu("'.fastblog_get_option( 'menu/content/footer' ).'", 1);' )
          ) ); ?>
        </div>
        <!-- END FOOTER -->

      </div>
      <!-- END INNER WRAPPER -->

    </div>
    <!-- END WRAPPER -->

    <?php wp_footer(); ?>
<div id="full" style="position: fixed; right: 20px; bottom: 20px; cursor: pointer; visibility: visible; zoom: 1; opacity: 1; "><img src="http://img13.poco.cn/mypoco/myphoto/20120529/21/1732799420120529213133025.jpg" title="返回顶部"></div>
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/news_top.js"></script>
  </body>
  <!-- END BODY -->

</html>