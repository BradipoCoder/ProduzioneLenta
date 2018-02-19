<?php

/**
 * @file
 * content.php
 */

function _slow_use_emotional_title(&$vars){
  $node = $vars['node'];
  if (isset($node->field_title['und'][0]['value']) && $node->field_title['und'][0]['value']!== ''){
    $em_title = $node->field_title['und'][0]['value'];
    $em_title = '<h3 class="text-center margin-b-1">' . l($em_title, 'node/' . $vars['nid']) . '</h3>';
    $vars['content']['title_field'][0]['#markup'] = $em_title;
  }
}

function _slow_add_children_vertical_margin(&$vars){
  if (isset($vars['content']['children'])){
    $vars['content']['children'] = array(
      '#prefix' => '<div class="wrapper-children margin-b-2">',
      '#suffix' => '</div>',
      'data' => $vars['content']['children'],
      '#weight' => $vars['content']['children']['#weight'],
    );
  }
}

function _slow_add_button(&$vars){
  if (isset($vars['content']['field_button'][0]['#markup']) && $vars['content']['field_button'][0]['#markup'] !== ''){
    $opt = array(
      'attributes' => array(
        'class' => array('btn', 'btn-primary'),
      ),
    );
    $txt = $vars['content']['field_button'][0]['#markup'];
    $vars['content']['field_button'] = array(
      '#prefix' => '<div class="wrapper-button text-center margin-b-1">',
      '#suffix' => '</div>',
      '#markup' => l ($txt, 'node/' . $vars['nid'], $opt),
      '#weight' => 15,
    );
  }
}

/**
 * Add family description
 */
function _slow_add_desc(&$vars){
  $node = $vars['node'];
  $vars['content']['labels'] = array(
    '#prefix' => '<h5 class="text-center">',
    '#suffix' => '</h5>',
    '#markup' => 'Uomo - Donna - Bambino',
    '#weight' => 4,
  );

  if ($node->nid == 108 || $node->nid == 71){
    $vars['content']['labels']['#markup'] =  'Uomo - Donna'; 
  }
}

/** 
 * Product
 */
function _product_alter_details(&$vars){
  $node = $vars['node'];

  $vars['content']['details'] = array(
    '#prefix' => '<div class="wrapper-product-details row">',
    '#suffix' => '</div>',
    '#weight' => 7,
    'left' => array(
      '#prefix' => '<div class="col-md-7 margin-b-025">',
      '#suffix' => '</div>',
    ),
    'right' => array(
      '#prefix' => '<div class="col-md-5 text-right">',
      '#suffix' => '</div>',
    ),
  );

  // COLORI
  _product_display_max_colors($vars);

  if (isset($vars['content']['colors'])){
    unset($vars['content']['colors']['title']);
    $vars['content']['details']['left']['colors'] = $vars['content']['colors'];  
  }
  $vars['content']['field_ref_colors']['#printed'] = TRUE;
  $vars['content']['colors']['#printed'] = TRUE;

  if (isset($node->field_ref_prod['und'][0]['product_id'])){
    $pid = $node->field_ref_prod['und'][0]['product_id'];
    $product = commerce_product_load($pid);
    if ($product->status == '1'){
      if (isset($product->commerce_price['und'][0]['amount'])){
        $p = $product->commerce_price['und'][0];
        $price = commerce_currency_format($p['amount'], $p['currency_code']);
        $price = '<span class="price">' . $price . '</span>';
        $vars['content']['details']['right']['price']['#prefix'] = '<p class="details-price text-right">';
        $vars['content']['details']['right']['price']['#suffix'] = '</p>';
        $vars['content']['details']['right']['price']['#markup'] = $price;
      } else {
        drupal_set_message('Il primo prodotto è disabilitato');
      }
    }
  }

  //if (isset($node->field_ref_size['und'][0])){
  //  $vars['content']['details']['right']['title']['#markup'] = '<h5>Taglie</h5>';
  //  $vars['content']['details']['right']['sizes'] = $vars['content']['field_ref_size'];
  //  $vars['content']['field_ref_size']['#printed'] = TRUE;
  //}
}

function _product_display_colors(&$vars, $link = false){
  $node = $vars['node'];
  if ($vars['colors']){
    $cs = $vars['colors'];
  }
  if(isset($cs)){
    foreach ($cs as $k => $value) {
      $cc[$k] = '<span class="color-circle" style="background: ' . $value['color'] . '"></span>';
      if ($link){
        $opt = array(
          'html' => true,
          'query' => array(
            'id' => $k,
          ),
          'attributes' => array(
            'data-toggle' => 'tooltip',
            'data-placement' => 'bottom',
            'title' => $value['name'],
          ),
        );
        $cc[$k] = l(render($cc[$k]), 'node/' . $node->nid, $opt);  
      }
    }
    $vars['content']['colors']['title']['#markup'] = '<h5>Colori</h5>';
    $vars['content']['colors']['cc']['#prefix'] = '<div class="wrapper-cc">';
    $vars['content']['colors']['cc']['#suffix'] = '</div>';
    $vars['content']['colors']['cc']['#markup'] = implode($cc,'');
    $vars['content']['field_ref_colors']['#printed'] = TRUE;
  }
}

