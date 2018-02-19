<?php
/**
 * @file
 * ct_cover.features.inc
 */

/**
 * Implements hook_node_info().
 */
function ct_cover_node_info() {
  $items = array(
    'cover' => array(
      'name' => t('Cover'),
      'base' => 'node_content',
      'description' => t('Aggiungi una cover al sito'),
      'has_title' => '1',
      'title_label' => t('Titolo'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
