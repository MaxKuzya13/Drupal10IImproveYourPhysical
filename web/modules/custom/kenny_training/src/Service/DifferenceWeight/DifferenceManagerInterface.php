<?php

namespace Drupal\kenny_training\Service\DifferenceWeight;

interface DifferenceManagerInterface {

  /**
   * Get a difference in weight.
   *
   * @param $pid
   *   The current paragraph id.
   * @param string $people
   *    The sex state by people who do training.
   * @return array|null
   */
  public function getCurrentParagraph($pid, $people = 'man');

  /**
   * Get the type of training current node.
   *
   * @param int $pid
   *   The current paragraph id.
   * @param string $people
   *   The sex state by people who do training.
   * @return int|string
   *
   */
  public function getTypeOfTrainingId($pid, $people = 'man');

  /**
   * Get the weight of the last training.
   *
   * @param int $pid
   *   The paragraph id.
   * @param int $exercise
   *   The exercise id.
   * @param int $type_of_training_id
   *   The type of training id.
   * @param string $people
   *    The sex state by people who do training.
   * @return int
   *   Return weight by kg.
   */
  public function getRelativeWeight($pid, $exercise, $type_of_training_id, $people = 'man');
}