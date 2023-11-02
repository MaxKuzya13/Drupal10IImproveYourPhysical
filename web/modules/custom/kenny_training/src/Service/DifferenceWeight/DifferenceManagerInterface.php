<?php

namespace Drupal\kenny_training\Service\DifferenceWeight;

interface DifferenceManagerInterface {

  /**
   * Get a difference in weight.
   *
   * @param $pid
   *   The current paragraph id.
   * @return array|null
   */
  public function getCurrentParagraph($pid);

  /**
   * Get the type of training current node.
   *
   * @param int $pid
   *   The current paragraph id.
   * @return int|string
   *
   */
  public function getTypeOfTrainingId($pid);

  /**
   * Get the weight of the last training.
   *
   * @param int $pid
   *   The paragraph id.
   * @param int $exercise
   *   The exercise id.
   * @param int $type_of_training_id
   *   The type of training id.
   * @return int
   *   Return weight by kg.
   */
  public function getRelativeWeight($pid, $exercise, $type_of_training_id);
}