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
  public function getMeasurements($uid, $limit) {
    $node_storage = $this->entityTypeManager->getStorage('node');
    $start_date = $this->switchDate($limit);
    $output = [];

    $last_query = $node_storage->getQuery()
      ->condition('type', 'measurements')
      ->condition('field_uid', $uid)
      ->condition('field_created', $start_date->format('Y-m-d'),'>=')
      ->accessCheck(FALSE)
      ->sort('field_created', 'DESC')
      ->range(0, 1);

    $last_nids = $last_query->execute();
    if ($last_nids) {
      $last_measurements_id = reset($last_nids);
      $output['last_measurements'] = $node_storage->load($last_measurements_id);
    }

    $first_query = $node_storage->getQuery()
      ->condition('type', 'measurements')
      ->condition('field_uid', $uid)
      ->condition('field_created',$start_date->format('Y-m-d'),'>=')
      ->accessCheck(FALSE)
      ->sort('field_created', 'ASC')
      ->range(0, 1);
    $first_nids = $first_query->execute();
    if ($first_nids) {
      $first_measurements_id = reset($first_nids);
      $output['first_measurements'] = $node_storage->load($first_measurements_id);
    }

    return $output;
  }

  /**
   * {@inheritdoc}
   */
  public function getMeasurementsResults($last, $first) {

    $output = [];

    $output['created'] = $this->mathResults('created', $last, $first);
    $output['height'] = $this->mathResults('height', $last, $first);
    $output['weight'] = $this->mathResults('weight', $last, $first);
    $output['neck'] = $this->mathResults('neck', $last, $first);
    $output['chest'] = $this->mathResults('chest', $last, $first);
    $output['biceps'] = $this->mathResults('biceps', $last, $first);
    $output['forearms'] = $this->mathResults('forearms', $last, $first);
    $output['waist'] = $this->mathResults('waist', $last, $first);
    $output['glutes'] = $this->mathResults('glutes', $last, $first);
    $output['thigh'] = $this->mathResults('thigh', $last, $first);


    return $output;

  }

  /**
   * Math results in string.
   *
   * @param string $type
   *   The name of muscle.
   * @param \Drupal\node\NodeInterface $last
   *    Last measuruments.
   * @param \Drupal\node\NodeInterface $first
   *    First measurements by period.
   * @return string
   */
  protected function mathResults($type, $last, $first) {
    if($type !== 'created') {
      if ($last->get("field_{$type}")->value >= $first->get("field_{$type}")->value) {
        $result = '+ ' . $last->get("field_{$type}")->value - $first->get("field_{$type}")->value;
      } else {
        $result = '- ' . $first->get("field_{$type}")->value - $last->get("field_{$type}")->value;
      }
    } else {
      $date_last = new \DateTime($last->get("field_{$type}")->value);
      $date_first = new \DateTime($first->get("field_{$type}")->value);
      $difference = $date_last->diff($date_first);
      $result = $difference->days;

    }




    return $result;
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

    $input_array = $this->getMainFields($training_people);
    extract($input_array);

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

      $start_date = $this->switchDate($limit);
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
        return $media_storage->load(72);
        break;

    }

    return null;
  }

  // -------------------------------------------------------------------------
  // Блок по статистиці загальній, кількість тренувань, скільки на яку групу м'язів,
  // скільки по інтенсивності,
  /**
   * {@inheritdoc}
   */

  public function getNumberOfTraining($training_people, $limit) {
    $output = [];

    $start_date = $this->switchDate($limit);
    $input_array = $this->getMainFields($training_people);
    extract($input_array);

    $node_storage = $this->entityTypeManager->getStorage('node');

    $query = $node_storage->getQuery()
      ->condition('type', $training)
      ->exists($field_exercises)
      ->accessCheck(FALSE)
      ->condition($field_training_date, $start_date->format('Y-m-d'),'>=');

    $nids = $query->execute();

    if (!empty($nids)) {
      $output['Total'] = count($nids);
    } else {
      $output['Total'] = 0;
    }

    $name_type_of_training = ['Force', 'Intensive'];
    foreach ($name_type_of_training as $name) {
      $type_of_training_id = $this->entityTypeManager->getStorage('taxonomy_term')
        ->loadByProperties(['name' => $name, 'vid' => $type_of_training]);
      $type_of_training_id = reset($type_of_training_id)->id();

      $node_storage = $this->entityTypeManager->getStorage('node');

      $query = $node_storage->getQuery()
        ->condition('type', $training)
        ->condition($field_type_of_training, $type_of_training_id)
        ->exists($field_exercises)
        ->accessCheck(FALSE)
        ->condition($field_training_date, $start_date->format('Y-m-d'),'>=');

      $nids = $query->execute();

      if (!empty($nids)) {
        $output[$name] = count($nids);
      } else {
        $output[$name] = 0;
      }
    }

    return $output;
  }



  /**
   * {@inheritdoc}
   */
  public function getNumberOfTrainingByBodyPart($training_people, $limit) {

    $start_date = $this->switchDate($limit);

    $input_array = $this->getMainFields($training_people);
    extract($input_array);

    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $node_storage = $this->entityTypeManager->getStorage('node');


    $force_id = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadByProperties(['name' => 'Force', 'vid' => $type_of_training]);
    $force_id = reset($force_id)->id();

    $intensive_id = $this->entityTypeManager->getStorage('taxonomy_term')
        ->loadByProperties(['name' => 'Intensive', 'vid' => $type_of_training]);
    $intensive_id = reset($intensive_id)->id();

    if ($training_people == 'girl') {
      $taxonomy_tree = $term_storage->loadTree('girls_body_part');
    } else {
      $taxonomy_tree = $term_storage->loadTree('body_part');
    }


    $output = [];
    foreach ($taxonomy_tree as $term) {

      $query = $node_storage->getQuery()
        ->condition('type', $training)
        ->condition($field_body_part, $term->tid)
        ->exists($field_exercises)
        ->accessCheck(FALSE)
        ->condition($field_training_date, $start_date->format('Y-m-d'),'>=');

      $nids = $query->execute();
      if (!empty($nids)) {
        $output[$term->name]['Total'] = count($nids);
      } else {
        $output[$term->name]['Total'] = 0;
      }

      $query_force = $node_storage->getQuery()
        ->condition('type', $training)
        ->condition($field_body_part, $term->tid)
        ->condition($field_type_of_training, $force_id)
        ->exists($field_exercises)
        ->accessCheck(FALSE)
        ->condition($field_training_date, $start_date->format('Y-m-d'),'>=');

      $force_ids = $query_force->execute();
      if (!empty($force_ids)) {
        $output[$term->name]['Force'] = count($force_ids);
      } else {
        $output[$term->name]['Force'] = 0;
      }

      $query_intensive = $node_storage->getQuery()
        ->condition('type', $training)
        ->condition($field_body_part, $term->tid)
        ->condition($field_type_of_training, $intensive_id)
        ->exists($field_exercises)
        ->accessCheck(FALSE)
        ->condition($field_training_date, $start_date->format('Y-m-d'),'>=');

      $intensive_ids = $query_intensive->execute();
      if (!empty($intensive_ids)) {
        $output[$term->name]['Intensive'] = count($intensive_ids);
      } else {
        $output[$term->name]['Intensive'] = 0;
      }


    }
    return $output;

  }

  /**
   * {@inheritdoc}
   */
  public function mostPopularExercise($training_people, $limit) {

    $start_date = $this->switchDate($limit);

    $input_array = $this->getMainFields($training_people);
    extract($input_array);

    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $node_storage = $this->entityTypeManager->getStorage('node');
    $paragraph_storage = $this->entityTypeManager->getStorage('paragraph');

//    $force_id = $this->entityTypeManager->getStorage('taxonomy_term')
//      ->loadByProperties(['name' => 'Force', 'vid' => $type_of_training]);
//    $force_id = reset($force_id)->id();
//
//    $intensive_id = $this->entityTypeManager->getStorage('taxonomy_term')
//      ->loadByProperties(['name' => 'Intensive', 'vid' => $type_of_training]);
//    $intensive_id = reset($intensive_id)->id();

    if ($training_people == 'girl') {
      $taxonomy_tree = $term_storage->loadTree('girls_body_part');
    } else {
      $taxonomy_tree = $term_storage->loadTree('body_part');
    }
//
//    $output = [];
    $count_tid = [];

    foreach ($taxonomy_tree as $term) {
      $query = $node_storage->getQuery()
        ->condition('type', $training)
        ->condition($field_body_part, $term->tid)
        ->exists($field_exercises)
        ->accessCheck(FALSE)
        ->condition($field_training_date, $start_date->format('Y-m-d'),'>=');

      $nids = $query->execute();


      if (!empty($nids)) {
        foreach ($nids as $nid) {
          $node = $node_storage->load($nid);
          $paragraphs = $node->get($field_exercises)->referencedEntities();

          foreach ($paragraphs as $paragraph) {
            $count_tid[] = $paragraph->get($field_exercise)->target_id;
          }
        }


//        $paragraphs = $nodes->get($field_exercises)->referencedEntities();

      }
    }

    $value_counts = array_count_values($count_tid);

    $max_count = max($value_counts);

    $max_value_keys = array_keys($value_counts, max($value_counts));

    $exercise_info = [
      'count' => $max_count,
      'exercises_names' => [],
    ];

    $exercise_names = [];

    if (is_array($max_value_keys)) {
      foreach ($max_value_keys as $tid) {
        $exercise_storage = $term_storage->load($tid);
        $exercise_names[] = $exercise_storage->getName();

      }
    }
    $exercise_info['exercises_names'] = $exercise_names;
    return $exercise_info;
  }

  /**
   * Main fields.
   *
   * @param string $training_people
   *   The name of people who do training.
   * @return array
   */
  protected function getMainFields($training_people) {
    if ($training_people == 'man') {
      $output['training'] = 'training_plan';
      $output['type_of_training'] = 'type_of_training';
      $output['field_type_of_training'] = 'field_type_of_training';
      $output['field_body_part'] = 'field_body_part';
      $output['field_exercises'] = 'field_exercises';
      $output['field_exercise'] = 'field_exercise';
      $output['condition_exercises'] = 'field_exercises.entity:paragraph.field_exercise';
      $output['field_training_date'] = 'field_training_date';

    } elseif ($training_people == 'girl') {
      $output['training'] = 'girls_training';
      $output['type_of_training'] = 'girls_type_of_training';
      $output['field_type_of_training'] = 'field_girls_type_of_training';
      $output['field_body_part'] = 'field_girls_body_part';
      $output['field_exercises'] = 'field_girls_exercises';
      $output['field_exercise'] = 'field_girl_exercise';
      $output['condition_exercises'] = 'field_girls_exercises.entity:paragraph.field_girl_exercise';
      $output['field_training_date'] = 'field_girls_training_date';
    }

    return $output;
  }

  /**
   * Set start date.
   *
   * @param string $limit
   *   The timeline.
   * @return \DateTime
   * @throws \Exception
   */
  protected function switchDate($limit) {
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

    return $start_date;
  }
}