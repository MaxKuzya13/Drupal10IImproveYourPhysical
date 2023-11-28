<?php

namespace Drupal\kenny_tracker\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;
use Drupal\kenny_tracker\Service\TrackerMeasurements\KennyTrackerMeasurementsInterface;
use Drupal\taxonomy\Plugin\views\argument\Taxonomy;
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
  protected AccountProxyInterface|KennyTrackerMeasurementsInterface $trackerMeasurements;

  /**
   * Constructor by NewTrainingGirlsBlock.
   *
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *    The current user.
   * @param \Drupal\Core\Session\AccountProxyInterface $tracker_measurements
   *     The tracker measurements..
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder, AccountProxyInterface $current_user, KennyTrackerMeasurementsInterface $tracker_measurements) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->formBuilder = $form_builder;
    $this->currentUser = $current_user;
    $this->trackerMeasurements = $tracker_measurements;
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
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $uid = $this->currentUser->id();
    $tracking = $this->trackerMeasurements->isTrack($uid);

    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    $entity_type_manager = \Drupal::entityTypeManager();




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
      $tracking_measurements = $node_storage->load($tracking_measurements_id);
      $output['#markup'] = '<span> U have an available track </span>' ;
      $output['measurements'] = [
        'tracking_measurements' => $entity_type_manager
          ->getViewBuilder('node')
          ->view($tracking_measurements, 'full'),
      ];
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
