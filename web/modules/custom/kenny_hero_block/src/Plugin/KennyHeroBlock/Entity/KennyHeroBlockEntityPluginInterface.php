<?php

namespace Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Entity;

use Drupal\Core\Entity\EntityInterface;
use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\KennyHeroBlockPluginInterface;
use Drupal\klog_hero\Plugin\KlogHero\KlogHeroPluginInterface;

/**
 * Interface for Kenny Hero Block Entity plugin type.
 */
interface KennyHeroBlockEntityPluginInterface extends KennyHeroBlockPluginInterface {

  /**
   * Gets entity type id.
   *
   * @return string
   *  The entity type id.
   */
  public function getEntityType();

  /**
   * Gets entity bundles.
   *
   * @return array
   *  The array with entity type bundles.
   */
  public function getEntityBundle();

  /**
   * Gets current entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *  The entity object.
   */
  public function getEntity();

}
