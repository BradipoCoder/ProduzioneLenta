<?php

/**
 * Filtro di selezione di tutti i prodotti
 */

function _prod_slow_page_filter(){

  $data = drupal_get_form('prod_slow_form');

  return $data;
}

/**
 * Implements hook_form().
 */
function prod_slow_form($form, &$form_state){
  
  $form = array(
    '#prefix' => '<div id="prodslow-ajax-div" class="wrapper-form-prodslow">',
    '#suffix' => '</div>',
  );

  $form_state['cache'] = false;

  _prod_slow_trigger_buttons($form, $form_state);
  _prod_slow_filter_list($form, $form_state);
  _prod_slow_nids($form, $form_state);
  _prod_slow_filter($form, $form_state);

  // TO DO: la seconda volta che imposto un filtro, non viene impostato
  _prod_slow_remove_tags($form, $form_state);

  // Nome del filtro da azzerare (resetto tutto)
  $r_name = _prod_slow_check_remove($form_state);
  if ($r_name){
    // Questo è il modo giusto di resettare un campo in D7
    $form_state['values'][$r_name] = '0';
    unset($form_state['input'][$r_name]);
    $form_state['rebuild'] = true;
    unset($form_state['slow']['remove']);
  }

  //if (isset($form_state['values'])){dpm($form_state['values']);}

  $form['filter'] = array(
    '#prefix' => '<div class="wrapper-prodslow-submit">',
    '#suffix' => '</div>',
    '#type' => 'submit',
    '#value' => 'Applica',
    '#weight' => 8,
    '#name' => 'filter',
  );

  return $form;
}

function prod_slow_callback(&$form, &$form_state) {
  return $form;
}

function prod_slow_remove_callback(&$form, &$form_state){
  return $form;
}

function prod_slow_form_submit(&$form, &$form_state){
  
  $slow = $form_state['slow'];

  $args = array();
  foreach ($slow['filters'] as $key => $filter) {
    if (isset($form_state['values'][$key]) && $form_state['values'][$key]!== '0'){
      $args[$key] = $form_state['values'][$key];
    }
  }

  $form_state['redirect'] = array(
    'prodotti',
    array(
      'query' => $args,
    ),
  );  

}

function _prod_slow_trigger_buttons(&$form, &$form_state){
  if (isset($form_state['triggering_element']['#attributes']['rel'])){
    $name = $form_state['triggering_element']['#attributes']['rel'];
    $form_state['slow']['remove'] = $name;
  }
}

/**
 * Questa funzione, recupera i nid di tutti i prodotti (inizialmente)
 * Se ci sono dei filtri impostati, filtri i nid in base a questi filtri
 * @param  [type] &$form       [description]
 * @param  [type] &$form_state [description]
 * @return [type]              [description]
 */
function _prod_slow_nids(&$form, &$form_state){
  $form_state['slow']['nids'] = array();
  $form_state['slow']['nids'] = _prod_slow_query_nids($form_state, false);
}

function _prod_slow_filter_list(&$form, &$form_state){
  $form_state['slow']['filters'] = _prod_slow_get_filter_list();
}

function _prod_slow_get_filter_list(){
  $filters = array(
    'gend' => array(
      'type' => 'taxonomy',
      'title' => 'Genere',
      'voc_name' => 'gender',
      'field_name' => 'field_ref_gen',
      'weight' => 0,
    ),
    'family' => array(
      'type' => 'taxonomy',
      'title' => 'Categoria',
      'voc_name' => 'family',
      'field_name' => 'field_ref_family',
      'weight' => 1,
    ),
    'cat' => array(
      'type' => 'taxonomy',
      'title' => 'Modello',
      'voc_name' => 'cat',
      'field_name' => 'field_ref_cat',
      'weight' => 2,
    ),
    'collection' => array(
      'type' => 'entity',
      'title' => 'Collezione',
      'field_name' => 'field_ref_col',
      'weight' => 3,
    ),
  );

  return $filters;
}

