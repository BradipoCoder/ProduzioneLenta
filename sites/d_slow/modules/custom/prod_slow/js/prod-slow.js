
// Animazioni Prod Slow
jQuery().ready(function(){
  prod_slow_arm_toggle();
});

function prod_slow_arm_toggle(){
  var button = jQuery('#btn-filter-toggle');
  var content = jQuery('.prodslow-filter-content-toggle');
  var text = jQuery('#text-toggle');

  jQuery(button).click(function(){
    content.toggleClass('open');

    if (content.hasClass('open')){
      text.html('Nascondi');  
    } else {
      text.html('Mostra');
    }
    
  });
}