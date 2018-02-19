
// Animazioni Produzione Lenta
jQuery().ready(function(){

  jQuery('.slow-flex-caption').hide().removeClass('hide');

  // Aspettare che le immagini siano tutte caricate
  jQuery('.flexslider').imagesLoaded(function(){
    center_flex_slider('#flexslider-1'); 
  });

  jQuery('.flexslider').bind('start', function(e, slider) {
    center_flex_slider('#flexslider-1');
  });

  jQuery(window).resizeend({
    onDragEnd : function() {
      // alla fine del ridimensionamento
      center_flex_slider('#flexslider-1');
    },
    runOnStart : false
    }
  );


  jQuery('.mobile-prev').click(function(e){
    e.preventDefault();
    jQuery('#flexslider-1').flexslider('prev');
  });
  jQuery('.mobile-next').click(function(e){
    e.preventDefault();
    jQuery('#flexslider-1').flexslider('next');
  });

});



function center_flex_slider(selector){
  var flexslider_h = jQuery(selector).outerHeight();
  jQuery(selector + ' ul.slides li').each(function(){
    var caption = jQuery(this).find('.slow-flex-caption');
    var h = caption.outerHeight();
    var top = (flexslider_h - h)/2;
    if (top < 0){
      top = 10;
      caption.css('maxHeight', (flexslider_h - 20) + 'px');
      caption.css('overflow', 'hidden');
    }
    caption.css('marginTop', top + 'px');
  });
  jQuery('.slow-flex-caption').fadeIn();
}