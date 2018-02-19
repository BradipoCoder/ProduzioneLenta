<?php
/**
 * Node Product Full
 *
 */
?>

<?php
  hide($content['links']);
  hide($content['product:field_ref_size']);
  hide($content['related']);
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php print render($title_suffix); ?>

  <div class="node-content"<?php print $content_attributes; ?>>
    <div class="row">
      <div class="col-sm-6 col-md-4 margin-b-1">
        <?php print render($content['product:field_img']); ?>
      </div>
      <div class="col-sm-6 col-md-4 margin-b-1">
        <div class="margin-sm-l-2">
          <?php print render($content['family']); ?>
          <?php print render($content['title_field']); ?>
          <?php print render($content['product:commerce_price']); ?>
          <?php print render($content['colors']); ?>
          <?php print render($content['field_desc']); ?>
          <?php print render($content['social']); ?>
          <?php //print render($content['product:field_ref_size']); ?>
        </div>
      </div>
      <div class="col-sm-12 col-md-4 margin-b-1">
        <div class="margin-md-l-2">
          <div class="card card-lg card-no-margin">
            <h4 class="margin-t-0">Personalizza</h4>
            <?php print render($content); ?>
          </div>
        </div>
      </div>
    </div>
    <?php print render($content['related']); ?>
  </div>

</div>