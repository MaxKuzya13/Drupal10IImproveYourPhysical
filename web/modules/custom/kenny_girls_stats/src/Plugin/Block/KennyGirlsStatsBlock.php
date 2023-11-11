<?php

namespace Drupal\kenny_girls_stats\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\kenny_girls_stats\Service\KennyGirlsStatsByExerciseInterface;
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
   * @var \Drupal\kenny_girls_stats\Service\KennyGirlsStatsByExerciseInterface;
   */
  protected $statsByExercise;


  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory, KennyGirlsStatsByExerciseInterface $stats_by_exercise) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->statsByExercise = $stats_by_exercise;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('kenny_girls_stats.exercise_stats'),
    );
  }


  public function build() {

    $limit = '3 month';

    $config = $this->configFactory->get('kenny_girls_stats.settings');
    dump($config);



//    $exercises_array = $this->statsByExercise->getExercisesArray($config);
//
//    $output = [];
//
//    $body_part_list = $this->entityTypeManager->getStorage('taxonomy_term')
//      ->loadTree('body_part');
//
//    foreach ($body_part_list as $term) {
//      $body_part = $term->name;
//      $lower_body_part = strtolower(str_replace(' ', '', $body_part));
//
//      // Отримайте параграф для поточного "Body part".
//      $paragraph = $this->statsByExercise->getParagraph($body_part, $exercises_array);
//
//      $media = $this->statsByExercise->getMedia($body_part);
//
//      if (!is_null($paragraph)) {
//        $relative_paragraph = $this->statsByExercise->getRelativeParagraph($paragraph, $limit);
//
//        if (!empty($relative_paragraph)) {
//          $result = $this->statsByExercise->getResults($paragraph, $relative_paragraph);
//          $workWeightText = $this->t('Ur working weight @weight_class by @absolute_weight kg / @correlation_weight%', [
//            '@weight_class' => $result['weight_class'],
//            '@absolute_weight' => $result['absolute_weight'],
//            '@correlation_weight' => $result['correlation_weight'],
//
//          ]);
//        } else {
//          $relative_output = [
//            '#prefix' => '<p>' . 'No relative training by ' . $body_part . '</p>',
//          ];
//        }
//        // Відображення параграфа, якщо він існує.
//        $output['paragraph_' . $lower_body_part] = [
//          '#prefix' => '<p>' . $body_part . '</p>',
//          'paragraph' => $this->entityTypeManager
//            ->getViewBuilder('paragraph')
//            ->view($paragraph, 'stats'),
//          'relative_paragraph' => !$relative_paragraph ? $relative_output : $this->entityTypeManager
//            ->getViewBuilder('paragraph')
//            ->view($relative_paragraph, 'stats'),
//          'working_weight' => $relative_paragraph ? [
//            '#markup' => '<p>' . $workWeightText . '</p>',
//          ] : [],
//          'media_paragraph' => $this->entityTypeManager
//            ->getViewBuilder('media')
//            ->view($media, 'full')
//        ];
//
//      } else {
//        // Відобразити повідомлення про відсутність тренувань для "Body part".
//        $output['paragraph_' . $lower_body_part] = [
//          '#prefix' => '<p>' . 'No training by ' . $body_part . '</p>',
//        ];
//      }
//    }
//    return $output;
  }



}