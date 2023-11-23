<?php

namespace Drupal\kenny_training\Service\DifferenceWeight;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DifferenceManager implements DifferenceManagerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   * */
  protected $entityTypeManager;

  /**
   * The paragraph storage.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $paragraphStorage;

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeStorage;


  /**
   * Construct a diffirence manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->paragraphStorage = $entity_type_manager->getStorage('paragraph');
    $this->nodeStorage = $entity_type_manager->getStorage('node');
  }

  /**
   * DiffirenceManager create container.
   */
  public function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getCurrentParagraph($pid, $people = 'man') {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $paragraph */
    $paragraph = $this->paragraphStorage->load($pid);
    if ($paragraph instanceof ParagraphInterface) {
      if ($people == 'man') {
        $exercise = $paragraph->get('field_exercise')->entity->id();
      } else {
        $exercise = $paragraph->get('field_girl_exercise')->entity->id();
      }


      // Get type of training 'intensive' or 'force'
      $type_of_training_id = $this->getTypeOfTrainingId($pid, $people);

      $weight = $paragraph->get('field_weight')->value;


      // Get a weight past training
      $relative_weight = $this->getRelativeWeight($pid, $exercise, $type_of_training_id, $people);
      if ($weight > $relative_weight) {
        $difference['weight'] = '+ ' . $weight - $relative_weight;
        $difference['class'] = 'grower';
      } elseif ($weight == $relative_weight) {
        $difference['weight'] = '+ 0';
        $difference['class'] = 'equal';
      } else {
        $difference['weight'] = '- ' . $relative_weight - $weight;
        $difference['class'] = 'less';
      }

      return $difference;
    }

    return null;
  }

  /**
   * {@inheritdoc}
   */
  public function getTypeOfTrainingId($pid, $people = 'man') {

    $nodes_using_paragraph = [];

    if ($people == 'man') {
      $field_name = 'field_exercises';
      $field_type_of_training = 'field_type_of_training';
    } else {
      $field_name = 'field_girls_exercises';
      $field_type_of_training = 'field_girls_type_of_training';
    }

    /** @var \Drupal\node\NodeStorageInterface $nodes */
    $nodes = $this->nodeStorage->loadMultiple();

    foreach ($nodes as $node) {
      // Check whether such a field exists.

      if ($node->hasField($field_name)) {
        $items = $node->get($field_name);

        // Check each paragraph.
        foreach ($items as $item) {
          if ($item->target_id == $pid) {
            // Save list of nodes.
            $nodes_using_paragraph[] = $node;
          }
        }
      }
    }

    foreach ($nodes_using_paragraph as $node) {
      if ($node->hasField($field_type_of_training)) {
        $field_value = $node->get($field_type_of_training)->getValue();
        foreach ($field_value as $item) {
          // Get a target id.
          $training_type = $item['target_id'];
        }
      }
      return $training_type;
    }
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getRelativeWeight($pid, $exercise, $type_of_training_id, $people = 'man') {

    if ($people == 'man') {
      $node_type = 'training_plan';
      $field_exercises = 'field_exercises';
      $field_exercise = 'field_exercise';
      $field_type_of_training = 'field_type_of_training';

    } else {
      $node_type = 'girls_training';
      $field_exercises = 'field_girls_exercises';
      $field_exercise = 'field_girl_exercise';
      $field_type_of_training = 'field_girls_type_of_training';
    }

    $query = $this->nodeStorage->getQuery()
      ->condition('type', $node_type)
      ->accessCheck('FALSE')
      ->condition('status', NodeInterface::PUBLISHED)
      ->condition($field_type_of_training, $type_of_training_id);


    $nids = $query->execute();

    $result_pids = [];
    foreach ($nids as $nid) {
      $node = $this->nodeStorage->load($nid);
      // Check nodes to field exists.
      if ($node && $node->hasField($field_exercises)) {
        // Get paragraphs.
        $referenced_paragraph = $node->get($field_exercises)->referencedEntities();
        foreach ($referenced_paragraph as $paragraph) {
          // Get all paragraphs that have exercise same mine
          if ($paragraph->hasField($field_exercise) && $paragraph->get($field_exercise)->target_id == $exercise) {
            $result_pids[] = $paragraph->id();
          }

        }
      }
    }

    // Sort by id, desc
    rsort($result_pids);
    $key = array_search($pid, $result_pids);
    if ($key !== false && $key < count($result_pids) - 1) {
      $relative_paragraph = $result_pids[$key + 1];
    } else {
      $relative_paragraph = [];
    }

    // Get weight past training
    if(!empty($relative_paragraph)) {
      $paragraph = $this->paragraphStorage->load($relative_paragraph);
      $relative_weight = $paragraph->get('field_weight')->value;
    } else {
      $relative_weight = 1;
    }

    return $relative_weight;
  }
}