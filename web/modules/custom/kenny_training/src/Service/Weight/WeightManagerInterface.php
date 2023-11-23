<?php

namespace Drupal\kenny_training\Service\Weight;

interface WeightManagerInterface {

  /**
   * Get total weight of the exercise.
   *
   * @param int $pid
   *   The paragraph id.
   *
   * @return int|null
   *   Total weight of the exercise or null.
   */
  public function getTotalExerciseWeight($pid);

  /**
   * Get total weight of the exercise.
   *
   * @param int $nid
   *   The node id.
   * @param string $people
   *    The sex state by people who do training.
   * @return int|null
   *   Total weight of the training or null.
   */
  public function getTotalWeight($nid, $people = 'man');


}