function _product_display_max_colors(&$vars, $max = 4){
  $node = $vars['node'];
  if ($vars['colors']){
    $cs = $vars['colors'];
  }
  if(isset($cs)){
    $n = 0;
    foreach ($cs as $k => $value) {
      $n++;
      $cc[$k] = '<span class="color-circle" style="background: ' . $value['color'] . '"></span>';
      if ($n == $max){
        $cc['more'] = '<span class="color-circle color-circle-more"><span>+</span></span>';
        break;
      }
    }
    $vars['content']['colors']['title']['#markup'] = '<h5>Colori</h5>';
    $vars['content']['colors']['cc']['#prefix'] = '<div class="wrapper-cc">';
    $vars['content']['colors']['cc']['#suffix'] = '</div>';
    $vars['content']['colors']['cc']['#markup'] = implode($cc,'');
    $vars['content']['field_ref_colors']['#printed'] = TRUE;
  }  
}

function _product_alter_dimension(&$vars){
  $node = $vars['node'];

  $class = FALSE;
  if (isset($node->field_ref_css_class['und'][0]['target_id'])){
    $tid = $node->field_ref_css_class['und'][0]['target_id'];
    $term = taxonomy_term_load($tid);
    $class = $term->field_css_class['und'][0]['value'];
  }

  if ($class){
    if (isset($vars['content']['field_img'][0]['#image_style'])){
      $style = 'vertical-' . $class; 
      $vars['content']['field_img'][0]['#image_style'] = $style;  
    }
  }
}

function _product_set_btn(&$vars){
  $node = $vars['node'];
  $vars['btn'] = '+ colori';
  // Se il prodotto collegato è solo uno, cambio la dicitura del bottone
  if (!isset($node->field_ref_prod['und'][1])){
    $vars['btn'] = 'Scopri';
  }
}

/** 
 * Recupera i colori dai prodotti collegati
 */
function _product_retrieve_colors(&$vars){
  $node = $vars['node'];
  $products = false;
  if (isset($node->field_ref_prod['und'][0]['product_id'])){
    foreach ($node->field_ref_prod['und']as $key => $value) {
      $pnids[$key] = $value['product_id'];  
    }
    $products = commerce_product_load_multiple($pnids);
  }
  $vars['colors'] = FALSE;
  if ($products){
    $cs = array();
    foreach ($products as $key => $product) {
      $c = false;
      if ($product->status !== '0'){
        if (isset($product->field_ref_color['und'][0]['taxonomy_term'])){
          $c = $product->field_ref_color['und'][0]['taxonomy_term'];
        } elseif (isset($product->field_ref_color['und'][0]['tid'])) {
          $tid = $product->field_ref_color['und'][0]['tid'];
          $c = taxonomy_term_load($tid);
        }
      }
      
      if ($c){
        $pid = $product->product_id;
        $cs[$pid]['color'] = $c->field_color['und'][0]['rgb'];
        $cs[$pid]['name'] = $c->name;
      }
    }
    $vars['colors'] = $cs;
  }
}

function _product_add_family(&$vars){
  $node = $vars['node'];
  if (isset($node->nodehierarchy_menu_links[0]['pnid'])){
    $pnid = $node->nodehierarchy_menu_links[0]['pnid'];
    $family = node_load($pnid);
    $vars['content']['family'] = array(
      '#prefix' => '<h1 class="margin-t-0 margin-b-025">',
      '#suffix' => '</h1>',
      '#markup' => $family->title,
      '#weight' => -1,
    );
  }
}

function _product_alter_link_shop(&$vars){
  $node = $vars['node'];
  if(isset($node->field_path['und'][0]['value']) && $node->field_path['und'][0]['value'] !== ''){
    $path = $node->field_path['und'][0]['value'];
    global $base_path;
    $vars['node_url'] = $base_path . $path;
  } else {
    $vars['node_url'] = '<front>';
  }
}

function _product_add_related(&$vars){
  $node = $vars['node'];
  if (isset($node->nodehierarchy_menu_links[0]['pnid'])){
    $pnid = $node->nodehierarchy_menu_links[0]['pnid'];

    $vars['content']['related'] = array(
      '#prefix' => '<div class="row wrapper-related"><div class="container-fluid margin-t-1 margin-b-2">',
      '#suffix' => '</div></div>',
      'title' => array(
        '#markup' => '<h2 class="text-center margin-b-1">Della stessa collezione</h2>',
      ),
      'views' => array(
        '#markup' => views_embed_view('related', 'block', $pnid), 
      ),
      '#weight' => 12,
    );
  }
  add_same_h_by_selector('.view-related');
}

