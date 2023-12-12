<?php

namespace Drupal\kenny_stats\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\kenny_stats\Service\KennyGirlsStatsByExerciseInterface;
use Drupal\kenny_stats\Service\KennyStatsByExerciseInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Provides a 'Stats by exercise' block.
 *
 * @Block(
 *   id = "kenny_stats_block",
 *   admin_label = @Translation("Kenny Stats by exercise Block"),
 * )
 */

class KennyStatsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Config Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The stats by exercise.
   *
   * @var \Drupal\kenny_stats\Service\KennyStatsByExerciseInterface;
   */
  protected $statsByExercise;


  /**
   * Constructor for KennyStatsBlock.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory, KennyStatsByExerciseInterface $stats_by_exercise) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->statsByExercise = $stats_by_exercise;
  }

  /**
   * Container for KennyStatsBlock
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('kenny_stats.exercise_stats'),
    );
  }


  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\kenny_stats\Form\StatsDateForm');
    $output['form'] = $form;
    // Отримати значення, яке приходить з форми, якщо воно встановлено в сесії.
    $value = isset($_SESSION['kenny_stats_form_value']) ? $_SESSION['kenny_stats_form_value'] : '';
    $limit = !empty($value) ? $value : '1 year';
    // Знищити значення в сесії, оскільки ми його вже отримали.
    unset($_SESSION['kenny_stats_form_value']);
    $output['stats_for_time'] = [
      '#type' => 'html_tag',
      '#tag' => 'h3',
      '#value' => 'Stats for the last ' . $limit,
      '#attributes' => [
        'class' => ['kenny-stats-title'],
      ],
    ];
    $config = $this->configFactory->get('kenny_stats.settings');
    $exercises_array = $this->statsByExercise->getExercisesArray($config);

    /** @var \Drupal\taxonomy\TermStorageInterface $body_part_list */
    $body_part_list = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadTree('body_part');

    // ----------------------------------------- Measurements
    $current_uid = \Drupal::currentUser()->id();
    $measurements = $this->statsByExercise->getMeasurements($current_uid, $limit);

    $output['measurements']['container'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['measurements-container-stats']],
    ];



    if (isset($measurements['last_measurements'])) {
      $output['measurements']['container']['measurements_last'] = $this->entityTypeManager
          ->getViewBuilder('node')
          ->view($measurements['last_measurements'], 'teaser');

    }


    if (isset($measurements['first_measurements'])) {
      $output['measurements']['container']['measurements_first'] = $this->entityTypeManager
          ->getViewBuilder('node')
          ->view($measurements['first_measurements'], 'teaser');

    }

      $result_measurements = $this->statsByExercise
        ->getMeasurementsResults($measurements['last_measurements'], $measurements['first_measurements']);



    $output['measurements']['container']['results'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['result-measurements-container-stats']],
    ];

    foreach ($result_measurements as $name => $res) {
      if (strpos($res, '-') !== false) {
        $class = 'less';
      }  else {
        $class = 'grow';
      }

      if ($name == 'created') {
        $output['measurements']['container']['results'][$name] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $res . ' days',
          '#attributes' => [
            'class' => ['kenny-stats-days-by-period'],
          ],
        ];
      } elseif ($name !== 'weight') {

        $output['measurements']['container']['results'][$name] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $res . ' sm',
          '#attributes' => [
            'class' => ['kenny-stats-body-part-by-period', $class],
          ],
        ];
      } else {
        $output['measurements']['container']['results'][$name] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $res . ' kg',
          '#attributes' => [
            'class' => ['kenny-stats-body-part-by-period', $class],
          ],
        ];
      }

    }




    // ----------------------------------------- Measurements



    //------------------------------------------
    $training_people = 'man';

    $count_of_training = $this->statsByExercise->getNumberOfTraining($training_people, $limit);

    $output['count_of_training']['container'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['count-of-training']],
    ];

    $output['count_of_training']['container']['total_stats'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['count-of-training__total-stats']],
    ];

    foreach ($count_of_training as $count => $value) {

      $output['count_of_training']['container']['total_stats'][$count] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' =>  $count . ' training: ' . $value,
        '#attributes' => [
          'class' => ['count-of-training'],
        ],
      ];
    }



    $count_by_body_part = $this->statsByExercise->getNumberOfTrainingByBodyPart($training_people, $limit);
