<?php
/**
 * Node Product Child
 */
?>

<?php
  hide($content['links']);
?>

<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?>"<?php print $attributes; ?>>

  <?php print render($title_prefix); ?>
  <?php print render($title_suffix); ?>

  <div class="node-content"<?php print $content_attributes; ?>>
    <div class="card same-h">
      <div class="img-overlay"<?php print $content_attributes; ?>>
        <a href="<?php print $node_url; ?>" title="<?php print $title; ?>">

          <?php print render($content['field_img']); ?>
          <?php print render($content['off']); ?>
          
          <span class="img-overlay__hover">
            <span class="img-overlay__content negative">
              <span class="img-overlay__title"><span class="btn btn-primary"><?php print $btn; ?></span></span>
            </span>
          </span>

        </a>
      </div>
      <?php print render($content); ?>
    </div>
  </div>
</div>