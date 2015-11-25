<?php

/**
 *
 * Implements hook_preprocess_html().
 *
 */
function us_web_design_standards_preprocess_html(&$variables) {
  // set the path to the theme and make it available in html.tpl.php
  $variables['theme_path'] = drupal_get_path('theme', 'us_web_design_standards');
}

/**
*
* Implements hook_menu_local_task().
*
*/

function us_web_design_standards_menu_local_task(&$variables) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];

  return '<li class="usa-tab">' . l($link_text, $link['href'], $link['localized_options']) . '</li>';
}

/**
*
* Implements hook_menu_tree().
*
*/

function us_web_design_standards_menu_tree($variables){
  return '<ul class="usa-sidenav-list">' . $variables['tree'] . '</ul>';
}
