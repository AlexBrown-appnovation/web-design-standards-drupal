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

/**
*
* Implements hook_status_messages().
*
*/

function us_web_design_standards_status_messages($variables) {
  $display = $variables['display'];
  $output = '';

  $status_heading = array(
    'success' => t('Success'),
    'status' => t('Status'),
    'error' => t('Error'),
    'warning' => t('Warning'),
    'info' => t('Info'),
  );
  foreach (drupal_get_messages($display) as $type => $messages) {

    if (!empty($status_heading[$type]) && $status_heading[$type] == 'Error') {
      $output .= "<div class=\"usa-alert usa-alert-$type\" role=\"alert\">\n<div class=\"usa-alert-body\">\n";
    } else {
      $output .= "<div class=\"usa-alert usa-alert-$type\">\n<div class=\"usa-alert-body\">\n";
    }

    if (!empty($status_heading[$type])) {
      $output .= '<h3 class="usa-alert-heading">' . $status_heading[$type] . "</h3>\n";
    }

    if (count($messages) > 1) {
      $output .= " <ul>\n";
      foreach ($messages as $message) {
        $output .= '  <li>' . '<p>' . $message . '</p>' . "</li>\n";
      }
      $output .= " </ul>\n";
    }
    else {
      $output .= reset($messages);
    }
    $output .= "</div>\n</div>\n";
  }
  return $output;
}
