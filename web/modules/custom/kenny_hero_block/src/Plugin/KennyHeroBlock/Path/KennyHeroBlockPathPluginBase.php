<?php

namespace Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path;

use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\KennyHeroBlockPluginBase;


/**
 * The base for KennyHeroBlock path plugin type.
 */
abstract class KennyHeroBlockPathPluginBase extends KennyHeroBlockPluginBase implements KennyHeroBlockPathPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getMatchPath() {
    return $this->pluginDefinition['match_path'];
  }

  /**
   * {@inheritdoc}
   */
  public function getMatchType() {
    return $this->pluginDefinition['match_type'];
  }
}
