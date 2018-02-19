<?php

/**
 * Post del blog Child
 */

?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>" <?php print $attributes; ?>>
  <?php print render($title_prefix); ?>
  <?php print render($title_suffix); ?>
  <div class="node-content card card-no-padding"<?php print $content_attributes; ?>>
    <div class="hidden-sm hidden-md hidden-lg">  
      <?php print render($content['data']['field_img']); ?>
    </div>
    <?php print render($content); ?>
  </div>
</div>