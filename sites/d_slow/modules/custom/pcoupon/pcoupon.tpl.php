<div class="row">
  <div class="wrapper-print-coupon">
    <div class="container margin-t-2">
      <div class="card card-coupon">
        <div class="content margin-v-1">
          <div class="head text-center">
            <?php print render($content['logo']); ?>
            <h1 class="margin-b-05"><?php print render($content['discount_title']); ?></h1>
            <p>Codice sconto: <strong><?php print render($content['code']); ?></strong></p>
          </div>
          <div class="margin-v-1 coupon-small-description">
            <?php print render($content['desc']); ?>  
          </div>
          <div class="info text-center small">
            <p><strong>www.produzionelenta.it</strong> | <strong>info@produzionelenta.it</strong> | <strong>347.9643971</strong></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="wrapper-help hide-in-print">
  <div class="row">
    <div class="container container-coupon-help">
      <div class="text-max-width">
        <div class="wrapper-print-button text-center margin-b-2">
          <a href="#" id="print-coupon" class="btn btn-primary"><i class="fa fa-print"></i> Stampa</a>
          <a href="<?php print render($content['clip_url']); ?>" data-clipboard-text="<?php print render($content['clip_url']); ?>" id="clip-coupon" class="btn btn-primary"><i class="fa fa-share"></i> Copia link</a>
        </div>
        <?php print render($content['help']); ?>
      </div>
    </div>
  </div>
</div>