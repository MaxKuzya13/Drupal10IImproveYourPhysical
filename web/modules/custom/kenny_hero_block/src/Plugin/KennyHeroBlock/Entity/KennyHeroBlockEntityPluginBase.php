<?php

namespace Drupal\kenny_hero_block\Plugin\KennyHeroBlock\Entity;

use Drupal\kenny_hero_block\Plugin\KennyHeroBlock\KennyHeroBlockPluginBase;

/**
 * Interface for Kenny Hero Block Entity plugin type.
 */
abstract class KennyHeroBlockEntityPluginBase extends KennyHeroBlockPluginBase implements KennyHeroBlockEntityPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function getEntityType() {
    return $this->pluginDefinition['entity_type'];
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityBundle() {
    return $this->pluginDefinition['entity_bundle'];
  }

  /**
   * {@inheritdoc}
   */
  public function getEntity() {
    return $this->configuration['entity'];
  }

}
