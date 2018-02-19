<?php

/**
 * Node News All
 */
?>

<?php
  // We hide the comments and links now so that we can render them later.
  hide($content['comments']);
  hide($content['links']);
  hide($content['sidebar']);
  hide($content['children']);
  hide($content['field_short']);
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php print render($title_suffix); ?>

  <div class="node-content margin-b-2"<?php print $content_attributes; ?>>
    <?php print render($content['title_field']); ?>
  
    <div class="row">
      <div class="col-md-7 col-md-offset-1 col-lg-7">
        <?php print render($content); ?>
      </div>
      <div class="col-md-4 col-lg-3 col-lg-offset-1">
        <?php print render($content['sidebar']); ?>
      </div>
    </div>
  </div>
</div>