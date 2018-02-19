<?php

/**
 * @file
 * paragraphs.php
 */

// ** PARAGRAPH **
// ---------------

function slow_preprocess_entity(&$vars){
  if($vars['entity_type'] == 'paragraphs_item'){
    switch ($vars['paragraphs_item']->bundle) {
      case 'block':
        _slow_preprocess_p_block($vars);
        break;

      case 'imgs':
        _slow_preprocess_p_imgs($vars);
        break;

      case 'img_big':
        _slow_preprocess_p_img_big($vars);
        break;

        # code...
        break;
    }
  }
}

function _slow_preprocess_p_block(&$vars){
  $vars['content']['#prefix'] = '<div class="col-md-4">';
  $vars['content']['#suffix'] = '</div>';
  _slow_add_dot($vars, 1);
}

function _slow_alter_field_blocks(&$vars){
  if (isset($vars['content']['field_blocks'])){
    $vars['content']['field_blocks']['#prefix'] = '<div class="row row-blocks">';
    $vars['content']['field_blocks']['#suffix'] = '</div>';
  }
}

function _slow_preprocess_p_imgs(&$vars){
  $p = $vars['paragraphs_item'];

  // Options
  $style = 'square';
  if (isset($p->field_vertical['und'][0]['value']) && $p->field_vertical['und'][0]['value']){
    $style = 'vertical';
  }

  $vars['content']['#prefix'] = '<div class="row row-imgs">';
  $vars['content']['#suffix'] = '</div>';

  if ($vars['view_mode'] == 'full'){
    hide($vars['content']['field_img_2']);

    $elements = element_children($p->field_img_2['und']);
    foreach ($elements as $key => $n) {
      $img = $vars['content']['field_img_2']['#items'][$n];
      $vars['content'][$n] = array(
        '#prefix' => '<div class="col-sm-6"><div class="margin-b-1">',
        '#suffix' => '</div></div>',
        'img' => array(
          'data' => $vars['content']['field_img_2'][$n],
        ),
      );
      $vars['content'][$n]['img']['data']['#display_settings']['colorbox_node_style'] = $style;

      if (isset($vars['content']['field_img_2'][$n]['#item']['title']) && $vars['content']['field_img_2'][$n]['#item']['title'] !== ''){
        $title = $vars['content']['field_img_2'][$n]['#item']['title'];
        $vars['content'][$n]['desc'] = array(
          '#prefix' => '<div class="margin-t-05"><p class="small">',
          '#suffix' => '</p></div>',
          '#markup' => $title,
          '#weight' => 2,
        );
      }
    }
  }

  if ($vars['view_mode'] == 'paragraphs_editor_preview'){
    $vars['content']['#prefix'] = '<div class="wrapper-p-imgs">';
    $vars['content']['#suffix'] = '</div>';
  }
}

function _slow_preprocess_p_img_big(&$vars){
  
  if ($vars['view_mode'] == 'full'){
    $vars['content']['field_img'] = array(
      '#prefix' => '<div class="wrapper-p-img margin-b-1">',
      '#suffix' => '</div>',
      'data' => $vars['content']['field_img'][0],
    );
    if (isset($vars['content']['field_img']['data']['#item']['title'])){
      $title = $vars['content']['field_img']['data']['#item']['title'];
      $vars['content']['field_img']['desc'] = array(
        '#prefix' => '<div class="margin-t-05"><p class="small">',
        '#suffix' => '</p></div>',
        '#markup' => $title,
        '#weight' => 2,
      );
    }
  }
}