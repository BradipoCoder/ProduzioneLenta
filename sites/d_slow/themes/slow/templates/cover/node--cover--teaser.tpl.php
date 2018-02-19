<?php
/**
 * Node Cover Teaser
 */
?>

<?php
  hide($content['links']);
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>
    <?php print render($title_prefix); ?>
    <?php print render($title_suffix); ?>

    <div class="node-content"<?php print $content_attributes; ?>>
      <div class="margin-v-4 container negative">
        <div class="row">
          <div class="col-md-4 col-md-offset-6">
            <?php print render($content); ?>
          </div>
      </div>
    </div>
  </div>
</div>