jQuery(document).ready(function() {
  
  
  jQuery.getJSON('tweets', function(data) {
  
    jQuery.each(data.tweets, function(index, tweet) {
      jQuery('.tweets').append(tweet);
    });
    
    var delay = 7000;
    var animation = 1000;
    var count = jQuery('.tweets .tweet').length;
    var total = count * (animation + delay + animation);
    fadeTweets(delay, animation);
    setInterval(function() {
      fadeTweets(delay, animation);
    }, total);
  
  });
  
  function fadeTweets(delay, animation) {
    jQuery('.tweets .tweet').each(function(n) {
       jQuery(this).delay(n * (delay + animation + animation)).fadeIn(animation).delay(delay).fadeOut(animation);
    });
  }
  
});