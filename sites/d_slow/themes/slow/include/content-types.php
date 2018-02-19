<?php

/**
 * @file
 * content-types.php
 */

function _slow_preprocess_node_page(&$vars){
  if ($vars['view_mode'] == 'child'){
    $vars['classes_array'][] = 'col-sm-6';
    $vars['classes_array'][] = 'col-md-4';
  }
  if ($vars['view_mode'] == 'teaser'){
    _slow_use_emotional_title($vars);
    _slow_add_children_vertical_margin($vars);
    _slow_add_button($vars);
  }

  if ($vars['view_mode'] == 'full'){
    $vars['content']['pager']['#weight'] = 12;
  }

  if ($vars['nid'] == 1){
    _slow_preprocess_node_page_collection($vars);  
  }

  if ($vars['nid'] == 3){
    _slow_preprocess_node_page_news($vars);
  }
}

function _slow_preprocess_node_contatti(&$vars){
  if ($vars['view_mode'] == 'teaser' || $vars['view_mode'] == 'full'){
    $vars['classes_array'][] = 'negative';
  } 
}

function _slow_preprocess_node_page_collection(&$vars){
  if ($vars['view_mode'] == 'teaser' || $vars['view_mode'] == 'full'){
    $vars['content']['children'] = array(
      //'#prefix' => '<div class="row"><div class="col-md-8 col-md-offset-2">',
      //'#suffix' => '</div></div>',
      '#weight' => $vars['content']['children']['#weight'],
      'data' => $vars['content']['children'],
    );

    $opt = array(
      'attributes' => array(
        'class' => array('btn', 'btn-primary'),
      ),
    );

    $vars['content']['featured'] = array(
      'title' => array(
        '#markup' => '<h2 class="text-center margin-b-1">Modelli in evidenza</h2>',
      ),
      'views' => array(
        '#markup' => views_embed_view('hp_products', 'default'), 
      ),
      'more' => array(
        '#prefix' => '<div class="wrapper-more text-center margin-t-1 margin-b-4">',
        '#suffix' => '</div>',
        '#markup' => l('Tutti i prodotti', 'prodotti', $opt),
      ),
      '#weight' => -2,
    );
    if ($vars['view_mode'] == 'full'){
      $vars['content']['featured']['views']['#prefix'] = '<div class="margin-b-2">';
      $vars['content']['featured']['views']['#suffix'] = '</div>';
    }
    add_same_h_by_selector('.view-hp-products');
  }
}

function _slow_preprocess_node_page_news(&$vars){
  if ($vars['view_mode'] == 'full'){
    $vars['content']['children']['#printed'] = TRUE;
  }

  if ($vars['view_mode'] == 'teaser'){
    $vars['content']['children'] = array(
      '#prefix' => '<div class="row"><div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">',
      '#suffix' => '</div></div>',
      'data' => $vars['content']['children'],
      '#weight' => $vars['content']['children']['#weight'],
    );  
  }
}

function _slow_preprocess_node_prodcat(&$vars){
  if ($vars['view_mode'] == 'child'){
    $context = _slow_get_context();
    if ($context !== 'news'){
      $vars['classes_array'][] = 'col-md-3';
      $vars['classes_array'][] = 'col-sm-6';
    }
    $vars['btn'] = 'Scopri';
    _slow_add_desc($vars);
  }
  if ($vars['view_mode'] == 'full'){
    $vars['content']['pager']['#weight'] = 13;
    _slow_alter_pagination($vars);
    $vars['content']['title_field']['#printed'] = TRUE;
    $vars['content']['field_title']['#printed'] = TRUE;
  }
}

function _slow_preprocess_node_product(&$vars){
  _product_retrieve_colors($vars);

  if ($vars['view_mode'] == 'child'){
    $vars['classes_array'][] = 'col-sm-6';
    $vars['classes_array'][] = 'col-md-3';
    
    _product_set_btn($vars);
    _product_alter_details($vars);
    _product_alter_dimension($vars);
  }

  if ($vars['view_mode'] == 'full'){
    _product_add_family($vars);
    _product_display_colors($vars, true);
    $vars['content']['colors']['#prefix'] = '<div class="margin-b-1">';
    $vars['content']['colors']['#suffix'] = '</div>';
    $vars['content']['colors']['#weight'] = 3;
    _product_add_related($vars);
  }
}

function _slow_preprocess_node_news(&$vars){
  if ($vars['view_mode'] == 'child'){
    $vars['classes_array'][] = 'col-xs-12';
    _slow_add_more_link($vars);
    _slow_create_media($vars);
  }

  if ($vars['view_mode'] == 'full'){
    _s_archive_sidebar($vars);
    _slow_collection_sidebar($vars);
  }
}