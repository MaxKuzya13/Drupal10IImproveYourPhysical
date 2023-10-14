<?php

/**
 * @file
 * Main file for all custom theme hooks preprocess
 */

/**
 * Implements template_preprocess_HOOK() for kenny-hero-block.html.twig.
 */
function template_preprocess_kenny_hero_block(&$variables) {
  // Image is required for video. It will be used for poster.
  if (!empty($variables['video'])) {
    if (!$variables['image']) {
      $variables['video'] = [];
    }
  }

}