//
//    $count_output = array_keys($count_by_body_part);

    foreach ($count_by_body_part as $body_part => $value) {
      $lower_case = strtolower($body_part);

      $output['count_of_training']['container']["count_of_{$lower_case}"] = [
        '#type' => 'container',
        '#attributes' => [
          'class' => ['count-of-training__body-part']
        ],
      ];

      foreach ($value as $name => $val) {
        $lower_case_name = strtolower($name);
        $output['count_of_training']['container']["count_of_{$lower_case}"][$lower_case_name] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' =>  $body_part . " {$name}: " . $val,
          '#attributes' => [
            'class' => ["count-of-{$lower_case_name}"],
          ],
        ];
      }
    }





    $most_popular_exercise = $this->statsByExercise->mostPopularExercise($training_people, $limit);

    $output["most_popular_exercises-title"] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' =>  "Most popular exercises ({$most_popular_exercise['count']} replacement)",
      '#attributes' => [
        'class' => ["most-popular-exercises__title"],
      ],
    ];

    $output['most_popular_exercises']['container'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['most-popular-exercises']
      ],
    ];


    foreach ($most_popular_exercise['exercises_names'] as $exercise) {
      $type = strtolower(str_replace(' ', '_', $exercise));

      $output['most_popular_exercises']['container']["most_popular_exercise_{$type}"] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' =>  $exercise,
        '#attributes' => [
          'class' => ["most-popular-exercises__items"],
        ],
      ];
    }



    //------------------------------------------

    foreach ($body_part_list as $term) {
      $body_part = $term->name;
      $lower_body_part = strtolower(str_replace(' ', '', $body_part));

      // Отримати параграф для поточного "Body part".
      $paragraph = $this->statsByExercise->getParagraph($body_part, $exercises_array);

      $media = $this->statsByExercise->getMedia($body_part);

      if (!is_null($paragraph)) {
        $relative_paragraph = $this->statsByExercise->getCurrentParagraph('man', '', $paragraph, $limit);

        if (!empty($relative_paragraph)) {
          $result = $this->statsByExercise->getResults($paragraph, $relative_paragraph);
          $workWeightText = $this->t('Ur working weight @weight_class by @absolute_weight kg / @correlation_weight%', [
            '@weight_class' => $result['weight_class'],
            '@absolute_weight' => $result['absolute_weight'],
            '@correlation_weight' => $result['correlation_weight'],

          ]);
        } else {
          $relative_output = [
            '#prefix' => '<p>' . 'No relative training by ' . $body_part . '</p>',
          ];
        }
        // Відображення параграфа, якщо він існує.

        $output['paragraph_' . $lower_body_part] = [
          '#prefix' => '<p>' . $body_part . '</p>',
          'paragraph' => $this->entityTypeManager
            ->getViewBuilder('paragraph')
            ->view($paragraph, 'stats'),
          'relative_paragraph' => !$relative_paragraph ? $relative_output : $this->entityTypeManager
            ->getViewBuilder('paragraph')
            ->view($relative_paragraph, 'stats'),
          'working_weight' => $relative_paragraph ? [
            '#markup' => '<p>' . $workWeightText . '</p>',
          ] : [],
          'media_paragraph' => $this->entityTypeManager
            ->getViewBuilder('media')
            ->view($media, 'full')
        ];

      } else {
        // Відобразити повідомлення про відсутність тренувань для "Body part".
        $output['paragraph_' . $lower_body_part] = [
          '#prefix' => '<p>' . 'No training by ' . $body_part . '</p>',
        ];
      }
    }
    return $output;
  }



}