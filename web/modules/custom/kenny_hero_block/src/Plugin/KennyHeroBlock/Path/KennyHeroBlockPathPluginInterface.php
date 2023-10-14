<?php

namespace Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Path;


use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\KennyHeroBlockPluginInterface;

/**
 * Interface for KlogHero path plugin type.
 */
interface KennyHeroBlockPathPluginInterface extends KennyHeroBlockPluginInterface {

  /**
   * Gets match paths.
   *
   * @return array
   *  An array with paths.
   */
  public function getMatchPath();

  /**
   * Gets match type.
   *
   * @return string
   *  The match type.
   */
  public function getMatchType();
}
