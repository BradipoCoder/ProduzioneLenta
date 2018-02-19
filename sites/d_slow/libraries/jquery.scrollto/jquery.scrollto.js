/**
 * Scroll To 
 */

jQuery().ready(function(){
  jQuery('.scroll-to').click(function(e){
    e.preventDefault();
    var anchor = jQuery(this).attr('href');

    toolbar = jQuery('#toolbar').outerHeight();
    navbar = jQuery('#navbar').outerHeight();
    if (jQuery(anchor).length == 1 ){
      jQuery('html, body').stop().animate({
        scrollTop: jQuery(anchor).offset().top - navbar - toolbar + 1
      }, 1000, 'easeOutQuad', function(){
        //alla fine dell'animazione
      });
    }
  });
});