<?php

namespace Drupal\kenny_stats\Plugin\Block;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
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

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
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
      $paragraph = $this->getParagraph($body_part);



      if (!is_null($paragraph)) {
        $relative_paragraph = $this->getRelativeParagraph($paragraph, $limit);


        // Відображення параграфа, якщо він існує.
        $output['paragraph_' . $lower_body_part] = [
          '#prefix' => '<p>' . $body_part . '</p>',
          'paragraph' => $this->entityTypeManager
            ->getViewBuilder('paragraph')
            ->view($paragraph, 'stats'),
          'relative_paragraph' => $this->entityTypeManager
            ->getViewBuilder('paragraph')
            ->view($relative_paragraph, 'stats')
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


  public function getParagraph($body_part) {

    $lower_body_part = strtolower($body_part);
    switch ($lower_body_part) {
      case 'chest':
        $exercise = $this->getExercises($lower_body_part);
        $exercise_chest_value = 'Жим лежачи';
        $exercise_id = array_search($exercise_chest_value, $exercise);
        if ($exercise_id) {
          $last_paragraph = $this->getLastParagraph($exercise_id);
        }
        break;

      case 'biceps':
        $exercise = $this->getExercises($lower_body_part);
        $exercise_chest_value = 'Біцепс гантелями';
        $exercise_id = array_search($exercise_chest_value, $exercise);
        if ($exercise_id) {
          $last_paragraph = $this->getLastParagraph($exercise_id);
        }
        break;

      case 'shoulders':
        $exercise = $this->getExercises($lower_body_part);
        $exercise_chest_value = 'Сидячі гантелі вгору';
        $exercise_id = array_search($exercise_chest_value, $exercise);
        if ($exercise_id) {
          $last_paragraph = $this->getLastParagraph($exercise_id);
        }
        break;

      case 'legs':
        $exercise = $this->getExercises($lower_body_part);
        $exercise_chest_value = 'Присідання';
        $exercise_id = array_search($exercise_chest_value, $exercise);
        if (!empty($exercise_id)) {
          $last_paragraph = $this->getLastParagraph($exercise_id);
        }


        break;

      case 'triceps':
        $exercise = $this->getExercises($lower_body_part);
        $exercise_chest_value = 'Французький жим';
        $exercise_id = array_search($exercise_chest_value, $exercise);
        if ($exercise_id) {
          $last_paragraph = $this->getLastParagraph($exercise_id);
        }
        break;

      case 'back':
        $exercise = $this->getExercises($lower_body_part);
        $exercise_chest_value = 'Станова тяга';
        $exercise_id = array_search($exercise_chest_value, $exercise);
        if ($exercise_id) {
          $last_paragraph = $this->getLastParagraph($exercise_id);
        }
        break;

    }

    return $last_paragraph;
  }

  protected function getExercises($lower_body_part) {
    $exercise_id = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadTree($lower_body_part);

    $exercises = [];

    if($exercise_id) {
      foreach ($exercise_id as $item) {
        $exercises[$item->tid] = $item->name;
      }
    }
    return $exercises;
  }

  protected function getLastParagraph($exercise_id) {

    $node_storage = $this->entityTypeManager->getStorage('node');

    $nids = $node_storage->getQuery()
      ->condition('type', 'training_plan')
      ->exists('field_exercises')
      ->accessCheck(FALSE)
      ->condition('field_exercises.entity:paragraph.field_exercise', $exercise_id)
      ->sort('field_training_date', 'DESC')
      ->range(0, 1)
      ->execute();

    if (!empty($nids)) {
      $latest_node_id = reset($nids);
      $latest_node = $node_storage->load($latest_node_id);

      $paragraphs = $latest_node->get('field_exercises')->referencedEntities();

      foreach ($paragraphs as $paragraph) {
        $field_exercise_value = $paragraph->get('field_exercise')->target_id;
        $pid = $paragraph->id();
        if ($field_exercise_value == $exercise_id) {
          $last_paragraph = $this->entityTypeManager->getStorage('paragraph')
            ->load($pid);
          return $last_paragraph;
        }


      }
    }

    return null;

  }

  protected function getRelativeParagraph($paragraph, $limit) {


    if ($paragraph instanceof Paragraph) {
      $exercise_id = $paragraph->get('field_exercise')->target_id;
    }

    $node_storage = $this->entityTypeManager->getStorage('node');

    $current_date = new \DateTime('now', new \DateTimeZone('UTC'));
    $start_date = clone $current_date;

    switch ($limit) {

      case '1 month':
        $start_date->modify('-1 month');
        break;

      case '3 month':
        $start_date->modify('-3 month');
        break;

      case '6 month':
        $start_date->modify('-6 month');
        break;

      case '1 year':
        $start_date->modify('-1 year');
        break;

      case 'default':
        $start_date->modify('-2 year');
        break;

      default:
        $start_date->modify('-10 year');
    }

    $nids = $node_storage->getQuery()
      ->condition('type', 'training_plan')
      ->exists('field_exercises')
      ->accessCheck(FALSE)
      ->condition('field_exercises.entity:paragraph.field_exercise', $exercise_id)
      ->condition('field_training_date', $start_date->format('Y-m-d'),'>=')
      ->sort('field_training_date', 'ASC')
      ->range(0, 1)
      ->execute();


    if (!empty($nids)) {
      $first_node_id = reset($nids);
      $first_node = $node_storage->load($first_node_id);

      $paragraphs = $first_node->get('field_exercises')->referencedEntities();

      foreach ($paragraphs as $paragraph) {
        $field_exercise_value = $paragraph->get('field_exercise')->target_id;
        $pid = $paragraph->id();
        if ($field_exercise_value == $exercise_id) {
          $first_paragraph = $this->entityTypeManager->getStorage('paragraph')
            ->load($pid);
          return $first_paragraph;
        }


      }
    }

    return null;
  }

}