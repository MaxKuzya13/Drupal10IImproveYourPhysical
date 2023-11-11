<?php

namespace Drupal\kenny_girls_stats\Service;

interface KennyGirlsStatsByExerciseInterface {


  /**
   * Return last paragraph.
   *
   * @param int $exercise_id
   *   Term id
   * @return mixed
   */
  public function getLastParagraph($exercise_id);

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