// Stampa coupon

jQuery().ready(function(){
  armPrintButton();
});

function armPrintButton(){
  jQuery('#print-coupon').click(function(e){
    e.preventDefault();
    window.print();   
  });

  jQuery('#clip-coupon').click(function(e){
    e.preventDefault();
    alert('Link copiato negli appunti: usalo per convidere il tuo buono regalo.');
  });
  
  new Clipboard('#clip-coupon');
}