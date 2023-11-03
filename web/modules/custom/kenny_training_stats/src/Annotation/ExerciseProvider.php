<?php

namespace Drupal\kenny_training_stats\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * ExerciseProvider annotation.
 *
 * @Annotation
 */
class ExerciseProvider extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The plugin status.
   *
   * By default, all plugins are enabled and this value set in TRUE. You can set
   * it to FALSE to temporary disable plugin.
   *
   * @var bool
   */
  public $enabled;


  /**
   * The weight of plugin
   *
   * Plugin with higher weight, will be used.
   *
   * @var int
   */
  public $weight;
}
