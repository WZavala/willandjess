jQuery(document).ready(function() {
  
  jQuery("#respond_song_list").tokenInput('songs');
  
  jQuery('#photos .carousel').carouFredSel({
    width: '100%',
    transition: true,
    items: {
      visible: 3,
      start: -1
    },
    scroll: {
      items: 1,
      duration: 500,
      timeoutDuration: 3000
    },
    auto: {
      play: false
    },
    prev: '#photos .prev',
    next: '#photos .next'
  });
  
  jQuery('.page-people .carousel-wrapper .carousel').carouFredSel({
    transition: true,
    direction: 'up',
    items: 1,
    scroll: {
      fx: 'directscroll'
    },
    auto: {
      play: false
    },
    pagination: {
      container: function() {
        return jQuery(this).parents(".carousel-wrapper").find(".pager");
      },
      anchorBuilder: function( nr ) {
        return '<div class="thumb thumb' + nr + '"><a href="#"><img src="' + this.src + '" alt="" title="' + this.title + '" /></a></div>';
      },
      onBefore: function( map ) {
        
      var parent = jQuery(this).parents('.page-people');
    
    	jQuery('.info .'+jQuery(map.items.old).attr('class'), parent).animate({
    	  opacity: 'hide',
        height: 'hide'
      }, 'slow');
    	
    	jQuery('.info .'+jQuery(map.items.visible).attr('class'), parent).animate({
          opacity: 'show',
          height: 'show'
        }, 'slow');
        
      }
      
    }
  });

  
  // Hendle the From Submit
  jQuery('form.rsvp').on('submit' , function(event) {
  
    // Do not Redirect
    event.preventDefault();
    
    // Disable the Submit Button
    jQuery('input[type="submit"]', this).attr('disabled', 'disabled');
    
    // Remove the errors
    jQuery('.error', this).remove();
    
    // Serialzie the data
    var data = jQuery(this).serialize();
    
    // Get the POST url
    var url = jQuery(this).attr('action');
    
    // Set this to a variable
    var element = jQuery(this);
    
    // Submit the Data
    jQuery.post(url, data, function(data) {
      
      // Deal with any errors
      if (data.errors) {
            
        jQuery.each(data.errors, function(index, error) {
          jQuery('#'+error.id).before('<div class="error"><ul><li>'+error.text+'</li></ul></div>');
        });
        
        jQuery.scrollTo('#rsvp');
        
        // Make the submit button work again
        jQuery('input[type="submit"]', element).removeAttr('disabled');
        
        return;
        
      }
      
      // Change the title
      jQuery('#rsvp .title').text(data.title);
      
      // Add the returned HTML
      jQuery(element).before('<div class="body" style="display:none;">'+data.content+'</div>');
      
      // Hide the Form
      jQuery(element).animate({
        opacity: 'hide',
        height: 'hide'
      }, 'slow');
      
      jQuery.scrollTo('#rsvp');
      
      // Show the Form
      jQuery('#rsvp .body').animate({
        opacity: 'show',
        height: 'show'
      }, 'slow');
            
    });
    
    
  });
  
});