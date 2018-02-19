<?php

/**
 * Post del blog FULL
 */
?>

<?php
  // We hide the comments and links now so that we can render them later.
  hide($content['comments']);
  hide($content['links']);
  hide($content['field_date']);
  hide($content['field_ref_cat_news']);
  hide($content['field_ref_tag']);
  hide($content['pager']);
  hide($content['sidebar']);
  hide($content['field_ref_coll']);
?>


<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> node-full margin-b-4 clearfix"<?php print $attributes; ?>>
  <div class="container">
    <div class="row row-with-margin-top">
      <div class="col-md-7 col-lg-7">
        <?php print render($title_prefix); ?>
        <?php print render($title_suffix); ?>

        <p class="news-header margin-b-1">
          <span class="small"><?php print format_date(strtotime($node->field_date['und'][0]['value']), 'short'); ?></span> &nbsp;
          <span class="h5"><?php print $content['field_ref_cat_news'][0]['#markup']; ?></span>
        </p>

        <?php print render($content['title_field']); ?>
        
        <hr class="mid">

        <div class="margin-b-1">

          <?php print render($content); ?>

          <?php if (isset($node->field_ref_tag['und'][0]['tid'])) : ?>
            <hr>
            <?php print render($content['field_ref_tag']); ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-lg-4 col-lg-offset-1 col-md-5 col-md-offset-0">
        <div class="margin-md-l">
          <?php print render($content['field_ref_coll']); ?>
          <?php print render($content['sidebar']); ?>
        </div>
      </div>
    </div>
    <hr class="margin-t-0">
    <?php print render($content['pager']); ?>
  </div>
</div>