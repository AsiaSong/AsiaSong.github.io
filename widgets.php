<?php
/**
 * @package WordPress
 * @subpackage Fast_Blog_Theme
 * @since Fast Blog 1.0
 */

// -----------------------------------------------------------------------------

class FastBlog_Twitter extends WP_Widget
{

  // ---------------------------------------------------------------------------

  function FastBlog_Twitter()
  {
    $this->WP_Widget
    (
      'fastblog_twitter',
      'Fast Blog | '.__('Twitter', 'fastblog'),
      array
      (
        'description' => __('Twitter stream.', 'fastblog'),
        'classname' => 'widget_twitter'
      )
    );
  }

  // ---------------------------------------------------------------------------

  function widget($args, $instance)
  {
    if ( ! $instance['username']) return;
    $options = get_option('fastblog_widgets');
    if ( ! is_array($options))
    {
      $options = array();
    }
    $username_hash = md5($instance['username']);
    if
    (
      isset($options['twitter'][$username_hash]) &&
      ($options['twitter'][$username_hash]['last_update'] >= $instance['time']) &&
      ($options['twitter'][$username_hash]['last_update']+$instance['interval']*60 > time())
    )
    {
      $tweets = $options['twitter'][$username_hash]['data'];
    }
    else
    {
      if (($tweets = tb_twitter_get_tweets($instance['username'], $instance['count'], TRUE)) !== FALSE)
      {
        $options['twitter'][$username_hash] = array
        (
          'last_update' => time(),
          'data' => $tweets
        );
        update_option('fastblog_widgets', $options);
      }
      else
      {
        $tweets =
          "<div class=\"username\">{$instance['username']}</div>".
          "<div class=\"count\">{$instance['count']}</div>".
          '<div class="data"></div>';
      }
    }
    extract($args);
    echo $before_widget;
    echo $before_title.'<a href="http://twitter.com/'.$instance['username'].'/" title="'.__('Follow me!', 'fastblog').'">'.__('Twitter', 'fastblog').'</a>'.$after_title;
    if (is_array($tweets))
    {
      foreach ($tweets as $tweet)
      {
        echo
          '<p>'.
            $tweet['html'].'<br />'.
            '<small>'.human_time_diff($tweet['created_at']).' '.__('ago', 'fastblog').'</small>'.
          '</p>';
      }
    }
    else
    {
      echo $tweets;
    }
    echo $after_widget;
  }

  // ---------------------------------------------------------------------------

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['username'] = strip_tags($new_instance['username']);
    $instance['count'] = tb_range($new_instance['count'], 1, 20);
    $instance['interval'] = tb_range($new_instance['interval'], 1);
    $instance['time'] = time();
    return $instance;
  }

  // ---------------------------------------------------------------------------

  function form($instance)
  {
    $instance = wp_parse_args((array)$instance, array
    (
      'username' => '',
      'count' => 5,
      'interval' => 10
    ));
    ?>
      <p><?php tb_widgets_input( $this, 'username', $instance['username'], __( 'Username', 'fastblog' ) ); ?></p>
      <p><?php tb_widgets_input( $this, 'count', $instance['count'], __( 'Tweets count', 'fastblog' ), '', 'widefat', 2 ); ?></p>
      <p><?php tb_widgets_input( $this, 'interval', $instance['interval'], __( 'Update interval', 'fastblog' ), __( 'Tweets receiving interval (in minutes).', 'fastblog' ), 'widefat', 6 ); ?></p>
    <?php
  }

}

// -----------------------------------------------------------------------------

class FastBlog_Flickr extends WP_Widget
{

  // ---------------------------------------------------------------------------

  function FastBlog_Flickr()
  {
    $this->WP_Widget
    (
      'fastblog_flickr',
      'Fast Blog | '.__('Flickr', 'fastblog'),
      array
      (
        'description' => __('Flickr photo stream.', 'fastblog'),
        'classname' => 'widget_flickr'
      )
    );
  }

