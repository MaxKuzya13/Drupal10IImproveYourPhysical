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

    $output['title'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' =>  $this->t('Active tracking'),
      '#attributes' => [
        'class' => ['tracker__title'],
      ],
    ];


    if (!$tracking) {
      $output['#attached']['library'] = 'core/drupal.dialog.ajax';
      $output = [
        '#theme' => 'links',
        '#links' => [
          'link' => [
            'title' => 'Create a new tracking',
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
      $output['#attached']['library'][] = 'kenny_tracker/kenny_tracker_script';
      $tracking_measurements_id = $this->trackerMeasurements->getTrackedMeasurements($uid);
      $tracking_measurements_id = reset($tracking_measurements_id);



      // Get name of body part that might be tracking.
      $selected_fields = $this->trackerMeasurements->selectedFields($tracking_measurements_id);

      // Values of started measurements.
      $started_measurements = $this->trackerMeasurements->getStarted($tracking_measurements_id, $selected_fields['fields']);

      // Values of relative measurements array.
      $relative_measurements = $this->trackerMeasurements->getRelative($tracking_measurements_id, $selected_fields['fields']);

      // Values of desired measurements.
      $decired_measurements = $this->trackerMeasurements->getDecired($tracking_measurements_id);


      // ------------------------------------------------------------------------------------------------- //

      $output['selected_fields']['container'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['tracker__selected-fields', 'tracker-container']],
      ];

      $output['selected_fields']['container']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' =>  $this->t('Body part:'),
        '#attributes' => [
          'class' => ['tracker__selected-fields__title', 'tracker-container__title'],
        ],
      ];

      foreach ($selected_fields['group'] as $k => $value) {
        $output['selected_fields']['container'][$k] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $value,
          '#attributes' => [
            'class' => ['tracker__selected-fields__body-part', 'tracker-container__each'],
          ],
        ];
      };

      // ------------------------------------------------------------------------------------------------- //

      $output['started_measurements']['container'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['tracker__started-measurements', 'tracker-container']],
      ];

      $output['started_measurements']['container']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' =>  $this->t('Started value:'),
        '#attributes' => [
          'class' => ['tracker__started-measurements-title', 'tracker-container__title'],
        ],
      ];

      foreach ($started_measurements as $k => $value) {
        $output['started_measurements']['container'][$k] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>   $value ,
          '#attributes' => [
            'class' => ['tracker__started-measurements-values', 'tracker-container__each'],
          ],
        ];
      };


      // ------------------------------------------------------------------------------------------------- //


      $desired_result = $this->trackerMeasurements->getDesiredResult($tracking_measurements_id);

      $output['desired_result']['container'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['tracker__desired-result', 'tracker-container']],
      ];

      $output['desired_result']['container']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' =>  $this->t('Decired - starter: '),
        '#attributes' => [
          'class' => ['tracker__desired-result__title', 'tracker-container__title'],
        ],
      ];

      foreach ($desired_result as $k => $value) {

        $output['desired_result']['container'][$k] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $value,
          '#attributes' => [
            'class' => ['tracker__desired-result__each', 'tracker-container__each'],
          ],
        ];

      };

      // ------------------------------------------------------------------------------------------------- //


      $output['decired_measurements']['container'] = [
        '#type' => 'container',
        '#attributes' => ['class' => ['tracker__decired-measurements', 'tracker-container']],
      ];

      $output['decired_measurements']['container']['title'] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' =>  $this->t('Decired value:'),
        '#attributes' => [
          'class' => ['tracker__decired-measurements__title', 'tracker-container__title'],
        ],
      ];

      foreach ($decired_measurements as $k => $value) {

        $output['decired_measurements']['container'][$k] = [

          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $value,
          '#attributes' => [
            'class' => ['tracker__decired-measurements-values', 'tracker-container__each'],
          ],
        ];

      };

      // ------------------------------------------------------------------------------------------------- //



      if(!empty($relative_measurements)) {
        $output['relative_measurements']['container'] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['tracker__relative-measurements', 'tracker-container']],
        ];

        $output['relative_measurements']['container']['title'] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $this->t('Relative value:'),
          '#attributes' => [
            'class' => ['tracker__relative-measurements__title', 'tracker-container__title'],
          ],
        ];

        foreach ($relative_measurements as $k => $value) {

          $output['relative_measurements']['container'][$k] = [
            '#type' => 'container',
            '#attributes' => ['class' => ['tracker__relative-measurements__each', ]],
          ];


          foreach ($value as $key => $val) {
            $output['relative_measurements']['container'][$k][$key] = [

              '#type' => 'html_tag',
              '#tag' => 'div',
              '#value' =>  $val ,
              '#attributes' => [
                'class' => ['tracker__relative-measurements__each__result', 'tracker-container__each'],
              ],
            ];
          }

        };
      };


      // ------------------------------------------------------------------------------------------------- //




      if (!empty($relative_measurements)) {
        $progress_over_time = $this->trackerMeasurements->getProgressOverTime($tracking_measurements_id);
        $output['progress_over_time']['container'] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['tracker__progress-over-time', 'tracker-container']],
        ];

        $output['progress_over_time']['container']['title'] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $this->t('Progress: '),
          '#attributes' => [
            'class' => ['tracker__progress-over-time__title', 'tracker-container__title'],
          ],
        ];

        $i = 0;
        foreach ($progress_over_time['body_part'] as $k => $value) {

          if ($k == 'date') {
            $class = $progress_over_time['class']['date'];
          } else {
            $class = $progress_over_time['class'][$i];
          }

          $output['progress_over_time']['container'][$k] = [
            '#type' => 'html_tag',
            '#tag' => 'div',
            '#value' =>  $value,
            '#attributes' => [
              'class' => ['tracker__progress-over-time__each', 'tracker-container__each', $class],
            ],
          ];
          $i++;


        };
      }


      // ------------------------------------------------------------------------------------------------- //


      $still_left = $this->trackerMeasurements->isStillLeft($tracking_measurements_id);



      if (!empty($still_left)) {

        $output['still_left']['container'] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['tracker__still-left', 'tracker-container']],
        ];

        $output['still_left']['container']['title'] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $this->t('Left to decired: '),
          '#attributes' => [
            'class' => ['tracker__still-left__title', 'tracker-container__title'],
          ],
        ];

        $index = 0;
        foreach ($still_left['body_part'] as $k => $value) {

          $class = $still_left['class'][$index];
          $output['still_left']['container'][$k] = [
            '#type' => 'html_tag',
            '#tag' => 'div',
            '#value' =>  $value,
            '#attributes' => [
              'class' => ['tracker__still-left__each', $class],
            ],
          ];

          $index++;

        };
      }



//      $tracking_measurements = $node_storage->load($tracking_measurements_id);
////      dump($tracking_measurements);
//      $output['#markup'] = '<span> U have an available track </span>' ;
//      $output['measurements'] = [
//        'tracking_measurements' => $entity_type_manager
//          ->getViewBuilder('node')
//          ->view($tracking_measurements, 'full'),
//      ];
    }

    $nid = $tracking_measurements_id;

    $output['delete_tracker']['link'] = [
      '#theme' => 'links',
      '#links' => [
        'link' => [
          'title' => $this->t('Delete this track'),
          'url' => Url::fromRoute('entity.node.delete_form', ['node' => $nid]),
          'attributes' => [
            'class' => ['tracker__delete']
          ],
        ],
      ]
    ];
    return $output;



  }

  /**z
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {

    return AccessResult::allowed();
  }
}
