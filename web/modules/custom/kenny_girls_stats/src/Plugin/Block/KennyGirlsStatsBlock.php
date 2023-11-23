<?php

namespace Drupal\kenny_girls_stats\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\kenny_stats\Service\KennyStatsByExerciseInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Stats by exercise' block.
 *
 * @Block(
 *   id = "kenny_girls_stats_block",
 *   admin_label = @Translation("Kenny Girls Stats by exercise Block"),
 * )
 */

class KennyGirlsStatsBlock extends BlockBase implements ContainerFactoryPluginInterface {

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
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param EntityTypeManagerInterface $entity_type_manager
   * @param ConfigFactoryInterface $config_factory
   * @param KennyStatsByExerciseInterface $stats_by_exercise
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory, KennyStatsByExerciseInterface $stats_by_exercise) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->statsByExercise = $stats_by_exercise;
  }

  /**
   * Container by KennyGirlsStatsBLock.
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
    $limit = !empty($value) ? $value : '1 day';
    // Знищити значення в сесії, оскільки ми його вже отримали.
    unset($_SESSION['kenny_stats_form_value']);

    $training_people = 'girl';
    $config = $this->configFactory->get('kenny_girls_stats.settings');

    $exercises_array = $config->get();

    //------------------------------------------

    $count_of_training = $this->statsByExercise->getNumberOfTraining($training_people, $limit);
    $count_of_force_training = $this->statsByExercise->getNumberOfTrainingByTrainingType($training_people, $limit, 'force');
    $count_of_intensive_training = $this->statsByExercise->getNumberOfTrainingByTrainingType($training_people, $limit, 'intensive');

    $output['count_of_training'] = [
      '#markup' => "<span>" . 'The total number of training per ' . $limit . ' : ' . $count_of_training['count'] . "</span>"
    ];

    $output['count_of_force_training'] = [
      '#markup' => "</br>" . "<span>" . 'Force training : ' . $count_of_force_training . "</span>"
    ];

    $output['count_of_intensive_training'] = [
      '#markup' => "</br>" . "<span>" . 'Intensive training : ' . $count_of_intensive_training . "</span>"
    ];



    $count_by_body_part = $this->statsByExercise->getNumberOfTrainingByBodyPart($training_people, $limit);

    $count_output = array_keys($count_by_body_part);

    foreach ($count_output as $type) {
      $key = strtolower(str_replace(' ', '_', $type));

      $output["count_of_{$key}"] = [
        '#markup' => "</br>" . "<span>" . "{$type} training : " . $count_by_body_part[$type] . "</span>"
      ];
    }

    $most_popular_exercise = $this->statsByExercise->mostPopularExercise($training_people, $limit);

    $output["most_popular_exercise"] = [
      '#markup' => "</br>" . "<span>" . 'Most popular exercise : '  . "</span>"
    ];

    foreach ($most_popular_exercise['exercises_names'] as $exercise) {
      $type = strtolower(str_replace(' ', '_', $exercise));

      $output["most_popular_exercise_{$type}"] = [
        '#markup' => "<span>" . $exercise . ' (' . $most_popular_exercise['count'] . ') ' . "</span>"
      ];
    }
    //------------------------------------------

    foreach ($exercises_array as $exercise_name => $exercise_id) {
      $paragraph = $this->statsByExercise->getCurrentParagraph($training_people, $exercise_id);
      $reformated_exercise_name = ucwords(str_replace('_', ' ', $exercise_name));

      if (!is_null($paragraph)) {
        $relative_paragraph = $this->statsByExercise->getCurrentParagraph('girl', '', $paragraph, $limit);

        if (!empty($relative_paragraph)) {
          $result = $this->statsByExercise->getResults($paragraph, $relative_paragraph);
          $workWeightText = $this->t('Ur working weight @weight_class by @absolute_weight kg / @correlation_weight%', [
            '@weight_class' => $result['weight_class'],
            '@absolute_weight' => $result['absolute_weight'],
            '@correlation_weight' => $result['correlation_weight'],

          ]);
        } else {
          $relative_output = [
            '#prefix' => '<p>' . 'No relative training by ' . $reformated_exercise_name . '</p>',
          ];
        }

        $output['paragraph_' . $exercise_name] = [
          '#prefix' => '<p>' . $reformated_exercise_name . '</p>',
          'paragraph' => $this->entityTypeManager
            ->getViewBuilder('paragraph')
            ->view($paragraph, 'stats'),
          'relative_paragraph' => !$relative_paragraph ? $relative_output : $this->entityTypeManager
            ->getViewBuilder('paragraph')
            ->view($relative_paragraph, 'stats'),
          'working_weight' => $relative_paragraph ? [
            '#markup' => '<p>' . $workWeightText . '</p>',
          ] : [],
        ];
      } else {
        // Відобразити повідомлення про відсутність тренувань для "Body part".

       $output['paragraph_' . $exercise_name] = [
          '#prefix' => '<p>' . 'No training by ' . $reformated_exercise_name . '</p>',
        ];
      }


    }
    return $output;





//      $media = $this->statsByExercise->getMedia($body_part);
//
//          'media_paragraph' => $this->entityTypeManager
//            ->getViewBuilder('media')
//            ->view($media, 'full')

  }



}