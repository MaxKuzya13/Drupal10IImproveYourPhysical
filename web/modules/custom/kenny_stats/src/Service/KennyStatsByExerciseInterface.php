<?php

namespace Drupal\kenny_stats\Service;

interface KennyStatsByExerciseInterface {

  /**
   * Is isset training.
   *
   * @param int $uid
   *   The user id.
   * @param string $gender
   *   The gender name.
   * @return bool
   */
  public function issetTraining($uid, $gender);

  /**
   * Last and first measurements data by current timeline.
   *
   * @param int $uid
   *   The current user id.
   * @param string $limit
   *   The timeline limit.
   * @return array
   */
  public function getMeasurements($uid, $limit);

  /**
   * Results by measurements
   *
   * @param \Drupal\node\NodeInterface $last
   *   Last measuruments.
   * @param \Drupal\node\NodeInterface $first
   *   First measurements by period.
   * @return array
   */
  public function getMeasurementsResults($last, $first);

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
   *
   * @param int $uid
   *   The current user id.
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
  public function getCurrentParagraph($uid, $training_people, $exercise_id = '', $paragraph = '', $limit = '');


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

  /**
   * Number of training.
   *
   * @param string $training_people
   *   The sex by people who do training.
   * @param string $limit
   *   The timeline limit.
   * @param int $uid
   *    The current user id.
   * @return int
   */
  public function getNumberOfTraining($training_people, $limit, $uid);


  /**
   * The number of training by body part.
   *
   * @param string $training_people
   *     The sex by people who do training.
   * @param string $limit
   *     The timeline limit.
   * @param int $uid
   *     The current user id.
   * @return int
   */

  public function getNumberOfTrainingByBodyPart($training_people, $limit, $uid);

  /**
   * List of exercises that most popular.
   *
   * @param string $training_people
   *      The sex by people who do training.
   * @param string $limit
   *      The timeline limit.
   * @param int $uid
   *      The current user id.
   * @return array
   */
  public function mostPopularExercise($training_people, $limit, $uid);
}