<?php

namespace Drupal\kenny_stats\Service;

interface KennyStatsByExerciseInterface {

  /**
   * Get last paragraph
   *
   * @param string $body_part
   *   The name of body part
   *
   * @param array $exercises_array
   *    The array of term name and term id
   * @return \Drupal\paragraphs\ParagraphInterface|string
   */
  public function getParagraph($body_part, $exercises_array);

  /**
   * @param string $training_people
   *   Male or female.
   * @param int $exercise_id
   *   Exercise term id.
   * @param \Drupal\paragraphs\ParagraphInterface $paragraph
   *   The paragraph.
   * @param string $limit
   *   The timeline limit.
   * @return mixed
   */
  public function getCurrentParagraph($training_people, $exercise_id = '', $paragraph = '', $limit = '');

  /**
   * Get list of body part and exercise id.
   *
   * @param $config
   *   Current config.
   * @return array
   */
  public function getExercisesArray($config);

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
   * @return \Drupal\media\MediaStorage|mixed|null
   */
  public function getMedia($body_part);
}