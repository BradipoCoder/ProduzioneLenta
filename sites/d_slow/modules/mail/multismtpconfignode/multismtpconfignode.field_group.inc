<?php
/**
 * @file
 * multismtpconfignode.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function multismtpconfignode_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_email|node|multismtp_config|form';
  $field_group->group_name = 'group_email';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'multismtp_config';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'E-mail Options',
    'weight' => '2',
    'children' => array(
      0 => 'field_smtp_allow_html',
      1 => 'field_smtp_email',
      2 => 'field_smtp_name',
    ),
    'format_type' => 'tab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'description' => '',
        'classes' => 'group-email field-group-tab',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_email|node|multismtp_config|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_server|node|multismtp_config|form';
  $field_group->group_name = 'group_server';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'multismtp_config';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'SMTP Server Configuration',
    'weight' => '1',
    'children' => array(
      0 => 'field_smtp_server',
      1 => 'field_smtp_port',
      2 => 'field_smtp_protocol',
      3 => 'field_smtp_login',
      4 => 'field_smtp_password',
    ),
    'format_type' => 'tab',
    'format_settings' => array(
      'label' => 'SMTP Server Configuration',
      'instance_settings' => array(
        'required_fields' => 1,
        'classes' => '',
        'description' => '',
      ),
      'formatter' => 'open',
    ),
  );
  $field_groups['group_server|node|multismtp_config|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('E-mail Options');
  t('SMTP Server Configuration');

  return $field_groups;
}
