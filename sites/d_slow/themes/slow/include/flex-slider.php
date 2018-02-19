<?php

/**
 * @file
 * flex-slider.php
 */

function slow_preprocess_views_view_field__slider_home(&$vars){

  $nid = false;
  if (isset($vars['row']->nid)){
    $nid = $vars['row']->nid;  
  }

  // aggiungo un formato all'immagine per i mobile
  if (isset($vars['row']->field_field_img[0]['raw']['uri'])){
    $uri = $vars['row']->field_field_img[0]['raw']['uri'];
    
    // Slider xs
    $image = array(
      '#theme' => 'image_style',
      '#style_name' => 'square',
      '#path' => $uri,
    );
    $vars['imgs'][0] = array(
      '#prefix' => '<div class="hidden-lg hidden-md hidden-sm">',
      '#suffix' => '</div>',
      'data' => $image,
    );

    // Slider sm
    $image['#style_name'] = 'slider_home_mobile';
    $vars['imgs'][1] = array(
      '#prefix' => '<div class="hidden-lg hidden-md hidden-xs">',
      '#suffix' => '</div>',
      'data' => $image,
    );

    // Slider desktop
    $image['#style_name'] = 'slider_home';
    $vars['imgs'][2] = array(
      '#prefix' => '<div class="hidden-sm hidden-xs">',
      '#suffix' => '</div>',
      'data' => $image,
    );
  }

  if (isset($vars['row']->field_field_short[0]['raw']['value'])){
    $vars['text'] = array(
      '#markup' => $vars['row']->field_field_short[0]['raw']['value'],
    );
  }

  $path = '<front>';
  if (isset($vars['row']->field_field_ref_cont[0]['raw']['target_id'])){
    $path = 'node/' . $vars['row']->field_field_ref_cont[0]['raw']['target_id'];
  }

  if (isset($vars['row']->field_field_button[0]['raw']['value'])){
    $btn = $vars['row']->field_field_button[0]['raw']['value'];
    $opt = array(
      'attributes' => array(
        'class' => array('btn', 'btn-primary'),
      ),
    );
    $vars['button'] = array(
      '#markup' => l($btn, $path, $opt),
    );
  }

  if (user_is_logged_in()){
    $vars['edit'] = array(
      '#markup' => l('Edit', 'node/' . $nid . '/edit', $opt),
    );
  }
}