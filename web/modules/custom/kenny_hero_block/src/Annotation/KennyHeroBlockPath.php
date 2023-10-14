<?php
namespace Drupal\kenny_hero_block\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * KennyHeroBlockPath annotation.
 *
 * @Annotation
 */
class KennyHeroBlockPath extends Plugin {

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
   * The paths to match.
   *
   * An array with paths to limit with plugin execution. Can contain wildcard.
   * (*) and Drupal placeholders such as <front>.
   *
   * @var array
   */
  public $match_path;


  /**
   * The match type for match_path.
   *
   * Value can be:
   * - listed: (default) Shows only at paths from match_path.
   * - unlisted: Shows at all paths. except those listed in match_path.
   *
   * @var string
   */
  public $match_type;

  /**
   * The weight of plugin
   *
   * Plugin with higher weight, will be used.
   *
   * @var int
   */
  public $weight;
}
