<?php
/**
 * Node Gallery Teaser
 *
 * Hook suggestions examples:
 *
 *  node--child.tpl.php
 *  node--child--1.tpl.php
 *  node--type.tpl.php
 *  node--type--1.tpl.php
 *  node--type--child.tpl.php
 *  node--type--child--1.tpl.php
 *
 */
?>

<?php
  hide($content['links']);
  hide($content['title_field']);
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php print render($title_suffix); ?>

  <div class="node-content negative"<?php print $content_attributes; ?>>
    <div class="row">
      <div class="col-md-8">
        <div class="margin-md-r-2">
          <?php print render($content['field_imgs']); ?> 
        </div>
      </div>
      <div class="col-md-4">
        <h3>#produzionelenta</h3>
        <h1>Condividi l'amore per il tuo territorio!</h1>
        <?php print render($content); ?> 
      </div>
    </div>
    
  </div>

</div>