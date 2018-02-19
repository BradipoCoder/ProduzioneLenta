<?php

/**
 * Query e opzioni
 * passando un array vuoto
 * verranno utilizzati i filtri negli argomenti
 */

function _prod_slow_query_nids($form_state, $pager){
  
  $query = new EntityFieldQuery();
  $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', array('product'))
    ->propertyCondition('status', NODE_PUBLISHED)
    ->propertyOrderBy('changed', 'DESC');

  if ($pager){
    //$query->addTag('random');
    $query->pager(12);
  }

  if (isset($form_state['slow']['filters'])){
    $filters = $form_state['slow']['filters'];  
  } else {
    $filters = _prod_slow_get_filter_list();
  }

  // Check the remove tag
  $r_name = _prod_slow_check_remove($form_state);

  foreach ($filters as $k => $filter) {

    if ($k !== $r_name){

      $value = false;
        
      // Controllo gli argomenti (url)
      if (isset($_GET[$k])){
        $value = $_GET[$k];
      }

      // Controllo form_state values (ajax)
      if (isset($form_state['values'][$k]) && $form_state['values'][$k] !== '0'){
        $value = $form_state['values'][$k];
      }

      // Taxonomy
      if ($filter['type'] == 'taxonomy'){
        $tid = $value;
        if ($tid){
          $field_name = $filter['field_name'];

          // Altero il funzionamento dei generi
          if ($k == 'gend'){
            _prod_slow_alter_query_gend($query, $field_name, $tid);
          } else {
            $query->fieldCondition($field_name, 'tid', $tid);  
          }
        }
      }

      // Entity
      if ($filter['type'] == 'entity'){
        $nid = $value;
        if ($nid){
          $field_name = $filter['field_name'];
          $query->fieldCondition($field_name, 'target_id', $nid); 
        }
      }
    }
  }

  $nids = array();
  $query->execute();
  $results = $query->ordered_results;
  foreach ( $results as $node ) {
    array_push ($nids, $node->entity_id );
  }

  return $nids;
}

function _prod_slow_alter_query_gend(&$query, $field_name, $tid){
  if ($tid == '50' || $tid == '49'){
    $tids[] = $tid;
    // Aggiungo unisex
    $tids[] = '51';
    $query->fieldCondition($field_name, 'tid', $tids);  
  } else {
    $query->fieldCondition($field_name, 'tid', $tid);  
  }
}

function prod_slow_query_random_alter($query) {
  $query->orderRandom();
}


/**
 * Return possibile options from taxonomy filter
 */
function _prod_slow_get_options_by_vid($nids, $filter_item){
  $options[0] = '- Seleziona -';

  $tids = _prod_slow_query_tid_by_vid($nids, $filter_item['voc_name']);
  $terms = taxonomy_term_load_multiple($tids);

  foreach ($terms as $tid => $term) {
    $options[$tid] = $term->name;
  }

  return $options; 
}

function _prod_slow_query_tid_by_vid($nids, $voc_name){

  $voc = taxonomy_vocabulary_machine_name_load($voc_name);
  $vid = $voc->vid;

  $query = db_select('taxonomy_index', 'ti');
  $query->fields('ti', array('nid','tid'));

  $query->join('taxonomy_term_data', 'ttd', 'ttd.tid = ti.tid');
  $query->fields('ttd', array('vid', 'weight', 'name'));

  $query->condition('ttd.vid', $vid, '=');
  $query->condition('ti.nid', $nids, 'IN');

  $query->orderBy('weight', 'ASC');
  $query->orderBy('name', 'ASC');

  $result =$query->execute()->fetchAllAssoc('tid');
  
  return array_keys($result); 
}

/**
 * Return possibile options from entity filter
 */
function _prod_slow_get_options_entity($nids, $filter_item){
  
  $options[0] = '- Seleziona -';
  $nodes = node_load_multiple($nids);

  // TO DO @ riscrivere come query normale
  // Più veloce e rimane in ordine

  foreach ($nodes as $k => $node) {
    if (isset($node->field_ref_col['und']['0']['target_id'])){
      $nid = $node->field_ref_col['und']['0']['target_id'];
      if (!isset($options[$nid])){
        $ref = node_load($nid);
        $options[$nid] = $ref->title;  
      }
    }
  }

  return $options; 
}

/**
 * Se il form è appena stato aggiornato in seguito alla rimozione di un tag
 * ritorna il nome del tag da resettare
 */
function _prod_slow_check_remove($form_state){
  $r_name = false;
  if (isset($form_state['slow']['remove']) && $form_state['slow']['remove']){
    $r_name = $form_state['slow']['remove'];
  }
  return $r_name;
}

/**
 * Setto tutti i campi della collezione
 */
function _prod_slow_work_collection(){  
  $query = new EntityFieldQuery();
  $query
    ->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', array('product'))
    ->propertyCondition('status', NODE_PUBLISHED);

  $nids = array();
  $query->execute();
  $results = $query->ordered_results;
  foreach ( $results as $node ) {
    array_push ($nids, $node->entity_id );
  }

  $nodes = node_load_multiple($nids);
  foreach ($nodes as $key => $node) {
    if (isset($node->nodehierarchy_menu_links[0]['pnid'])){
      $pnid = $node->nodehierarchy_menu_links[0]['pnid'];
    
      if (!isset($node->field_ref_col['und'][0]['target_id'])){
        $node->field_ref_col['und'][0]['target_id'] = $pnid;
        //node_save($node);
      }
    }
  }
}

