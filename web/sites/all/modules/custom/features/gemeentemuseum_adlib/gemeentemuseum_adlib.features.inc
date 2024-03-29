<?php

/**
 * @file
 * gemeentemuseum_adlib.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function gemeentemuseum_adlib_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "adlibapi" && $api == "default_adlibapi_presets") {
    return array("version" => "1");
  }
  if ($module == "feeds" && $api == "feeds_importer_default") {
    return array("version" => "1");
  }
  if ($module == "feeds_tamper" && $api == "feeds_tamper_default") {
    return array("version" => "2");
  }
  if ($module == "field_group" && $api == "field_group") {
    return array("version" => "1");
  }
}

/**
 * Implements hook_views_api().
 */
function gemeentemuseum_adlib_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function gemeentemuseum_adlib_node_info() {
  $items = array(
    'adlib_object' => array(
      'name' => t('Adlib Object'),
      'base' => 'node_content',
      'description' => t('Imported object form an Adlib database'),
      'has_title' => '1',
      'title_label' => t('Titel'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
