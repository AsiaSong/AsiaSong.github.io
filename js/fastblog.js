/*   
Theme Name: Fast Blog
*/

// -----------------------------------------------------------------------------

jQuery(document).ready(function() {

  // Flickr API key
  flickr_api_key = '6baa65d21a4e4139d5e2f1b4943dcb2e';
  
  // Browsers support
  ie = jQuery.browser.msie ? parseInt(jQuery.browser.version) : 256;

  // Browser notification
  if (ie <= 6) {
    jQuery('.browser-notification .close').click(function() { jQuery(this).parent().hide(); });
    jQuery('.browser-notification.ie6').show();
  }
  
  // Form
  jQuery('p:not(.input):has(> input[type="text"]), .post .content div:not(.input):has(> input[type="text"])').addClass('input');
  jQuery('p:not(.textarea):has(> textarea), .post .content div:not(.textarea):has(> textarea)').addClass('textarea');
  jQuery('p:not(.submit):has(> input[type="submit"]), .post .content div:not(.submit):has(> input[type="submit"])').replaceWith(function() {
    var title = jQuery('input', this).val();
    return '<p class="submit"><a title="'+title+'">'+title+'</a></p>';
  });
  jQuery('form .submit a').click(function() {
    jQuery(this).parentsUntil('form').last().parent().submit();
  });
  
  // Menu
  jQuery('#menu li:has(> ul) > a').append(' &rsaquo;');
 
  // Cufon font replacement
  gradient = {color: scheme.colors.gradient.primary, hover: {color: scheme.colors.gradient.secondary}};
  cufon_replace = {
    logo:         [{selector: '#logo span', options: {color: scheme.colors.gradient.secondary}}],
    tagline:      [{selector: '#tagline', options: {}}],
    menu:         [{selector: '#menu li:not(.current) > a', options: gradient},
                   {selector: '#menu li.current > a', options: {color: scheme.colors.gradient.secondary}}],
    post_title:   [{selector: '.post .title', options: gradient}],
    widget_title: [{selector: '#sidebar .title', options: {}}],
    headlines:    [{selector: '.post .content h1, .post .content h2, .post .content h3, .post .content h4', options: gradient}],
    shortcode:    [{selector: '.cufon-shortcode', options: {}}],
    other:        [{selector: 'form .submit a, .message', options: gradient}],
    custom:       [{selector: typography.custom_selector, options: {}}]
  };
  for (var element in typography.fonts) {
    for (var i in cufon_replace[element]) {
      var selector = cufon_replace[element][i].selector;
      if ( ! selector) continue;
      var options = cufon_replace[element][i].options;
      options['fontFamily'] = typography.fonts[element];
      jQuery(selector).addClass('cufon');
      Cufon.replace(selector, options);
    }
  }

  // Menu
  if (ie <= 7) {
    jQuery('#menu li ul').each(function() {
      var max_width = 0;
      jQuery('> li', this).each(function() {
        var cufon_width = 0;
        jQuery('> a > cufon', this).each(function() {
          cufon_width += parseInt(jQuery(this).css('width'));
        });
        if (cufon_width == 0) cufon_width = 150;
        max_width = Math.max(max_width, cufon_width);
      });
      jQuery('> li', this).css('width', (max_width+20)+'px');
    });
  }
  
  // Search
  jQuery('#search input[name="s"]').focus(function(){
    if (jQuery(this).val() == fastblog.search) jQuery(this).val('');
  }).blur(function() {
    if (jQuery(this).val() == '') jQuery(this).val(fastblog.search);
  });
  
  // Post
  var posts = jQuery('.post');
  if (ie >= 9) jQuery('.post-icon > *', posts).fadeTo(0, 0).css('display', 'block');
  posts.hover(function() {
    var icon = jQuery('.corner, .post-icon > *', this);
    if (ie >= 9) icon.stop(true).fadeTo('fast', 1); else icon.css('display', 'block');
  }, function() {
    var icon = jQuery('.corner, .post-icon > *', this);
    if (ie >= 9) icon.stop(true).fadeTo('fast', 0); else icon.hide();
  });
  
  // Comments
  jQuery('.comment').hover(function() {
    jQuery('.tools', this).css('visibility', 'visible');
  }, function() {
    jQuery('.tools', this).css('visibility', 'hidden');
  });

  // Twitter and Flickr widgets
  jQuery('.widget_twitter, .widget_flickr').each(function() {
    // Title
    if (ie >= 9) jQuery('.title a', this).fadeTo(0, 0).css('display', 'block'); 
    jQuery('.title', this).hover(function() {
      var a = jQuery('a', this);
      if (ie >= 9) a.stop(true).fadeTo('fast', 1); else a.css('display', 'block');
    }, function() {
      var a = jQuery('a', this);
      if (ie >= 9) a.stop(true).fadeTo('fast', 0); else a.hide();
    });
    // Getting data
    if (jQuery(this).is(':has(.username):has(.count):has(.data)')) {
      var username = jQuery('.username', this).text();
      var count = parseInt(jQuery('.count', this).text());
      if (jQuery(this).hasClass('widget_twitter')) {
        jQuery.getJSON(
          'http://twitter.com/statuses/user_timeline/'+username+'.json?count='+count+'&callback=?',
          function(data) {
            jQuery.each(data, function(i, tweet) {
              var created_at = new Date(tweet.created_at).toLocaleString();
              var html = tweet.text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/ig, function(matches) {
                return '<a href="'+matches+'">'+matches+'</a>';
              }).replace(/\B@([_a-z0-9]+)/ig, function(matches) {
                return '<a href="http://twitter.com/'+matches.substring(1)+'/">'+matches.substring(0)+'</a>';
              }).replace(/\B#([_a-z0-9]+)/ig, function(matches) {
                return '<a href="http://twitter.com/#!/search?q=%23'+matches.substring(1)+'">'+matches.substring(0)+'</a>';
              });
              jQuery(".widget_twitter:has(.username:contains('"+tweet.user.screen_name+"')) .data").append('<p>'+html+'<br /><small>'+created_at+'</small></p>');
            });
          });
      }
      else if (jQuery(this).hasClass('widget_flickr')) {
        jQuery.getJSON(
          'http://api.flickr.com/services/rest/?api_key='+flickr_api_key+'&method=flickr.people.getPublicPhotos&format=json&user_id='+username+'&per_page='+count+'&jsoncallback=?',
          function(data) {
            if (data.stat != 'ok') return;
            jQuery.each(data.photos.photo, function(i, photo) {
              var img_class = i < 2 ? 'class="top" ' : '';
              jQuery(".widget_flickr:has(.username:contains('"+photo.owner+"')) .data").append('<a href="http://www.flickr.com/photos/'+photo.owner+'/'+photo.id+'" title="'+photo.title+'"><img src="http://farm'+photo.farm+'.static.flickr.com/'+photo.server+'/'+photo.id+'_'+photo.secret+'_s.jpg" alt="" width="75" height="75" '+img_class+'/></a>');
            });
          });
      }
    }
  });

  // Contact form
  jQuery('.contact-form').submit(function() {
    if (jQuery('.submit', this).hasClass('disabled')) return false;
    jQuery('.submit', this).addClass('disabled').fadeTo('normal', 0.4);
    jQuery('.status', this).text('');
    jQuery('.loader', this).fadeIn('normal');
    jQuery.post(jQuery(this).attr('action'), jQuery(this).serialize(), function(data) {
      contact_form = jQuery('.contact-form');
      if (data.result) jQuery('input[type!=hidden], textarea', contact_form).val('');
      jQuery('.submit', contact_form).removeClass('disabled').fadeTo('normal', 1);
      jQuery('.status', contact_form).text(data.message);
      jQuery('.loader', contact_form).fadeOut('normal');
    }, 'json');
    return false;
  });
  
  // Newsletter Sign-Up
  jQuery('.nsu_widget form label').remove();
  
  // Fancybox
  if (fancybox.enabled) {
    jQuery('a.fancybox, .post .content a:has(img)').filter(function () {
      return jQuery(this).hasClass('fancybox') || /\.(jpe?g|png|gif|bmp)$/i.test(jQuery(this).attr('href'));
    }).attr('rel', function() {
      return 'fancybox-'+jQuery(this).parentsUntil('.post, #sidebar > li').last().parent().attr('id');
    }).attr('title', function() {
      var img = jQuery('img', this);
      if ( ! jQuery(this).attr('title') && img.length > 0) {
        return img.attr('title') ? img.attr('title') : img.attr('alt');
      }
      else {
        return jQuery(this).attr('title');
      }
    }).each(function() {
      var options = {titleShow: fancybox.show_title, showNavArrows: true};
      if (jQuery(this).is('[href^="http://www.youtube.com/"]')) {
    	jQuery(this).attr('href', jQuery(this).attr('href').replace('/watch?v=', '/v/'));
        jQuery.extend(options, {type: 'swf', swf: {allowfullscreen: 'true', wmode: 'transparent'}});
      }
      jQuery(this).fancybox(options);	
    });
  }
  
});