  // ---------------------------------------------------------------------------

  function widget($args, $instance)
  {
    if ( ! $instance['username']) return;
    $options = get_option('fastblog_widgets');
    if ( ! is_array($options))
    {
      $options = array();
    }
    $username_hash = md5($instance['username']);
    if
    (
      isset($options['flickr'][$username_hash]) &&
      ($options['flickr'][$username_hash]['last_update'] >= $instance['time']) &&
      ($options['flickr'][$username_hash]['last_update']+$instance['interval']*60 > time())
    )
    {
      extract($options['flickr'][$username_hash]['data']);
    }
    else
    {
      if
      (
        (($userdata = tb_flickr_get_userdata(FASTBLOG_FLICKR_API_KEY, $instance['username'])) === FALSE) ||
        (($photos = tb_flickr_get_photos(FASTBLOG_FLICKR_API_KEY, $userdata['id'], $instance['count'])) === FALSE)
      )
      {
        return;
      }
      $options['flickr'][$username_hash] = array
      (
        'last_update' => time(),
        'data' => array('userdata' => $userdata, 'photos' => $photos)
      );
      update_option('fastblog_widgets', $options);
    }
    extract($args);
    echo $before_widget;
    echo $before_title.'<a href="'.$userdata['url'].'" title="'.__('My photos', 'fastblog').'">'.__('Flickr', 'fastblog').'</a>'.$after_title;
    foreach ($photos as $num => $photo)
    {
      echo
        '<a href="'.$photo['url'].'" title="'.$photo['title'].'">'.
          '<img src="'.$photo['thumbnail'].'" alt="" width="75" height="75"'.($num < 2 ? ' class="top"' : '').' />'.
        '</a>';
    }
    echo $after_widget;
  }

  // ---------------------------------------------------------------------------

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['username'] = strip_tags($new_instance['username']);
    $instance['count'] = tb_range($new_instance['count'], 1, 100);
    $instance['interval'] = tb_range($new_instance['interval'], 1);
    $instance['time'] = time();
    return $instance;
  }

  // ---------------------------------------------------------------------------

  function form($instance)
  {
    $instance = wp_parse_args((array)$instance, array
    (
      'username' => '',
      'count' => 5,
      'interval' => 10
    ));
    ?>
      <p><?php tb_widgets_input( $this, 'username', $instance['username'], __( 'Username', 'fastblog' ) ); ?></p>
      <p><?php tb_widgets_input( $this, 'count', $instance['count'], __( 'Photos count', 'fastblog' ), '', 'widefat', 2 ); ?></p>
      <p><?php tb_widgets_input( $this, 'interval', $instance['interval'], __( 'Update interval', 'fastblog' ), __( 'Photos receiving interval (in minutes).', 'fastblog' ), 'widefat', 6 ); ?></p>
    <?php
  }

}

// -----------------------------------------------------------------------------

class FastBlog_Socialmedia extends WP_Widget
{

  // ---------------------------------------------------------------------------

  var $icons_count = 6;

  // ---------------------------------------------------------------------------

  function get_socialmedia($instance, $id)
  {
    return array
    (
      'icon' => isset($instance["socialmedia_{$id}_icon"]) ? $instance["socialmedia_{$id}_icon"] : '',
      'url' => isset($instance["socialmedia_{$id}_url"]) ? $instance["socialmedia_{$id}_url"] : ''
    );
  }

  // ---------------------------------------------------------------------------

  function FastBlog_Socialmedia()
  {
    $this->WP_Widget
    (
      'fastblog_socialmedia',
      'Fast Blog | '.__('Social media', 'fastblog'),
      array
      (
        'description' => __('Social media icons.', 'fastblog'),
        'classname' => 'widget_socialmedia'
      )
    );
  }

  // ---------------------------------------------------------------------------

