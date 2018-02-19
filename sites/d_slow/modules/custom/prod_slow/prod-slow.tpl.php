<div class="wrapper-shop">
  <div class="prodslow-header">
    <h3 class="margin-t-0">I prodotti Produzione Lenta</h3>
    <div class="prodslow-filter-toggle hidden-md hidden-lg margin-b-05">
    <a id="btn-filter-toggle" href="#" class="btn btn-default"><span id="text-toggle">Mostra</span> filtri <i class="fa fa-filter"></i></a>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <div class="prodslow-filter-content-toggle">
        <?php print render($content['filter']); ?>
      </div>
      
    </div>
    <div class="col-md-9">
      <div class="wrapper-nodes margin-b-2">
        <?php print render($content['nodes']); ?>
      </div>
    </div>
  </div>
  <?php print render($content['pager']); ?>
</div>