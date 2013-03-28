jQuery(document).ready(function() {
  
  jQuery("#respond_song_list").tokenInput('songs');
  
  jQuery('#photos .carousel').carouFredSel({
    width: '100%',
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
  
});