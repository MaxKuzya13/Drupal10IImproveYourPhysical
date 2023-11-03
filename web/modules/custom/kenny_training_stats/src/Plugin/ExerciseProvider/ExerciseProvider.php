<?php

namespace Drupal\kenny_training_stats\Plugin\ExerciseProvider;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * The base for all Exercise Provider Block plugins.
 */
abstract class ExerciseProviderPluginBase extends PluginBase implements ExerciseProviderPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\CurrentRouteMatch
   */
  protected $routeMatch;

  /**
   * The entity type manager
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;


  /**
   * ExerciseProvider constructor
   *
   * @param array $configuration
   *  The configuration.
   * @param string $plugin_id
   *  The plugin ID.
   * @param mixed $plugin_definition
   *  The plugin definition.
   * @param \Drupal\Core\Routing\CurrentRouteMatch $current_route_match
   *  The current route match.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *  The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, CurrentRouteMatch $current_route_match, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->routeMatch = $current_route_match;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_route_match'),
      $container->get('entity_type.manager'),
    );
  }


  /**
   * Gets entity type manager.
   *
   * @return \Drupal\Core\Entity\EntityTypeManagerInterface
   *  The entity type manager.
   */
  public function getEntityTypeManager() {
    return $this->entityTypeManager;
  }

  /**
   * Gets current route match.
   *
   * @return \Drupal\Core\Routing\CurrentRouteMatch
   *  The current route match.
   */
  public function getRouteMatch() {
    return $this->routeMatch;
  }

  public function getTitle() {
    return 'Stats';
  }

  /**
   * {@inheritdoc}
   */
  public function getBodyPart() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getTypeOfTraining() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getExercise() {
    return NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getResults() {
    return NULL;
  }


  /**
   * {@inheritdoc}
   */
  public function getImageExercise() {
    return NULL;
  }
}