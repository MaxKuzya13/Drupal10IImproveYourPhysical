<?php

namespace Drupal\kenny_tracker\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\kenny_tracker\Service\TrackerMeasurements\KennyTrackerMeasurementsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'New Tracker Measurements' block.
 *
 * @Block(
 *   id = "kenny_new_tracker_measurements_block",
 *   admin_label = @Translation("Kenny New Measurements Tracker Block"),
 * )
 */
class NewTrackerMeasurements extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The database.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface;
   */
  protected $currentUser;

  /**
   * The tracker measurements.
   *
   * @var \Drupal\kenny_tracker\Service\TrackerMeasurements\KennyTrackerMeasurementsInterface
   */
  protected $trackerMeasurements;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * Constructor by NewTrainingGirlsBlock.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user.
   * @param \Drupal\kenny_tracker\Service\TrackerMeasurements\KennyTrackerMeasurementsInterface $tracker_measurements
   *   The tracker measurements.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder, AccountProxyInterface $current_user, KennyTrackerMeasurementsInterface $tracker_measurements, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
    $this->currentUser = $current_user;
    $this->trackerMeasurements = $tracker_measurements;
    $this->entityTypeManager = $entity_type_manager;
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * Container by NewTrainingGirlsBlock.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('form_builder'),
      $container->get('current_user'),
      $container->get('kenny_tracker.tracker_measurements'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $uid = $this->currentUser->id();
    $tracking = $this->trackerMeasurements->isTrack($uid);

    /** @var \Drupal\node\NodeStorageInterface $node_storage */
    $node_storage = $this->nodeStorage;

    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = $this->entityTypeManager;


    if (!$tracking) {
      $output['#attached']['library'] = 'core/drupal.dialog.ajax';
      $output = [
        '#theme' => 'links',
        '#links' => [
          'link' => [
            'title' => 'Create a new training',
            'url' => Url::fromRoute('kenny_tracker.new_measurements_tracker'),
            'attributes' => [
              'class' => ['use-ajax', 'create-a-new-tracker'],
              'data-dialog-type' => 'modal',
              'data-dialog-options' => json_encode(['height' => 600, 'width' => '50vw']),
            ],
          ],
        ]
      ];
    } else {
      $tracking_measurements_id = $this->trackerMeasurements->getTrackedMeasurements($uid);
      $tracking_measurements_id = reset($tracking_measurements_id);

      // Get name of body part that might be tracking.
      $selected_fields = $this->trackerMeasurements->selectedFields($tracking_measurements_id);

      // Values of started measurements.
      $started_measurements = $this->trackerMeasurements->getStarted($tracking_measurements_id, $selected_fields['fields']);

      // Values of relative measurements array.
      $relative_measurements = $this->trackerMeasurements->getRelative($tracking_measurements_id, $selected_fields['fields']);

      // Values of desired meaqsurements.
      $decired_measurements = $this->trackerMeasurements->getDecired($tracking_measurements_id);

      foreach ($selected_fields['group'] as $k => $value) {
        $output['selected_fields'][$k] = [
          '#markup' => "</br>" . "<span>" . $value . "</span>"
        ];
      };

      foreach ($started_measurements as $k => $value) {
        $output['started_measurements'][$k] = [
          '#markup' => "</br>" . "<span>" . 'Started value ' . $value . "</span>"
        ];
      };

      foreach ($relative_measurements as $k => $value) {
        foreach ($value as $key => $val) {
          $output['relative_measurements'][$k][$key] = [
            '#markup' => "</br>" . "<span>" . 'Relative value ' . $val . "</span>"
          ];
        }

      };

      foreach ($decired_measurements as $k => $value) {
        $output['decired_measurements'][$k] = [
          '#markup' => "</br>" . "<span>" . 'Decired value ' . $value . "</span>"
        ];
      };


//      $tracking_measurements = $node_storage->load($tracking_measurements_id);
////      dump($tracking_measurements);
//      $output['#markup'] = '<span> U have an available track </span>' ;
//      $output['measurements'] = [
//        'tracking_measurements' => $entity_type_manager
//          ->getViewBuilder('node')
//          ->view($tracking_measurements, 'full'),
//      ];
    }



    return $output;



  }

  /**z
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {

    return AccessResult::allowed();
  }
}