function _prod_slow_filter(&$form, &$form_state){
  $filters = $form_state['slow']['filters'];

  // Nome del filtro da azzerare
  $r_name = _prod_slow_check_remove($form_state);
  
  foreach ($filters as $k => $filter) {
    if ($filter['type'] == 'taxonomy'){
      _prod_slow_filter_type_taxonomy($form, $form_state, $k, $filter);   
    }
    if ($filter['type'] == 'entity'){
      _prod_slow_filter_type_entity($form, $form_state, $k, $filter);
    }

    $value = 0;

    if ($k !== $r_name){
      // Se l'utente cambia tramite ajax
      if (isset($form_state['values'][$k])){
        $value = $form_state['values'][$k]; 
      }
    }

    // Controllo gli argomenti
    if (isset($_GET[$k])){
      $value = $_GET[$k];
    }

    $form[$k]['#default_value'] = $value;
    $form_state['slow']['filters'][$k]['value'] = $value;
  }
}

function _prod_slow_filter_type_taxonomy(&$form, &$form_state, $k, $filter){
  $slow = $form_state['slow'];

  $form[$k] = array(
    '#type' => 'select',
    '#title' => $filter['title'],
    '#options' => _prod_slow_get_options_by_vid($slow['nids'], $filter),
    '#ajax' => array(
      'callback' => 'prod_slow_callback',
      'wrapper' => 'prodslow-ajax-div',
      'method' => 'replace',
    ),
    '#weight' => $filter['weight'],
  ); 
}

function _prod_slow_filter_type_entity(&$form, &$form_state, $k, $filter){
  $slow = $form_state['slow'];

  $form[$k] = array(
    '#type' => 'select',
    '#title' => $filter['title'],
    '#options' => _prod_slow_get_options_entity($slow['nids'], $filter),
    '#ajax' => array(
      'callback' => 'prod_slow_callback',
      'wrapper' => 'prodslow-ajax-div',
      'method' => 'replace',
    ),
    '#weight' => $filter['weight'],
  );

  $value = 0; 
}

function _prod_slow_remove_tags(&$form, &$form_state){
  $filters = $form_state['slow']['filters'];

  foreach ($filters as $name => $filter) {
    // Se il filtro è attivo, lo nascondo
    if (isset($filter['value']) && $filter['value']){
      $form[$name]['#prefix'] = '<div class="hide">';
      $form[$name]['#suffix'] = '</div>';

      // TO DO - arg thing
      if (isset($form_state['values'][$name])){
        $k = $form_state['values'][$name];  
      } else {
        $k = $_GET[$name];
      }
      
      if ($form[$name]['#type'] == 'select'){
        $text = $form[$name]['#options'][$k];
        //dpm($form[$name]['#options']);
      } else {
        $text = $values[$name];
      }

      $form['remove_' . $name] = [
        '#type' => 'button',
        '#prefix' => '<div class="form-group"><label>' . $filters[$name]['title'] .  '</label><div>',
        '#suffix' => '</div></div>',
        '#value' => $text . ' &times;',
        '#weight' => $form[$name]['#weight'],
        '#name' => 'remove_' . $name,
        '#attributes' => [
          'rel' => $name,
          'class' => [
            'btn-tag',
            'btn-remove',
            'btn-info',
          ],
        ],
        '#ajax' => array(
          'callback' => 'prod_slow_remove_callback',
          'wrapper' => 'prodslow-ajax-div',
          'method' => 'replace',
        ),
      ];
    }
  }
}

function _prod_slow_page_nodes(){
  $nids = _prod_slow_query_nids(array(), true);
  $nodes = node_load_multiple($nids);
  $content = array(
    '#prefix' => '<div class="wrapper-nodes row">',
    '#suffix' => '</div>',
    '#weight' => 20,
  );
  $content['nodes'] = node_view_multiple($nodes, 'child');
  return $content;
}