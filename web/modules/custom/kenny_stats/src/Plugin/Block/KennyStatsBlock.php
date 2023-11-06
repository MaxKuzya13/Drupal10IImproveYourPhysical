<?php

namespace Drupal\kenny_stats\Plugin\Block;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\kenny_stats\Service\KennyStatsByExerciseInterface;
use Drupal\media\MediaInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\paragraphs\ParagraphInterface;
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
   * The stats by exercise.
   *
   * @var \Drupal\kenny_stats\Service\KennyStatsByExercise;
   */
  protected KennyStatsByExerciseInterface $statsByExercise;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager, KennyStatsByExerciseInterface $stats_by_exercise) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->statsByExercise = $stats_by_exercise;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('kenny_stats.exercise_stats'),
    );
  }


  public function build() {

    $limit = '1 month';

    $output = [];

    $body_part_list = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadTree('body_part');

    foreach ($body_part_list as $term) {
      $body_part = $term->name;
      $lower_body_part = strtolower(str_replace(' ', '', $body_part));

      // Отримайте параграф для поточного "Body part".
      $paragraph = $this->statsByExercise->getParagraph($body_part);

      $media = $this->statsByExercise->getMedia($body_part);

      if (!is_null($paragraph)) {
        $relative_paragraph = $this->statsByExercise->getRelativeParagraph($paragraph, $limit);
        $result = $this->statsByExercise->getResults($paragraph, $relative_paragraph);
        $workWeightText = $this->t('Ur working weight @weight_class by @absolute_weight kg / @correlation_weight%', [
          '@weight_class' => $result['weight_class'],
          '@absolute_weight' => $result['absolute_weight'],
          '@correlation_weight' => $result['correlation_weight'],

        ]);


        // Відображення параграфа, якщо він існує.
        $output['paragraph_' . $lower_body_part] = [
          '#prefix' => '<p>' . $body_part . '</p>',
          'paragraph' => $this->entityTypeManager
            ->getViewBuilder('paragraph')
            ->view($paragraph, 'stats'),
          'relative_paragraph' => $this->entityTypeManager
            ->getViewBuilder('paragraph')
            ->view($relative_paragraph, 'stats'),
          'working_weight' => [
            '#markup' => '<p>' . $workWeightText . '</p>',
          ],
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