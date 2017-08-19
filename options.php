<?php
/**
 * @package WordPress
 * @subpackage Fast_Blog_Theme
 * @since Fast Blog 1.0
 */
?>

<div class="wrap">

  <!-- BEGIN HEADER -->
  <?php screen_icon( 'themes' ); ?>
  <h2><?php _e( '主题设置', 'fastblog' ); ?></h2>
  <?php if ( ! FASTBLOG_TUMBLOG ): ?>
    <div id="message" class="error"><p><?php _e( "<strong>WooTumblog</strong>插件未开启，某些设置将不会得到其应有的效果。", 'fastblog' ); ?></p></div>
  <?php endif; ?>
  <?php if ( isset( $_REQUEST['updated'] ) && ( $_REQUEST['updated'] == 'true' ) ): ?>
    <div id="message" class="updated"><p><?php _e( '设置已保存。' ); ?></p></div>
  <?php endif; ?>
  <!-- END HEADER -->

  <!-- BEGIN UPDATE -->
  <?php tb_options_update( FASTBLOG_UPDATE_URL, fastblog_get_option( '_update_data' ), 'fastblog_theme_update', 'fastblog' ); ?>
  <!-- END UPDATE -->

  <form method="post" action="options.php">

    <?php settings_fields( 'fastblog_options' ); ?>

    <!-- BEGIN APPEARANCE OPTIONS -->
    <?php
      tb_options_open_section( __( '外貌', 'fastblog' ) );
      foreach ( tb_get_directory( TEMPLATEPATH.'/schemes' ) as $filename )
      {
        $schemes[$filename] = str_replace( array( '_', '-' ), array( ' ', ' + ' ), ucfirst( $filename ) );
      }
      tb_options_field( __( '配色方案', 'fastblog' ), '', 'select', 'fastblog[scheme]', fastblog_get_option( 'scheme' ), $schemes );
      tb_options_field( __( 'ICO图标路径', 'fastblog' ), '', 'input_code', 'fastblog[favicon]', fastblog_get_option( 'favicon' ), '('.__( '空=默认', 'fastblog' ).')' );
      tb_options_field( __( '侧边栏的位置' ), '', 'radio_group', 'fastblog[sidebar]', fastblog_get_option( 'sidebar' ), array
      (
        'left' => __( '左边', 'fastblog' ),
        'right' => __( '右边', 'fastblog' )
      ) );
      tb_options_close_section();
    ?>
    <!-- END APPEARANCE OPTIONS -->

    <!-- BEGIN HEADER OPTIONS -->
    <?php
      tb_options_open_section( __( '页头', 'fastblog' ) );
      tb_options_open_field( '高度' );
      tb_options_input_number( 'fastblog[header][height]', fastblog_get_option( 'header/height' ), '', 3 ); echo ' px';
      tb_options_description( '('.__( '默认', 'fastblog' ).' = 65px)' );
      tb_options_close_field();
      tb_options_field( __( 'LOGO的文字或图像路径', 'fastblog' ), '', 'input_code', 'fastblog[header][logo]', fastblog_get_option( 'header/logo' ), '('.__( '空=默认', 'fastblog' ).')' );
      tb_options_field( __( '页头副标题', 'fastblog' ), '', 'checkbox', "fastblog[tagline]", fastblog_get_option( 'tagline' ), __( '显示副标题', 'fastblog' ) );
      tb_options_field( __( '页头广告位（建议468*60）', 'fastblog' ), '', 'textarea_code', 'fastblog[header][contact]', fastblog_get_option( 'header/contact' ) );
      tb_options_close_section();
    ?>
    <!-- END HEADER OPTIONS -->

    <!-- BEGIN MENU OPTIONS -->
    <?php
      tb_options_open_section( __( '菜单', 'fastblog' ) );
      $menu_content_array = array
      (
        'pages' => __( '页面', 'fastblog' ),
        'categories' => __( '分类', 'fastblog' )
      );
      if ( FASTBLOG_TUMBLOG && FASTBLOG_TUMBLOG_CONTENT_TAXONOMY )
      {
        $menu_content_array['tumblog'] = __( 'Tumblogs', 'fastblog' );
      }
      foreach ( array
      (
        'main' => __( '主导航菜单内容', 'fastblog' ),
        'footer' => __( '页脚导航菜单的内容', 'fastblog' )
      ) as $menu => $label )
      {
        tb_options_field( $label, __( '选择您的导航菜单需要显示的菜单。', 'fastblog' ), 'radio_group', "fastblog[menu][content][{$menu}]", fastblog_get_option( 'menu/content/'.$menu ), $menu_content_array );
      }
      tb_options_field( __( '搜索', 'fastblog' ), '', 'checkbox', "fastblog[search]", fastblog_get_option( 'search' ), __( '在主导航菜单中显示搜索表单', 'fastblog' ) );
      tb_options_close_section();
    ?>
    <!-- END MENU OPTIONS -->

    <!-- BEGIN CONTENT OPTIONS -->
    <?php
      tb_options_open_section( __( '内容', 'fastblog' ) );
      tb_options_open_field( __( '图片', 'fastblog' ) );
      tb_options_checkbox( "fastblog[fancybox][enabled]", fastblog_get_option( 'fancybox/enabled' ), __( '使用Fancybox显示图像', 'fastblog' ) );
      echo '<div class="indent-1">';
      tb_options_checkbox( "fastblog[fancybox][show_title]", fastblog_get_option( 'fancybox/show_title' ), __( '显示图片标题', 'fastblog' ) );
      echo '</div>';
      tb_options_close_field();
      tb_options_close_section();
    ?>
    <!-- END CONTENT OPTIONS -->

    <!-- BEGIN POST OPTIONS -->
    <?php
      tb_options_open_section( __( '文章', 'fastblog' ) );
      tb_options_open_field( __( '设置', 'fastblog' ) );
      tb_options_checkbox( "fastblog[post][hide_title]", fastblog_get_option( 'post/hide_title' ), __( "不显示文章标题后的页面", 'fastblog' ) );
      tb_options_checkbox( "fastblog[post][about]", fastblog_get_option( 'post/about' ), __( '下面文章的作者详情', 'fastblog' ) );
      tb_options_close_field();
      tb_options_field( __( '文章<meta>', 'fastblog' ), '', 'checkbox_group', "fastblog[post][meta]", fastblog_get_option( 'post/meta' ), array
      (
        'date' => __( '日期', 'fastblog' ),
        'category' => __( '文章分类', 'fastblog' ),
        'comments' => __( '评论', 'fastblog' ),
        'author' => __( '作者', 'fastblog' ),
        'short_url' => __( '短网址', 'fastblog' ),
        'admin_edit' => __( '管理编辑链接', 'fastblog' )
      ) );
      tb_options_field( __( '短网址', 'fastblog' ), '', 'checkbox', "fastblog[post][disable_short_url]", fastblog_get_option( 'post/disable_short_url' ), __( "如果此处是关闭的，不要创建短网址。", 'fastblog' ) );
      tb_options_close_section();
    ?>
    <!-- END POST OPTIONS -->

    <!-- BEGIN PAGE OPTIONS -->
    <?php
      tb_options_open_section( __( '页', 'fastblog' ) );
      tb_options_field( __( '设置', 'fastblog' ), '', 'checkbox', "fastblog[page][hide_title]", fastblog_get_option( 'page/hide_title' ), __( "不显示页面标题", 'fastblog' ) );
      tb_options_field( __( '页<meta>', 'fastblog' ), '', 'checkbox_group', "fastblog[page][meta]", fastblog_get_option( 'page/meta' ), array
      (
        'date' => __( '修改日期', 'fastblog' ),
        'comments' => __( '评论', 'fastblog' ),
        'admin_edit' => __( '管理编辑链接', 'fastblog' )
      ) );
      tb_options_close_section();
    ?>
    <!-- END PAGE OPTIONS -->

    <!-- BEGIN CONTACT FORM OPTIONS -->
    <?php
      tb_options_open_section( __( '联系表格', 'fastblog' ) );
      tb_options_field( __( '收件人电子邮件地址', 'fastblog' ), '', 'input_code', 'fastblog[contact_form][email]', fastblog_get_option( 'contact_form/email' ) );
      tb_options_open_field( __( '邮箱主题', 'fastblog' ) );
      tb_options_input( 'fastblog[contact_form][subject]', fastblog_get_option( 'contact_form/subject' ) );
      echo '<table class="clear">';
      foreach ( array
      (
        'blogname' => __( '博客名称', 'fastblog' ),
        'blogurl' => __( '博客链接', 'fastblog' ),
        'name' => __( '名称字段', 'fastblog' ),
        'email' => __( '邮箱字段', 'fastblog' ),
        'subject' => __( '标题栏', 'fastblog' )
      ) as $variable => $label )
      {
        ?>
        <tr>
          <td><span class="description"><code>%<?php echo $variable; ?>%</code></span></td>
          <td><span class="description"><?php echo $label; ?></span></td>
        </tr>
        <?php
      }
      echo '</table>';
      tb_options_close_field();
      tb_options_field( __( '设置', 'fastblog' ), '', 'checkbox', 'fastblog[contact_form][from_header]', fastblog_get_option( 'contact_form/from_header' ), __( '覆盖名称字段', 'fastblog' ) );
      tb_options_close_section();
    ?>
    <!-- END CONTACT FORM OPTIONS -->

    <!-- BEGIN ADVANCED OPTIONS -->
    <?php
      tb_options_open_section( __( '高级', 'fastblog' ) );
      tb_options_field( __( '自定义CSS代码', 'fastblog' ), '', 'textarea_code', 'fastblog[custom_css]', fastblog_get_option( 'custom_css' ) );
      tb_options_field( __( '自定义js代码', 'fastblog' ), '', 'textarea_code', 'fastblog[custom_js]', fastblog_get_option( 'custom_js' ) );
      tb_options_field( __( 'Bit.ly登陆', 'fastblog' ), '', 'input_code', 'fastblog[bitly][login]', fastblog_get_option( 'bitly/login' ), '('.__( '空白=默认内置', 'fastblog' ).')' );
      tb_options_field( __( 'Bit.ly API密钥', 'fastblog' ), '', 'input_code', 'fastblog[bitly][api_key]', fastblog_get_option( 'bitly/api_key' ), '('.__( '空白=默认内置', 'fastblog' ).')' );
      tb_options_close_section();
    ?>
    <!-- END ADVANCED OPTIONS -->

    <!-- BEGIN OTHER OPTIONS -->
    <?php
      tb_options_open_section( __( '其他', 'fastblog' ) );
      tb_options_field( __( '页脚文本', 'fastblog' ), '', 'input_code', 'fastblog[footer]', fastblog_get_option( 'footer' ), '('.__( '留空=auto参数', 'fastblog' ).')' );
      tb_options_field( __( '替代feed网址', 'fastblog' ), __( '例如Feedburner。', 'fastblog' ), 'input_code', 'fastblog[feed]', fastblog_get_option( 'feed' ) );
      tb_options_field( __( '谷歌统计代码', 'fastblog' ), __( '或其他统计代码。', 'fastblog' ), 'textarea_code', 'fastblog[analytics]', fastblog_get_option( 'analytics' ) );
      tb_options_close_section();
    ?>
    <!-- END OTHER OPTIONS -->

    <!-- BEGIN SUBMIT -->
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e( '保存更改' ); ?>" />
    </p>
    <!-- END SUBMIT -->

    <!-- BEGIN FIXED SUBMIT -->
    <div class="submit-fixed">
      <input type="button" class="button-secondary button-tab" value="<?php _e( '全部展开', 'fastblog' ); ?>" />
      <input type="button" class="button-secondary button-tab" value="<?php _e( '关闭全部', 'fastblog' ); ?>" />
      <input type="submit" class="button-primary" value="<?php _e( '保存更改' ); ?>" />
    </div>
    <!-- END FIXED SUBMIT -->

  </form>

</div>