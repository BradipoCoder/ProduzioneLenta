<?php
/**
 * Page
 * page.tpl.php
 */
?>

<header id="navbar" role="banner" class="<?php print $navbar_classes; ?>">
  <div class="container-fluid">
    <div class="navbar-header">
      <?php if ($logo): ?>
        <a class="logo navbar-btn pull-left" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
          <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
        </a>
      <?php endif; ?>

      <?php if (!empty($site_name)): ?>
        <a class="name navbar-brand txt-brand <?php print $navbar_brand_classes; ?>" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
          <span class="site-title"><?php print $site_name; ?></span>
          <?php if (!empty($site_slogan)) : ?>
            <br/><span class="site-slogan small"><?php print $site_slogan; ?></span>
          <?php endif; ?>
        </a>
      <?php endif; ?>

      <span class="slow-cart-mobile hidden-md hidden-lg">
        <?php print render($page['cart']); ?>
      </span>

      <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>

    <?php if (!empty($primary_nav)): ?>
      <div class="navbar-collapse collapse">
        <nav role="navigation">

          <ul class="nav navbar-nav navbar-left navbar-countdown">
            <?php print render($page['countdown']); ?>
          </ul>

          <?php if (!empty($primary_nav)): ?>
            <ul class="nav navbar-nav navbar-right navbar-mainmenu">
              <?php print render($primary_nav); ?>
              <li class="slow-cart hidden-xs hidden-sm">
                <?php print render($page['cart']); ?>
              </li>
            </ul>
          <?php endif; ?>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</header>

<?php if (!empty($page['wide_top'])): ?>
  <div class="wrapper-top container-fluid">
    <div class="row">
      <?php print render($page['wide_top']); ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($page['wide_parallax'])): ?>
  <div class="wrapper-parallax container-fluid">
    <div class="row">
      <?php print render($page['wide_parallax']); ?>
    </div>
  </div>
<?php endif; ?>

<div class="wrapper-header-colored clearfix <?php print $whc_class; ?>" data-bleed="1" data-parallax="scroll" data-image-src="<?php print $page['parallax_src']; ?>">
  <?php print render($page['hover']); ?>
  <div class="container-fluid">
    <?php if (!empty($breadcrumb)): print $breadcrumb; endif;?>
    <?php if (!empty($messages)) : ?>
      <div class="messages">
        <?php print $messages; ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($tabs)): ?>
      <?php print render($tabs); ?>
    <?php endif; ?>

    <?php if (!empty($page['help'])): ?>
      <?php print render($page['help']); ?>
    <?php endif; ?>

    <?php if (!empty($action_links)): ?>
      <ul class="action-links"><?php print render($action_links); ?></ul>
    <?php endif; ?>

    <?php print render($page['head']); ?>
  </div>
</div>

<div class="wrapper-content">
  <div class="main-container container-fluid">
    <a id="main-content"></a>
    <?php if (isset($page['card'])) : ?>
      <div class="row">
        <div class="col-md-6 col-md-offset-3">
          <?php print render($page['card']); ?>
        </div>
      </div>
    <?php endif; ?> 
    <?php print render($page['content']); ?>
  </div>
</div>

<?php if (!empty($page['bottom'])): ?>
  <div class="bottom">
    <div class="container">
      <?php print render($page['bottom']); ?>
    </div>
  </div>
<?php endif; ?>

<?php if (!empty($page['wide_bottom'])): ?>
  <div class="wrapper-bottom container-fluid">
    <div class="row">
      <?php print render($page['wide_bottom']); ?>
    </div>
  </div>
<?php endif; ?>

<div class="wrapper-footer">
  <footer class="footer container-fluid negative">
    <?php print render($page['footer']); ?>
  </footer>
</div>
