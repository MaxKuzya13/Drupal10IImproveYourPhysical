<?php

namespace Drupal\kenny_training\Service\Weight;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

class WeightManager implements WeightManagerInterface {

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

    public function __construct(EntityTypeManagerInterface $entity_type_manager) {
      $this->entityTypeManager = $entity_type_manager;
      $this->paragraphStorage = $entity_type_manager->getStorage('paragraph');
      $this->nodeStorage = $entity_type_manager->getStorage('node');
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalExerciseWeight($pid) {

      /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $paragraph */
      $paragraph = $this->paragraphStorage->load($pid);
      if ($paragraph instanceof ParagraphInterface) {
        $weight = $paragraph->get('field_weight')->value;
        $repetition = $paragraph->get('field_repetition')->value;
        $approaches = $paragraph->get('field_approaches')->value;

        $exercise_weight = $weight * $repetition * $approaches;
        return $exercise_weight;
      }

      return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalWeight($nid) {

      /** @var \Drupal\node\NodeStorageInterface $node */
      $node = $this->nodeStorage->load($nid);
      if ($node instanceof NodeInterface) {
        $exercises = $node->get('field_exercises')->referencedEntities();
        $total_weight = 0;

        foreach ($exercises as $exercise) {
          if ($exercise instanceof ParagraphInterface) {
            $weight = $exercise->get('field_weight')->value;
            $repetition = $exercise->get('field_repetition')->value;
            $approaches = $exercise->get('field_approaches')->value;

            $total_weight += ($weight * $repetition * $approaches);
          }
        }
        return $total_weight;
      }

      return null;

    }

}