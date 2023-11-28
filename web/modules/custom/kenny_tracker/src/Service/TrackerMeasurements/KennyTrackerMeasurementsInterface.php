<?php

namespace Drupal\kenny_tracker\Service\TrackerMeasurements;

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
}