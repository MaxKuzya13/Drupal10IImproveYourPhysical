<?php

namespace Drupal\kenny_stats\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\paragraphs\Entity\Paragraph;
use Symfony\Component\DependencyInjection\ContainerInterface;

class KennyStatsByExercise implements KennyStatsByExerciseInterface {

  /**
   * The entity type manager
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  public function __construct( EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
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

  /**
   * {@inheritdoc}
   */
  public function getRelativeParagraph($paragraph, $limit) {

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

  /**
   * {@inheritdoc }
   */
  public function getResults($paragraph, $relative_paragraph) {
    $current_weight = $paragraph->get('field_weight')->value;
    $relative_weight = $relative_paragraph->get('field_weight')->value;
    if ($current_weight >= $relative_weight) {
      $absolute_weight = $current_weight - $relative_weight;
      $correlation_weight = ($current_weight / $relative_weight * 100 - 100);
      $weight_class = 'grower';
    } else {
      $absolute_weight = $relative_weight - $current_weight;
      $correlation_weight = ($relative_weight / $current_weight * 100 - 100);
      $weight_class = 'decrease';
    }

    if ($correlation_weight != round($correlation_weight)) {
      $correlation_weight = round($correlation_weight, 2);
    }

    $result = [
      'absolute_weight' => $absolute_weight,
      'correlation_weight' => $correlation_weight,
      'weight_class' => $weight_class
    ];
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function getMedia($body_part) {

    switch ($body_part) {
      case 'Chest':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_image = $media_storage->load(4);
        break;

      case 'Biceps':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_image = $media_storage->load(5);
        break;

      case 'Shoulders':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_image = $media_storage->load(7);
        break;

      case 'Legs':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_image = $media_storage->load(8);
        break;

      case 'Triceps':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_image = $media_storage->load(6);
        break;

      case 'Back':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_image = $media_storage->load(3);
        break;

    }

    return null;
  }

  /**
   * List of exercises by body part.
   *
   * @param string $lower_body_part
   *   Body part in lower register.
   * @return array
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
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

  /**
   * Loading last paragraph.
   *
   * @param int $exercise_id
   *   Exercise id.
   * @return \Drupal\Core\Entity\EntityInterface|null
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
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

}