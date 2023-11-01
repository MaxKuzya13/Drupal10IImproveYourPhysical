<?php

namespace Drupal\kenny_training\Service\FilterDate;

use Drupal\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

class FilterDate implements FilterDateInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Component\DependencyInjection\ContainerInterface
   */
  protected $entityTypeManager;

  /**
   * Construct a database instance
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Create a new static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  public function filterTrainingByDate($selected_period = 'default') {
    $current_date = new \DateTime('now', new \DateTimeZone('UTC'));
    $start_date = clone $current_date;

    switch ($selected_period) {

      case '-1month':
        $start_date->modify('-1 month');
        break;

      case '-3month':
        $start_date->modify('-3 month');
        break;

      case '-6month':
        $start_date->modify('-6 month');
        break;

      case '-1year':
        $start_date->modify('-1 year');
        break;

      case 'default':
        $start_date->modify('-1 day');
        break;

      default:
        $start_date->modify('-10 year');
    }

    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'training_plan')
      ->condition('field_training_date', $start_date->format('Y-m-d'),'>=')
      ->sort('field_training_date', 'DESC')
      ->accessCheck(FALSE); // Bypass node access check, or adjust as needed.
    $nids = $query->execute();

    return !empty($nids) ? $this->entityTypeManager
      ->getStorage('node')
      ->loadMultiple($nids) : [];
  }
}