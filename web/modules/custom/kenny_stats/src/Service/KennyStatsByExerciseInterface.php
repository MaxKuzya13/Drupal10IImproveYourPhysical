<?php

namespace Drupal\kenny_stats\Service;

interface KennyStatsByExerciseInterface {

  /**
   * Get last paragraph
   *
   * @param string $body_part
   *   The name of body part
   * @return \Drupal\Core\Entity\EntityInterface|mixed|null
   */
  public function getParagraph($body_part);

  /**
   * Get relative paragraph.
   *
   * @param \Drupal\Core\Entity\EntityInterface $paragraph
   *   The current paragraph.
   * @param int $limit
   *   The limit by time.
   * @return \Drupal\Core\Entity\EntityInterface|null
   */
  public function getRelativeParagraph($paragraph, $limit);

  /**
   * Get stats results.
   *
   * @param \Drupal\Core\Entity\EntityInterface $paragraph
   *    The current paragraph.
   * @param \Drupal\Core\Entity\EntityInterface $relative_paragraph
   *    The relative paragraph.
   * @return array
   */
  public function getResults($paragraph, $relative_paragraph);

  /**
   * @param string $body_part
   *    The name of body part
   * @return \Drupal\media\MediaStorage |mixed|null
   */
  public function getMedia($body_part);
}