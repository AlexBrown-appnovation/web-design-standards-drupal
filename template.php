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

/**
*
* Implements hook_preprocess_search_block_form().
*
*/

function us_web_design_standards_preprocess_search_block_form(&$vars) {
  $vars['search_form'] = str_replace('type="text"', 'type="search"', $vars['search_form']);
}

/**
*
* Implements hook_form_search_block_alter().
*
*/

function us_web_design_standards_form_search_block_form_alter(&$form, &$form_state, $form_id) {
  $form['#attributes']['class'][] = 'usa-search';
  // we are using the default size of search-field-bg but we will probably
  // want to let the user choose between big/medium/small in the future
  $form['search_block_form']['#prefix'] = '<div role="search">';
  $form['search_block_form']['#suffix'] = '</div>';

  // create and use a button instead of the default input
  $form['button'] = array(
    '#type' => 'item',
    '#markup' => '<button type="submit" id="edit-submit" name="op" class="form-submit" value="Search"><span class="usa-search-submit-text">Search</span></button>',
    '#weight' => 1000,
  );
  $form['actions']['#attributes']['class'][] = 'element-invisible';
}

/**
*
* Implements hook_form_alter().
*
*/

function us_web_design_standards_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'search_form') {
    $form['#attributes']['class'][] = 'usa-search usa-search-big';
    $form['basic']['#attributes']['role'][] = 'search';
  }
}

/**
*
* Implements hook_form_element().
*
*/

function us_web_design_standards_form_element($variables) {
  $element = &$variables['element'];

  // This function is invoked as theme wrapper, but the rendered form element
  // may not necessarily have been processed by form_builder().
  $element += array(
    '#title_display' => 'before',
  );

  // Add element #id for #type 'item'.
  if (isset($element['#markup']) && !empty($element['#id'])) {
    $attributes['id'] = $element['#id'];
  }
  // Add element's #type and #name as class to aid with JS/CSS selectors.
  $attributes['class'] = array('form-item');
  if (!empty($element['#type'])) {
    $attributes['class'][] = 'form-type-' . strtr($element['#type'], '_', '-');
  }

  // add our usa-input-error class to the form item when we get an error
  if (form_get_error($variables['element'])) {
    $attributes['class'] = array('form-item', 'usa-input-error');
  } else {
    $attributes['class'] = array('form-item');
  }

  if (!empty($element['#name'])) {
    $attributes['class'][] = 'form-item-' . strtr($element['#name'], array(' ' => '-', '_' => '-', '[' => '-', ']' => ''));
  }
  // Add a class for disabled elements to facilitate cross-browser styling.
  if (!empty($element['#attributes']['disabled'])) {
    $attributes['class'][] = 'form-disabled';
  }
  $output = '<div' . drupal_attributes($attributes) . '>' . "\n";

  // If #title is not set, we don't display any label or required marker.
  if (!isset($element['#title'])) {
    $element['#title_display'] = 'none';
  }
  $prefix = isset($element['#field_prefix']) ? '<span class="field-prefix">' . $element['#field_prefix'] . '</span> ' : '';
  $suffix = isset($element['#field_suffix']) ? ' <span class="field-suffix">' . $element['#field_suffix'] . '</span>' : '';

  switch ($element['#title_display']) {
    case 'before':
    case 'invisible':
      $output .= ' ' . theme('form_element_label', $variables);
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;

    case 'after':
      $output .= ' ' . $prefix . $element['#children'] . $suffix;
      $output .= ' ' . theme('form_element_label', $variables) . "\n";
      break;

    case 'none':
    case 'attribute':
      // Output no label and no required marker, only the children.
      $output .= ' ' . $prefix . $element['#children'] . $suffix . "\n";
      break;
  }

  if (!empty($element['#description'])) {
    $output .= '<div class="description">' . $element['#description'] . "</div>\n";
  }

  $output .= "</div>\n";

  return $output;
}