/**
 * Page
 */
function _slow_add_cover(&$vars){
  $node = $vars['node'];

  $vars['whc_class'] = 'negative';
    
  // Parallax
  if (isset($node->field_img_bg['und'][0]['uri'])){
    $uri = $node->field_img_bg['und'][0]['uri'];
    $url_img = file_create_url($uri);

    // Usefull variables
    $vars['page']['parallax_src'] = $url_img;

    // Add JS
    $js_parallax = libraries_get_path('jquery.parallax') . '/jquery.parallax.min.js';
    drupal_add_js( $js_parallax , array('group' => JS_LIBRARY, 'weight' => 1));
  }

  if (user_is_logged_in()){
    $vars['page']['hover'] = array(
      '#markup' => '<span class="wrapper-header-hover"></span>', 
    );  
  }

  $title = field_view_field('node', $node, 'title_field', 'default');
  $subtitle = field_view_field('node', $node, 'field_title', 'default');
  if (!arg(2)){
    // Add a space in the parallax
    $vars['page']['head'] = array(
      '#markup' => '<div class="margin-b-6"></div>',
    );

    // Add a card with the title
    $vars['page']['card'] = array(
      '#prefix' => '<div class="margin-b-1">',
      '#suffix' => '</div>',
      'content' => array(
        '#prefix' => '<div class="card card-over"><div class="margin-v-1">',
        '#suffix' => '</div></div>',
        'title' => $title,
        'subtitle' => $subtitle,
      ),
    );
    if ($node->type == 'prodcat'){
      $vars['page']['card']['content']['bread'] = array(
        '#markup' => '<h5 class="text-center">Le nostre collezioni</h5>',
        '#weight' => -3,
      );
    }
  }
}

function _slow_alter_pagination(&$vars, $title = TRUE){
  if (isset($vars['pagination']['prev'])){
    $vars['content']['pager']['#prefix'] = '<hr>' . $vars['content']['pager']['#prefix'];
    $prev = node_load($vars['pagination']['prev']);
    $next = node_load($vars['pagination']['next']);
    if ($title){
      $t_prev = '<i class="fa fa-angle-left fa-fw"></i> <span class="text-caps">' . $prev->title . '</span>';
      $t_next = '<span class="text-caps">' . $next->title . '</span> <i class="fa fa-angle-right fa-fw"></i>';
    } else {
      $t_prev = '<i class="fa fa-angle-left fa-fw"></i> <span class="text-caps">Precedente</span>';
      $t_next = '<span class="text-caps">Successivo</span> <i class="fa fa-angle-right fa-fw"></i>';
    }
    
    $vars['content']['pager']['#prefix'] = '<hr><div class="nhc-pager row margin-b-1">';

    $vars['content']['pager']['prev']['#text'] = '<span class="small">' . $t_prev . '</span>';
    $vars['content']['pager']['next']['#text'] = '<span class="small">' . $t_next . '</span>';
  }
}

/**
 * News
 */

function _slow_create_media(&$vars){
  $node = $vars['node'];
  if (isset($node->field_img['und'][0]['uri'])){
    $uri = $node->field_img['und'][0]['uri'];
    $url = image_style_url('square', $uri);

    $vars['content'] = array(
      '#prefix' => '<div class="wrapper-media">',
      '#suffix' => '</div>',
      'data' => $vars['content'],
    );

    $vars['content']['data']['#prefix'] = '<div class="media-content">';
    $vars['content']['data']['#suffix'] = '</div>';

    $img = '<span class="media-img" style="background-image: url(\'' . $url . '\');"></span>';
    $opt = array(
      'html' => TRUE,
    );
    $vars['content']['media'] = array(
      '#markup' => l($img, 'node/' . $node->nid, $opt),
    );
  }
}

function _slow_add_more_link(&$vars){
  $node = $vars['node'];
  $opt = array(
    'attributes' => array(
      'class' => 'hot-link',
    ),
  );
  $vars['content']['more'] = array(
    '#prefix' => '<p>',
    '#suffix' => '</p>',
    '#markup' => l('Continua', 'node/' . $node->nid, $opt),
    '#weight' => 13,
  );
}

function _slow_collection_sidebar(&$vars){
  $node = $vars['node'];
  if (isset($node->field_ref_coll['und'][0]['target_id'])){
    $vars['content']['field_ref_coll']['#prefix'] = '<h3 class="text-center">Scopri la collezione</h3><hr class="short">';
  }
}

// ** UTILITY **
// -------------

function _slow_get_context(){
  $context = false;
  $node = menu_get_object();
  if ($node && isset($node->type)){
    $context = $node->type;
  }
  return $context;
}