<?php

namespace Drupal\kenny_hero_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\kenny_hero_block\Plugin\KennyHeroBlockPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a kenny hero block block.
 *
 * @Block(
 *   id = "kenny_hero_block",
 *   admin_label = @Translation("Kenny Hero Block"),
 *   category = @Translation("Custom"),
 * )
 */
final class KennyHeroBlockBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The plugin manager for kenny hero block entity plugins.
   *
   * @var \Drupal\kenny_hero_block\Plugin\KennyHeroBlockPluginManager
   */
  protected $kennyHeroBlockEntityManager;

  /**
   * The plugin manager for kenny hero block hero path plugins.
   *
   * @var \Drupal\kenny_hero_block\Plugin\KennyHeroBlockPluginManager
   */
  protected $kennyHeroBlockPathManager;

  /**
   * Construct a new KlogHeroBlock instance.
   *
   * @param array $configuration
   *  The plugin configuration.
   * @param $plugin_id
   *  The plugin_id for the plugin interface.
   * @param $plugin_definition
   *  The plugin implementation definition.
   * @param \Drupal\kenny_hero_block\Plugin\KennyHeroBlockPluginManager $kenny_hero_block_entity
   *  The plugin manager for kenny hero block entity plugins.
   * @param \Drupal\kenny_hero_block\Plugin\KennyHeroBlockPluginManager $kenny_hero_block_path
   * The plugin manager for kenny hero block path plugins.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, KennyHeroBlockPluginManager $kenny_hero_block_entity, KennyHeroBlockPluginManager $kenny_hero_block_path) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->kennyHeroBlockEntityManager = $kenny_hero_block_entity;
    $this->kennyHeroBlockPathManager = $kenny_hero_block_path;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('plugin.manager.kenny_hero_block.entity'),
      $container->get('plugin.manager.kenny_hero_block.path'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $entity_plugins = $this->kennyHeroBlockEntityManager->getSuitablePlugins();
    $path_plugins = $this->kennyHeroBlockPathManager->getSuitablePlugins();
    $plugins = $entity_plugins + $path_plugins;
    uasort($plugins, '\Drupal\Component\Utility\SortArray::sortByWeightElement');
    if (!empty($plugins)) {
      $plugin = end($plugins);
    }


    if ($plugin['plugin_type'] == 'entity') {
      /** @var \Drupal\kenny_hero_block\Plugin\KennyHeroBlock\KennyHeroBlockPluginInterface $instance */
      $instance = $this->kennyHeroBlockEntityManager->createInstance($plugin['id'], ['entity' => $plugin['entity']] );
    }

    if ($plugin['plugin_type'] == 'path') {
      /** @var \Drupal\kenny_hero_block\Plugin\KennyHeroBlock\KennyHeroBlockPluginInterface $instance */
      $instance = $this->kennyHeroBlockPathManager->createInstance($plugin['id']);
    }


    $build['content'] = [
      '#theme' => 'kenny_hero_block',
      '#title' => $instance->getHeroTitle(),
      '#subtitle' => $instance->getHeroSubtitle(),
      '#image' => $instance->getHeroImage(),
      '#video' => $instance->getHeroVideo(),
      '#plugin_id' => $instance->getPluginId(),
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return [
      'url.path',
    ];
  }
}