  function widget($args, $instance)
  {
    extract($args);
    echo $before_widget;
    if ($instance['title'])
    {
      echo $before_title.$instance['title'].$after_title;
    }
    echo '<div>';
    for ($i = 0; $i < $this->icons_count; $i++)
    {
      extract($this->get_socialmedia($instance, $i));
      $class = $i < 6 ? 'top' : '';
      if ($i % 6 == 0) $class .= ' first';
      if ($icon && $url)
      {
        echo
          '<a href="'.$url.'" title="'.str_replace('-', ' ', ucfirst($icon)).'">'.
            '<img src="'.get_bloginfo('template_directory').'/images/socialmedia/'.$icon.'.png" alt="" width="24" height="24" class="'.trim($class).'" />'.
          '</a>';
      }
      else
      {
        echo '<div class="'.trim($class.' empty').'"></div>';
      }
    }
    echo '</div>';
    echo $after_widget;
  }

  // ---------------------------------------------------------------------------

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = trim(strip_tags($new_instance['title']));
    for ($i = 0; $i < $this->icons_count; $i++)
    {
      extract($this->get_socialmedia($new_instance, $i));
      $instance["socialmedia_{$i}_icon"] = $icon;
      $instance["socialmedia_{$i}_url"] = trim($url);
    }
    return $instance;
  }

  // ---------------------------------------------------------------------------

  function form($instance)
  {
    $socialmedia_options[''] = '('.__('None', 'fastblog').')';
    foreach (tb_get_directory(TEMPLATEPATH.'/images/socialmedia', 'png', TRUE) as $filename)
    {
      $socialmedia_options[$filename] = str_replace('-', ' ', ucfirst($filename));
    }
    echo '<p>';
    tb_widgets_input($this, 'title', isset($instance['title']) ? $instance['title'] : '', __('Title', 'fastblog'));
    echo '</p>';
    for ($i = 0; $i < $this->icons_count; $i++)
    {
      extract($this->get_socialmedia($instance, $i));
      echo '<p style="margin-bottom: 5px;">';
      tb_widgets_select($this, "socialmedia_{$i}_icon", $icon, $socialmedia_options, ($i+1).'. '.__('Icon & URL', 'fastblog'));
      echo '</p><p>';
      tb_widgets_input($this, "socialmedia_{$i}_url", $url);
      echo '</p>';
    }
  }

}

// -----------------------------------------------------------------------------

class FastBlog_SchemeSwitcher extends WP_Widget
{

  // ---------------------------------------------------------------------------

  function FastBlog_SchemeSwitcher()
  {
    $this->WP_Widget
    (
      'fastblog_schemeswitcher',
      'Fast Blog | '.__('Scheme switcher', 'fastblog'),
      array
      (
        'description' => __("Allows visitors to change site's color scheme.", 'fastblog'),
        'classname' => 'widget_schemeswitcher'
      )
    );
  }

  // ---------------------------------------------------------------------------

  function widget($args, $instance)
  {
    global $fastblog_schemes;
    extract($args);
    echo $before_widget;
    if ($instance['title'])
    {
      echo $before_title.$instance['title'].$after_title;
    }
    echo '<div>';
    foreach ($fastblog_schemes as $num => $scheme)
    {
      $class = $num < 6 ? 'top' : '';
      if ($num % 6 == 0) $class .= ' first';
      echo
        '<a href="?scheme='.$scheme.'" title="'.str_replace('-', ' + ', ucfirst($scheme)).'" class="'.trim($class).'" style="background-position: -'.($num*24).'px 0;"></a>';
    }
    echo '</div>';
    echo $after_widget;
  }

  // ---------------------------------------------------------------------------

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = trim(strip_tags($new_instance['title']));
    return $instance;
  }

  // ---------------------------------------------------------------------------

  function form($instance)
  {
    $instance = wp_parse_args((array)$instance, array
    (
      'title' => ''
    ));
    ?>
    <p><?php tb_widgets_input( $this, 'title', $instance['title'], __( 'Title', 'fastblog' ) ); ?></p>
    <?php
  }

}