<?php
/**
 * Questo file tpl è stato impostato per posizionare il blocco in un container fluido (100% della larghezza) Es. Wide top
 */
?>

<div class="wrapper-scroll-home wrapper-scroll-home-node-<?php print $nid; ?> <?php print $class; ?>" style="background: url(<?php print $url; ?>) no-repeat center center fixed; ">
  <div class="container container-scroll-home-node-<?php print $nid; ?>">
    <a id="scroll-home-anchor-<?php print $nid; ?>" class="scroll-home-anchor scroll-home-anchor-n-<?php print $n; ?>"></a>
    <div class="scroll-home-node scroll-home-node-<?php print $nid; ?> negative">
      <?php print render($node); ?>
    </div>
    <div class="scroll-home-separator"></div>
  </div>
</div>