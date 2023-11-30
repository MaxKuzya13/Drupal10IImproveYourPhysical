<?php

namespace Drupal\kenny_tracker\Service\TrackerMeasurements;

use mysql_xdevapi\DatabaseObject;

interface KennyTrackerMeasurementsInterface {

  /**
   * Check if this user has a track.
   *
   * @param int $uid
   *    The user id.
   * @return bool
   */
  public function isTrack($uid);

  /**
   * Sets the values to track.
   *
   * @param int $uid
   *   The user id.
   * @param int $nid
   *   The node id.
   * @return mixed
   */
  public function setTrack($uid, $nid);

  /**
   * Delete values from database.
   *
   * @param int $uid
   *    The user id.
   * @param int $nid
   *     The nid id.
   * @return mixed
   */
  public function deleteTrack($uid, $nid);

  /**
   * Data in database.
   *
   * @param int $uid
   *     The user id.
   * @return array
   */
  public function getTrackedMeasurements($uid);

  /**
   * Set data by relevant measurements.
   *
   * @param int $measurement_id
   *   The measurement id.
   * @param int $uid
   *   The user id.
   * @param $date
   *   The date object.
   * @return mixed
   */
  public function setRelevantMeasurements($measurement_id, $uid, $date);

  /**
   * Result from starts tracking to now.
   *
   * @param int $uid
   *    The user id.
   * @return array
   */
  public function getProgressOverTime($nid);

  /**
   * Desired minus started result.
   *
   * @param int $uid
   *     The user id.
   * @return array
   */
  public function getDesiredResult($nid);

  /**
   * Desired minus last measurement.
   *
   * @param int $nid
   *   The node id.
   * @return null|array
   */
  public function isStillLeft($nid);

  /**
   * Name of selected fields.
   *
   * @param int $id
   *   Measurements id.
   * @return array
   */
  public function selectedFields($id);

  /**
   * Values by the started tracking.
   *
   * @param int $id
   *   Measurements id.
   * @param array $selected_fields
   *   List of fields name.
   * @return array
   */
  public function getStarted($id, $selected_fields);

  /**
   * Values by the Relative tracking.
   *
   * @param int $id
   *   Measurements id.
   * @param array $selected_fields
   *   List of fields name.
   * @return array
   */
  public function getRelative($id, $selected_fields);

  /**
   * The desired values.
   *
   * @param int $id
   *   The measurement id.
   * @return array
   */
  public function getDecired($id);
}