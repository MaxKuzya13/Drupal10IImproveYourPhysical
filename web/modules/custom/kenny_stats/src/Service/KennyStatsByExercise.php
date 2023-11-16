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
   *
   */
  protected $entityTypeManager;

  /**
   * @param EntityTypeManagerInterface $entity_type_manager
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * @param ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getParagraph($body_part, $exercises_array) {

    // Upper Body -> upper_body
    $lower_body_part = strtolower(str_replace(' ', '_', $body_part));

    $key = $lower_body_part;
    $exercise_id = $exercises_array[$key] ?? null;

    if ($exercise_id) {
      return $this->getCurrentParagraph('man', $exercise_id);
    }

    return '';

  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentParagraph($training_people, $exercise_id = '', $paragraph = '', $limit = '') {


    if ($training_people == 'man') {
      $training = 'training_plan';
      $type_of_training = 'type_of_training';
      $field_type_of_training = 'field_type_of_training';
      $field_exercises = 'field_exercises';
      $field_exercise = 'field_exercise';
      $condition_exercises = 'field_exercises.entity:paragraph.field_exercise';
      $field_training_date = 'field_training_date';

    } elseif ($training_people == 'girl') {
      $training = 'girls_training';
      $type_of_training = 'girls_type_of_training';
      $field_type_of_training = 'field_girls_type_of_training';
      $field_exercises = 'field_girls_exercises';
      $field_exercise = 'field_girl_exercise';
      $condition_exercises = 'field_girls_exercises.entity:paragraph.field_girl_exercise';
      $field_training_date = 'field_girls_training_date';
    }

    $force = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadByProperties(['name' => 'Force', 'vid' => $type_of_training]);
    $force = reset($force)->id();

    if (!empty($exercise_id)) {
      /** @var \Drupal\node\NodeStorageInterface $node_storage */
      $node_storage = $this->entityTypeManager->getStorage('node');
    } elseif ($paragraph instanceof Paragraph) {
      $exercise_id = $paragraph->get($field_exercise)->target_id;

      /** @var \Drupal\node\NodeStorageInterface $node_storage */
      $node_storage = $this->entityTypeManager->getStorage('node');
      $current_date = new \DateTime('now', new \DateTimeZone('UTC'));
      $start_date = clone $current_date;

      switch ($limit) {
        case '1 day':
          $start_date->modify('-1 day');
          break;

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
    }

    if (!isset($start_date)) {
      $query = $node_storage->getQuery()
        ->condition('type', $training)
        ->condition($field_type_of_training, $force)
        ->exists($field_exercises)
        ->accessCheck(FALSE)
        ->condition($condition_exercises, $exercise_id)
        ->sort($field_training_date, 'DESC')
        ->range(0, 1);
    } else {
      $query = $node_storage->getQuery()
        ->condition('type', $training)
        ->condition($field_type_of_training, $force)
        ->exists($field_exercises)
        ->accessCheck(FALSE)
        ->condition($condition_exercises, $exercise_id)
        ->condition($field_training_date, $start_date->format('Y-m-d'),'>=')
        ->sort($field_training_date, 'ASC')
        ->range(0, 1);
    }

    $nids = $query->execute();

    if (!empty($nids)) {
      // Get single nid.
      $current_node_id = reset($nids);

      /** @var \Drupal\node\NodeStorageInterface $current_node */
      $current_node = $node_storage->load($current_node_id);

      /** @var \Drupal\paragraphs\ParagraphInterface $paragraphs */
      $paragraphs = $current_node->get($field_exercises)->referencedEntities();

      foreach ($paragraphs as $paragraph) {
        $field_exercise_value = $paragraph->get($field_exercise)->target_id;
        $pid = $paragraph->id();
        if ($field_exercise_value == $exercise_id) {
          /** @var \Drupal\paragraphs\ParagraphInterface $current_paragraph */
          $current_paragraph = $this->entityTypeManager->getStorage('paragraph')
            ->load($pid);
          return $current_paragraph;
        }

      }
    }

    return null;

  }


  /**
   * {@inheritdoc }
   */
  public function getExercisesArray($config) {

    /** @var \Drupal\taxonomy\TermStorageInterface $body_parts */
    $body_parts = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadTree('body_part');

    // Get array of body part
    $body_part_names = [];
    foreach ($body_parts as $body_part) {
      $name = $body_part->name;
      $body_part_names[] = strtolower(str_replace(' ', '_', $name));
    }

    // Get array of body part name => tid
    $exercises_array = [];
    foreach ($body_part_names as $exercise) {
      $exercises_array[$exercise] = $config->get($exercise);
    }


    return $exercises_array;
  }

  /**
   * {@inheritdoc }
   */
  public function getResults($paragraph, $relative_paragraph) {
    // Work weight of last paragraph.
    $current_weight = $paragraph->get('field_weight')->value;

    // Work weight of relative paragraph.
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
        return $media_storage->load(4);
        break;

      case 'Biceps':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_storage->load(5);
        break;

      case 'Shoulders':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_storage->load(7);
        break;

      case 'Legs':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_storage->load(8);
        break;

      case 'Triceps':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_storage->load(6);
        break;

      case 'Back':
        /** @var \Drupal\media\MediaStorage $media_storage */
        $media_storage = $this->entityTypeManager->getStorage('media');
        return $media_storage->load(3);
        break;

    }

    return null;
  }


}