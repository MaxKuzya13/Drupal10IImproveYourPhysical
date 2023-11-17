<?php

namespace Drupal\kenny_stats\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\kenny_stats\Service\KennyGirlsStatsByExerciseInterface;
use Drupal\kenny_stats\Service\KennyStatsByExerciseInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

    $form = \Drupal::formBuilder()->getForm('Drupal\kenny_stats\Form\TestDateForm');
    $output['form'] = $form;
    // Отримати значення, яке приходить з форми, якщо воно встановлено в сесії.
    $value = isset($_SESSION['kenny_stats_form_value']) ? $_SESSION['kenny_stats_form_value'] : '';
    $limit = !empty($value) ? $value : '1 year';
    // Знищити значення в сесії, оскільки ми його вже отримали.
    unset($_SESSION['kenny_stats_form_value']);

    $config = $this->configFactory->get('kenny_stats.settings');
    $exercises_array = $this->statsByExercise->getExercisesArray($config);

    /** @var \Drupal\taxonomy\TermStorageInterface $body_part_list */
    $body_part_list = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadTree('body_part');


    //------------------------------------------
    $training_people = 'man';

    $count_of_training = $this->statsByExercise->getNumberOfTraining($training_people, $limit);
    $count_of_force_training = $this->statsByExercise->getNumberOfTrainingByTrainingType($training_people, $limit, 'force');
    $count_of_intensive_training = $this->statsByExercise->getNumberOfTrainingByTrainingType($training_people, $limit, 'intensive');

    $output['count_of_training'] = [
      '#markup' => "<span>" . 'A total of ' . $count_of_training['count'] . ' training were held in the last ' . $limit . "</span>"
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