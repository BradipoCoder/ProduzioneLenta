<?php

/**
 * @file
 * template.php
 */

require('include/content.php');
require('include/content-types.php');
require('include/paragraphs.php');
require('include/flex-slider.php');
require('include/commerce.php');

/**
 * Implements hook_preprocess_html()
 * Google fonts and Google Analitycs
 */
function slow_preprocess_html(&$variables) {
  $fonts = array(
    0 => 'http://fonts.googleapis.com/css?family=Merriweather+Sans:300,300i,400,400i,800',
  );

  foreach ($fonts as $key => $css) {
    drupal_add_css($css, array('type' => 'external'));
  }

  $ga = _slow_get_ga_script();
  drupal_add_js($ga, array('type' => 'inline', 'scope' => 'header', 'weight' => 5));
}

// ** PREPROCESS NODE **
// ---------------------

/**
 * Implements hook_preprocess_node()
 */
function slow_preprocess_node(&$vars){
  $node = $vars['node'];
  switch ($node->type) {
    case 'page':
      _slow_preprocess_node_page($vars);
      break;

    case 'prodcat':
      _slow_preprocess_node_prodcat($vars);
      break;
    
    case 'product':
      _slow_preprocess_node_product($vars);
      break;

    case 'news':
      _slow_preprocess_node_news($vars);
      break;
    
    default:
      # code...
      break;
  }

  if (isset($node->nid) && $vars['nid'] == 6){
    _slow_preprocess_node_contatti($vars);
  }

}

/**
 * Implements hook_preprocess_page()
 */
function slow_preprocess_page(&$vars){
  $vars['whc_class'] = '';
  $vars['page']['parallax_src'] = '';
  
  if (isset($vars['node']) && $vars['node']->type == 'prodcat'){
    _slow_add_cover($vars);
  } else {
    $vars['whc_class'] = 'bg-white';
  }
}

// ** ADMIN **
// -----------

/**
 * Implements hook_form_alter().
 */
function slow_form_alter(&$form, $form_state, $form_id){
  //if (user_is_logged_in()){
  //  drupal_set_message('Use: hook_form_' . $form_id . '_alter(&$form, $form_state, $form_id)');
  //}
}

/**
 * Implements hook_form_FORM_ID_alter(&$form, &$form_state, $form_id)
 * Node editing and some permission
 */
function slow_form_node_form_alter(&$form, $form_state){
  global $user;

  $form['nodehierarchy']['#title'] = 'Genitore';
  if (isset($form['nodehierarchy']['nodehierarchy_menu_links'][0]['#title'])){
    $form['nodehierarchy']['nodehierarchy_menu_links'][0]['#title'] = 'Genitore';
  }

  if (isset($form['#node']->type) && $form['#node']->type == 'prodcat'){
    if (isset($form['nhc_fieldset']['nhc_ct_button']['#default_value'])){
      $form['nhc_fieldset']['nhc_ct_button']['#default_value']['product'] = 'product';
    }
  }

  if ($user->uid == 1){
    // Administrator
  } else {
    // Authenticated user
    $form['options']['promote']['#access'] = false;
    $form['options']['sticky']['#access'] = false;
    $form['revision_information']['#access'] = false;
  }
}

// ** GA **
// --------

function _slow_get_ga_script(){
  $ga = "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87382819-1', 'auto');
  ga('send', 'pageview');";
  return $ga;
}

// ** SCROLL HOME **
// -----------------

function slow_preprocess_scroll_home_wrapper(&$vars){
  $vars['theme_hook_suggestions'][] = 'scroll_home_wrapper__' . $vars['nid'];
  if ($vars['nid'] == 2){
    $node = node_load(2);
    $vars['url'] = false;
    if (isset($node->field_img_bg['und'][0])){
      $img = $node->field_img_bg['und'][0]['uri']; 
      $vars['url'] = file_create_url($img);
    }
  }
}

// ** VIEWS **
// -----------

function slow_preprocess_views_view_table(&$vars) {
  $vars['classes_array'][] = 'table-striped';
}

/*
 * Funzione magica per aggiungere preprocessori ad ogni campo di una vista
 */
function slow_preprocess_views_view_fields(&$vars){
  if (isset($vars['view']->name)){
    $function = 'slow_preprocess_views_view_field__' . $vars['view']->name;
    if (function_exists($function)) { 
      $function($vars); 
    } else {
      //dpm('Function ' . $function . ' doesn\'t exists.. [_preprocess_views_view_fields]');
    }
  } 
}